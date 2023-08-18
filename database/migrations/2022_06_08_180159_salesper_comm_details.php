<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalesperCommDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SalesperCommDetails', function (Blueprint $table) {
            $table->id();
            $table->string('Division')->nullable(true);
            $table->string('SalespersonNumber')->nullable(true);
            $table->string('CustomerDivision')->nullable(true);
            $table->string('CustomerNumber')->nullable(true);
            $table->string('InvoiceNumber')->nullable(true);
            $table->string('InvoiceType')->nullable(true);
            $table->string('InvoiceDate')->nullable(true);
            $table->string('PayDate')->nullable(true);
            $table->string('ApplyToNumberCMDM')->nullable(true);
            $table->string('CommRate')->nullable(true);
            $table->string('InvoiceTotal')->nullable(true);
            $table->string('SalesSubjectToComm')->nullable(true);
            $table->string('CostSubjectToComm')->nullable(true);
            $table->string('CommAmount')->nullable(true);
            $table->string('InvoiceAmountPaid')->nullable(true);
            $table->string('SplitCommPercent')->nullable(true);
            $table->string('Name')->nullable(true);
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
