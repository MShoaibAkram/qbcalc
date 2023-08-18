<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MCommRpt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comm_rpt', function (Blueprint $table) {
            $table->id();
            $table->string('RepID')->nullable(true);
            $table->string('RepName')->nullable(true);
            $table->string('DownlineIDs')->nullable(true);
            $table->string('CustID')->nullable(true);
            $table->string('InvDate')->nullable(true);
            $table->string('PayDate')->nullable(true);
            $table->string('ApplyTo')->nullable(true);
            $table->string('CommRate')->nullable(true);
            $table->string('InvTotal')->nullable(true);
            $table->string('SalesSubject')->nullable(true);
            $table->string('CostSubject')->nullable(true);
            $table->string('CommAmt')->nullable(true);
            $table->string('InvAmtPaid')->nullable(true);
            $table->string('Split')->nullable(true);
            $table->string('CommType')->nullable(true);
            $table->string('Exception')->nullable(true);
            $table->string('Memo')->nullable(true);
            $table->string('PONumber')->nullable(true);
            $table->string('TxnNumber')->nullable(true);
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
        //
    }
}
