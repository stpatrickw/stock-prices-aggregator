<?php

namespace App\Models;


class PriceChangeEntity
{

    public string $name;
    public string $symbol;
    public float $price;
    public float $priceChange;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->symbol = $data['symbol'];
        $this->price = $data['price'];
        $this->priceChange = $data['priceChange'];
    }
}
