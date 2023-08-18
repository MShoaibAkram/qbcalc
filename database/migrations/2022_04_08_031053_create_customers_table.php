<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("rep_id")->nullable();
            $table->string("type")->nullable();
            $table->string("terms")->nullable();
            $table->string("price_level")->nullable();
            $table->string("discount")->nullable();
            $table->string("class")->nullable();
            $table->integer("comm_rate")->nullable();
            $table->string("split_rep")->nullable();
            $table->integer("split_percent")->nullable();
            $table->string("qb_listId")->nullable();
            $table->date("modified_at")->nullable();
            $table->string("qb_listId_sales_rep")->nullable();
            $table->string("qb_listId_type")->nullable();
            $table->string("qb_listId_terms")->nullable();
            $table->date("estab_at")->nullable();
            $table->integer("comm_rateYr1")->nullable();
            $table->integer("comm_rateYr2")->nullable();
            $table->integer("comm_rateYr3")->nullable();
            $table->string("currency_listId")->nullable();
            $table->string("currency_name")->nullable();
            $table->string("company_name")->nullable();
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
        Schema::dropIfExists('customers');
    }
}
