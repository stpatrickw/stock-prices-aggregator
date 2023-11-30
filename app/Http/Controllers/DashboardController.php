<?php

namespace App\Http\Controllers;

use App\Models\StockSymbolModel;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function dashboardAction(Request $request): View
    {
        $symbolModels = StockSymbolModel::all()->all();
        $priceChanges = $this->stockService->getPriceChanges($symbolModels);

        return view($request->ajax() ? 'symbols-list' : 'dashboard', [
            'symbols' => $priceChanges
        ]);
    }
}
