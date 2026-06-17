<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Services\CashCoreService;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function __construct(private CashCoreService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->get('filter', 'all');
        $query = CashTransaction::where('user_id', $user->id)
            ->with('category')
            ->orderByDesc('transaction_date');

        if ($filter === 'income') {
            $query->income();
        } elseif ($filter === 'expense') {
            $query->expense();
        }

        $transactions = $query->paginate(20);
        $categories = CashCategory::where('user_id', $user->id)->get();

        return view('cashcore.transactions.index', compact('transactions', 'categories', 'filter'));
    }

    public function create(Request $request)
    {
        $categories = CashCategory::where('user_id', $request->user()->id)->get();
        return view('cashcore.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'cash_category_id' => 'nullable|exists:cash_categories,id',
            'transaction_date' => 'required|date',
            'is_recurring' => 'boolean',
            'recurring_interval' => 'nullable|in:monthly,quarterly,yearly',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['is_recurring'] = $request->boolean('is_recurring');

        CashTransaction::create($validated);

        return redirect()->route('cashcore.transactions.index')
            ->with('success', __('cashcore.transaction_created'));
    }

    public function edit(Request $request, CashTransaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);
        $categories = CashCategory::where('user_id', $request->user()->id)->get();
        return view('cashcore.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, CashTransaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'cash_category_id' => 'nullable|exists:cash_categories,id',
            'transaction_date' => 'required|date',
            'is_recurring' => 'boolean',
            'recurring_interval' => 'nullable|in:monthly,quarterly,yearly',
            'notes' => 'nullable|string',
        ]);

        $validated['is_recurring'] = $request->boolean('is_recurring');

        $transaction->update($validated);

        return redirect()->route('cashcore.transactions.index')
            ->with('success', __('cashcore.transaction_updated'));
    }

    public function destroy(Request $request, CashTransaction $transaction)
    {
        $this->authorizeOwner($request, $transaction);
        $transaction->delete();

        return redirect()->route('cashcore.transactions.index')
            ->with('success', __('cashcore.transaction_deleted'));
    }

    public function importForm()
    {
        return view('cashcore.transactions.import');
    }

    public function import(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $path = $request->file('csv_file')->getRealPath();
        $count = $this->service->importCsv($request->user()->id, $path);

        return redirect()->route('cashcore.transactions.index')
            ->with('success', __('cashcore.import_success', ['count' => $count]));
    }

    private function authorizeOwner(Request $request, CashTransaction $transaction): void
    {
        abort_if($transaction->user_id !== $request->user()->id, 403);
    }
}
