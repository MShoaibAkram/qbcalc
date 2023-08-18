<?php

namespace App\Http\Controllers;

use App\Helpers\GCalcCommissions;
use App\Models\ARDSalespersonMasterfile;
use App\Models\Item;
use App\Models\QbInvoice;
use App\Models\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function commReportBySalesRep() {

        $ardSalesMasterFile = DB::table('a_r_d_salesperson_masterfiles')->groupBy('salespersonNumber')->get();
        return view("pages.report.comm-report-by-sales-rep", compact('ardSalesMasterFile'));
    }

    public function postCommReportBySalesRep(Request $request){
        $selectedRepId = $request->get('repId');
        $filter = '';
        switch($selectedRepId){
            case 'ALL':
                $filter = '';
                break;
            case 'EXCLUDE HOUS':
                $filter = 'a_r_d_salesperson_masterfiles.salespersonNumber != "HOUS"';
                break;
            default:
                $filter = "a_r_d_salesperson_masterfiles.salespersonNumber = '".$selectedRepId."'";
        }

        new GCalcCommissions($filter);

       // $rsSalesRepses = ARDSalespersonMasterfile::all();

        if($filter == ''){
            $results = DB::table('qb_invoices')->join('a_r_d_salesperson_masterfiles', 'qb_invoices.sales_rep_ref', '=', 'a_r_d_salesperson_masterfiles.salespersonNumber')
                ->selectRaw('a_r_d_salesperson_masterfiles.name as name, count(qb_invoices.sub_total) as qty, SUM(qb_invoices.sub_total) as total, qb_invoices.customer_ref as ref_number,  qb_invoices.ref_number as item_ref,  qb_invoices.txn_date as txn_date')
                ->groupBy('qb_invoices.sales_rep_ref')
                ->get();
        }else{

            $results = DB::table('qb_invoices')->join('a_r_d_salesperson_masterfiles', 'qb_invoices.sales_rep_ref', '=', 'a_r_d_salesperson_masterfiles.salespersonNumber')
                ->whereRaw($filter)
                ->selectRaw('a_r_d_salesperson_masterfiles.name as name, count(qb_invoices.sub_total) as qty, SUM(qb_invoices.sub_total) as total, qb_invoices.customer_ref as ref_number, qb_invoices.ref_number as item_ref, qb_invoices.txn_date as txn_date')
                ->groupBy('qb_invoices.sales_rep_ref')
                ->get();
        }

        $finalResults = [];
        foreach ($results as $res){
            $item = Item::where('item_id', $res->item_ref)->first();
            if($item != null){
               $res->item = $item;
               $finalResults[] = $res;
            }
        }


        $fromDate = null;
        $toDate = null;



        if(count($finalResults) != 0){
            $fromDate = collect($finalResults)->min('txn_date');
            $toDate = collect($finalResults)->max('txn_date');
        }


        return view('pages.report.result.comm_report_by_sales_rep', compact('finalResults', 'selectedRepId', 'fromDate', 'toDate'));



    }

    public function unpaidInvoice() {
        return view("pages.report.unpaid-invoice");
    }

    public function current() {
        return view("pages.report.current");
    }

    public function history() {
        return view("pages.report.history");
    }

    public function other() {
        return view("pages.report.other");
    }
}
