<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashScenario;
use App\Models\CashTransaction;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function index(Request $request)
    {
        $scenarios = CashScenario::where('user_id', $request->user()->id)->latest()->get();
        return view('cashcore.scenarios.index', compact('scenarios'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $period = now()->format('Y-m');
        $currentRevenue = CashTransaction::where('user_id', $user->id)->income()->forPeriod($period)->sum('amount');
        $currentCosts = CashTransaction::where('user_id', $user->id)->expense()->forPeriod($period)->sum('amount');

        return view('cashcore.scenarios.create', compact('currentRevenue', 'currentCosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'current_revenue' => 'required|numeric|min:0',
            'current_costs' => 'required|numeric|min:0',
            'adjusted_revenue' => 'required|numeric|min:0',
            'adjusted_costs' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['projected_profit'] = $validated['adjusted_revenue'] - $validated['adjusted_costs'];

        CashScenario::create($validated);

        return redirect()->route('cashcore.scenarios.index')
            ->with('success', __('cashcore.scenario_saved'));
    }

    public function destroy(Request $request, CashScenario $scenario)
    {
        abort_if($scenario->user_id !== $request->user()->id, 403);
        $scenario->delete();

        return redirect()->route('cashcore.scenarios.index')
            ->with('success', __('cashcore.scenario_deleted'));
    }
}
