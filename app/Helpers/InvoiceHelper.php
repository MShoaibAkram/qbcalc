<?php
/**
 * Created by PhpStorm.
 * User: shoaibakram
 * Date: 23/05/2022
 * Time: 11:23 PM
 */

namespace App\Helpers;

use App\Models\Invoice;
use App\Models\ProfitRange;
use App\Models\QbInvoice;
use App\Models\QbTempInvoice;
use App\Models\ReceivePaymentRequest;
use App\Models\Setup;
use App\Models\TempProfitVolumeRepRate;
use App\Models\TempSalesVolumeRepRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Query\JobType;
use App\Models\QbQueue;

class InvoiceHelper{


    private $rangeMethod;
    private $from_date;
    private $to_date;
    
    public $QUICKBOOKS_TESTING = true;
    public $HIGH_VALUE = "zzzzzzzz";
    public $sThisInvTxnID = '';
    public $sThisPmtAppTxnID = '';
    public $invoiceDeleted = false;
    public $currentRsInvoice = null;
    public $currentRsPayment = null;

    public function __construct($param){
        $this->rangeMethod = $param->range_method;
        if($param->from_date != null){
            $param->from_date = date('Y-m-d', strtotime($param->from_date));
        }else{
            $param->from_date = date('Y-m-d');
        }
        if($param->to_date != null){
            $param->to_date = date('Y-m-d', strtotime($param->to_date));
        }else{
            $param->to_date = date('Y-m-d');
        }
        $this->from_date = $param->from_date;
        $this->to_date = $param->to_date;

    }

    /*
        INSERT INTO qbInvoices ( TxnNumber, RefNumber, TxnDate, CustomerRef, CustomerName, SubTotal, SalesRepRef, PONumber, [Memo], Commission, Cost, InvoiceType, GrossProfit, TxnID, IsPaid, InvoiceTotal, DiscAmt, ShipToCity, BillToZip, ShipToName, ShipToAddr1, ShipToZip, CurrencyName, ExchangeRate, CurrencyListID, CustomData, DueDate, PaidDate )
SELECT qryCommissionable.TxnNumber, qryCommissionable.RefNumber, qryCommissionable.InvDate, qryCommissionable.Ref, qryCommissionable.Name, qryCommissionable.TotalCommissionable, qryCommissionable.RepRef, qryCommissionable.PONumber, qryCommissionable.Memo, qryCommissionable.TotalCommission, qryCommissionable.TotalCost, qryCommissionable.InvoiceType, qryCommissionable.GrossProfit, qryCommissionable.TxnID, qryCommissionable.PaidInFull, qryCommissionable.InvoiceTotal, qryCommissionable.Discount, qryCommissionable.MaxOfShipToCity, qryCommissionable.MaxOfBillToZip, qryCommissionable.MaxOfShipToName, qryCommissionable.MaxOfShipToAddr1, qryCommissionable.MaxOfShipToZip, qryCommissionable.CurrencyName, qryCommissionable.ExchangeRate, qryCommissionable.CurrencyListID, qryCommissionable.CustomData, qryCommissionable.DueDate, qryCommissionable.PaidDate
FROM qryCommissionable;
     */

    public function updateInvoiceData(){
        ini_set('memory_limit', '-1');
        try {
           
            $this->qryAppendCommissionableInvoices($this->qryCommissionable());

            if ($this->rangeMethod == 1) { //Sales Volume

                ProfitRange::where('pct_of', '!=', 'Gross Sales')->delete();
                $this->qryMakeTempSalesVolumeRepRates();
               
                QbInvoice::truncate();
                $this->qryAppendCommissionableInvoices($this->qryCommissionable());

            } elseif ($this->rangeMethod == 0) { //Over all Gross Profit
                ProfitRange::where('pct_of', '!=', 'Overall Gross Profit')->delete();
                $this->qryMakeTempProfitVolumeRepRates();
                $this->qryProfitVolumeCommRatesByRepWithDefaults();
                TempProfitVolumeRepRate::truncate();
                $this->qryUpdateCommissionsOnProfitVolume();
                QbInvoice::truncate();
                $this->qryAppendCommissionableInvoices($this->qryCommissionable());
            } elseif ($this->rangeMethod == 2) {
                ProfitRange::where('pct_of', '!=', 'Gross Sales')->delete();
                $this->qryMakeTempProfitVolumeRepRatesByInvoice();
                $this->qryProfitVolumeCommRatesByRepByInvoiceWithDefaults();
                ProfitRange::where('pct_of', '!=', 'Gross Sales')->delete();
                $this->qryUpdateCommissionsOnProfitVolumeByInvoice();
                QbInvoice::truncate();
                $this->qryAppendCommissionableInvoices($this->qryCommissionable());
            }


            \Log::info(print_r("here", true));

            $setupTbl = Setup::first();
            if ($setupTbl->CommMethod == 'Gross Profit') {
                $minGPAmt = $setupTbl->MinGPAmt;
                if ($minGPAmt > 0) {
                    QbInvoice::where('gross_profit', '<', $minGPAmt)->where('GrossProfit', '>', 0)->delete();
                }
            }

            \Log::info(print_r("here1", true));

            $setupTbl->PostComplete = 0;
            $setupTbl->save();

            //Calculate Commission
            $this->calculateCommission($this->from_date, $this->to_date, $setupTbl);
            //Calculation Commission End

            \Log::info(print_r("here2", true));
            $setupTbl = Setup::first();
            $qbInvoices = QbInvoice::all();
            if ($setupTbl->CommMethod == 'Sales') {
                foreach ($qbInvoices as $qbInvoice) {
                    $qbInvoice->cost = 0;
                    $qbInvoice->save();
                }

            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getLine(), true));
            \Log::info(print_r($e->getMessage(), true));
        }

    }


    private function calculateCommission($startDate, $endDate, &$setupTbl){
        $sLastInvTxnID = '';

        $payDiscInvInFull = false;
        $updated = false;
        $rsPayments = null;
        $cAmountPaid = null;

        $rsInvoicesIterator = 0;
        $rsPaymentIterator = 0;

    

        $rsInvoices = QbInvoice::orderBy('txn_id')->get();
        if(count($rsInvoices) > 0){
            $this->currentRsInvoice = $rsInvoices[$rsInvoicesIterator];
        }

        \Log::info(print_r("calculateCommission", true));

        if($setupTbl->LastGetInvMethod == 'allinvoices'){
            $dates = array($startDate, $endDate);
            $rsPayments = ReceivePaymentRequest::where('app_amount', '!=', '0')
                ->where('processed', '0')->whereBetween('app_txn_date', $dates)->orderBy('app_txn_id')->get();
        }else{

            $rsPayments = ReceivePaymentRequest::where('app_amount', '!=', 0)
                ->where('processed', null)->orderBy('app_txn_id')->get();
        }

        \Log::info(print_r("calculateCommission1", true));


        if(count($rsPayments) > 0){
            $this->currentRsPayment = $rsPayments[$rsPaymentIterator];
        }


        $this->invoiceDeleted = false;
        if($this->currentRsInvoice == null){
            $this->sThisInvTxnID = $this->HIGH_VALUE;
        }else{
            $this->currentRsInvoice->percent_paid = 0;
            $this->sThisInvTxnID = $this->currentRsInvoice->txn_id;
        }

        if($this->currentRsPayment == null){
            $this->sThisPmtAppTxnID = $this->HIGH_VALUE;
        }else{
            $this->sThisPmtAppTxnID = $this->currentRsPayment->app_txn_id;
        }

        \Log::info(print_r("calculateCommission:BeforeWhile", true));
        \Log::info(print_r($this->sThisPmtAppTxnID, true));
        \Log::info(print_r($this->sThisInvTxnID, true));

        while($this->sThisInvTxnID != $this->HIGH_VALUE && $this->sThisPmtAppTxnID != $this->HIGH_VALUE){

            \Log::info(print_r("calculateCommission:While1", true));
            if($this->sThisInvTxnID < $this->sThisPmtAppTxnID){
                \Log::info(print_r("calculateCommission:While2", true));
                if($payDiscInvInFull){
                    if(!$this->currentRsInvoice->is_paid){
                        $this->currentRsInvoice->percent_paid = 1;
                    }else{
                        if($setupTbl->UseSalesOrdersAsProjection){
                            $this->currentRsInvoice->percent_paid = 1;
                        }else{
                            $cAmount = ReceivePaymentRequest::where('app_txn_id', $this->currentRsInvoice->txn_id)->where('processed', '!=', 0)->sum('app_amount');
                            if($this->currentRsInvoice->invoice_total ==0){
                                $this->currentRsInvoice->percent_paid = 0;
                            }else{
                                $this->currentRsInvoice->percent_paid = $cAmount/$this->currentRsInvoice->invoice_total;
                            }
                        }
                    }
                }else{
                    \Log::info(print_r("calculateCommission:While3", true));
                    if($this->QUICKBOOKS_TESTING){
                        if($this->currentRsInvoice->is_paid == false){
                            $cAmount = ReceivePaymentRequest::where('app_txn_id', $this->currentRsInvoice->txn_id)->sum('app_amount');
                            if($this->currentRsInvoice->invoice_total ==0){
                                $this->currentRsInvoice->percent_paid = 0;
                            }else{
                                $this->currentRsInvoice->percent_paid = ($this->currentRsInvoice->invoice_total - $cAmount) / $this->currentRsInvoice->invoice_total;
                            }
                        }else{
                            if($this->currentRsInvoice->percent_paid != 1){
                                $this->currentRsInvoice->percent_paid = 0;
                            }
                        }
                    }
                    //IN CASE OF TESTING OFF
                    else{
                        if($setupTbl->UseSalesOrdersAsProjection){
                            $this->currentRsInvoice->percent_paid = 1;
                        }
                    }
                }
                \Log::info(print_r("calculateCommission:While4", true));
                if($this->currentRsInvoice->percent_paid == 0){
                    $this->invoiceDeleted = true;
                    $this->currentRsInvoice->delete();
                }else{
                    $this->currentRsInvoice->commission = $this->currentRsInvoice->commission * $this->currentRsInvoice->percent_paid;
                }
                $this->gotoNextInvoice($rsInvoices, $rsInvoicesIterator);
            }else{
                \Log::info(print_r("calculateCommission:While5", true));
                if($this->sThisInvTxnID == $this->sThisPmtAppTxnID){
                    if($this->currentRsInvoice->invoice_total == 0){
                        $this->currentRsInvoice->percent_paid = 1;
                    }else{
                        $this->currentRsInvoice->percent_paid = ($this->currentRsInvoice->percent_paid + $this->currentRsPayment->app_amount) / $this->currentRsInvoice->invoice_total;
                    }
                    $this->gotoNextPayment($rsPayments, $rsPaymentIterator, $updated);
                }else{
                    $this->gotoNextPayment($rsPayments, $rsPaymentIterator, $updated);
                }
            }

        }
    }


    private function gotoNextInvoice($rsInvoices, &$rsInvoicesIterator){
        \Log::info(print_r("GoToNextInvoice:1", true));
        if(!isset($this->currentRsInvoice)){
            $this->sThisInvTxnID = $this->HIGH_VALUE;
        }else{
            if($this->invoiceDeleted){
                $this->invoiceDeleted = false;
            }else{
                //rsInvoices.UpdateBatch
                $this->currentRsInvoice->update();
            }
            $rsInvoicesIterator += 1;
            if(count($rsInvoices) <= $rsInvoicesIterator){
                $this->sThisInvTxnID = $this->HIGH_VALUE;
            }else{
                $this->currentRsInvoice = $rsInvoices[$rsInvoicesIterator];
                $this->currentRsInvoice->percent_paid = 0;
                $this->sThisInvTxnID = $this->currentRsInvoice->txn_id;
                $this->currentRsInvoice->save();
            }
        }

        \Log::info(print_r("GoToNextInvoice:2", true));
    }

    private function gotoNextPayment($rsPayments, &$rsPaymentIterator, &$updated){
        \Log::info(print_r("gotoNextPayment:1", true));
        if(!isset($this->currentRsPayment)){
            $this->sThisPmtAppTxnID = $this->HIGH_VALUE;
        }else{
            if($this->currentRsPayment->processed){
                $this->currentRsPayment->upate();
            }

            $rsPaymentIterator += 1;
            if(count($rsPayments) <= $rsPaymentIterator){
                $this->sThisPmtAppTxnID = $this->HIGH_VALUE;
            }else{
                $this->currentRsPayment = $rsPayments[$rsPaymentIterator];
                $this->sThisPmtAppTxnID = $this->currentRsPayment->app_txn_id;
            }
        }
        $updated = false;
        \Log::info(print_r("gotoNextPayment:2", true));
    }


    //Query Helper Methods..
    /*
     * 2- qryAppendCommissionableInvoices
     * 3- qryMakeTempSalesVolumeRepRates
     * 4- qryUpdateCommissionsOnSalesVolume
     */

    private function qryCommissionable(){

        $qryCommissionables = QbTempInvoice::groupBy('currency_name', 'exchange_rate', 'currency_list_id', 'txn_number', 'ref_number', 'memo', 'po_number', 'invoice_type', 'txn_id', 'custom_data', 'due_date', 'paid_date')
            ->selectRaw('*, SUM(amount) as total_commissionable,
                 SUM(cost) as total_cost,
                  MAX(txn_date) as inv_date, 
                  MAX(customer_ref) as ref_number, 
                  MAX(customer_name) as name, 
                  MAX(invoice_total) as invoice_total, 
                  MAX(sales_rep_ref) as sales_rep_ref, 
                  SUM(commission) as total_commission, 
                  MAX(disc_amt) as discount, 
                  MAX(ship_to_city) as max_ship_to_city,
                  MAX(bill_to_zip) as max_bill_to_zip,
                  MAX(ship_to_name) as max_ship_to_name,
                  MAX(ship_to_adrr1) as max_ship_to_adrr1,
                  MAX(ship_to_zip) as max_ship_to_zip,
                  MAX(is_paid) as paid_in_full')->get();



       
        return $qryCommissionables;

    }


    private function qryAppendCommissionableInvoices($qryCommissionables){
        if(isset($qryCommissionables)){
            foreach($qryCommissionables as $qryCommissionable) {
                $qbInvoice = new QbInvoice();
                $qbInvoice->txn_number = $qryCommissionable->txn_number;
                $qbInvoice->txn_date = $qryCommissionable->txn_date;
                $qbInvoice->customer_ref = $qryCommissionable->customer_ref;
                $qbInvoice->customer_name = $qryCommissionable->name;
                $qbInvoice->sub_total = $qryCommissionable->total_commissionable;
                $qbInvoice->sales_rep_ref = $qryCommissionable->sales_rep_ref;
                $qbInvoice->po_number = $qryCommissionable->po_number;
                $qbInvoice->memo = $qryCommissionable->memo;
                $qbInvoice->commission = $qryCommissionable->total_commission;
                $qbInvoice->cost = $qryCommissionable->total_cost;
                $qbInvoice->invoice_type = $qryCommissionable->invoice_type;
                $qbInvoice->gross_profit = $qryCommissionable->total_amount - $qryCommissionable->total_cost;
                $qbInvoice->txn_id = $qryCommissionable->txn_id;

                if($qryCommissionable->paid_in_full == "true"){
                    $qbInvoice->is_paid = 1;
                }else{
                    $qbInvoice->is_paid = 0;
                }
                
                $qbInvoice->invoice_total = $qryCommissionable->invoice_total;
                $qbInvoice->disc_amt = $qryCommissionable->discount;
                $qbInvoice->ship_to_city = $qryCommissionable->max_ship_to_city;
                $qbInvoice->bill_to_zip = $qryCommissionable->max_bill_to_zip;
                $qbInvoice->ship_to_name = $qryCommissionable->max_ship_to_name;
                $qbInvoice->ship_to_adrr1 = $qryCommissionable->max_ship_to_adrr1;
                $qbInvoice->ship_to_zip = $qryCommissionable->max_ship_to_zip;
                $qbInvoice->currency_name = $qryCommissionable->currency_name;
                $qbInvoice->exchange_rate = $qryCommissionable->exchange_rate;
                $qbInvoice->currency_list_id = $qryCommissionable->currency_list_id;
                $qbInvoice->custom_data = $qryCommissionable->custom_data;
                $qbInvoice->due_date = $qryCommissionable->due_date;
                $qbInvoice->paid_date = $qryCommissionable->paid_date;
                $qbInvoice->save();
            }
        }

    }

    private function qryMakeTempSalesVolumeRepRates(){

        $qrySalesVolumeCommRatesByReps = DB::table('qb_invoices')
            ->selectRaw('*, qb_invoices.sales_rep_ref, SUM(sub_total) as sum_sub_total, MAX(qb_invoices.sales_rep_ref) as sales_rep_ref, MIN(profit) as min_profit, MAX(profit) as max_profit')
            ->join('profit_ranges', function($join){
                    $join->on('qb_invoices.sales_rep_ref', '<=', 'profit_ranges.sales_rep');
                    //$join->on('qb_invoices.to_pct', '<=', 'profit_ranges.to_pct');
                    //$join->on('qb_invoices.from_pct', '>=', 'profit_ranges.from_pct');
            })->get();


        if(isset($qrySalesVolumeCommRatesByReps)){
            foreach($qrySalesVolumeCommRatesByReps as $qrySalesVolumeCommRatesByRep){
                $tempSalesVol = new TempSalesVolumeRepRate();
                $tempSalesVol->sales_rep_ref = $qrySalesVolumeCommRatesByRep->sales_rep_ref;
                $tempSalesVol->sum_of_total = $qrySalesVolumeCommRatesByRep->sum_sub_total;
                //$tempSalesVol->rate = $qrySalesVolumeCommRatesByRep->rate;
                $tempSalesVol->save();
            }

        }

    }

    private function qryUpdateCommissionsOnSalesVolume(){

        $qryUpdateCommissionsOnSalesVolume =
            DB::table('qb_temp_invoices')
                ->join('temp_sales_volume_rep_rates', function($join){
                    $join->on('qb_temp_invoices.sales_rep_ref', '=', 'temp_sales_volume_rep_rates.sales_rep_ref');
                })
                ->update(
                    array('qb_temp_invoices.rate_percent' => 'temp_sales_volume_rep_rates.rate',
                        'qb_temp_invoices.commission'=> '(temp_sales_volume_rep_rates.rate*qb_temp_invoices.amount)/100'
                    ));
    }

    private function qryMakeTempProfitVolumeRepRates(){
        $qryProfitVolumeCommRatesByRep = DB::table('qb_invoices')
            ->selectRaw('*, qb_invoices.sales_rep_ref, SUM(sub_total) as sum_sub_total, MAX(qb_invoices.sales_rep_ref) as sales_rep_ref, MIN(profit) as min_profit, MAX(profit) as max_profit')
            ->join('profit_ranges', function($join){
                    $join->on('qb_invoices.sales_rep_ref', '=', 'profit_ranges.sales_rep');
                   // $join->on('qb_invoices.sales_rep_ref', '<=', 'profit_ranges.to_pct');
                    //$join->on('qb_invoices.sales_rep_ref', '>=', 'profit_ranges.from_pct');
            })
            ->get();
    }

    private function qryProfitVolumeCommRatesByRepWithDefaults(){
        //UPDATE tblTempProfitVolumeRepRates LEFT JOIN tblProfitRanges ON (tblTempProfitVolumeRepRates.Profit >= tblProfitRanges.FromPct) AND (tblTempProfitVolumeRepRates.Profit <= tblProfitRanges.ToPct) SET
        // tblTempProfitVolumeRepRates.Rate = [tblProfitRanges].[Rate],
        // tblTempProfitVolumeRepRates.PctOf = 'Overall Gross Profit'
        //WHERE (((tblTempProfitVolumeRepRates.Rate) Is Null) AND ((tblProfitRanges.SalesRep)='HOUS'));

        $qryUpdateCommissionsOnSalesVolume =
            DB::table('temp_profit_volume_rep_rates')
                ->join('profit_ranges', function($join){
                    $join->on('profit_ranges.sales_rep', '=', 'temp_profit_volume_rep_rates.sales_rep_ref');
                    $join->on('profit_ranges.to_pct', '<=', 'temp_profit_volume_rep_rates.to_pct');
                })
                ->update(
                    array('temp_profit_volume_rep_rates.rate' => 'profit_ranges.rate',
                        'temp_profit_volume_rep_rates.pct_of'=> 'Overall Gross Profit'
                    ))
                ->where('temp_profit_volume_rep_rates.rate', '=', '')->where('profit_ranges.sales_rep', '=', 'HOUS');

    }

    private function qryUpdateCommissionsOnProfitVolume(){
        //UPDATE qbTempInvoices INNER JOIN tblTempProfitVolumeRepRates
        // ON qbTempInvoices.SalesRepRef = tblTempProfitVolumeRepRates.SalesRepRef SET
        // qbTempInvoices.RatePercent = [tblTempProfitVolumeRepRates].[Rate], qbTempInvoices.Commission
        // = Round(Nz([tblTempProfitVolumeRepRates].[Rate])*(Nz([qbTempInvoices].[Amount])-Nz([qbTempInvoices].[Cost]))/100,2);

        $qryUpdateCommissionsOnSalesVolume =
            DB::table('qb_temp_invoices')
                ->join('temp_profit_volume_rep_rates', function($join){
                    $join->on('qb_temp_invoices.sales_rep_ref', '=', 'temp_profit_volume_rep_rates.sales_rep_ref');
                })
                ->update(
                    array('qb_temp_invoices.rate_percent' => 'temp_profit_volume_rep_rates.rate',
                        'qb_temp_invoices.commission'=> '(temp_sales_volume_rep_rates.rate*qb_temp_invoices.amount - qb_temp_invoices->cost)/100'
                    ));
    }

    private function qryMakeTempProfitVolumeRepRatesByInvoice(){

        $qryProfitVolumeCommRatesByRep = DB::table('invoices')
            ->selectRaw('*, sales_rep_ref, SUM(sub_total) as sum_sub_total, MAX(sales_rep_ref) as sales_rep_ref, MIN(profit) as min_profit, MAX(profit) as max_profit')
            ->join('profit_ranges', function($join){
                $qbInvoiceSalesref = QbInvoice::groupBy('sales_rep_ref', 'ref_number')->selectRaw('sales_rep_ref, SUM(sub_total), SUM(sales_rep_ref) as sales_rep_ref, MIN(profit) as min_profit, MAX(profit) as max_profit')->first();
                if($qbInvoiceSalesref != null){
                    $join->on($qbInvoiceSalesref->sales_rep_ref, '=', 'profit_ranges.sales_rep');
                    $join->on($qbInvoiceSalesref->profit, '<=', 'profit_ranges.to_pct');
                    $join->on($qbInvoiceSalesref->profit, '>=', 'profit_ranges.from_pct');
                }
                
            })->get();

    }

    private function qryProfitVolumeCommRatesByRepByInvoiceWithDefaults(){

        //UPDATE tblTempProfitVolumeRepRates LEFT JOIN tblProfitRanges ON (tblTempProfitVolumeRepRates.PctProfit <= tblProfitRanges.ToPct) AND (tblTempProfitVolumeRepRates.PctProfit >= tblProfitRanges.FromPct) SET tblTempProfitVolumeRepRates.Rate = [tblProfitRanges].[Rate], tblTempProfitVolumeRepRates.PctOf = 'Gross Profit'
        //WHERE (((tblTempProfitVolumeRepRates.Rate) Is Null) AND ((tblProfitRanges.SalesRep)='ALL'));

        $qryUpdateCommissionsOnSalesVolume =
            DB::table('temp_profit_volume_rep_rates')
                ->join('profit_ranges', function($join){
                    $join->on('profit_ranges.sales_rep', '=', 'temp_profit_volume_rep_rates.sales_rep_ref');
                    $join->on('profit_ranges.to_pct', '<=', 'temp_profit_volume_rep_rates.to_pct');
                })
                ->update(
                    array('temp_profit_volume_rep_rates.rate' => 'profit_ranges.rate',
                        'temp_profit_volume_rep_rates.pct_of'=> 'Gross Profit'
                    ))
                ->where('temp_profit_volume_rep_rates.rate', '=', '')->where('profit_ranges.sales_rep', '=', 'ALL');

    }

    private function qryUpdateCommissionsOnProfitVolumeByInvoice(){

        //UPDATE qbTempInvoices INNER JOIN
        // tblTempProfitVolumeRepRates ON qbTempInvoices.RefNumber = tblTempProfitVolumeRepRates.RefNumber
        // SET qbTempInvoices.RatePercent = [tblTempProfitVolumeRepRates].[Rate],
        // qbTempInvoices.Commission = Round(Nz([tblTempProfitVolumeRepRates].[Rate])*(Nz([qbTempInvoices].[Amount])-Nz([qbTempInvoices].[Cost]))/100,2);


        $qryUpdateCommissionsOnSalesVolume =
            DB::table('qb_temp_invoices')
                ->join('temp_profit_volume_rep_rates', function($join){
                    $join->on('qb_temp_invoices.ref_number', '=', 'temp_profit_volume_rep_rates.ref_number');
                })
                ->update(
                    array('qb_temp_invoices.rate_percent' => 'temp_profit_volume_rep_rates.rate',
                        'qb_temp_invoices.commission'=> '(temp_sales_volume_rep_rates.rate*qb_temp_invoices.amount - qb_temp_invoices->cost)/100'
                    ));

    }

}