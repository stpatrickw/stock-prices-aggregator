<?php

namespace App\Models;

use Carbon\Carbon;

class StockTimeSeriesEntity
{

    public Carbon $openTime;
    public float $open;
    public float $high;
    public float $low;
    public float $close;
    public float $volume;
    public string $interval;

}
