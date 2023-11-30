<?php

namespace App\Http\Controllers;


use App\Models\StockSymbolModel;
use App\Services\StockService;

class ApiStockController extends Controller
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function latestPriceAction($symbol = null)
    {
        if (!empty($symbol)) {
            $symbolModel = StockSymbolModel::where(['symbol' => $symbol])->first();
            if ($symbolModel === null) {
                return response()->json(['error' => 'Stock symbol is not found']);
            }
            $symbolModels[] = $symbolModel;
        } else {
            $symbolModels = StockSymbolModel::all();
        }

        foreach ($symbolModels as $symbolModel) {
            $price = $this->stockService->getLatestPrice($symbolModel->id);
            $prices[] = [
                'symbol' => $symbolModel->symbol,
                'price' => $price
            ];
        }

        return response()->json($prices);
    }

    public function priceChangesAction($symbol = null)
    {
        if (!empty($symbol)) {
            $symbolModel = StockSymbolModel::where(['symbol' => $symbol])->first();
            if ($symbolModel === null) {
                return response()->json(['error' => 'Stock symbol is not found']);
            }
            $symbolModels[] = $symbolModel;
        } else {
            $symbolModels = StockSymbolModel::all()->all();
        }

        $priceChanges = $this->stockService->getPriceChanges($symbolModels);

        return response()->json($priceChanges);
    }

    public function symbolListAction()
    {
        $symbolModels = StockSymbolModel::all();

        return response()->json($symbolModels);
    }
}
