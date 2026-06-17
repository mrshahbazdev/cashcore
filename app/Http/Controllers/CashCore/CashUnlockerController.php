<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\LiquidityBlocker;
use Illuminate\Http\Request;

class CashUnlockerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $activeBlockers = LiquidityBlocker::where('user_id', $user->id)->active()->orderByDesc('blocked_amount')->get();
        $resolvedBlockers = LiquidityBlocker::where('user_id', $user->id)->where('status', 'resolved')->latest()->get();
        $totalBlocked = $activeBlockers->sum('blocked_amount');

        return view('cashcore.unlocker.index', compact('activeBlockers', 'resolvedBlockers', 'totalBlocked'));
    }

    public function create()
    {
        return view('cashcore.unlocker.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blocker_type' => 'required|in:open_invoice,payment_terms,inventory,inefficient_flow',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'blocked_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'debtor_name' => 'nullable|string|max:255',
            'days_overdue' => 'integer|min:0',
        ]);

        $validated['user_id'] = $request->user()->id;

        LiquidityBlocker::create($validated);

        return redirect()->route('cashcore.unlocker.index')
            ->with('success', __('cashcore.blocker_created'));
    }

    public function edit(Request $request, LiquidityBlocker $blocker)
    {
        abort_if($blocker->user_id !== $request->user()->id, 403);
        return view('cashcore.unlocker.edit', compact('blocker'));
    }

    public function update(Request $request, LiquidityBlocker $blocker)
    {
        abort_if($blocker->user_id !== $request->user()->id, 403);

        $validated = $request->validate([
            'blocker_type' => 'required|in:open_invoice,payment_terms,inventory,inefficient_flow',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'blocked_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'debtor_name' => 'nullable|string|max:255',
            'days_overdue' => 'integer|min:0',
            'status' => 'in:active,in_progress,resolved',
        ]);

        $blocker->update($validated);

        return redirect()->route('cashcore.unlocker.index')
            ->with('success', __('cashcore.blocker_updated'));
    }

    public function destroy(Request $request, LiquidityBlocker $blocker)
    {
        abort_if($blocker->user_id !== $request->user()->id, 403);
        $blocker->delete();

        return redirect()->route('cashcore.unlocker.index')
            ->with('success', __('cashcore.blocker_deleted'));
    }

    public function updateStatus(Request $request, LiquidityBlocker $blocker)
    {
        abort_if($blocker->user_id !== $request->user()->id, 403);

        $request->validate(['status' => 'required|in:active,in_progress,resolved']);
        $blocker->update(['status' => $request->status]);

        return back()->with('success', __('cashcore.blocker_updated'));
    }
}
