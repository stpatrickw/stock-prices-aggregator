<?php

namespace App\Console\Commands;

use App\Models\StockSymbolModel;
use App\Services\StockMarketSourceInterface;
use App\Services\StockService;
use Illuminate\Console\Command;

class SyncStockTimeSeriesCommand extends Command
{

    private const SYNC_INTERVAL = StockMarketSourceInterface::INTERVAL_1MIN;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stock-series';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize stock time series from stock sources';

    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        parent::__construct();
        $this->stockService = $stockService;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all stock symbols (limited by 10, the most known as Apple, Google, Meta, Amazon)
        $symbolModels = StockSymbolModel::all();

        foreach ($symbolModels as $symbolModel) {
           $this->stockService->syncTimeSeries($symbolModel, self::SYNC_INTERVAL);
        }

        $this->output->write('Done', true);

        return Command::SUCCESS;
    }
}
