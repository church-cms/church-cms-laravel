<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePrayerRequest;
use App\Http\Requests\RejectPrayerRequest;
use App\Models\Prayer;
use App\Models\PrayerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrayerBoardController extends Controller
{
    /**
     * Main admin dashboard — tabbed view.
     */
    public function index()
    {
        $churchId = Auth::user()->church_id;

        $counts = [
            'pending'     => Prayer::forChurch($churchId)->pending()->count(),
            'active'      => Prayer::forChurch($churchId)->active()->count(),
            'answered'    => Prayer::forChurch($churchId)->answered()->count(),
            'ended'       => Prayer::forChurch($churchId)->ended()->count(),
            'rejected'    => Prayer::forChurch($churchId)->rejected()->count(),
        ];

        return view('admin.prayerboard.index', compact('counts'));
    }

    /**
     * AJAX: Return HTML partial for a tab.
     */
    public function list(Request $request, $status)
    {
        $allowedStatuses = [
            'pending'     => Prayer::STATUS_PENDING,
            'active'      => Prayer::STATUS_ACTIVE,
            'answered'    => Prayer::STATUS_ANSWERED,
            'ended'       => Prayer::STATUS_ENDED,
            'rejected'    => Prayer::STATUS_REJECTED,
        ];

        if (!array_key_exists($status, $allowedStatuses)) {
            abort(404);
        }

        $churchId   = Auth::user()->church_id;
        $statusEnum = $allowedStatuses[$status];

        $query = Prayer::forChurch($churchId)
            ->where('status', $statusEnum)
            ->with(['category', 'user', 'approver'])
            ->recent();

        if ($request->filled('category_id')) {
            $query->inCategory($request->category_id);
        }

        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->inMonth($parts[0], $parts[1]);
            }
        }

        $prayers = $query->paginate(12)->withQueryString();

        $categories = PrayerCategory::forChurch($churchId)->active()->ordered()->get();

        return view("admin.prayerboard._{$status}", compact('prayers', 'categories', 'status'));
    }

    /**
     * Show detail of a single prayer with audit history.
     */
    public function show($id)
    {
        $churchId = Auth::user()->church_id;
        $prayer   = Prayer::forChurch($churchId)->with(['category', 'user', 'approver', 'rejector', 'pinner', 'answerer'])->findOrFail($id);

        $auditLogs = \Spatie\Activitylog\Models\Activity::where('subject_type', Prayer::class)
            ->where('subject_id', $id)
            ->with('causer')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.prayerboard.show', compact('prayer', 'auditLogs'));
    }

    /**
     * POST approve/{id}
     */
    public function approve(ApprovePrayerRequest $request, $id)
    {
        $prayer = $this->findPrayerForChurch($id);

        if ($prayer->status !== Prayer::STATUS_PENDING) {
            return $this->jsonError('Only pending prayers can be approved.');
        }

        // Optionally update text if admin edited it
        if ($request->filled('text') && $request->text !== $prayer->text) {
            $prayer->update(['text' => $request->text]);
        }

        $prayer->approve(Auth::user(), $request->expiry_days);

        return response()->json([
            'success' => true,
            'message' => 'Prayer approved. It is now live on the prayer board.',
        ]);
    }

    /**
     * POST reject/{id}
     */
    public function reject(RejectPrayerRequest $request, $id)
    {
        $prayer = $this->findPrayerForChurch($id);

        if ($prayer->status !== Prayer::STATUS_PENDING) {
            return $this->jsonError('Only pending prayers can be rejected.');
        }

        $prayer->reject(Auth::user(), $request->reason);

        return response()->json([
            'success' => true,
            'message' => 'Prayer rejected.',
        ]);
    }

    /**
     * POST mark-answered/{id}
     */
    public function markAnswered(Request $request, $id)
    {
        $prayer = $this->findPrayerForChurch($id);

        if ($prayer->status !== Prayer::STATUS_ACTIVE) {
            return $this->jsonError('Only active prayers can be marked as answered.');
        }

        $prayer->markAnswered(Auth::user(), $request->input('testimony'));

        return response()->json([
            'success' => true,
            'message' => 'Prayer marked as answered. Praise God!',
        ]);
    }

    /**
     * POST pin/{id}
     */
    public function pin($id)
    {
        $prayer = $this->findPrayerForChurch($id);

        if ($prayer->status !== Prayer::STATUS_ACTIVE) {
            return $this->jsonError('Only active prayers can be pinned.');
        }

        $prayer->pin(Auth::user());

        return response()->json(['success' => true, 'message' => 'Prayer pinned to top.']);
    }

    /**
     * POST unpin/{id}
     */
    public function unpin($id)
    {
        $prayer = $this->findPrayerForChurch($id);
        $prayer->unpin(Auth::user());

        return response()->json(['success' => true, 'message' => 'Prayer unpinned.']);
    }

    /**
     * POST extend/{id}
     */
    public function extend(Request $request, $id)
    {
        $request->validate([
            'additional_days' => 'required|integer|in:7,14,30,60',
        ]);

        $prayer = $this->findPrayerForChurch($id);

        if ($prayer->status !== Prayer::STATUS_ACTIVE) {
            return $this->jsonError('Only active prayers can be extended.');
        }

        $prayer->extend(Auth::user(), $request->additional_days);

        return response()->json([
            'success'    => true,
            'message'    => "Prayer extended by {$request->additional_days} days.",
            'expires_at' => $prayer->fresh()->expires_at->format('M j, Y'),
        ]);
    }

    /**
     * POST unpublish/{id}
     */
    public function unpublish($id)
    {
        $prayer = $this->findPrayerForChurch($id);

        if ($prayer->status !== Prayer::STATUS_ACTIVE) {
            return $this->jsonError('Only active prayers can be unpublished.');
        }

        $prayer->unpublish(Auth::user());

        return response()->json(['success' => true, 'message' => 'Prayer removed from public board.']);
    }

    // ===== PRIVATE HELPERS =====

    private function findPrayerForChurch($id)
    {
        $churchId = Auth::user()->church_id;
        return Prayer::forChurch($churchId)->findOrFail($id);
    }

    private function jsonError($message, $code = 422)
    {
        return response()->json(['success' => false, 'message' => $message], $code);
    }
}
