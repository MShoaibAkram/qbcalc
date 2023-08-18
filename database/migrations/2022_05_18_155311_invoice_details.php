<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->string("invoice_id");
            $table->string("txn_line_id"); 
            $table->string("item_ref_list_id");
            $table->string("item_ref_name");
            $table->string("desc");
            $table->string("quantity");
            $table->string("cost");
            $table->string("class_list_id");
            $table->string("class_name");
            $table->string("customer_list_id");
            $table->string("customer_name");
            $table->string("sales_tax_list_id");
            $table->string("sales_tax_name");
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
        Schema::dropIfExists('invoice_details');
    }
}
