<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateARDSalespersonMasterfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_r_d_salesperson_masterfiles', function (Blueprint $table) {
            $table->id();
            $table->string('division', 2)->nullable(true)->nullable(true);
            $table->string('salespersonNumber', 5)->nullable(true);
            $table->string('name', 50)->nullable(true);
            $table->string('addressLine1', 50)->nullable(true);
            $table->string('addressLine2', 50)->nullable(true);
            $table->string('city', 30)->nullable(true);
            $table->string('state', 10)->nullable(true);
            $table->string('zipCode', 10)->nullable(true);
            $table->string('rxtension', 10)->nullable(true);
            $table->string('salesManagerDivision', 10)->nullable(true);
            $table->string('salesManager', 10)->nullable(true);
            $table->string('telephoneNo', 20)->nullable(true);
            $table->string('addressLine3', 50)->nullable(true);
            $table->string('countryCode', 5)->nullable(true);
            $table->string('emailAddress', 80)->nullable(true);
            $table->decimal('commRate', 16, 8)->nullable(true);
            $table->decimal('salesPTD', 16, 8)->nullable(true);
            $table->decimal('salesYTD', 16, 8)->nullable(true);
            $table->decimal('salesPYR', 16, 8)->nullable(true);
            $table->decimal('profitPTD', 16, 8)->nullable(true);
            $table->decimal('profitYTD', 16, 8)->nullable(true);
            $table->decimal('profitPYR', 16, 8)->nullable(true);
            $table->decimal('commPTD', 16, 8)->nullable(true);
            $table->decimal('commYTD', 16, 8)->nullable(true);
            $table->decimal('commPYR', 16, 8)->nullable(true);
            $table->decimal('salesManagerRate', 16, 8)->nullable(true);
            $table->decimal('salesNextPeriod', 16, 8)->nullable(true);
            $table->decimal('profitNextPeriod', 16, 8)->nullable(true);
            $table->decimal('commNextPeriod', 16, 8)->nullable(true);
            $table->boolean('multipleReps')->nullable(true);
            $table->string('splitRep1', 20)->nullable(true);
            $table->string('splitRep2', 20)->nullable(true);
            $table->string('splitRep3', 20)->nullable(true);
            $table->decimal('splitRepPct1', 16, 8)->nullable(true);
            $table->decimal('splitRepPct2', 16, 8)->nullable(true);
            $table->decimal('splitRepPct3', 16, 8)->nullable(true);
            $table->string('srGroup', 50)->nullable(true);
            $table->string('splitRep4', 50)->nullable(true);
            $table->string('splitRep5', 50)->nullable(true);
            $table->decimal('splitRepPct4', 16, 8)->nullable(true);
            $table->decimal('splitRepPct5', 16, 8)->nullable(true);
            $table->boolean('export')->nullable(true);
            $table->string('drawAmount', 20)->nullable(true);
            $table->timestamp('dateLastProcessed')->nullable(true);
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
        Schema::dropIfExists('a_r_d_salesperson_masterfiles');
    }
}
