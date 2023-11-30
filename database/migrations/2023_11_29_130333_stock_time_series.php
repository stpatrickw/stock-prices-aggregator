<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_time_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_symbol_id');
            $table->string('interval');
            $table->dateTime('opentime');
            $table->float('open', 12);
            $table->float('high', 12);
            $table->float('low', 12);
            $table->float('close', 12);
            $table->float('volume', 12);

            $table->index('interval');
            $table->index('opentime');
            $table->foreign('stock_symbol_id')->references('id')->on('stock_symbols');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_time_series');
    }
};
