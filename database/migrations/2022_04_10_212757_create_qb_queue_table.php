<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQbQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qb_queue', function (Blueprint $table) {
            $table->id();
            $table->string("queue_type");
            $table->string("param"); 
            $table->string("status");
            $table->string("RequestID");
            $table->string("iteratorID");
            $table->string("RemainingCount");
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
        Schema::dropIfExists('qb_queue');
    }
}
