<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaystackService;
use App\Services\Payment\FlutterwaveService;
use App\Services\Payment\MpesaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Payaccount;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Exception;

class DonationController extends Controller
{
    private const ONLINE_GATEWAYS = ['paystack', 'flutterwave', 'mpesa', 'gcash', 'pix', 'telebirr'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $donations  = Donation::where('user_id', Auth::id())
            ->where('church_id', Auth::user()->church_id)
            ->latest()->get();

        $payaccounts = $this->churchPayaccounts();

        //dd($payaccounts);

        return view('member.donation', compact('donations', 'payaccounts'));
    }

    /** JSON: active payaccounts for this church (safe — no secret keys). */
    public function gateways()
    {
        return response()->json($this->churchPayaccounts());
    }

    /** Verify Paystack / Flutterwave reference after JS popup success. */
    public function verify(Request $request)
    {
        $request->validate([
            'gateway'       => 'required|string',
            'reference'     => 'required|string',
            'payaccount_id' => 'required|integer',
            'amount'        => 'required|numeric|min:1',
            'category'      => 'nullable|string|max:50',
            'note'          => 'nullable|string|max:500',
        ]);

        $payaccount = Payaccount::where('id', $request->payaccount_id)
            ->where('church_id', Auth::user()->church_id)
            ->where('status', 1)
            ->first();

        if (!$payaccount) {
            return back()->with('error', 'Invalid payment account.');
        }

        $verified = false;

        try {
            switch ($request->gateway) {
                case 'paystack':
                    $svc      = new PaystackService($payaccount->param2 ?? '', $payaccount->param1 ?? '');
                    $result   = $svc->verify($request->reference);
                    $verified = $svc->isSuccessful($result);
                    break;

                case 'flutterwave':
                    $svc      = new FlutterwaveService($payaccount->param2 ?? '');
                    $result   = $svc->verify($request->reference);
                    $verified = $svc->isSuccessful($result);
                    break;

                default:
                    $verified = false;
            }
        } catch (Exception $e) {
            Log::error('Payment verify error: ' . $e->getMessage());
        }

        if (!$verified) {
            return back()->with('error', 'Payment verification failed. Please contact support.');
        }

        Donation::create([
            'church_id'   => Auth::user()->church_id,
            'user_id'     => Auth::id(),
            'amount'      => $request->amount,
            'currency'    => 'USD',
            'category'    => $request->category ?? 'offering',
            'method'      => $request->gateway,
            'gateway_ref' => $request->reference,
            'status'      => 'completed',
            'note'        => $request->note,
            'uuid'        => uniqid(),
            'donated_at'  => now(),
        ]);

        return redirect()->route('member.donate')
            ->with('success', 'Donation successful! Thank you for your generosity.');
    }

    /** M-Pesa STK push — returns JSON. */
    public function mpesaStk(Request $request)
    {
        $request->validate([
            'phone'         => 'required|string',
            'amount'        => 'required|numeric|min:1',
            'payaccount_id' => 'required|integer',
            'category'      => 'nullable|string|max:50',
            'note'          => 'nullable|string|max:500',
        ]);

        $payaccount = Payaccount::where('id', $request->payaccount_id)
            ->where('church_id', Auth::user()->church_id)
            ->where('status', 1)
            ->first();

        if (!$payaccount) {
            return response()->json(['error' => 'Invalid payment account.'], 422);
        }

        try {
            $svc   = new MpesaService($payaccount->param1 ?? '', $payaccount->param2 ?? '', $payaccount->param3 ?? '', $payaccount->param4 ?? '');
            $phone = $svc->formatPhone($request->phone);
            $ref   = 'DON-' . strtoupper(uniqid());

            $result = $svc->stkPush($phone, $request->amount, $ref, 'https://demo.churchcms.app/member/donate/mpesa-callback');

            if (!empty($result['CheckoutRequestID'])) {
                $donation = Donation::create([
                    'church_id'   => Auth::user()->church_id,
                    'user_id'     => Auth::id(),
                    'amount'      => $request->amount,
                    'currency'    => 'KES',
                    'category'    => $request->category ?? 'offering',
                    'method'      => 'mpesa',
                    'gateway_ref' => $result['CheckoutRequestID'],
                    'status'      => 'pending',
                    'note'        => $request->note,
                    'uuid'        => $ref,
                    'donated_at'  => now(),
                ]);

                return response()->json([
                    'success'              => true,
                    'message'              => 'STK push sent. Enter your M-Pesa PIN to complete.',
                    'checkout_request_id'  => $result['CheckoutRequestID'],
                    'donation_id'          => $donation->id,
                ]);
            }

            Log::error('M-Pesa STK no CheckoutRequestID', ['result' => $result]);
            $apiMsg = $result['errorMessage'] ?? ($result['ResultDesc'] ?? null);
            return response()->json(['error' => $apiMsg ?: 'Failed to initiate M-Pesa payment. Check your credentials.'], 422);
        } catch (Exception $e) {
            Log::error('M-Pesa STK error: ' . $e->getMessage());
            return response()->json(['error' => 'M-Pesa service unavailable. Try again later.'], 500);
        }
    }

    /** M-Pesa callback — Safaricom posts here after user confirms/cancels. */
    public function mpesaCallback(Request $request)
    {
        $body = $request->all();
        Log::info('M-Pesa callback: ' . json_encode($body));

        $stkCallback = $body['Body']['stkCallback'] ?? null;
        if (!$stkCallback) {
            return response()->json(['ResultCode' => 0]);
        }

        $checkoutId   = $stkCallback['CheckoutRequestID'] ?? null;
        $resultCode   = $stkCallback['ResultCode'] ?? -1;

        if ($checkoutId) {
            $status = ($resultCode == 0) ? 'completed' : 'cancelled';
            Donation::where('gateway_ref', $checkoutId)->update(['status' => $status]);
        }

        return response()->json(['ResultCode' => 0]);
    }

    /** Offline donation (cash, bank transfer). */
    public function store(Request $request)
    {
        $request->validate([
            'amount'        => 'required|numeric|min:1',
            'payaccount_id' => 'nullable|integer',
            'category'      => 'nullable|string|max:50',
            'note'          => 'nullable|string|max:500',
        ]);

        try {
            $method = 'cash';
            if ($request->payaccount_id) {
                $pa     = Payaccount::with('paymentgateway')->find($request->payaccount_id);
                $method = optional(optional($pa)->paymentgateway)->gatewayname ?? 'cash';
            }

            Donation::create([
                'church_id'  => Auth::user()->church_id,
                'user_id'    => Auth::id(),
                'amount'     => $request->amount,
                'currency'   => 'USD',
                'category'   => $request->category ?? 'offering',
                'method'     => $method,
                'status'     => 'pending',
                'note'       => $request->note,
                'uuid'       => uniqid(),
                'donated_at' => now(),
            ]);

            return back()->with('success', 'Donation submitted! Thank you for your generosity.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    private const ENV_PUBLIC_KEYS = [
        'paystack'    => 'PAYSTACK_PUBLIC_KEY',
        'flutterwave' => 'FLUTTERWAVE_PUBLIC_KEY',
    ];

    private const ENV_CURRENCIES = [
        'paystack'    => 'PAYSTACK_CURRENCY',
        'flutterwave' => 'FLUTTERWAVE_CURRENCY',
    ];

    private function churchPayaccounts(): array
    {
        return Payaccount::with('paymentgateway')
            ->where('church_id', Auth::user()->church_id)
            ->where('status', 1)
            ->get()
            ->map(function ($pa) {
                $name   = $pa->paymentgateway->gatewayname ?? '';
                $online = in_array($name, self::ONLINE_GATEWAYS);
                if ($online) {
                    $dbKey    = $pa->param1 ?? '';
                    $envKey   = isset(self::ENV_PUBLIC_KEYS[$name]) ? env(self::ENV_PUBLIC_KEYS[$name], '') : '';
                    $pubKey   = $dbKey ?: $envKey;
                    $currency = $pa->param5 ?: (isset(self::ENV_CURRENCIES[$name]) ? env(self::ENV_CURRENCIES[$name], 'NGN') : 'NGN');
                } else {
                    $pubKey   = null;
                    $currency = null;
                }
                return [
                    'id'           => $pa->id,
                    'gatewayname'  => $name,
                    'display_name' => $pa->paymentgateway->displayname ?? $name,
                    'instructions' => $pa->paymentgateway->instructions ?? '',
                    'public_key'   => $pubKey,
                    'currency'     => $currency,
                    'is_online'    => $online,
                ];
            })
            ->toArray();
    }
}
