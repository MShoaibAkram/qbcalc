<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("item_id")->nullable(true);
            $table->string("item")->nullable(true);
            $table->text("description")->nullable(true);
            $table->string("cost")->nullable(true);
            $table->string("price")->nullable(true);
            $table->string("rate")->nullable(true);
            $table->string("commission_amount")->nullable(true);
            $table->string("stdcost")->nullable(true);
            $table->string("rep")->nullable(true);
            $table->string("type")->nullable(true);
            $table->string("bonus")->nullable(true);
            $table->string("modified_at")->nullable(true);
            $table->string("qb_listId")->nullable(true);
            $table->string("stdcost_pct")->nullable(true);
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
        Schema::dropIfExists('items');
    }
}
