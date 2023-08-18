<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SplitCommTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('split_comm_Temps', function (Blueprint $table) {
            $table->id();
            $table->string('Invoice_number')->nullable(true);
            $table->string('sales_person')->nullable(true);
            $table->string('sales_person_rate')->nullable(true);
            $table->string('split_percent')->nullable(true);
            $table->string('txn_number')->nullable(true);
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
        Schema::dropIfExists('split_comm_Temps');
    }
}
