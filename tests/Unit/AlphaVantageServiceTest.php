<?php

namespace Tests\Unit;

use App\Exceptions\RequestException;
use App\Services\AlphaVantage\AlphaVantageAutoMapperConfig;
use App\Services\AlphaVantage\AlphaVantageService;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use PHPUnit\Framework\TestCase;

class AlphaVantageServiceTest extends TestCase
{

    private AutoMapper $autoMapper;
    private PendingRequest $client;

    public function setUp(): void
    {
        $config = new AutoMapperConfig();
        (new AlphaVantageAutoMapperConfig())->configure($config);
        $this->autoMapper = new AutoMapper($config);
        $this->client = $this->getMockBuilder(PendingRequest::class)
            ->getMock();
    }

    //The System Under Test
    private function getSUT(string $rawResponse)
    {
        $response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->method('json')
            ->willReturn(json_decode($rawResponse, true));
        $this->client->method('get')
            ->willReturn($response);

        return new AlphaVantageService($this->autoMapper, $this->client, 'testApiKey');
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetTimeSeries(string $rawResponse, int $seriesCount)
    {
        $SUT = $this->getSUT($rawResponse);
        $result = $SUT->getTimeSeries('IBM', '1min');

        $this->assertCount($seriesCount, $result);
    }

    /**
     * @dataProvider dataProviderThrows
     */
    public function testGetTimeSeriesThrows(string $rawResponse)
    {
        $SUT = $this->getSUT($rawResponse);
        $this->expectException(RequestException::class);
        $SUT->getTimeSeries('IBM', '1min');
    }

    public function dataProvider(): array
    {
        return [
            [
                'rawResponse' => '{
                    "Meta Data": {
                        "1. Information": "Intraday (1min) open, high, low, close prices and volume",
                        "2. Symbol": "IBM",
                        "3. Last Refreshed": "2023-11-28 19:41:00",
                        "4. Interval": "1min",
                        "5. Output Size": "Compact",
                        "6. Time Zone": "US/Eastern"
                    },
                    "Time Series (1min)": {
                        "2023-11-28 19:41:00": {
                            "1. open": "155.9000",
                            "2. high": "155.9000",
                            "3. low": "155.9000",
                            "4. close": "155.9000",
                            "5. volume": "1"
                        },
                        "2023-11-28 19:36:00": {
                            "1. open": "155.9000",
                            "2. high": "155.9000",
                            "3. low": "155.9000",
                            "4. close": "155.9000",
                            "5. volume": "1"
                        },
                        "2023-11-28 19:26:00": {
                            "1. open": "155.9000",
                            "2. high": "155.9000",
                            "3. low": "155.9000",
                            "4. close": "155.9000",
                            "5. volume": "1"
                        },
                        "2023-11-28 19:17:00": {
                            "1. open": "155.7700",
                            "2. high": "155.7700",
                            "3. low": "155.7700",
                            "4. close": "155.7700",
                            "5. volume": "1"
                        },
                        "2023-11-28 19:00:00": {
                            "1. open": "155.6500",
                            "2. high": "155.6500",
                            "3. low": "155.6500",
                            "4. close": "155.6500",
                            "5. volume": "543741"
                        }
                    }
                    }',
                'timeSeriesCount' => 5
            ],
        ];
    }

    public function dataProviderThrows(): array
    {
        return [
            [
                'rawResponse' =>     '{
                    "Information": "Thank you for using Alpha Vantage! Our standard API rate limit is 25 requests per day. Please subscribe to any of the premium plans at https://www.alphavantage.co/premium/ to instantly remove all daily rate limits."
                    }',
            ],
        ];
    }
}
