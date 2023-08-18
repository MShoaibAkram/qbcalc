<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setups', function (Blueprint $table) {
            $table->id();
            $table->string('CommMethod',50)->nullable(true);
            $table->string('CustomerComm',50)->nullable(true);
            $table->string('OverMethod',50)->nullable(true);
            $table->string('LimitLevels',50)->nullable(true);
            $table->string('OverLevels',50)->nullable(true);
            $table->string('DefaultRep',50)->nullable(true);
            $table->dateTime('FromDateInvSel')->nullable(true);
            $table->dateTime('ToDateInvSel')->nullable(true);
            $table->string('DisableQB',30)->nullable(true);
            $table->string('PostComplete',30)->nullable(true);
            $table->string('BypassMethod',30)->nullable(true);
            $table->string('RangeMethod',30)->nullable(true);
            $table->string('CostMethod',30)->nullable(true);
            $table->string('GroupBillsByMgr',30)->nullable(true);
            $table->string('InvoiceQty',30)->nullable(true);
            $table->string('MinGPAmt',30)->nullable(true);
            $table->string('QBDataFileName',30)->nullable(true);
            $table->string('CompanyName',30)->nullable(true);
            $table->string('RunSetupWizard',30)->nullable(true);
            $table->dateTime('FromDateJob')->nullable(true);
            $table->dateTime('ToDateJob')->nullable(true);
            $table->string('LastGetInvMethod',30)->nullable(true);
            $table->string('AddlCostDesc',30)->nullable(true);
            $table->string('LinkPOCosts',30)->nullable(true);
            $table->string('UseSalesReceipts',30)->nullable(true);
            $table->string('POLinkMatchDesc',30)->nullable(true);
            $table->string('BypassOnDesc',30)->nullable(true);
            $table->string('UseSalesOrdersAsProjection',30)->nullable(true);
            $table->string('KeepZeroLineItems',30)->nullable(true);
            $table->integer('ZeroQtyDefault')->unsigned()->nullable(true);
            $table->string('UOMEnabled',30)->nullable(true);
            $table->integer('CostMarkupPct')->unsigned()->nullable(true);
            $table->string('StdCostAsIs',30)->nullable(true);
            $table->integer('DefaultRepCommRate')->unsigned()->nullable(true);
            $table->string('UnassignedInvoiceRep',30)->nullable(true);
            $table->string('ZipForReports',30)->nullable(true);
            $table->integer('StdCostPctOfPrice')->unsigned()->nullable(true);
            $table->string('UseStdCostPctOfPrice',30)->nullable(true);
            $table->integer('CostMarkupAmt')->unsigned()->nullable(true);
            $table->integer('InvoiceCostMarkupAmt')->unsigned()->nullable(true);
            $table->string('CustomOverride',30)->nullable(true);
            $table->integer('CustomCalcBreakpoint')->unsigned()->nullable(true);
            $table->integer('CustomCalcPctLT')->unsigned()->nullable(true);
            $table->integer('CustomCalcPctGE')->unsigned()->nullable(true);
            $table->string('QWPrefix',50)->nullable(true);
            $table->string('Dummy1',30)->nullable(true);
            $table->string('LastCSVImportFileName',255)->nullable(true);
            $table->string('SalesRepItemRates',25)->nullable(true);
            $table->string('UsingFishbowl',25)->nullable(true);
            $table->string('UseZipCodes',25)->nullable(true);
            $table->integer('ZipCodeType')->unsigned()->nullable(true);
            $table->string('StdCostFieldName',25)->nullable(true);
            $table->string('ApplyDraws',50)->nullable(true);
            $table->string('UseZeroInvoices',50)->nullable(true);
            $table->string('StdCostIsPct',50)->nullable(true);
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
        Schema::dropIfExists('setups');
    }
}
