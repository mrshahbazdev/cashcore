<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashAlert;
use App\Models\CashReview;
use App\Services\CashCoreService;
use Illuminate\Http\Request;

class BehaviorController extends Controller
{
    public function __construct(private CashCoreService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $this->service->generateAlerts($user->id);

        $pendingReviews = CashReview::where('user_id', $user->id)->pending()->orderBy('scheduled_date')->get();
        $completedReviews = CashReview::where('user_id', $user->id)->completed()->latest('completed_date')->limit(10)->get();
        $alerts = CashAlert::where('user_id', $user->id)->unread()->latest()->get();
        $streak = CashReview::where('user_id', $user->id)->completed()->max('streak_count') ?? 0;

        return view('cashcore.behavior.index', compact('pendingReviews', 'completedReviews', 'alerts', 'streak'));
    }

    public function scheduleReview(Request $request)
    {
        $validated = $request->validate([
            'review_type' => 'required|in:monthly,quarterly,annual',
            'scheduled_date' => 'required|date|after:today',
        ]);

        CashReview::create([
            'user_id' => $request->user()->id,
            'review_type' => $validated['review_type'],
            'scheduled_date' => $validated['scheduled_date'],
            'checklist' => CashReview::getDefaultChecklist(),
        ]);

        return back()->with('success', __('cashcore.schedule_review'));
    }

    public function startReview(Request $request, CashReview $review)
    {
        abort_if($review->user_id !== $request->user()->id, 403);

        $review->update(['status' => 'in_progress']);

        return view('cashcore.behavior.review', compact('review'));
    }

    public function completeReview(Request $request, CashReview $review)
    {
        abort_if($review->user_id !== $request->user()->id, 403);

        $checklist = $request->input('checklist', []);

        $lastCompleted = CashReview::where('user_id', $request->user()->id)
            ->completed()
            ->latest('completed_date')
            ->first();

        $streak = 1;
        if ($lastCompleted && $lastCompleted->completed_date->diffInDays(now()) <= 45) {
            $streak = $lastCompleted->streak_count + 1;
        }

        $review->update([
            'status' => 'completed',
            'completed_date' => now(),
            'checklist' => $checklist,
            'streak_count' => $streak,
        ]);

        return redirect()->route('cashcore.behavior.index')
            ->with('success', __('cashcore.review_completed'));
    }

    public function markAlertRead(Request $request, CashAlert $alert)
    {
        abort_if($alert->user_id !== $request->user()->id, 403);

        $alert->markAsRead();

        return back();
    }

    public function dismissAlert(Request $request, CashAlert $alert)
    {
        abort_if($alert->user_id !== $request->user()->id, 403);

        $alert->update(['is_dismissed' => true]);

        return back();
    }
}
