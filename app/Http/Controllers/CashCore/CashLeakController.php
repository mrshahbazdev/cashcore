<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashLeak;
use App\Services\CashCoreService;
use Illuminate\Http\Request;

class CashLeakController extends Controller
{
    public function __construct(private CashCoreService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $activeLeaks = CashLeak::where('user_id', $user->id)->active()->orderByDesc('leak_score')->get();
        $resolvedLeaks = CashLeak::where('user_id', $user->id)->resolved()->latest()->get();
        $overallScore = $this->service->calculateOverallLeakScore($user->id);
        $totalLeakAmount = $activeLeaks->sum('monthly_amount');

        return view('cashcore.leaks.index', compact('activeLeaks', 'resolvedLeaks', 'overallScore', 'totalLeakAmount'));
    }

    public function runDetection(Request $request)
    {
        $leaks = $this->service->runLeakDetection($request->user()->id);

        return redirect()->route('cashcore.leaks.index')
            ->with('success', count($leaks) > 0
                ? __('cashcore.detected_leaks') . ': ' . count($leaks)
                : __('cashcore.no_leaks_found'));
    }

    public function updateStatus(Request $request, CashLeak $leak)
    {
        abort_if($leak->user_id !== $request->user()->id, 403);

        $request->validate(['status' => 'required|in:detected,reviewed,resolved,ignored']);
        $leak->update(['status' => $request->status]);

        return redirect()->route('cashcore.leaks.index')
            ->with('success', __('cashcore.leak_resolved'));
    }
}
