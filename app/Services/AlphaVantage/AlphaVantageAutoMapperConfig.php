<?php
namespace App\Services\AlphaVantage;

use App\Models\StockTimeSeriesEntity;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\DataType;


class AlphaVantageAutoMapperConfig
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config
            ->registerMapping(DataType::ARRAY, StockTimeSeriesEntity::class)
            ->forMember('open', function (array $source) {
                return (float)$source['1. open'];
            })
            ->forMember('high', function (array $source) {
                return (float)$source['2. high'];
            })
            ->forMember('low', function (array $source) {
                return (float)$source['3. low'];
            })
            ->forMember('close', function (array $source) {
                return (float)$source['4. close'];
            })
            ->forMember('volume', function (array $source) {
                return (float)$source['5. volume'];
            });
    }
}
