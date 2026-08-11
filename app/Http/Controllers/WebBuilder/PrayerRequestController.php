<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use App\Models\PrayerCategory;
use App\Models\PrayerParticipant;
use Illuminate\Http\Request;

class PrayerRequestController extends Controller
{
    public function index()
    {
        $church = request()->attributes->get('_church');

        $requests = Prayer::where('status', Prayer::STATUS_ACTIVE)
                          ->when($church, fn ($q) => $q->where('church_id', $church->id))
                          ->orderByDesc('created_at')
                          ->paginate(10);

        $categories = PrayerCategory::orderBy('name')->get();

        return view('theme::prayer_index', compact('requests', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text'        => 'required|string|min:10|max:500',
            'category_id' => 'nullable|exists:prayer_categories,id',
        ]);

        $church = $request->attributes->get('_church');

        Prayer::create([
            'church_id'       => optional($church)->id,
            'user_id'         => auth()->id(),
            'category_id'     => $validated['category_id'] ?? null,
            'text'            => $validated['text'],
            'original_text'   => $validated['text'],
            'status'          => Prayer::STATUS_PENDING,
            'member_count'    => 0,
            'guest_count'     => 0,
            'anonymous_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Your prayer request has been submitted.');
    }

    public function lift(Request $request, $id)
    {
        $church = $request->attributes->get('_church');

        $prayer = Prayer::where('id', $id)
            ->where('status', Prayer::STATUS_ACTIVE)
            ->when($church, fn ($q) => $q->where('church_id', $church->id))
            ->first();

        if (!$prayer) {
            return response()->json([
                'success' => false,
                'error'   => 'This prayer is no longer active',
                'code'    => 'PRAYER_INACTIVE',
            ], 422);
        }

        if (auth()->check()) {
            $user = auth()->user();
            $type = PrayerParticipant::TYPE_MEMBER;
            $hash = null;
        } else {
            $user = null;
            $type = PrayerParticipant::TYPE_GUEST;
            $hash = hash('sha256', $request->ip() . '|' . $request->userAgent() . '|' . $id);
        }

        $lifted = PrayerParticipant::lift($prayer, $user, $type, $hash);

        if (!$lifted) {
            return response()->json([
                'success' => false,
                'error'   => 'You have already prayed for this',
                'code'    => 'DUPLICATE_PARTICIPATION',
            ], 403);
        }

        $prayer->refresh();

        return response()->json([
            'success'               => true,
            'message'               => 'Prayer recorded',
            'participant_count'     => $prayer->total_participant_count,
            'participant_breakdown' => [
                'total'     => $prayer->total_participant_count,
                'members'   => $prayer->member_count,
                'guests'    => $prayer->guest_count,
                'anonymous' => $prayer->anonymous_count,
            ],
        ]);
    }
}
