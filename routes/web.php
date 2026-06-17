<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Locale switcher
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'de'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return back();
})->name('locale.set');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

// ── CashCore – Profit First Financial Intelligence ─────────────────
Route::middleware('auth')->prefix('cashcore')->name('cashcore.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CashCore\CashDashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}/edit', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/import', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'importForm'])->name('transactions.import');
    Route::post('/transactions/import', [\App\Http\Controllers\CashCore\CashTransactionController::class, 'import'])->name('transactions.import.store');

    // Leak Detection
    Route::get('/leaks', [\App\Http\Controllers\CashCore\CashLeakController::class, 'index'])->name('leaks.index');
    Route::post('/leaks/detect', [\App\Http\Controllers\CashCore\CashLeakController::class, 'runDetection'])->name('leaks.detect');
    Route::put('/leaks/{leak}/status', [\App\Http\Controllers\CashCore\CashLeakController::class, 'updateStatus'])->name('leaks.status');

    // Expense Scoring
    Route::get('/scoring', [\App\Http\Controllers\CashCore\ExpenseScoringController::class, 'index'])->name('scoring.index');
    Route::get('/scoring/{transaction}', [\App\Http\Controllers\CashCore\ExpenseScoringController::class, 'score'])->name('scoring.score');
    Route::post('/scoring/{transaction}', [\App\Http\Controllers\CashCore\ExpenseScoringController::class, 'storeScore'])->name('scoring.store');

    // Cash Unlocker
    Route::get('/unlocker', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'index'])->name('unlocker.index');
    Route::get('/unlocker/create', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'create'])->name('unlocker.create');
    Route::post('/unlocker', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'store'])->name('unlocker.store');
    Route::get('/unlocker/{blocker}/edit', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'edit'])->name('unlocker.edit');
    Route::put('/unlocker/{blocker}', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'update'])->name('unlocker.update');
    Route::delete('/unlocker/{blocker}', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'destroy'])->name('unlocker.destroy');
    Route::put('/unlocker/{blocker}/status', [\App\Http\Controllers\CashCore\CashUnlockerController::class, 'updateStatus'])->name('unlocker.status');

    // Behavioral System
    Route::get('/behavior', [\App\Http\Controllers\CashCore\BehaviorController::class, 'index'])->name('behavior.index');
    Route::post('/behavior/review', [\App\Http\Controllers\CashCore\BehaviorController::class, 'scheduleReview'])->name('behavior.schedule');
    Route::get('/behavior/review/{review}', [\App\Http\Controllers\CashCore\BehaviorController::class, 'startReview'])->name('behavior.review');
    Route::post('/behavior/review/{review}/complete', [\App\Http\Controllers\CashCore\BehaviorController::class, 'completeReview'])->name('behavior.complete');
    Route::put('/behavior/alert/{alert}/read', [\App\Http\Controllers\CashCore\BehaviorController::class, 'markAlertRead'])->name('behavior.alert.read');
    Route::put('/behavior/alert/{alert}/dismiss', [\App\Http\Controllers\CashCore\BehaviorController::class, 'dismissAlert'])->name('behavior.alert.dismiss');

    // Scenario Simulator
    Route::get('/scenarios', [\App\Http\Controllers\CashCore\ScenarioController::class, 'index'])->name('scenarios.index');
    Route::get('/scenarios/create', [\App\Http\Controllers\CashCore\ScenarioController::class, 'create'])->name('scenarios.create');
    Route::post('/scenarios', [\App\Http\Controllers\CashCore\ScenarioController::class, 'store'])->name('scenarios.store');
    Route::delete('/scenarios/{scenario}', [\App\Http\Controllers\CashCore\ScenarioController::class, 'destroy'])->name('scenarios.destroy');

    // Profit Allocation
    Route::get('/allocation', [\App\Http\Controllers\CashCore\ProfitAllocationController::class, 'index'])->name('allocation.index');
    Route::post('/allocation', [\App\Http\Controllers\CashCore\ProfitAllocationController::class, 'update'])->name('allocation.update');
});
