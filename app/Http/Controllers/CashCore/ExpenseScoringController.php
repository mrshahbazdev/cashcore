<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\ExpenseScore;
use Illuminate\Http\Request;

class ExpenseScoringController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $unscoredExpenses = CashTransaction::where('user_id', $user->id)
            ->expense()
            ->doesntHave('expenseScore')
            ->with('category')
            ->orderByDesc('amount')
            ->get();

        $scoredExpenses = CashTransaction::where('user_id', $user->id)
            ->expense()
            ->has('expenseScore')
            ->with('category', 'expenseScore')
            ->orderByDesc('amount')
            ->get();

        return view('cashcore.scoring.index', compact('unscoredExpenses', 'scoredExpenses'));
    }

    public function score(Request $request, CashTransaction $transaction)
    {
        abort_if($transaction->user_id !== $request->user()->id, 403);

        return view('cashcore.scoring.score', compact('transaction'));
    }

    public function storeScore(Request $request, CashTransaction $transaction)
    {
        abort_if($transaction->user_id !== $request->user()->id, 403);

        $validated = $request->validate([
            'purpose' => 'nullable|string|max:255',
            'benefit' => 'nullable|string|max:255',
            'revenue_score' => 'required|integer|min:0|max:10',
            'efficiency_score' => 'required|integer|min:0|max:10',
            'strategic_score' => 'required|integer|min:0|max:10',
            'usage_score' => 'required|integer|min:0|max:10',
        ]);

        $score = ExpenseScore::updateOrCreate(
            ['cash_transaction_id' => $transaction->id],
            array_merge($validated, ['user_id' => $request->user()->id])
        );

        $score->calculateTotal();
        $score->save();

        return redirect()->route('cashcore.scoring.index')
            ->with('success', __('cashcore.score_saved'));
    }
}
