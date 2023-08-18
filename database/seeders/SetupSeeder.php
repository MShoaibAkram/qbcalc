<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Setup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setups')->truncate();
        Setup::create([
            'CommMethod' => 'Gross Profit',
            'CustomerComm' => 'No',
            'OverMethod' => 'Amount Subject',
            'LimitLevels' => 'No',
            'OverLevels' => 3,
            'DefaultRep' => 'HOUS',
            'FromDateInvSel' => '2022-05-12 15:03:41',
            'ToDateInvSel' => '2022-05-12 15:03:41',
            'DisableQB' => 'No',
            'PostComplete' => 'No',
            'BypassMethod' => 'Bypass Normally',
            'RangeMethod' => 'Do Not Use Ranges',
            'CostMethod' => 'Purchase',
            'GroupBillsByMgr' => 'No',
            'InvoiceQty' => 'VPX',
            'MinGPAmt' => 0,
            'QBDataFileName' => null,
            'CompanyName' => 'DSD EXPRESS',
            'RunSetupWizard' => 0,
            'FromDateJob' => '2022-05-12 15:03:41',
            'ToDateJob' => '2022-05-12 15:03:41',
            'LastGetInvMethod' => 'paid',
            'AddlCostDesc' => null,
            'LinkPOCosts' => -1,
            'UseSalesReceipts' => 0,
            'POLinkMatchDesc' => 0,
            'BypassOnDesc' => 0,
            'UseSalesOrdersAsProjection' => 0,
            'KeepZeroLineItems' => -1,
            'ZeroQtyDefault' => 0,
            'UOMEnabled' => 0,
            'CostMarkupPct' => 0,
            'StdCostAsIs' => 0,
            'DefaultRepCommRate' => 30,
            'UnassignedInvoiceRep' => 'House',
            'ZipForReports' => 'Bill To',
            'StdCostPctOfPrice' => 0,
            'UseStdCostPctOfPrice' => 0,
            'CostMarkupAmt' => 0,
            'InvoiceCostMarkupAmt' => 0,
            'CustomOverride' => null,
            'CustomCalcBreakpoint' => 0,
            'CustomCalcPctLT' => 9.5,
            'CustomCalcPctGE' => 12.76,
            'QWPrefix' => 'AAAt',
            'Dummy1' => 0,
            'LastCSVImportFileName' => 'C:\TCNMOD\SAP_Inboud_Export_January_27_2012_09_44.csv',
            'SalesRepItemRates' => 0,
            'UsingFishbowl' => 0,
            'UseZipCodes' => -1,
            'ZipCodeType' => 1,
            'StdCostFieldName' => null,
            'ApplyDraws' => 0,
            'UseZeroInvoices' => 0,
            'StdCostIsPct' => 0
        ]);
    }
}
