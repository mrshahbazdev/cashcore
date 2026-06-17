<?php

namespace App\Http\Controllers\CashCore;

use App\Http\Controllers\Controller;
use App\Models\CashCategory;
use App\Services\CashCoreService;
use Illuminate\Http\Request;

class CashDashboardController extends Controller
{
    public function __construct(private CashCoreService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $period = $request->get('period', now()->format('Y-m'));

        CashCategory::getDefaults($user->id);

        $data = $this->service->getDashboardData($user->id, $period);

        return view('cashcore.dashboard', $data);
    }
}
