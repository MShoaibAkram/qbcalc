<?php

namespace App\Http\Controllers;

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

class InvoiceController extends Controller
{

    

    public function getInvoice() {
        return view("pages.invoice.get");
    }

    public function viewInvoice() {

        return view("pages\invoice.view");
    }

    public function getInvoiceDetails(Request $request){
        $queryString = $request->get('queryStr');
        if(isset($queryString)){
            $invoice = Invoice::where('ref_number', $queryString)->first();
            if($invoice != null){
                return response()->json(['status'=>200, 'invoice'=>$invoice]);
            }else{
                return response()->json(['status'=>201, 'invoice'=>null]);
            }
        }else{
            return response()->json(['status'=>203, 'invoice'=>null]);
        }
    }

    public function getInvoices(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $rangeMethod = $request->commission_method;
        
        DB::table('qb_queue')->insert([
            array(
                'queue_type' => JobType::Load_customer,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Item,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
                
            ),
            array(
                'queue_type' => JobType::Load_Item_Inventory,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Item_Services_Inventory,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Item_Non_Inventory,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Item_Other_Charges,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Item_Group_Data,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Invoice,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
             ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Item_Inventory_Assembly,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),

            array(
                'queue_type' => JobType::Load_Deleted_Invoices,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Sales_Receipt_Payment,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_SalesRep,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Txn_Deleted,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            ),
            array(
                'queue_type' => JobType::Load_Credit_Memo,
                'param' => json_encode([
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'range_method' => $rangeMethod,
                ]),
                'status' => "ready",
                'RequestID' => "1",
                'iteratorID' => '0',
                'RemainingCount' => '0'
            )
        ]);

        return view("pages.invoice.get");
    }
}
