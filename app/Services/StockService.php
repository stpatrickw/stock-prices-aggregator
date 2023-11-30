<?php
namespace App\Services;

use App\Exceptions\RequestException;
use App\Models\PriceChangeEntity;
use App\Models\StockSymbolModel;
use App\Models\StockTimeSeriesEntity;
use App\Models\StockTimeSeriesModel;
use Illuminate\Support\Facades\Cache;

class StockService
{
    protected const CACHE_TIME_SECONDS = 60;
    protected const CACHE_LATEST_PRICE_PREFIX = 'latest_price_';
    protected const CACHE_PREVIOUS_PRICE_PREFIX = 'previous_price_';

    public function __construct(
        protected StockMarketSourceInterface $stockMarketSource,
    ) {}

    public function getTimeSeries(string $symbol, string $interval = StockMarketSourceInterface::INTERVAL_1MIN): array
    {
        try {
            return $this->stockMarketSource->getTimeSeries($symbol, $interval);
        } catch (RequestException $exception) {
            // silently return en empty array if something goes wrong
            // In reality the logger should be added here
            return [];
        }
    }

    public function syncTimeSeries(
        StockSymbolModel $symbolModel,
        string $interval = StockMarketSourceInterface::INTERVAL_1MIN
    ): void {
        // Get the latest time series from the source (AlphaVantage, maybe changed later on)
        $series = $this->getTimeSeries($symbolModel->symbol);

        // Check if returned series don't exist in Database
        /** @var StockTimeSeriesEntity $item */
        foreach ($series as $item) {
            $stockTimeSeriesModel = StockTimeSeriesModel::where([
                'stock_symbol_id' => $symbolModel->id,
                'interval' => $interval,
                'opentime' => $item->openTime
            ])->get();

            if ($stockTimeSeriesModel->count() === 0) {
                $symbolModel->timeSeries()->create([
                    'opentime' => $item->openTime,
                    'open' => $item->open,
                    'high' => $item->high,
                    'low' => $item->low,
                    'close' => $item->close,
                    'volume' => $item->volume,
                    'interval' => $item->interval
                ]);
                $this->invalidateCaches($symbolModel->id);
            }
        }
    }

    public function getLatestPrice(int $symbolId)
    {
        return Cache::remember(self::CACHE_LATEST_PRICE_PREFIX . $symbolId, self::CACHE_TIME_SECONDS, function () use ($symbolId) {
            $latestTimeSeries = StockTimeSeriesModel::where(['stock_symbol_id' => $symbolId])
                ->orderByDesc('opentime')
                ->limit(1)
                ->first();
            return $latestTimeSeries?->close;
        });
    }

    public function getPreviousPrice(int $symbolId)
    {
        return Cache::remember(self::CACHE_PREVIOUS_PRICE_PREFIX . $symbolId, self::CACHE_TIME_SECONDS, function () use ($symbolId) {
            $previousTimeSeries = StockTimeSeriesModel::where(['stock_symbol_id' => $symbolId])
                ->orderByDesc('opentime')
                ->offset(1)
                ->limit(1)
                ->first();
            return $previousTimeSeries?->close;
        });
    }

    /**
     * @param StockSymbolModel[] $symbolModels
     * @return PriceChangeEntity[]
     */
    public function getPriceChanges(array $symbolModels): array
    {
        $priceChanges = [];
        foreach ($symbolModels as $symbolModel) {
            $currentPrice = $this->getLatestPrice($symbolModel->id);
            $previousPrice = $this->getPreviousPrice($symbolModel->id);

            $priceChanges[] = new PriceChangeEntity([
                'name' => $symbolModel->name,
                'symbol' => $symbolModel->symbol,
                'price' => $currentPrice,
                'priceChange' => StockServiceCalculator::calculatePercentageChange($currentPrice, $previousPrice)
            ]);
        }
        return $priceChanges;
    }

    private function invalidateCaches(int $symbolId)
    {
        Cache::forget(self::CACHE_LATEST_PRICE_PREFIX . $symbolId);
        Cache::forget(self::CACHE_PREVIOUS_PRICE_PREFIX . $symbolId);
    }

}
