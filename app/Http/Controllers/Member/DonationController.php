<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaystackService;
use App\Services\Payment\FlutterwaveService;
use App\Services\Payment\MpesaService;
use App\Services\Payment\GCashService;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Payaccount;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Paymentgateway;

class DonationController extends Controller
{
    private const ONLINE_GATEWAYS = ['paystack', 'flutterwave', 'mpesa', 'gcash', 'pix', 'telebirr', 'stripe'];

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

                case 'stripe':
                    $secretKey = $payaccount->param2 ?: config('paymentgateway.stripe.secret_key', '');
                    $svc       = new StripeService($secretKey);
                    $result    = $svc->verify($request->reference);
                    $verified  = $svc->isSuccessful($result);
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

            $result = $svc->stkPush($phone, $request->amount, $ref, route('member.donate.mpesa-callback'));

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

    /** Create a GCash source via PayMongo and return the checkout URL. */
    public function gcashInit(Request $request)
    {
        $request->validate([
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
            $gcash_currency = Paymentgateway::getCurrency('gcash',    'PHP');
            $secretKey = $payaccount->param2 ?: config('paymentgateway.gcash.secret_key', '');
            $currency  = $payaccount->param5 ?: $gcash_currency;
            $svc       = new GCashService($secretKey, $currency);
            $desc      = 'Church Donation — ' . ($request->category ?? 'offering');

            // PayMongo appends ?source_id=src_xxx to the success URL automatically
            $successUrl = route('member.donate.gcash-return') . '?status=success';
            $failedUrl  = route('member.donate.gcash-return') . '?status=failed';

            $result   = $svc->createSource((float) $request->amount, $successUrl, $failedUrl, $desc);
            $sourceId = $result['source_id'];

            // Store payment details in session keyed by source ID for verification on return
            session(["gcash_{$sourceId}" => [
                'payaccount_id' => $payaccount->id,
                'amount'        => $request->amount,
                'category'      => $request->category ?? 'offering',
                'note'          => $request->note ?? '',
                'currency'      => $currency,
            ]]);

            return response()->json(['checkout_url' => $result['checkout_url']]);
        } catch (Exception $e) {
            Log::error('GCash init error: ' . $e->getMessage());
            return response()->json(['error' => 'GCash service unavailable. Please try again.'], 500);
        }
    }

    /** Handle redirect back from PayMongo after GCash payment. */
    public function gcashReturn(Request $request)
    {
        if ($request->get('status') !== 'success') {
            return redirect()->route('member.donate')->with('error', 'GCash payment was cancelled or failed.');
        }

        $sourceId = $request->get('source_id');
        $pending  = $sourceId ? session("gcash_{$sourceId}") : null;

        if (!$pending) {
            return redirect()->route('member.donate')->with('error', 'Invalid or expired payment session.');
        }

        $payaccount = Payaccount::where('id', $pending['payaccount_id'])
            ->where('church_id', Auth::user()->church_id)
            ->where('status', 1)
            ->first();

        if (!$payaccount) {
            return redirect()->route('member.donate')->with('error', 'Invalid payment account.');
        }

        try {
            $secretKey = $payaccount->param2 ?: config('paymentgateway.gcash.secret_key', '');
            $svc       = new GCashService($secretKey, $pending['currency']);

            $source = $svc->getSource($sourceId);

            if (!$svc->isChargeable($source)) {
                return redirect()->route('member.donate')->with('error', 'GCash payment not completed. Please try again.');
            }

            $desc    = 'Church Donation — ' . $pending['category'];
            $payment = $svc->createPayment($sourceId, (float) $pending['amount'], $desc);

            session()->forget("gcash_{$sourceId}");

            Donation::create([
                'church_id'   => Auth::user()->church_id,
                'user_id'     => Auth::id(),
                'amount'      => $pending['amount'],
                'currency'    => $pending['currency'],
                'category'    => $pending['category'],
                'method'      => 'gcash',
                'gateway_ref' => $payment['id'] ?? $sourceId,
                'status'      => 'completed',
                'note'        => $pending['note'],
                'uuid'        => uniqid(),
                'donated_at'  => now(),
            ]);

            return redirect()->route('member.donate')
                ->with('success', 'GCash donation successful! Thank you for your generosity.');
        } catch (Exception $e) {
            Log::error('GCash return error: ' . $e->getMessage());
            return redirect()->route('member.donate')->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /** Create a Stripe PaymentIntent and return the client_secret. */
    public function stripeIntent(Request $request)
    {
        $request->validate([
            'amount'        => 'required|numeric|min:1',
            'payaccount_id' => 'required|integer',
            'category'      => 'nullable|string|max:50',
        ]);

        $payaccount = Payaccount::where('id', $request->payaccount_id)
            ->where('church_id', Auth::user()->church_id)
            ->where('status', 1)
            ->first();

        if (!$payaccount) {
            return response()->json(['error' => 'Invalid payment account.'], 422);
        }
        $stripe_currency = Paymentgateway::getCurrency('stripe', 'USD');

        try {
            $secretKey  = $payaccount->param2 ?: config('paymentgateway.stripe.secret_key', '');
            $publicKey  = $payaccount->param1 ?: config('paymentgateway.stripe.public_key', '');
            $currency   = $payaccount->param5 ?: $stripe_currency;
            $svc        = new StripeService($secretKey, $publicKey, $currency);
            $desc       = 'Church Donation — ' . ($request->category ?? 'offering');
            $result     = $svc->createIntent((float) $request->amount, $desc);

            return response()->json($result);
        } catch (Exception $e) {
            Log::error('Stripe intent error: ' . $e->getMessage());
            return response()->json(['error' => 'Stripe service unavailable. Please try again.'], 500);
        }
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
        'stripe'      => 'STRIPE_PUBLIC_KEY',
        'gcash'       => 'GCASH_PUBLIC_KEY',
    ];
    // $stripe_currency = Paymentgateway::getCurrency('stripe', 'USD');

    private const ENV_CURRENCIES = [
        'paystack'    => 'PAYSTACK_CURRENCY',
        'flutterwave' => 'FLUTTERWAVE_CURRENCY',
        'stripe'      => 'STRIPE_CURRENCY',
        'gcash'       => 'GCASH_CURRENCY',
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

                    //dump($name);

                    if ($name == 'mpesa') {

                        $pay_currency = Paymentgateway::getCurrency($name, 'KES');
                    } else if ($name == 'flutterwave') {

                        $pay_currency = Paymentgateway::getCurrency($name, 'NGN');
                    } else if ($name == 'paystack') {

                        $pay_currency = Paymentgateway::getCurrency($name, 'NGN');

                        //dd($name . $pay_currency);
                    } else if ($name == 'stripe') {

                        $pay_currency = Paymentgateway::getCurrency($name, 'USD');
                    } else if ($name == 'gcash') {

                        $pay_currency = Paymentgateway::getCurrency($name, 'PHP');
                    } else {
                        $pay_currency = Paymentgateway::getCurrency($name, 'USD');
                    }


                    $currency = $pa->param5 ?: (isset($pay_currency) ? $pay_currency : 'NGN');

                    // dump($currency);
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
