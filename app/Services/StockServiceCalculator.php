<?php
namespace App\Services;


class StockServiceCalculator
{

    public static function calculatePercentageChange(float $currentPrice, float $previousPrice)
    {
        return round(($currentPrice - $previousPrice) / $previousPrice * 100, 2);
    }

}
