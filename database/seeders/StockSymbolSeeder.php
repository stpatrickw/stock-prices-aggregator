<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSymbolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stock_symbols')->insert([
            'name' => 'International Business Machines Corp',
            'symbol' => 'IBM',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Apple Inc',
            'symbol' => 'AAPL',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Google',
            'symbol' => 'GOOG',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Amazon.com Inc',
            'symbol' => 'AMZN',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'NVIDIA Corp',
            'symbol' => 'NVDA',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Tesla Inc',
            'symbol' => 'TSLA',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Meta Platforms Inc - Class A',
            'symbol' => 'META',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Broadcom Inc',
            'symbol' => 'AVGO',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Walmart Inc',
            'symbol' => 'WMT',
        ]);
        DB::table('stock_symbols')->insert([
            'name' => 'Oracle Corp',
            'symbol' => 'ORCL',
        ]);
    }
}
