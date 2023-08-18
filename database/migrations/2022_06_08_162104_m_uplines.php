<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MUplines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uplines', function (Blueprint $table) {
            $table->id();
            $table->string('RepIDs')->nullable(true);
            $table->string('MgrID')->nullable(true);
            $table->string('MgrName')->nullable(true);
            $table->string('MgrRate')->nullable(true);
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
