<?php
namespace App\Services;

use App\Models\StockTimeSeriesEntity;

interface StockMarketSourceInterface
{
    public const INTERVAL_1MIN = '1min';
    public const INTERVAL_5MIN = '5min';
    public const INTERVAL_15MIN = '15min';
    public const INTERVAL_30MIN = '30min';
    public const INTERVAL_60MIN = '60min';

    /**
     * @param string $interval Interval in minutes
     * @return StockTimeSeriesEntity[]
     */
    public function getTimeSeries(string $symbol, string $interval): array;
}
