<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Invoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string("txn_id")->nullable(true);
            $table->string("modified_at")->nullable(true);
            $table->string("txn_number")->nullable(true);
            $table->string("txn_date")->nullable(true);
            $table->string("customer_list_id")->nullable(true);
            $table->string("customer_name")->nullable(true);
            $table->string("ref_number")->nullable(true);
            $table->string("due_date")->nullable(true);
            $table->string("sub_total")->nullable(true);
            $table->string("sales_tax_total")->nullable(true);
            $table->string("balance_remaining")->nullable(true);
            $table->string("applied_amount")->nullable(true);
            $table->string("is_paid")->nullable(true);
            $table->string("is_pending")->nullable(true);
            $table->string("sales_rep_ref")->nullable(true);
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
        Schema::dropIfExists('invoices');
    }
}
