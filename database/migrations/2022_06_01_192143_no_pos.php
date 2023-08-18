<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NoPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_pos', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_ref_number')->nullable(true);
            $table->string('invoice_txn_number')->nullable(true);
            $table->string('invoice_item_ref_name')->nullable(true);
            $table->string('invoice_item_description')->nullable(true);
            $table->string('line')->nullable(true);
            $table->string('so_ref_number')->nullable(true);
            $table->string('po_ref_number')->nullable(true);
            $table->string('bill_ref_number')->nullable(true);
            $table->string('cost_used')->nullable(true);
            $table->string('message')->nullable(true);
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
        Schema::dropIfExists('no_pos');
    }
}
