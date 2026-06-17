<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\ProfitAllocation;
use App\Services\CashCoreService;
use Illuminate\Http\Request;

class ProfitAllocationController extends Controller
{
    public function __construct(private CashCoreService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $period = $request->get('period', now()->format('Y-m'));

        $this->service->calculateProfitAllocations($user->id, $period);

        $allocations = ProfitAllocation::where('user_id', $user->id)
            ->where('period', $period)
            ->get();

        $totalRevenue = CashTransaction::where('user_id', $user->id)
            ->income()
            ->forPeriod($period)
            ->sum('amount');

        $history = ProfitAllocation::where('user_id', $user->id)
            ->orderByDesc('period')
            ->get()
            ->groupBy('period');

        return view('cashcore.allocation.index', compact('allocations', 'totalRevenue', 'period', 'history'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $period = $request->input('period', now()->format('Y-m'));

        $request->validate([
            'allocations' => 'required|array',
            'allocations.*.bucket' => 'required|string',
            'allocations.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        $total = collect($request->allocations)->sum('percentage');
        if (abs($total - 100) > 0.01) {
            return back()->withErrors(['allocations' => __('cashcore.allocations_must_equal_100')]);
        }

        foreach ($request->allocations as $data) {
            ProfitAllocation::updateOrCreate(
                ['user_id' => $user->id, 'bucket' => $data['bucket'], 'period' => $period],
                ['percentage' => $data['percentage']]
            );
        }

        $this->service->calculateProfitAllocations($user->id, $period);

        return redirect()->route('cashcore.allocation.index', ['period' => $period])
            ->with('success', __('cashcore.allocation_saved'));
    }
}
