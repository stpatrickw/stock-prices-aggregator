<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockControllerTest extends TestCase
{

    public function testSymbolListResponse()
    {
        $this->withBasicAuth('demo', 'Stock123Market');
        $response = $this->get('/api/stock/symbol/list');

        $response->assertStatus(200);
        $symbolList = $response->json();
        $this->assertNotEmpty($symbolList, 'Symbol list is empty.');
        $this->assertArrayHasKey('name', $symbolList[0], 'Key "name" is missing in response.');
        $this->assertArrayHasKey('symbol', $symbolList[0], 'Key "symbol" is missing in response.');
    }

    public function testLatestPriceBySymbolResponse()
    {
        $this->withBasicAuth('demo', 'Stock123Market');
        $response = $this->get('/api/stock/latest-price/IBM');

        $response->assertStatus(200);
        $latestPrices = $response->json();
        $this->assertNotEmpty($latestPrices, 'Price is empty.');
        $this->assertCount(1, $latestPrices);
        $this->assertArrayHasKey('symbol', $latestPrices[0], 'Key "symbol" is missing in response.');
        $this->assertArrayHasKey('price', $latestPrices[0], 'Key "price" is missing in response.');
        $this->assertEquals('IBM', $latestPrices[0]['symbol']);
    }

    public function testLatestPriceWithoutSymbolResponse()
    {
        $this->withBasicAuth('demo', 'Stock123Market');
        $response = $this->get('/api/stock/latest-price');

        $response->assertStatus(200);
        $latestPrices = $response->json();
        $this->assertNotEmpty($latestPrices, 'Price is empty.');
        $this->assertTrue(count($latestPrices) > 1);
    }

    public function testPriceChangesBySymbolResponse()
    {
        $this->withBasicAuth('demo', 'Stock123Market');
        $response = $this->get('/api/stock/price-changes/IBM');

        $response->assertStatus(200);
        $priceChanges = $response->json();
        $this->assertNotEmpty($priceChanges, 'Price changes is empty.');
        $this->assertArrayHasKey('symbol', $priceChanges[0], 'Key "symbol" is missing in response.');
        $this->assertArrayHasKey('priceChange', $priceChanges[0], 'Key "priceChange" is missing in response.');
        $this->assertEquals('IBM', $priceChanges[0]['symbol']);
    }

    public function testPriceChangesWithoutSymbolResponse()
    {
        $this->withBasicAuth('demo', 'Stock123Market');
        $response = $this->get('/api/stock/price-changes');

        $response->assertStatus(200);
        $priceChanges = $response->json();
        $this->assertNotEmpty($priceChanges, 'Price changes is empty.');
        $this->assertTrue(count($priceChanges) > 1);
    }
}
