<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Donation;
use Exception;
use Log;

class DonationController extends Controller
{
    public function index()
    {
        return view('admin.donation.index');
    }

    public function list(Request $request)
    {
        $donations = Donation::with('user.userprofile')
            ->where('church_id', Auth::user()->church_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $donations->whereHas('user.userprofile', function ($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $donations->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $donations->where('category', $request->category);
        }

        $donations = $donations->latest()->paginate(15);

        return response()->json([
            'data' => $donations->map(function ($d) {
                return [
                    'id'         => $d->id,
                    'name'       => optional(optional($d->user)->userprofile)->firstname
                        . ' ' . optional(optional($d->user)->userprofile)->lastname,
                    'email'      => optional($d->user)->email,
                    'amount'     => $d->amount,
                    'currency'   => $d->currency,
                    'category'   => $d->category,
                    'method'     => $d->method,
                    'status'     => $d->status,
                    'note'       => $d->note,
                    'donated_at' => $d->donated_at ? $d->donated_at->format('d M Y') : $d->created_at->format('d M Y'),
                ];
            }),
            'meta' => [
                'total'     => $donations->total(),
                'last_page' => $donations->lastPage(),
            ],
        ]);
    }

    public function show($id)
    {
        $donation = Donation::with('user.userprofile')
            ->where('id', $id)
            ->where('church_id', Auth::user()->church_id)
            ->firstOrFail();

        return view('admin.donation.show', compact('donation'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $donation = Donation::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $donation->status = $request->status;
            $donation->save();

            return redirect('/admin/donations')->with('successmessage', 'Status updated successfully');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to update status'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $donation = Donation::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $donation->delete();

            return response()->json(['success' => 'Donation deleted successfully']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to delete'], 500);
        }
    }
}
