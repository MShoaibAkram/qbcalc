<?php

/**
 * File contains class Qb_Clients() extends Qb()
 */

namespace App\QB;

use App\Helpers\InvoiceHelper;
use App\Models\ARDSalespersonMasterfile;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Item;
use App\Models\NoPos;
use App\Models\ProfitRange;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\QbQueue;
use App\Models\QbTempInvoice;
use App\Models\Setup;
use App\Models\SplitCommTemp;
use App\Query\JobType;
use App\Models\ReceivePaymentRequest;

class QBResponseHandler
{
    public function save($param)
    {
        $queue = \DB::table('qb_queue')->where('status', 'ready')->first();
        $result = '100';
        $invoiceHelper = null;        

        if ($queue) {
            try{
                if ($queue->queue_type == JobType::Load_customer) {
                    $result = $this->saveCustomer($param);
                }

                else if($queue->queue_type == JobType::Load_Item_Inventory_Assembly){
                    $result = $this->saveItemAssemblyInventoryData($param);
                }

                else if($queue->queue_type == JobType::Load_Item_Group_Data){
                    $result = $this->saveItemGroupData($param);
                }

                else if($queue->queue_type == JobType::Load_Item_Inventory){
                    $result = $this->saveItemInventory($param);
                }

                else if($queue->queue_type == JobType::Load_Item_Services_Inventory){
                    $result = $this->saveItemServices($param);
                }

                else if($queue->queue_type == JobType::Load_Item_Non_Inventory){
                    $result = $this->saveItemNonInventory($param);
                }

                else if($queue->queue_type == JobType::Load_Item_Other_Charges){
                    $result = $this->saveItemOtherCharges($param);

                }


                else if($queue->queue_type == JobType::Load_Invoice){
                    $result = $this->saveInvoice($param);
                }

                else if($queue->queue_type == JobType::Load_Deleted_Invoices){
                    $result = $this->saveDeletedInvoice($param);

                }

                else if($queue->queue_type == JobType::Load_Item) {
                    $result = $this->saveItem($param);
                }
                if ($queue->queue_type == JobType::Load_SalesRep) {
                    $result = $this->saveSalesRep($param);

                }
                if ($queue->queue_type == JobType::Load_Sales_Receipt_Payment) {
                    $result = $this->saveSalesReceiptPayment($param);
                }
                if ($queue->queue_type == JobType::Load_Txn_Deleted) {
                    $result = $this->saveTxnDeleted($param);
                }
                if ($queue->queue_type == JobType::Load_Credit_Memo) {
                    $result = $this->saveCreditMemo($param);
                }

                if($result == '100' && $queue->queue_type == JobType::Load_SalesRep){                    
                    //In case of all the data fetched call update invoice helper to calc and update commissions                    
                    $queueParam = json_decode($queue->param);
                    $invoiceHelper = new InvoiceHelper($queueParam);
                    \Log::info(print_r($invoiceHelper, true));
                    $invoiceHelper->updateInvoiceData();
                }
            } catch (\Exception $e) {
                \Log::info(print_r($e->getMessage(), true));
            }
        }

        return $result;
    }


    //INVOICE SAVE METHODS
    public function saveInvoice($param){
        try {

            //Truncate tables as per VBA logic

            $response = simplexml_load_string($param->response);
            $invoiceRef = $response->QBXMLMsgsRs->InvoiceQueryRs;

            \Log::info(print_r($invoiceRef, true));


            foreach ($invoiceRef->InvoiceRet as $invoice) {
                $invoiceTbl = new Invoice();

                $invoiceTbl->txn_id = $invoice->txnID;
                $invoiceTbl->modified_at = $invoice->TimeModified;
                $invoiceTbl->txn_number = $invoice->TxnNumber;
                if ($invoice->CustomerRef != null) {
                    $invoiceTbl->customer_list_id = $invoice->CustomerRef->ListID;
                    $invoiceTbl->customer_name = $invoice->CustomerRef->FullName;
                }
                $invoiceTbl->txn_date = $invoice->TxnDate;
                if ($invoice->RefNumber != null) {
                    $invoiceTbl->ref_number = $invoice->RefNumber;
                }
                if ($invoice->DueDate != null) {
                    $invoiceTbl->due_date = $invoice->DueDate;
                }
                if ($invoice->Subtotal != null) {
                    $invoiceTbl->sub_total = $invoice->Subtotal;
                }
                if ($invoice->SalesTaxTota != null) {
                    $invoiceTbl->sales_tax_total = $invoice->SalesTaxTotal;
                }
                if ($invoice->BalanceRemaining != null) {
                    $invoiceTbl->balance_remaining = $invoice->BalanceRemaining;
                }
                if ($invoice->AppliedAmount != null) {
                    $invoiceTbl->applied_amount = $invoice->AppliedAmount;
                }
                if ($invoice->IsPaid) {
                    $invoiceTbl->is_paid = $invoice->IsPaid;
                }
                if ($invoice->IsPending) {
                    $invoiceTbl->is_pending = $invoice->IsPending;
                }
                if ($invoice->SalesRepRef != null) {
                    $invoiceTbl->sales_rep_ref = $invoice->SalesRepRef->FullName;
                } else {
                    $invoiceTbl->sales_rep_ref = "HOUS";
                }
                $invoiceTbl->save();

                //Check & Save Data For Invoice Details
                if (isset($invoice->InvoiceLineRet)) {
                    foreach ($invoice->InvoiceLineRet as $invoiceLine) {
                        $inoviceDetailTbl = new InvoiceDetail();
                        $currentInvoice = Invoice::where('txn_id', $invoice->txnID)->first();
                        if ($currentInvoice != null) {
                            $inoviceDetailTbl->invoice_id = $currentInvoice->invoice_id;
                        }
                        $inoviceDetailTbl->txn_line_id = $invoiceLine->TxnLineID;
                        if (isset($invoiceLine->ItemRef)) {
                            $inoviceDetailTbl->item_ref_list_id = $invoiceLine->ItemRef->ListID;
                            $inoviceDetailTbl->item_ref_name = $invoiceLine->ItemRef->FullName;
                        }
                        if (isset($invoiceLine->Desc)) {
                            $inoviceDetailTbl->desc = $invoiceLine->Desc;
                        }
                        if (isset($invoiceLine->Quantity)) {
                            $inoviceDetailTbl->quantity = $invoiceLine->Quantity;
                        }
                        if (isset($invoiceLine->Cost)) {
                            $inoviceDetailTbl->cost = $invoiceLine->Cost;
                        }
                        if (isset($invoiceLine->ClassRef)) {
                            $inoviceDetailTbl->class_list_id = $invoiceLine->ClassRef->ListID;
                            $inoviceDetailTbl->class_name = $invoiceLine->ClassRef->FullName;
                        }
                        if (isset($invoiceLine->SalesTaxCodeRef)) {
                            $inoviceDetailTbl->sales_tax_list_id = $invoiceLine->SalesTaxCodeRef->ListID;
                            $inoviceDetailTbl->sales_tax_name = $invoiceLine->SalesTaxCodeRef->FullName;
                        }
                        $inoviceDetailTbl->save();
                    }
                }
            }

            $remainingCount = 0;
            $iterator = "0";
            $InvoiceQueryRs = $response->QBXMLMsgsRs->InvoiceQueryRs->attributes();

            $requestId = $InvoiceQueryRs['requestID'];
            if (isset($InvoiceQueryRs['iteratorID'])) {
                $iterator = $InvoiceQueryRs['iteratorID'];
                $remainingCount = $InvoiceQueryRs['iteratorRemainingCount'];
            }

            $queue = QbQueue::where('status', 'ready')->where('queue_type', JobType::Load_Invoice)->first();
            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }

    }

    public function saveDeletedInvoice($param){
        try {
            \Log::info(print_r($param, true));
            $response = simplexml_load_string($param->response);

            $invoiceRef = $response->QBXMLMsgsRs->TxnDeletedQueryRs;



            foreach ($invoiceRef->TxnDeletedRet as $invoice) {
                Invoice::where('txn_id', $invoice->TxnID)->delete();
            }

            $remainingCount = 0;
            $iterator = "0";
            $InventoryQueryRs = $response->QBXMLMsgsRs->TxnDeletedQueryRs->attributes();

            $requestId = $InventoryQueryRs['requestID'];
            if (isset($InventoryQueryRs['iteratorID'])) {
                $iterator = $InventoryQueryRs['iteratorID'];
                $remainingCount = $InventoryQueryRs['iteratorRemainingCount'];
            }


            $queue = QbQueue::where('status', 'ready')->where('queue_type', JobType::Load_Deleted_Invoices)->first();
            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }

    }


    //INVOICE SAVE METHODS END
    public function saveItemGroupData($param){
        try {
            \Log::info(print_r($param, true));
            $response = simplexml_load_string($param->response);
            $itemRef = $response->QBXMLMsgsRs->ItemGroupQueryRs;


        foreach($itemRef->ItemGroupRet as $item){

            if(!isset($item)){
                \Log::info(print_r('Non Inventory Groups Were Returned', true));
            }
            else{
                $itemTbl = new Item();

                $itemTbl->item = $item->ParentRef->FullName . ':'.$item->Name;


                $groupRate = null;
                $itemDataList = $item->DataExtRet;
                if($itemDataList != null){

                    foreach($itemDataList as $dataList){
                        $dataExtName = strtoupper($dataList->DataExtName);
                        if($dataExtName == 'COMM RATEI' && $dataList->DataExtValue != null){
                            $groupRate = $dataList->DataExtValue;
                        }
                        if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                            $itemTbl->stdcost_pct = $dataList->DataExtValue;
                        }
                        if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                            $itemTbl->stdcost_pct = $dataList->DataExtValue;
                        }

                        if($dataExtName == 'STD COST' && $dataList->DataExtValue != null){
                            $itemTbl->stdcost = $dataList->DataExtValue;
                        }

                        if($dataExtName == 'REP ID' && $dataList->DataExtValue != null){
                            $itemTbl->rep = $dataList->DataExtValue;
                        }

                        if($dataExtName == 'COMM AMTI' && $dataList->DataExtValue != null){
                            $itemTbl->rate = null;
                            $itemTbl->commission_amount = $dataList->DataExtValue;
                        }

                        if($dataExtName == 'BONUS' && $dataList->DataExtValue != null){
                            $itemTbl->rate = null;
                            $itemTbl->bonus = $dataList->DataExtValue;
                        }
                    }
                }

                $itemGroupLineList = $item->ItemGroupLineList; //need to update the tag
                if($itemGroupLineList != null){

                    foreach($itemGroupLineList as $itemGList){
                        Item::where('qb_listId', $item->ListID .':'. $itemGList->ListID)->delete();

                        $currentItem = Item::where('QBListID', $itemGList->ListID)->first();
                        if($groupRate == null){

                            $itemTbl->rate = $currentItem->rate;
                        }else{
                            $itemTbl->rate = $groupRate;
                        }

                        $itemTbl->type = 'I';

                        if($item->SalesDesc != null){
                            $itemTbl->description = $item->SalesDesc;
                        }

                        if($item->PurchaseCost != null){
                            $itemTbl->cost = $item->PurchaseCost;
                        }else{
                            $itemTbl->cost = 'null';
                        }

                        if($item->SalesPrice != null){
                            $itemTbl->price = $item->SalesPrice;
                        }else{
                            $itemTbl->price = 'null';
                        }
                        $itemTbl->qb_listId = $item->ListID;
                        $itemTbl->modified_at = $item->TimeModified;
                        $itemTbl->save();

                    }
                }
            }

        }


        $remainingCount = 0;
        $iterator = "0";
        
        $ItemQueryRs = $response->QBXMLMsgsRs->ItemGroupQueryRs->attributes();
        $requestId = $ItemQueryRs['requestID'];
        if(isset($ItemQueryRs['iteratorID'])){
            $iterator = $ItemQueryRs['iteratorID'];
            $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
        }

        $queue = QbQueue::where('status', 'ready')->first();
        $queue->RequestID = $requestId + 1;
        $queue->iteratorID = $iterator;
        $queue->RemainingCount = $remainingCount;
        $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }


    public function saveItemAssemblyInventoryData($param){
        try {
            $response = simplexml_load_string($param->response);
            $itemRef = $response->QBXMLMsgsRs->ItemInventoryAssemblyQueryRs;
            \Log::info(print_r($itemRef, true));


        Item::where('type', 'A')->delete();
        foreach($itemRef->ItemInventoryAssemblyRet as $item){
            $itemTbl = new Item();

              $itemTbl->item = $item->FullName . ':'.$item->Name;


              if(isset($item->DataExtRet)){
                  $itemDataList = $item->DataExtRet;
                foreach($itemDataList as $dataList){
                    $dataExtName = strtoupper($dataList->DataExtName);
                    if($dataExtName == 'COMM RATEI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'STD COST' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'REP ID' && $dataList->DataExtValue != null){
                        $itemTbl->rep = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'COMM AMTI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->commission_amount = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'BONUS' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->bonus = $dataList->DataExtValue;
                     }
                }
              }

              if($itemTbl->stdcost == null && isset($item->AverageCost)){
                  if(isset($item->AverageCost)){
                    $itemTbl->stdcost = $item->AverageCost;
                  }
              }

              $itemTbl->type = 'A';

              if($item->SalesDesc != null){
                  $itemTbl->description = $item->SalesDesc;
              }
              if($item->PurchaseCost != null){
                $itemTbl->cost = $item->PurchaseCost;
              }else{
                $itemTbl->cost = 'null';
              }

              if($item->SalesPrice != null){
                $itemTbl->price = $item->SalesPrice;
              }else{
                $itemTbl->price = 'null';
              }
              $itemTbl->qb_listId = $item->ListID;
              $itemTbl->modified_at = $item->TimeModified;
              $itemTbl->save();
        }


        $remainingCount = 0;
        $iterator = "0";

        $ItemQueryRs = $response->QBXMLMsgsRs->ItemInventoryAssemblyQueryRs->attributes();
        $requestId = $ItemQueryRs['requestID'];

        if(isset($ItemQueryRs['iteratorID'])){
            $iterator = $ItemQueryRs['iteratorID'];
            $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
        }


        $queue = QbQueue::where('status', 'ready')->first();
        $queue->RequestID = $requestId + 1;
        $queue->iteratorID = $iterator;
        $queue->RemainingCount = $remainingCount;
        $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }

    }


    public function saveItemOtherCharges($param){

        try {
            $response = simplexml_load_string($param->response);
            $itemRef = $response->QBXMLMsgsRs->ItemOtherChargeQueryRs;
            //\Log::info(print_r($itemRef, true));


        foreach($itemRef->ItemOtherChargeRet as $item){
            Item::where('qb_listId', $item->ListID)->delete();
            $itemTbl = new Item();

              $itemTbl->item = $item->ParentRef->FullName . ':'.$item->Name;
              $itemDataList = $item->DataExtRet;
              if($itemDataList != null){

                foreach($itemDataList as $dataList){
                    $dataExtName = strtoupper($dataList->DataExtName);
                    if($dataExtName == 'COMM RATEI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'STD COST' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'REP ID' && $dataList->DataExtValue != null){
                        $itemTbl->rep = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'COMM AMTI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->commission_amount = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'BONUS' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->bonus = $dataList->DataExtValue;
                     }
                }
              }

              if($itemTbl->std_cost == null && isset($item->ORSalesPurchase->SalesAndPurchase)){
                  if(isset($item->ORSalesPurchase->SalesAndPurchase->PurchaseCost)){
                    $itemTbl->std_cost = $item->ORSalesPurchase->SalesAndPurchase->PurchaseCost;
                  }
              }

              $itemTbl->type = 'O';

              if($item->SalesDesc != null){
                  $itemTbl->description = $item->SalesDesc;
              }
              if($item->PurchaseCost != null){
                $itemTbl->cost = $item->PurchaseCost;
              }else{
                $itemTbl->cost = 'null';
              }

              if($item->SalesPrice != null){
                $itemTbl->price = $item->SalesPrice;
              }else{
                $itemTbl->price = 'null';
              }
              $itemTbl->qb_listId = $item->ListID;
              $itemTbl->modified_at = $item->TimeModified;
              $itemTbl->save();
        }


        $remainingCount = 0;
        $iterator = "0";

        $ItemQueryRs = $response->QBXMLMsgsRs->ItemOtherChargeQueryRs->attributes();
        $requestId = $ItemQueryRs['requestID'];

        if(isset($ItemQueryRs['iteratorID'])){
            $iterator = $ItemQueryRs['iteratorID'];
            $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
        }

        $queue = QbQueue::where('status', 'ready')->first();
        $queue->RequestID = $requestId + 1;
        $queue->iteratorID = $iterator;
        $queue->RemainingCount = $remainingCount;
        $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }


    public function saveItemNonInventory($param){

        try {

            $response = simplexml_load_string($param->response);
            $itemRef = $response->QBXMLMsgsRs->ItemNonInventoryQueryRs;
            //\Log::info(print_r($itemRef, true));



        foreach($itemRef->ItemNonInventoryRet as $item){
            Item::where('qb_listId', $item->ListID)->delete();
            $itemTbl = new Item();

              $itemTbl->item = $item->ParentRef->FullName . ':'.$item->Name;

              $itemDataList = $item->DataExtRet;
              if($itemDataList != null){

                foreach($itemDataList as $dataList){
                    $dataExtName = strtoupper($dataList->DataExtName);
                    if($dataExtName == 'COMM RATEI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'STD COST' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'REP ID' && $dataList->DataExtValue != null){
                        $itemTbl->rep = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'COMM AMTI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->commission_amount = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'BONUS' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->bonus = $dataList->DataExtValue;
                     }
                }
              }

              $itemTbl->type = 'N';

              if($item->SalesDesc != null){
                  $itemTbl->description = $item->SalesDesc;
              }
              if($item->PurchaseCost != null){
                $itemTbl->cost = $item->PurchaseCost;
              }else{
                $itemTbl->cost = 'null';
              }

              if($item->SalesPrice != null){
                $itemTbl->price = $item->SalesPrice;
              }else{
                $itemTbl->price = 'null';
              }
              $itemTbl->qb_listId = $item->ListID;
              $itemTbl->modified_at = $item->TimeModified;
              $itemTbl->save();
        }


        $remainingCount = 0;
        $iterator = "0";

        $ItemQueryRs = $response->QBXMLMsgsRs->ItemNonInventoryQueryRs->attributes();
        $requestId = $ItemQueryRs['requestID'];

        if(isset($ItemQueryRs['iteratorID'])){
            $iterator = $ItemQueryRs['iteratorID'];
            $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
        }


        $queue = QbQueue::where('status', 'ready')->first();
        $queue->RequestID = $requestId + 1;
        $queue->iteratorID = $iterator;
        $queue->RemainingCount = $remainingCount;
        $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }

    public function saveItemServices($param){

        try {

            $response = simplexml_load_string($param->response);
            $itemRef = $response->QBXMLMsgsRs->ItemServiceQueryRs;
            //\Log::info(print_r($itemRef, true));


        foreach($itemRef->ItemServiceRet as $item){
            Item::where('qb_listId', $item->ListID)->delete();
            $itemTbl = new Item();

              $itemTbl->item = $item->ParentRef->FullName . ':'.$item->Name;

              $itemDataList = $item->DataExtRet;
              if($itemDataList != null){

                foreach($itemDataList as $dataList){
                    $dataExtName = strtoupper($dataList->DataExtName);
                    if($dataExtName == 'COMM RATEI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'STD COST' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'REP ID' && $dataList->DataExtValue != null){
                        $itemTbl->rep = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'COMM AMTI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->commission_amount = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'BONUS' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->bonus = $dataList->DataExtValue;
                     }
                }
              }

              $itemTbl->type = 'S';

              if($item->SalesDesc != null){
                  $itemTbl->description = $item->SalesDesc;
              }
              if($item->PurchaseCost != null){
                $itemTbl->cost = $item->PurchaseCost;
              }else{
                $itemTbl->cost = 'null';
              }

              if($item->SalesPrice != null){
                $itemTbl->price = $item->SalesPrice;
              }else{
                $itemTbl->price = 'null';
              }
              $itemTbl->qb_listId = $item->ListID;
              $itemTbl->modified_at = $item->TimeModified;
              $itemTbl->save();
        }

        $remainingCount = 0;
        $iterator = "0";

        $ItemQueryRs = $response->QBXMLMsgsRs->ItemServiceQueryRs->attributes();
        $requestId = $ItemQueryRs['requestID'];

        if(isset($ItemQueryRs['iteratorID'])){
            $iterator = $ItemQueryRs['iteratorID'];
            $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
        }
            $queue = QbQueue::where('status', 'ready')->first();

            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }



    public function saveItemInventory($param){

        try {

            $response = simplexml_load_string($param->response);
            $itemRef = $response->QBXMLMsgsRs->ItemInventoryQueryRs;
            \Log::info(print_r($response, true));


        foreach($itemRef->ItemInventoryRet as $item){
            Item::where('qb_listId', $item->ListID)->delete();
            $itemTbl = new Item();
            $itemTbl->item = $item->FullName .':'. $item->Name;


              if(isset($item->DataExtRet)){
                  $itemDataList = $item->DataExtRet;
                foreach($itemDataList as $dataList){
                    $dataExtName = strtoupper($dataList->DataExtName);
                    if($dataExtName == 'COMM RATEI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }
                    if($dataExtName == 'STD COST PCT' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost_pct = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'STD COST' && $dataList->DataExtValue != null){
                        $itemTbl->stdcost = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'REP ID' && $dataList->DataExtValue != null){
                        $itemTbl->rep = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'COMM AMTI' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->commission_amount = $dataList->DataExtValue;
                    }

                    if($dataExtName == 'BONUS' && $dataList->DataExtValue != null){
                        $itemTbl->rate = null;
                        $itemTbl->bonus = $dataList->DataExtValue;
                     }
                }
              }

              $itemTbl->type = 'I';

              if($item->SalesDesc != null){
                  $itemTbl->description = $item->SalesDesc;
              }
              if($item->PurchaseCost != null){
                $itemTbl->cost = $item->PurchaseCost;
              }else{
                $itemTbl->cost = 'null';
              }

                if ($item->SalesPrice != null) {
                    $itemTbl->price = $item->SalesPrice;
                } else {
                    $itemTbl->price = 'null';
                }
                $itemTbl->qb_listId = $item->ListID;
                $itemTbl->modified_at = $item->TimeModified;
                $itemTbl->save();
            }

            $remainingCount = 0;
            $iterator = "0";

            $ItemQueryRs = $response->QBXMLMsgsRs->ItemInventoryQueryRs->attributes();
            $requestId = $ItemQueryRs['requestID'];

            if(isset($ItemQueryRs['iteratorID'])){
                $iterator = $ItemQueryRs['iteratorID'];
                $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
            }
            $queue = QbQueue::where('status', 'ready')->first();
            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }

    public function saveItem($param){
        try {
            $response = simplexml_load_string($param->response);
        $itemRef = $response->QBXMLMsgsRs->ItemQueryRs->ItemInventoryRet;
       // \Log::info(print_r($itemRef, true));


        foreach($itemRef as $item){

            Item::create([
                "item" => trim($item->Name),
                'item_id' => $item->ListID,
                'description' => $item->SalesDesc,
                'cost' => $item->PurchaseCost,
                'price' => $item->SalesPrice,
                'rate' => '0',
                'commission_amount' => '0',
                'stdcost' => '0',
                'rep' => '0',
                'type' => 'test',
                'bonus' => '0',
                'modified_at' => $item->TimeModified,
                'qb_listId' => '123',
                'stdcost_pct' => '123'
            ]);

        }


        $remainingCount = 0;
        $iterator = "0";
        $ItemQueryRs = $response->QBXMLMsgsRs->ItemQueryRs->attributes();

        $requestId = $ItemQueryRs['requestID'];
        if(isset($ItemQueryRs['iteratorID'])){
            $iterator = $ItemQueryRs['iteratorID'];
            $remainingCount = $ItemQueryRs['iteratorRemainingCount'];
        }

        $queue = QbQueue::where('status', 'ready')->first();
        $queue->RequestID = $requestId + 1;
        $queue->iteratorID = $iterator;
        $queue->RemainingCount = $remainingCount;
        $queue->save();



            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }

    public function saveCustomer($param)
    {
        try {
            $response = simplexml_load_string($param->response);

            $customerRefs = $response->QBXMLMsgsRs->CustomerQueryRs->CustomerRet;
            \Log::info(print_r("customerRefs", true));

            foreach ($customerRefs as $customerRef) {
                if (is_null($customerRef)) continue;

                $name = null;
                $qb_listId = null;
                $type = null;
                $qb_listId_type = null;
                $term = null;
                $qb_listId_terms = null;
                $currency_name = null;
                $currency_listId = null;
                $company_name = null;
                $modified_at = null;
                $rep_id = null;
                $qb_listId_sales_rep = null;
                $comm_rate = null;
                $split_rep = null;
                $split_percent = null;
                $estab_at = null;
                $comm_rateYr1 = null;
                $comm_rateYr2 = null;
                $comm_rateYr3 = null;

                if (!is_null($customerRef->ListID)) {
                    $qb_listId = trim($customerRef->ListID);
                }
                // delete existing
                Customer::where('qb_listId', $qb_listId)->delete();

                if (!is_null($customerRef->FullName)) {
                    $name = trim($customerRef->FullName);
                }
                if (!is_null($customerRef->CompanyName)) {
                    $company_name = trim($customerRef->CompanyName);
                } else {
                    $company_name = $name;
                }
                if (!is_null($customerRef->SalesRepRef)) {
                    if ((!is_null($customerRef->SalesRepRef->FullName))) {
                        $rep_id = trim($customerRef->SalesRepRef->FullName);
                    }
                    if ((!is_null($customerRef->SalesRepRef->ListID))) {
                        $qb_listId_sales_rep = trim($customerRef->SalesRepRef->ListID);
                    }
                } else {
                    if (!is_null($customerRef->ParentRef) && !is_null($customerRef->ParentRef->FullName)) {
                        //find parent by name
                        $customer = Customer::where('qb_listId_sales_rep', trim($customerRef->ParentRef->FullName))->first();
                        if (!is_null($customer)) {
                            $qb_listId_sales_rep = $customer->qb_listId_sales_rep;
                            $rep_id = $customer->rep_id;
                        }
                    }
                }
                if (is_null($customerRef->ParentRef)) {
                    Customer::where('name', $name)->update(['rep_id' => $rep_id, '$qb_listId_sales_rep' => $qb_listId_sales_rep]);
                }
                if (!is_null($customerRef->CustomerTypeRef->FullName)) {
                    $type = trim($customerRef->CustomerTypeRef->FullName);
                }
                if (!is_null($customerRef->CustomerTypeRef->ListID)) {
                    $qb_listId_type = trim($customerRef->CustomerTypeRef->ListID);
                }
                if (!is_null($customerRef->TermsRef->FullName)) {
                    $term = trim($customerRef->TermsRef->FullName);
                }
                if (!is_null($customerRef->TermsRef->ListID)) {
                    $qb_listId_terms = trim($customerRef->TermsRef->ListID);
                }
                if (!is_null($customerRef->CurrencyRef->FullName)) {
                    $currency_name = trim($customerRef->CurrencyRef->FullName);
                }
                if (!is_null($customerRef->CurrencyRef->ListID)) {
                    $currency_listId = trim($customerRef->CurrencyRef->ListID);
                }
                if (!is_null($customerRef->TimeModified)) {
                    $time = strtotime($customerRef->TimeModified);
                    $modified_at = date('Y-m-d', $time);
                }
                if (!is_null($customerRef->DataExtRetList)) {
                    foreach ($customerRef->DataExtRetList as $DataExtRet) {
                        if (is_null($DataExtRet->DataExtName) || is_null($DataExtRet->DataExtValue)) continue;

                        if ($DataExtRet->DataExtName == "COMM RATE") {
                            if (is_numeric($DataExtRet->DataExtValue)) {
                                $comm_rate = $DataExtRet->DataExtValue;
                            }
                        }
                        if ($DataExtRet->DataExtName == "SPLIT REP") {
                            if (is_numeric($DataExtRet->DataExtValue)) {
                                $split_rep = $DataExtRet->DataExtValue;
                            }
                        }
                        if ($DataExtRet->DataExtName == "SPLIT PERCENT") {
                            $split_percent = $DataExtRet->DataExtValue;
                        }
                        if ($DataExtRet->DataExtName == "ESTAB DATE") {
                            if (is_numeric($DataExtRet->DataExtValue)) {
                                $time = strtotime($DataExtRet->DataExtValue);
                                $estab_at = date('Y-m-d', $time);
                            }
                        }
                        if ($DataExtRet->DataExtName == "COMM RATE YR1") {
                            if (is_numeric($DataExtRet->DataExtValue)) {
                                $comm_rateYr1 = $DataExtRet->DataExtValue;
                            }
                        }
                        if ($DataExtRet->DataExtName == "COMM RATE YR2") {
                            if (is_numeric($DataExtRet->DataExtValue)) {
                                $comm_rateYr2 = $DataExtRet->DataExtValue;
                            }
                        }
                        if ($DataExtRet->DataExtName == "COMM RATE YR3") {
                            if (is_numeric($DataExtRet->DataExtValue)) {
                                $comm_rateYr3 = $DataExtRet->DataExtValue;
                            }
                        }
                    }
                }

                Customer::create([
                    "name" => $name,
                    "qb_listId" => $qb_listId,
                    //type
                    "type" => $type,
                    "qb_listId_type" => $qb_listId_type,
                    //term
                    "term" => $term,
                    "qb_listId_terms" => $qb_listId_terms,
                    //currency
                    "currency_name" => $currency_name,
                    "currency_listId" => $currency_listId,

                    "company_name" => $company_name,
                    "modified_at" => $modified_at,

                    "rep_id" => $rep_id,
                    "qb_listId_sales_rep" => $qb_listId_sales_rep,

                    //datalist
                    "comm_rate" => $comm_rate,
                    "split_rep" => $split_rep,
                    "split_percent" => $split_percent,
                    "estab_at" => $estab_at,
                    "comm_rateYr1" => $comm_rateYr1,
                    "comm_rateYr2" => $comm_rateYr2,
                    "comm_rateYr3" => $comm_rateYr3
                ]);
            }

            $CustomerQueryRs = $response->QBXMLMsgsRs->CustomerQueryRs->attributes();
            $requestId = $CustomerQueryRs['requestID'];

            $iterator = "0";
            $remainingCount = 0;
            if (isset($CustomerQueryRs['iteratorID'])) {
                $iterator = $CustomerQueryRs['iteratorID'];
                $remainingCount = $CustomerQueryRs['iteratorRemainingCount'];
            }

            $queue = QbQueue::where('status', 'ready')->first();
            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch(\Exception $e){
            \Log::info(print_r($e->getMessage(), true));
        }
    }

    private function saveSalesReceiptPayment($param)
    {
        try {
            $response = simplexml_load_string($param->response);
            \Log::info(print_r($response->QBXMLMsgsRs->SalesReceiptQueryRs, true));
            $salesReceiptRets = $response->QBXMLMsgsRs->SalesReceiptQueryRs->SalesReceiptRet;

            foreach ($salesReceiptRets as $salesReceiptRet) {
                $TxnNumber = $salesReceiptRet->TxnNumber;

                $processedReceivePaymentRequest = ReceivePaymentRequest::where([['txn_number', $TxnNumber], ['processed', -1]])->first();


                if (!is_null($processedReceivePaymentRequest)) {
                    continue;
                } else {
                    ReceivePaymentRequest::where('txn_number', $TxnNumber)->delete();
                }
                //default
                $created_at = null;
                $modified_at = null;
                $txn_number = null;
                $customer_name = null;
                $customer_list_id = null;
                $txn_date = null;
                $txn_id = null;
                $ref_number = null;
                $amount = null;
                $payment_method = null;
                $payment_method_qb_list_id = null;
                $ar_account = null;
                $ar_account_qb_list_id = null;
                $deposit_to_account = null;
                $deposit_to_account_qb_list_id = null;
                $memo = null;
                $unused_payment = null;
                $unused_credits = null;
                $comm_track_type = null;
                $app_txn_id = null;
                $app_txn_type = null;
                $app_txn_date = null;
                $app_refnumber = null;
                $app_balance_remaining = null;
                $app_amount = null;
                $app_discount_amount = null;
                $app_discount_account_qb_list_id = null;
                //map
                $created_at = date('Y-m-d H:i:s', strtotime($salesReceiptRet->TimeCreated));
                $modified_at = date('Y-m-d H:i:s', strtotime($salesReceiptRet->TimeModified));
                $txn_number = $salesReceiptRet->TxnNumber;
                $customer_name = $salesReceiptRet->CustomerRef->FullName;
                $customer_list_id = $salesReceiptRet->CustomerRef->ListID;
                $txn_date = $salesReceiptRet->TxnDate;
                $txn_id = $salesReceiptRet->txnID;
                $ref_number = $salesReceiptRet->RefNumber;
                $amount = $salesReceiptRet->TotalAmount;
                if (is_null($salesReceiptRet->PaymentMethodRef)) {
                    $payment_method = $salesReceiptRet->PaymentMethodRef->PaymentMethod;
                    $payment_method_qb_list_id = $salesReceiptRet->PaymentMethodRef->PaymentMethodQBListID;
                }
                $ar_account = null;
                $ar_account_qb_list_id = null;
                $deposit_to_account = $salesReceiptRet->DepositToAccountRef->FullName;
                $deposit_to_account_qb_list_id = $salesReceiptRet->DepositToAccountRef->ListID;
                $memo = $salesReceiptRet->Memo;
                $unused_payment = 0;
                $unused_credits = 0;
                $comm_track_type = 1;
                $app_txn_id = $salesReceiptRet->txnID;
                $app_txn_type = 22;
                $app_txn_date = $salesReceiptRet->TxnDate;
                $app_refnumber = $salesReceiptRet->RefNumber;
                $app_balance_remaining = 0;
                $app_amount = $salesReceiptRet->TotalAmount;
                $app_discount_amount = null;
                $app_discount_account_qb_list_id = null;

                ReceivePaymentRequest::create([
                    "processed_at" => $created_at,
                    "modified_at" => $modified_at,
                    "txn_number" => $txn_number,
                    "customer_name" => $customer_name,
                    "customer_list_id" => $customer_list_id,
                    "txn_date" => $txn_date,
                    "txn_id" => $txn_id,
                    "ref_number" => $ref_number,
                    "amount" => $amount,
                    "payment_method" => $payment_method,
                    "payment_method_qb_list_id" => $payment_method_qb_list_id,
                    "ar_account" => $ar_account,
                    "ar_account_qb_list_id" => $ar_account_qb_list_id,
                    "deposit_to_account" => $deposit_to_account,
                    "deposit_to_account_qb_list_id" => $deposit_to_account_qb_list_id,
                    "memo" => $memo,
                    "unused_payment" => $unused_payment,
                    "unused_credits" => $unused_credits,
                    "comm_track_type" => $comm_track_type,
                    "app_txn_id" => $app_txn_id,
                    "app_txn_type" => $app_txn_type,
                    "app_txn_date" => $app_txn_date,
                    "app_refnumber" => $app_refnumber,
                    "app_balance_remaining" => $app_balance_remaining,
                    "app_amount" => $app_amount,
                    "app_discount_amount" => $app_discount_amount,
                    "app_discount_account_qb_list_id" => $app_discount_account_qb_list_id

                ]);


                //QB TEMP INVOICE LOGIC

                $setupTbl = Setup::first();

                $sngCostMarkupPct = $setupTbl->CostMarkupPct / 100;
                $curCostMarkupAmt = $setupTbl->CostMarkupAmt;

                $CostMarkupAmt = $setupTbl->UseStdCostPctOfPrice;
                $StdCostPctOfPrice = ($setupTbl->StdCostPctOfPrice == null ? 25 : $setupTbl->StdCostPctOfPrice) / 100;

                $SRTxnNumber = $salesReceiptRet->TxnNumber;
                $SRTxnDate = $salesReceiptRet->TxnDate;
                $SRCustomerName = $salesReceiptRet->CustomerRef->FullName;

                \Log::info(print_r($SRCustomerName, true));

                $Customer = Customer::where("name",  $SRCustomerName)->first();

                if($Customer != null){

                    $CustomerPct = $Customer->comm_rate;

                    $EstabDate = $Customer->estab_at;

                    if(isset($EstabDate)){
                        $interval = date($EstabDate)->diff(date($SRTxnDate));
                        if($interval == 1){
                            if($Customer->comm_rateYr2 != null){
                                $CustomerPct = $Customer->comm_rateYr2;
                            }
                        }else if($interval > 1){
                            if($Customer->comm_rateYr3 != null){
                                $CustomerPct = $Customer->comm_rateYr3;
                            }else if($Customer->comm_rateYr2 != null){
                                $CustomerPct = $Customer->comm_rateYr2;
                            }
                        }
                    }

                    $SRCustomerRef = 'NO REF';
                    if($salesReceiptRet->CustomerRef != null){
                        $SRCustomerRef = $salesReceiptRet->CustomerRef->ListID;
                    }
                    $SRSalesRep = null;
                    if($salesReceiptRet->SalesRepRef != null){
                        $SRSalesRep = $salesReceiptRet->SalesRepRef->FullName;
                    }else{
                        $SRSalesRep =  $Customer->rep_id;
                    }

                    if($SRSalesRep == null){
                        $SRSalesRep = $setupTbl->DefaultRep;
                    }

                    $ardSalesPersonMasterFile = ARDSalespersonMasterfile::where('salespersonNumber', $SRSalesRep)->first();

                    if($ardSalesPersonMasterFile == null){
                        if(ARDSalespersonMasterfile::count() > 200){ //200 max records we get in one request
                            \Log::info(print_r("You have exceeded the allowable number of Sales Reps", true));
                            $SalesRepPct = 0;
                        }else{
                            $ardSalesPersonMasterFile = new ARDSalespersonMasterfile();
                            $ardSalesPersonMasterFile->salespersonNumber = $SRSalesRep;
                            $ardSalesPersonMasterFile->commRate = $setupTbl->DefaultRepCommRate;
                            $ardSalesPersonMasterFile->save();
                            $SalesRepPct = $setupTbl->DefaultRepCommRate;
                        }

                    }else{
                        $SalesRepPct = $ardSalesPersonMasterFile->commRate;
                    }

                    if($salesReceiptRet->RefNumber != null){
                        $SRRefNumber = $salesReceiptRet->RefNumber;
                    }

                    if($salesReceiptRet->SubTotal != null){
                        $SRSubtotal = $salesReceiptRet->SubTotal;
                    }

                    if($salesReceiptRet->Memo != null){
                        $SRMemo = $salesReceiptRet->Memo;
                    }

                    $SRPONumber = null;
                    //Add the Sales Receipt header to qbTempInvoices

                    $dataList = $salesReceiptRet->DataExtRetList;

                    //See if there is a rate for this invoice

                    $InvoicePct = Null;
                    $SplitPercent = Null;
                    $SplitRep = Null;
                    $AddCosts = $setupTbl->InvoiceCostMarkupAmt;
                    $POLink = Null;

                    if($dataList != null){
                        foreach($dataList as $dataRet){
                            $dataExName = strtoupper($dataRet->DataExtName);
                            if($dataExName == "COMM RATE"){
                                if(isset($dataRet->DataExtValue)){
                                    $InvoicePct = $dataRet->DataExtValue;
                                    if($InvoicePct > 100){
                                        $InvoicePct = 99;
                                    }
                                    if($InvoicePct < 0){
                                        $InvoicePct = 0;
                                    }
                                }
                            }else if($dataExName == "SPLIT PERCENT"){
                                if(isset($dataRet->DataExtValue)){
                                    $SplitPercent = $dataRet->DataExtValue;
                                    if($SplitPercent > 100){
                                        $SplitPercent = 99;
                                    }
                                    if($SplitPercent < 0){
                                        $SplitPercent = 0;
                                    }
                                }
                            }else if($dataExName == "SPLIT REP"){
                                if(isset($dataRet->DataExtValue)){
                                    $SplitRep = $dataRet->DataExtValue;
                                }
                            }else if($dataExName == "PO LINK"){
                                if(isset($dataRet->DataExtValue)){
                                    $POLink = $dataRet->DataExtValue;
                                }
                            }else if($dataExName == "ADD COSTS"){
                                if(isset($dataRet->DataExtValue)){
                                    $AddCosts = $AddCosts + $dataRet->DataExtValue;
                                }
                            }
                        }
                    }

                    $qbTempInvoice = new QbTempInvoice();
                    $qbTempInvoice->txn_number = $SRTxnNumber;
                    $qbTempInvoice->ref_number = $SRRefNumber;
                    $qbTempInvoice->txn_date = $SRTxnDate;
                    $qbTempInvoice->customer_ref = $SRCustomerRef;
                    $qbTempInvoice->customer_name = $SRCustomerName;
                    $qbTempInvoice->sub_total = $SRSubtotal;
                    $qbTempInvoice->invoice_total = $SRSubtotal;
                    $qbTempInvoice->memo  = $SRMemo;
                    $qbTempInvoice->po_number = $SRPONumber;
                    $qbTempInvoice->sales_rep_ref = $SRSalesRep;
                    $qbTempInvoice->rate_percent = $InvoicePct;
                    $qbTempInvoice->is_paid = 1;
                    $qbTempInvoice->invoice_type = 'S';
                    $qbTempInvoice->txn_id = $salesReceiptRet->txnID;
                    $qbTempInvoice->amount = $amount;
                    $qbTempInvoice->po_link = $POLink;
                    $qbTempInvoice->save();

                    //If there are add-on costs, then add another row

                    if($AddCosts != 0){
                        \Log::info(print_r('****ADD COST IN SETUP IS NOT 0', true));
                        $qbTempInvoice = new QbTempInvoice();
                        $qbTempInvoice->txn_number = $SRTxnNumber;
                        $qbTempInvoice->ref_number = $SRRefNumber;
                        $qbTempInvoice->txn_date = $SRTxnDate;
                        $qbTempInvoice->customer_ref = $SRCustomerRef;
                        $qbTempInvoice->customer_name = $SRCustomerName;
                        $qbTempInvoice->sub_total = $SRSubtotal;
                        $qbTempInvoice->invoice_total = $SRSubtotal;
                        $qbTempInvoice->memo  = $SRMemo;
                        $qbTempInvoice->po_number = $SRPONumber;
                        $qbTempInvoice->sales_rep_ref = $SRSalesRep;
                        $qbTempInvoice->rate_percent = $InvoicePct;
                        $qbTempInvoice->is_paid = 1;
                        $qbTempInvoice->item_type = 'N';
                        $qbTempInvoice->txn_id = $salesReceiptRet->txnID;
                        $qbTempInvoice->line = $SRTxnNumber.'-9999';
                        $qbTempInvoice->description = $setupTbl->AddlCostDesc;
                        $qbTempInvoice->amount = 0;
                        $qbTempInvoice->item_ref_name = $setupTbl->AddlCostDesc;
                        $qbTempInvoice->item_list_id = "None";
                        $qbTempInvoice->quantity = 1;
                        $qbTempInvoice->rate = 0;
                        $qbTempInvoice->invoice_type = "S";
                        $qbTempInvoice->cost = $AddCosts;
                        $qbTempInvoice->bonus = 0;

                        $qbTempInvoice->rate_percent = $SalesRepPct;
                        if($CustomerPct > $qbTempInvoice->rate_percent){
                            $qbTempInvoice->rate_percent = $CustomerPct;
                        }
                        if($InvoicePct > $qbTempInvoice->rate_percent){
                            $qbTempInvoice->rate_percent = $InvoicePct;
                        }

                        if($qbTempInvoice->rate_percent < 0){
                            $qbTempInvoice->rate_percent = 0;
                        }


                        $qbTempInvoice->po_link = $POLink;

                        if($setupTbl->CommMethod == 'Gross Profit'){
                            $qbTempInvoice->commission = ($qbTempInvoice->amount - $qbTempInvoice->cost) * $qbTempInvoice->rate_percent / 100;
                        }else{
                            $qbTempInvoice->commission = $qbTempInvoice->amount  * $qbTempInvoice->rate_percent / 100;
                        }

                        $qbTempInvoice->group_name = null;
                        $qbTempInvoice->group_description = null;
                        $qbTempInvoice->save();
                    }

                    if($SplitRep == null){
                        $SplitPercent = null;
                    }else{
                        if($SplitPercent == null){
                            $SplitRep = null;
                        }
                    }

                    if($SplitRep == null){
                        $SplitRep = $Customer->split_rep;
                        $SplitPercent = $Customer->split_percent;
                    }

                    if($SplitRep == null){
                        $SplitPercent = null;
                    }else{
                        if($SplitPercent == null){
                            $SplitRep = null;
                        }
                    }

                    $ckd = 1;
                    if($SplitPercent == 100) {
                        $ckd = 2;
                    }

                    \Log::info(print_r('****SETUP ckd Value****', true));

                    if(substr($SRRefNumber, 0, 2) !== 'FC'){
                        if($SplitPercent != null &&  $SplitRep != null){
                            $splitCommTemp  = new SplitCommTemp();
                            $splitCommTemp->invoice_number = $qbTempInvoice->invoice_number;
                            $splitCommTemp->sales_person = $qbTempInvoice->sales_rep_ref;
                            $splitCommTemp->sales_person_rate = $qbTempInvoice->rate_percent;
                            $splitCommTemp->split_percent = ($ckd - ($SplitPercent/100));
                            $splitCommTemp->txn_number = $qbTempInvoice->txn_number;
                            $splitCommTemp->save();

                            $splitCommTemp  = new SplitCommTemp();
                            $splitCommTemp->invoice_number = $qbTempInvoice->invoice_number;
                            $splitCommTemp->sales_person = $qbTempInvoice->sales_rep_ref;
                            $splitCommTemp->sales_person_rate = null;
                            $splitCommTemp->split_percent = (($SplitPercent/100));
                            $splitCommTemp->txn_number = $qbTempInvoice->txn_number;
                            $splitCommTemp->save();

                        }else{
                            if($ardSalesPersonMasterFile->multipleReps == false){
                                if($ardSalesPersonMasterFile->splitRep1 != null && $ardSalesPersonMasterFile->splitRepPct1 != null){
                                    $splitCommTemp  = new SplitCommTemp();
                                    $splitCommTemp->invoice_number = $qbTempInvoice->invoice_number;
                                    $splitCommTemp->sales_person = $ardSalesPersonMasterFile->splitRep1;
                                    $splitCommTemp->sales_person_rate = $ardSalesPersonMasterFile->splitRepPct1;
                                    $splitCommTemp->split_percent = $ardSalesPersonMasterFile->splitRepPct1;
                                    $splitCommTemp->txn_number = $qbTempInvoice->txn_number;
                                    $splitCommTemp->save();

                                }
                                if($ardSalesPersonMasterFile->splitRep2 != null && $ardSalesPersonMasterFile->splitRepPct2 != null){
                                    $splitCommTemp  = new SplitCommTemp();
                                    $splitCommTemp->invoice_number = $qbTempInvoice->invoice_number;
                                    $splitCommTemp->sales_person = $ardSalesPersonMasterFile->splitRep2;
                                    $splitCommTemp->sales_person_rate = $ardSalesPersonMasterFile->splitRepPct2;
                                    $splitCommTemp->split_percent = $ardSalesPersonMasterFile->splitRepPct2;
                                    $splitCommTemp->txn_number = $qbTempInvoice->txn_number;
                                    $splitCommTemp->save();

                                }
                                if($ardSalesPersonMasterFile->splitRep3 != null && $ardSalesPersonMasterFile->splitRepPct3 != null){
                                    $splitCommTemp  = new SplitCommTemp();
                                    $splitCommTemp->invoice_number = $qbTempInvoice->invoice_number;
                                    $splitCommTemp->sales_person = $ardSalesPersonMasterFile->splitRep3;
                                    $splitCommTemp->sales_person_rate = $ardSalesPersonMasterFile->splitRepPct3;
                                    $splitCommTemp->split_percent = $ardSalesPersonMasterFile->splitRepPct3;
                                    $splitCommTemp->txn_number = $qbTempInvoice->txn_number;
                                    $splitCommTemp->save();
                                }
                            }else{
                                $splitCommTemp  = new SplitCommTemp();
                                $splitCommTemp->invoice_number = $qbTempInvoice->invoice_number;
                                $splitCommTemp->sales_person = $qbTempInvoice->sales_rep_ref;
                                $splitCommTemp->sales_person_rate = $qbTempInvoice->rate_percent;
                                $splitCommTemp->split_percent = 0;
                                $splitCommTemp->txn_number = $qbTempInvoice->txn_number;
                                $splitCommTemp->save();
                            }

                        }
                    }

                    $sgrouplistid = '';

                    \Log::info(print_r('****ABOVE DOSRLINE ITEM****', true));

                    $orSalesReceiptLineGroupRetList = $salesReceiptRet->SalesReceiptLineGroupRet;
                    if(isset($orSalesReceiptLineGroupRetList)){
                        $indx = 1;
                        foreach ($orSalesReceiptLineGroupRetList as $orsrlrSalesReceiptLineGroupRet){
                            $sgrouplistid = $orsrlrSalesReceiptLineGroupRet->ItemGroupRef->ListID . ':';
                            if(isset($orsrlrSalesReceiptLineGroupRet->SalesReceiptLineRet)){
                                foreach($orsrlrSalesReceiptLineGroupRet->SalesReceiptLineRet as $SRLR){
                                    if(isset($SRLR->ItemGroupRef->ListID)){
                                        $this->DoSRLineItem($salesReceiptRet, $SRLR, $SRTxnNumber, $SRRefNumber, $SRSalesRep, $indx++, $SRLR->ItemGroupRef->ListID, $POLink, $CostMarkupAmt, $sngCostMarkupPct, $curCostMarkupAmt, $SalesRepPct, $CustomerPct, $InvoicePct);
                                    }
                                }

                            }
                        }
                    }

                    $orSalesReceiptLineRetList = $salesReceiptRet->SalesReceiptLineRet;
                    if(isset($orSalesReceiptLineRetList)){
                        $indx = 1;
                        foreach($orSalesReceiptLineRetList as $SRLR){
                            if(isset($SRLR->ItemRef)){
                                $this->DoSRLineItem($salesReceiptRet, $SRLR, $SRTxnNumber, $SRRefNumber, $SRSalesRep, $indx++, '', $POLink, $CostMarkupAmt, $sngCostMarkupPct, $curCostMarkupAmt, $SalesRepPct, $CustomerPct, $InvoicePct);
                            }
                        }
                    }
                }
                //QB TEMP INVOICE LOGIC ENDS
            }


            $SalesReceiptQueryRs = $response->QBXMLMsgsRs->SalesReceiptQueryRs->attributes();
            $requestId = $SalesReceiptQueryRs['requestID'];

            $iterator = "0";
            $remainingCount = 0;
            if (isset($SalesReceiptQueryRs['iteratorID'])) {
                $iterator = $SalesReceiptQueryRs['iteratorID'];
                $remainingCount = $SalesReceiptQueryRs['iteratorRemainingCount'];
            }

            $queue = QbQueue::where('status', 'ready')->first();
            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            \Log::info(print_r($queue, true));
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }

    public function saveTxnDeleted($param)
    {
        try {
            $response = simplexml_load_string($param->response);
            \Log::info(print_r($response->QBXMLMsgsRs, true));

            $TxnDeletedRets = $response->QBXMLMsgsRs->TxnDeletedQueryRs->TxnDeletedRet;

            foreach ($TxnDeletedRets as $TxnDeletedRet) {
                $TxnID = $TxnDeletedRet->TxnID;
                ReceivePaymentRequest::where('txn_id', $TxnID)->delete();
            }

            $queue = QbQueue::where('status', 'ready')->first();
            $queue->status = "end";
            $queue->save();
            return '100';
        }
        catch (\Exception $e) {
                \Log::info(print_r($e->getMessage(), true));
            }
    }

    private function saveCreditMemo($param)
    {
        try {
            $response = simplexml_load_string($param->response);
            \Log::info(print_r($response->QBXMLMsgsRs->CreditMemoQueryRs->attributes(), true));


            $setup = \DB::table('setups')->where('id', 1)->first();
            $FromDateInvSel = $setup->FromDateInvSel;
            $ToDateInvSel = $setup->ToDateInvSel;
            if (!is_null($FromDateInvSel) && !is_null($ToDateInvSel)) {
                $ToDateInvSel = date('Y-m-d H:i:s', strtotime($ToDateInvSel));
//            $ToDateInvSel = strtotime("+86399 seconds", $ToDateInvSel);
                $CreditMemoRets = $response->QBXMLMsgsRs->CreditMemoQueryRs->CreditMemoRet;
                foreach ($CreditMemoRets as $CreditMemoRet) {
                    $ReceivePaymentRequest = ReceivePaymentRequest::where('txn_number', $CreditMemoRet->txnID)->first();
                    if (!is_null($ReceivePaymentRequest)) {
                        $cPaidAlready = $ReceivePaymentRequest->app_amount;
                        ReceivePaymentRequest::create([
                            "txn_number" => 0,
                            "customer_name" => $CreditMemoRet->CustomerRef->FullName,
                            "customer_list_id" => $CreditMemoRet->CustomerRef->ListID,
                            "txn_date" => $CreditMemoRet->TxnDate,
                            "txn_id" => "0000000",
                            "ref_number" => "PSEUDO",
                            "amount" => -($CreditMemoRet->SubTotal - $cPaidAlready),
                            "payment_method" => "CREDIT MEMO",
                            "deposit_to_account" => null,
                            "memo" => null,
                            "unused_payment" => 0,
                            "unused_credits" => 0,
                            "comm_track_type" => 3,
                            "app_txn_id" => $CreditMemoRet->txnID,
                            "app_txn_type" => 13,
                            "processed" => 0,
                            "app_txn_date" => $CreditMemoRet->TxnDate,
                            "app_refnumber" => $CreditMemoRet . RefNumber,
                            "app_balance_remaining" => -($CreditMemoRet->SubTotal - $cPaidAlready),
                            "app_amount" => -($CreditMemoRet->SubTotal - $cPaidAlready)
                        ]);
                    }
                }
            }

            $CreditMemoQueryRs = $response->QBXMLMsgsRs->CreditMemoQueryRs->attributes();
            $requestId = $CreditMemoQueryRs['requestID'];

            $iterator = "0";
            $remainingCount = 0;
            if (isset($CreditMemoQueryRs['iteratorID'])) {
                $iterator = $CreditMemoQueryRs['iteratorID'];
                $remainingCount = $CreditMemoQueryRs['iteratorRemainingCount'];
            }

            $queue = QbQueue::where('status', 'ready')->first();
            $queue->RequestID = $requestId + 1;
            $queue->iteratorID = $iterator;
            $queue->RemainingCount = $remainingCount;
            $queue->save();

            if ($remainingCount == 0) {
                $queue->status = "end";
                $queue->save();
                return '100';
            } else {
                return '30';
            }
        }catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }

    public function saveSalesRep($param)
    {
        try {
            $response = simplexml_load_string($param->response);
            \Log::info(print_r($param, true));

            $salesRepRefs = $response->QBXMLMsgsRs->SalesRepQueryRs->SalesRepRet;

            $setupTbl = Setup::first();

            foreach ($salesRepRefs as $salesRepRef) {
                $initial = trim($salesRepRef->Initial);
                $fullName = trim($salesRepRef->SalesRepEntityRef->FullName);

                $aRDSalespersonMasterfile = ARDSalespersonMasterfile::where('salespersonNumber', $initial)->first();
                if (!is_null($aRDSalespersonMasterfile)) {
                    ARDSalespersonMasterfile::where('salespersonNumber', $initial)->update(['name' => $fullName]);
                } else {
                    ARDSalespersonMasterfile::create([
                        "name" => $fullName,
                        "salespersonNumber" => $initial,

                        "commRate" => $setupTbl->DefaultRepCommRate
                    ]);
                }
            }

            $queue = QbQueue::where('status', 'ready')->first();
            $queue->status = "end";
            $queue->save();
            return '100';
        }
        catch (\Exception $e) {
            \Log::info(print_r($e->getMessage(), true));
        }
    }



    public function DoSRLineItem($salesReceiptRet, $SRLR, $SRTxnNumber, $SRRefNumber, $SRSalesRep, $SRLine, $sgrouplistid, $POLink, $UseStdCostPctOfPrice, $sngCostMarkupPct, $curCostMarkupAmt, $SalesRepPct, $CustomerPct, $InvoicePct){
        $setupTbl = Setup::first();
        $noPos = null;
        $addingNoPOS = false;
        \Log::info(print_r('****INSIDE DOSRLINE ITEM****', true));

        $SRDesc = $SRLR->Desc == null ?  "" : $SRLR->Desc;
        $SRAmount = $SRLR->Amount == null ? 0 : $SRLR->Amount;
        $SRQtyItemRef = $SRLR->ItemRef == null ? "" : $SRLR->ItemRef->FullName;
        $SRItemListID = $SRLR->ItemRef == null ? '' : $SRLR->ItemRef->ListID;
        $SRItemType = $SRLR->ItemRef == null ? '' : $SRLR->ItemRef->Type;
        $SRQuantity = $SRLR->Quantity == null ? 0 : $SRLR->Quantity;

        $Item = Item::where('qb_listId', $SRItemListID)->first();

        $SRRate = $SRLR->Rate;
        $SRRatePercent = $SRLR->RatePercent;


        if(isset($Item)){
            $ItemPct = $Item->rate;

            $DataList = $SRLR->DataExtRet;

            $LineItemPct = Null;
            $StdCost = Null;
            $LineItemAmt = Null;
            $StdCostPct = 0;

            foreach($DataList as $DataRet){
                $DataExName = strtoupper($DataRet->DataExtName);
                if(isset($DataRet->DataExtValue)){
                    if($DataExName == 'COMM RATEI'){
                        $LineItemPct = $DataRet->DataExtValue;
                        if($LineItemPct > 100){
                            $LineItemPct = 99;
                        }
                        if($LineItemPct < 0){
                            $LineItemPct = 0;
                        }
                    }else if($DataExName == 'STD COST PCT'){
                        $StdCost = $DataRet->DataExtValue;
                        if($StdCost > 100){
                            $StdCost = 99;
                        }
                        if($StdCost < 0){
                            $StdCost = 0;
                        }
                    }else if($DataExName == 'COMM AMTI'){
                        $LineItemAmt = $DataRet->DataExtValue;
                        if($LineItemAmt > 100){
                            $LineItemAmt = 99;
                        }
                        if($LineItemAmt < 0){
                            $LineItemAmt = 0;
                        }
                    }else{
                        $StdCostPct = $DataRet->DataExtValue;
                    }
                }
            }


            $qbTempInvoice = new QbTempInvoice();
            $qbTempInvoice->txn_number = $SRTxnNumber;
            $qbTempInvoice->ref_number = $SRRefNumber;
            $qbTempInvoice->sales_rep_ref = $SRSalesRep;
            $qbTempInvoice->line = $SRTxnNumber .'-'.$SRLine;
            $qbTempInvoice->description = $SRDesc;
            $qbTempInvoice->amount = $SRAmount;
            $qbTempInvoice->item_ref_name = $SRQtyItemRef;
            $qbTempInvoice->item_list_id  = $sgrouplistid .''.$SRItemListID;
            $qbTempInvoice->item_type = $SRItemType;
            $qbTempInvoice->invoice_type = 'S';
            $qbTempInvoice->quantity = $SRQuantity;
            $qbTempInvoice->rate = $SRRate;


            $qbTempInvoice->txn_id = $salesReceiptRet->txnID;
            $qbTempInvoice->po_link = $POLink;


            if($SRQtyItemRef == null) {
                $qbTempInvoice->cost = null;
            }

            $sCostSource = "";

            if($StdCost == null){
                if($UseStdCostPctOfPrice != 0 && $qbTempInvoice->amount != 0){
                    $sCostSource = 'Pct of Price';
                    $qbTempInvoice->cost =  $qbTempInvoice->amount * $UseStdCostPctOfPrice;
                }
                if($StdCostPct != 0){
                    $sCostSource = 'Pct of Price by Item';
                    $qbTempInvoice->cost = ($qbTempInvoice->amount * $StdCostPct) / 100;
                }
            }else{
                $sCostSource = "Std Cost";
                if($setupTbl->StdCostAsIs == false){
                    $qbTempInvoice->cost = $StdCost;
                }else{
                    $qbTempInvoice->cost = $StdCost * $qbTempInvoice->quantity;
                }
            }

            if($setupTbl->CostMethod == 'PO Link'){
                $vCost = null;
                if($qbTempInvoice->po_link == null){
                    $purchase = PurchaseOrder::where('ref_number', $qbTempInvoice->ref_number)->first();
                    if(!isset($purchase)){
                        $purchase = PurchaseOrder::where('ref_number', $qbTempInvoice->po_link)->first();
                    }

                    if($purchase != null){
                        $vCost = PurchaseOrderDetail::where('item_ref_list_id', $qbTempInvoice->item_list_id)->where('purchase_order_id', $purchase->id)->max('rate');
                    }

                    if($vCost != null){
                        $qbTempInvoice->cost = $vCost * $qbTempInvoice->quantity;
                    }else{

                        $addingNoPOS = true;
                        $noPos = new NoPos();
                        $noPos->inovice_number = $qbTempInvoice->ref_number;
                        $noPos->invoice_txn_number = $qbTempInvoice->txn_number;
                        $noPos->invoice_item_ref_name = $qbTempInvoice->item_ref_name;
                        if($SRDesc != null){
                            $noPos->invoice_item_description =  $SRDesc;
                        }
                        $noPos->line = $qbTempInvoice->line;
                        $noPos->po_ref_number = $qbTempInvoice->po_link;

                    }
                }
            }

            if($qbTempInvoice->cost == null){
                $item = Item::where('qb_listId', $sgrouplistid.''. $SRLR->ItemRef->ListID)->where('stdcost', '>', '0')->frist();
                if($item != null){
                    $qbTempInvoice->cost = $item->stdcost;
                }
            }

            if($qbTempInvoice->cost == null ){
                $item = Item::where('qb_listId', $sgrouplistid.''. $SRLR->ItemRef->ListID)->where('type',  'I')->frist();
                if($item != null && $setupTbl->CostMethod == 'Average'){
                    //!Cost = Nz(DLookup("AvgCost", "tblCosts", "QBListID='" & sgrouplistid & SRLR.ItemRef.ListID.GetValue() & "' AND Invoice='" & !RefNumber & "'"), 0) * Nz(!Quantity)
                }else{
                    $qbTempInvoice->cost = $item->stdcost;
                }
            }

            if($qbTempInvoice->cost == null ){
                $qbTempInvoice->cost = 0;
            }

            //Adding Markup Factors
            $qbTempInvoice->cost = $qbTempInvoice->cost + $sngCostMarkupPct;
            $qbTempInvoice->cost = $qbTempInvoice->cost + $curCostMarkupAmt;

            $item = Item::where('qb_listId', $sgrouplistid.''. $SRLR->ItemRef->ListID)->frist();
            if($item != null){
                $qbTempInvoice->bonus =   $item->bonus;
            }

            if($LineItemAmt != null){
                $qbTempInvoice->commission = $LineItemAmt;
            }else{
                if($item != null){
                    $qbTempInvoice->commission =  $item->commission_amount;
                }
            }

            if($setupTbl->RangeMethod == 'Overall Gross Profit' && $qbTempInvoice->amount != 0){
                $queryCond = (($qbTempInvoice->amount - $qbTempInvoice->cost) * 100) / $qbTempInvoice->cost;
                $profitRange = ProfitRange::where('from_pct', '<=', $queryCond)->where('to_pct', '>=', $queryCond)->first();
                if($profitRange != null){
                    $qbTempInvoice->rate_percent = $profitRange->rate;
                }
                if($qbTempInvoice->rate_percent != null){
                    if($profitRange->pct_of == 'Gross Profit'){
                        $qbTempInvoice->commission  = (($qbTempInvoice->amount - $qbTempInvoice->cost) * $qbTempInvoice->rate_percent) / 100;
                    }else{
                        $qbTempInvoice->commission  = ($qbTempInvoice->amount * $qbTempInvoice->rate_percent) / 100;
                    }

                }
            }

            if(!str_contains($setupTbl->RangeMethod, 'Do Not') && str_contains($setupTbl->RangeMethod, '/Cost') && $qbTempInvoice->cost != 0){
                $queryCond = (($qbTempInvoice->amount - $qbTempInvoice->cost) * 100) / $qbTempInvoice->cost;
                $profitRange = ProfitRange::where('from_pct', '<=', $queryCond)->where('to_pct', '>=', $queryCond)->first();
                if($profitRange != null){
                    $qbTempInvoice->rate_percent = $profitRange->rate;
                }
                if($qbTempInvoice->rate_percent != null){
                    if($profitRange->pct_of == 'Gross Profit'){
                        $qbTempInvoice->commission  = (($qbTempInvoice->amount - $qbTempInvoice->cost) * $qbTempInvoice->rate_percent) / 100;
                    }else{
                        $qbTempInvoice->commission  = ($qbTempInvoice->amount * $qbTempInvoice->rate_percent) / 100;
                    }

                }
            }

            if($qbTempInvoice->commission != null){

                $qbTempInvoice->rate_percent = $SalesRepPct;
                if($CustomerPct > $qbTempInvoice->rate_percent){
                    $qbTempInvoice->rate_percent = $CustomerPct;
                }
                if($InvoicePct > $qbTempInvoice->rate_percent){
                    $qbTempInvoice->rate_percent = $InvoicePct;
                }
                if($ItemPct > $qbTempInvoice->rate_percent){
                    $qbTempInvoice->rate_percent = $ItemPct;
                }
                if($LineItemPct > $qbTempInvoice->rate_percent){
                    $qbTempInvoice->rate_percent = $LineItemPct;
                }
                if($qbTempInvoice->rate_percent < 0){
                    $qbTempInvoice->rate_percent = 0;
                }

                if($setupTbl->CommMethod == 'Gross Profit'){
                    $qbTempInvoice->commission = $qbTempInvoice->amount - $qbTempInvoice->cost / 100;
                }else{
                    $qbTempInvoice->commission = $qbTempInvoice->amount * $qbTempInvoice->rate_percent / 100;
                }

            }else{
                if($qbTempInvoice->quantity == 0){
                    $qbTempInvoice->quantity = 1;
                }

                $qbTempInvoice->commission = $qbTempInvoice->commission * $qbTempInvoice->quantity;
                $qbTempInvoice->rate_percent = null;
            }


            $item = Item::where('qb_listId', $sgrouplistid.''. $SRLR->ItemRef->ListID)->frist();
            if($item != null){
                $qbTempInvoice->commission = $qbTempInvoice->commission + $item->bonus;
            }

            if($addingNoPOS){
                $noPos->cost_used = $qbTempInvoice->cost;
                if($sCostSource != ''){
                    $noPos->message = $sCostSource;
                }

                $noPos->save();
                $addingNoPOS = false;
            }else{
                $qbTempInvoice->group_name = '';
                $qbTempInvoice->total_amount = $SRAmount;
                $qbTempInvoice->group_description = $SRDesc;
                $qbTempInvoice->memo = '';
                $qbTempInvoice->po_number = $POLink;

            }
            $qbTempInvoice->save();
        }

    }
}
