<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTimeSeriesModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_time_series';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'opentime',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'interval'
    ];

    public function stockSymbol(): BelongsTo
    {
        return $this->belongsTo(StockSymbolModel::class);
    }

}
