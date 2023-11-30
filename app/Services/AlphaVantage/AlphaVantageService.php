<?php
namespace App\Services\AlphaVantage;

use App\Exceptions\RequestException;
use App\Models\StockTimeSeriesEntity;
use App\Services\StockMarketSourceInterface;
use AutoMapperPlus\AutoMapperInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;

class AlphaVantageService implements StockMarketSourceInterface
{
    public const FUNCTION_TIME_SERIES_INTRADAY = 'TIME_SERIES_INTRADAY';


    public function __construct(
        private AutoMapperInterface $mapper,
        private PendingRequest $client,
        private string $apiKey
    ) {}

    /**
     * @param string $symbol
     * @param string $interval
     * @return array
     * @throws RequestException
     */
    public function getTimeSeries(string $symbol, string $interval): array
    {
        $dataResponse = $this->doRequest([
            'function' => self::FUNCTION_TIME_SERIES_INTRADAY,
            'symbol' => $symbol,
            'interval' => $interval
        ]);

        $timeSeriesSectionKey = sprintf('Time Series (%s)', $interval);
        if (!isset($dataResponse[$timeSeriesSectionKey])) {
            throw new RequestException(array_pop($dataResponse));
        }
        $dataSeries = $dataResponse[$timeSeriesSectionKey] ?? [];

        $timeSeries = [];
        foreach ($dataSeries as $time => $item) {
            /** @var StockTimeSeriesEntity $timeItem */
            $timeItem = $this->mapper->map($item, StockTimeSeriesEntity::class);
            $timeItem->openTime = Carbon::parse($time);
            $timeItem->interval = $interval;
            $timeSeries[] = $timeItem;
        }

        return $timeSeries;
    }

    /**
     * @param array $parameters
     * @return array|mixed
     */
    private function doRequest(array $parameters)
    {
        $parameters = array_merge($parameters, ['apikey' => $this->apiKey]);
        $queryRequest = http_build_query($parameters);
        $response = $this->client->get('?' . $queryRequest);
        return $response->json();
    }
}
