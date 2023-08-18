<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblTempSalesReceiptRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_sales_volume_rep_rates', function (Blueprint $table) {
            $table->id();
            $table->string('sales_rep_ref');
            $table->string('sum_of_total');
            $table->string('to_pct');
            $table->string('pct_of');
            $table->string('rate');
            $table->timestamps();
        });

        Schema::create('temp_profit_volume_rep_rates', function (Blueprint $table) {
            $table->id();
            $table->string('sales_rep_ref');
            $table->string('to_pct');
            $table->string('profit');
            $table->string('rate');
            $table->string('pct_of');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_profit_volume_rep_rates');
        Schema::dropIfExists('temp_sales_volume_rep_rates');
    }
}
