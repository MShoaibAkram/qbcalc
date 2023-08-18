<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProfitRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_ranges', function (Blueprint $table) {
            $table->id();
            $table->string("to_pct")->nullable(true);
            $table->string("from_pct")->nullable(true);
            $table->string("pct_of")->nullable(true);
            $table->string("sales_rep")->nullable(true);
            $table->string("from_date")->nullable(true);
            $table->string("to_date")->nullable(true);
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
        Schema::dropIfExists('profit_ranges');
    }
}
