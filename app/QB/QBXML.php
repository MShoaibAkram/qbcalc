<?php

/**
 * File contains class Qb_Clients() extends Qb()
 */

namespace App\QB;

use App\Models\ReceivePaymentRequest;
use Illuminate\Support\Facades\Log;
use App\Query\JobType;

class QBXML
{

    public function getXML($xml_param){

        $queue = \DB::table('qb_queue')->where('status', 'ready')->first();
        $type = $queue->queue_type;
        $param = $queue->param;
        $result = "E: Invalid ticket.";        

        if($type == JobType::Load_customer){
            $result = $this->getCustomerXML($queue, $xml_param);
        }
        else if($type == JobType::Load_Item_Inventory){
            $result = $this->getItemInventoryXML($queue, $xml_param);
        }

        else if($type == JobType::Load_Item_Services_Inventory){
            $result = $this->getItemServicesInventoryXML($queue, $xml_param);
        }

        else if($type == JobType::Load_Item_Non_Inventory){
            $result = $this->getItemNonInventoryXML($queue, $xml_param);
        }

        else if($type == JobType::Load_Item_Other_Charges){
            $result = $this->getItemOtherChargesXML($queue, $xml_param);
        }

        else if($type == JobType::Load_Item){
            $result = $this->getItemXML($queue, $xml_param);
        }

        else if($type == JobType::Load_Item_Group_Data){
            $result = $this->getItemGroupDataXml($queue, $xml_param);
        }
        else if($type == JobType::Load_Item_Inventory_Assembly){
            $result = $this->getItemAssemblyInventoryDataXml($queue, $xml_param);
        }
        else if($type == JobType::Load_Invoice){
            $result = $this->getInvoiceXML($queue, $xml_param);
        }
        else if($type == JobType::Load_Deleted_Invoices){
            $result = $this->getDeletedInvoiceXML($queue, $xml_param);
        }
        if($type == JobType::Load_SalesRep){
            $result = $this->getSalesRepXML($queue, $xml_param);
        }
        if($type == JobType::Load_Sales_Receipt_Payment){
            $result = $this->getSalesReceiptPaymentXML($queue, $xml_param);
        }
        if($type == JobType::Load_Txn_Deleted){
            $result = $this->getTxnDeletedXML($queue, $xml_param);
        }
        if($type == JobType::Load_Credit_Memo){
            $result = $this->getCreditMemoXML($queue, $xml_param);
        }

        return $result;
    }

    //INVOICE DATA XMLs
    public function getInvoiceXML($queue, $xml_param){
        $param = json_decode($queue->param);
        //$from_date = $param->from_date;
        //$to_date = $param->to_date;
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;
         \Log::info(print_r($iteratorID, true));

         $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <InvoiceQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                </InvoiceQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';
        \Log::info(print_r($xml, true));
        return $xml;

    }

    public function getDeletedInvoiceXML($queue, $xml_param){
        $param = json_decode($queue->param);
        
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        \Log::info(print_r($iteratorID, true));

        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <TxnDeletedQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <TxnDelType >14</TxnDelType>
                </TxnDeletedQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;

    }
    //INVOICE DATA XMLs END

    public function getItemAssemblyInventoryDataXml($queue, $xml_param){
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 10;
        \Log::info(print_r($iteratorID, true));

        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemInventoryAssemblyQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <ActiveStatus >All</ActiveStatus>
                    <OwnerID >0</OwnerID>
                </ItemInventoryAssemblyQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;

    }


    public function getItemGroupDataXml($queue, $xml_param){
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 10;
        \Log::info(print_r($iteratorID, true));

        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemGroupQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <ActiveStatus >All</ActiveStatus>
                    <OwnerID >0</OwnerID>
                </ItemGroupQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;

    }

    public function getItemOtherChargesXML($queue, $xml_param){
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        //test with hardcode date
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemOtherChargeQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <ActiveStatus >All</ActiveStatus>
                    <OwnerID >0</OwnerID>
                </ItemOtherChargeQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
        
    }



    public function getItemNonInventoryXML($queue, $xml_param){
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        //test with hardcode date
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemNonInventoryQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <ActiveStatus >All</ActiveStatus>
                    <OwnerID >0</OwnerID>
                </ItemNonInventoryQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
        
        
    }


    public function getItemServicesInventoryXML($queue, $xml_param){
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        //test with hardcode date
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemServiceQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <ActiveStatus >All</ActiveStatus>
                    <OwnerID >0</OwnerID>
                </ItemServiceQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
        
        
    }

    public function getItemInventoryXML($queue, $xml_param){
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        //test with hardcode date
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }
            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemInventoryQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <ActiveStatus >All</ActiveStatus>
                    <OwnerID >0</OwnerID>
                </ItemInventoryQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
        
        
    }

    public function getItemXML($queue, $xml_param){

        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        //test with hardcode date
        $to_date = date_format(date_create($param->to_date), 'd/m/y');
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;


        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }

            
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <ItemQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <FromModifiedDate >'.$from_date.'</FromModifiedDate>
                </ItemQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
    }

    public function getSalesReceiptPaymentXML($queue, $xml_param)
    {
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        $to_date = $param->to_date;
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }

        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <SalesReceiptQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned>'.$MAX_PER_PASS.'</MaxReturned>
                     <ModifiedDateRangeFilter> <!-- optional -->
                            <FromModifiedDate>'.$from_date.'</FromModifiedDate>
                            <ToModifiedDate>'.$to_date.'</ToModifiedDate>
                    </ModifiedDateRangeFilter>
                    <OwnerID>0</OwnerID>
                </SalesReceiptQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
    }

    public function getTxnDeletedXML($queue, $xml_param)
    {
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        $to_date = $param->to_date;
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
                <QBXMLMsgsRq onError="stopOnError">
                        <TxnDeletedQueryRq>
                           <TxnDelType >SalesReceipt</TxnDelType>
                           <DeletedDateRangeFilter> <!-- optional -->
                                <FromDeletedDate >'.$from_date.'</FromDeletedDate> <!-- optional -->
                                <ToDeletedDate >'.$to_date.'</ToDeletedDate> <!-- optional -->
                            </DeletedDateRangeFilter>
                        </TxnDeletedQueryRq>
                </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
    }

    public function getCustomerXML($queue, $xml_param)
    {
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        $to_date = $param->to_date;
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;        

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }

        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <CustomerQueryRq requestID="'.$RequestID.'" '.$iterator.'>
                    <MaxReturned >'.$MAX_PER_PASS.'</MaxReturned>
                    <FromModifiedDate >'.$from_date.'</FromModifiedDate>
                    <ToModifiedDate >'.$to_date.'</ToModifiedDate>
                    <OwnerID >0</OwnerID>
                </CustomerQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
    }

    private function getCreditMemoXML($queue, $xml_param)
    {
        $param = json_decode($queue->param);
        $from_date = $param->from_date;
        $to_date = $param->to_date;
        $RequestID = $queue->RequestID;
        $iteratorID = $queue->iteratorID;
        $MAX_PER_PASS = 200;

        \Log::info(print_r($iteratorID, true));
        $iterator = 'iterator="Start"';
        if($iteratorID !== "0"){
            $iterator='iterator="Continue" iteratorID="'.$iteratorID.'"';
        }

        $ReceivePaymentRequest = \DB::table('receive_payment_requests')->where('comm_track_type', 3)->orderBy('modified_at', 'desc')->first();
        if(!is_null($ReceivePaymentRequest) && !is_null($ReceivePaymentRequest->modified_at)){
            $from_date = date('Y-m-d',strtotime($ReceivePaymentRequest->modified_at));
        }
        \Log::info(print_r($from_date, true));

        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
            <QBXMLMsgsRq onError="stopOnError">
                <CreditMemoQueryRq  requestID="'.$RequestID.'" '.$iterator.'>
                   <MaxReturned>'.$MAX_PER_PASS.'</MaxReturned>
                   <ModifiedDateRangeFilter>
                    <FromModifiedDate >'.$from_date.'</FromModifiedDate>
                    <ToModifiedDate >'.$to_date.'</ToModifiedDate>
                    </ModifiedDateRangeFilter>
                    <OwnerID>0</OwnerID>
                </CreditMemoQueryRq>
            </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;
    }

    public function getSalesRepXML($queue, $xml_param)
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <?qbxml version="15.0"?>
        <QBXML>
                <QBXMLMsgsRq onError="stopOnError">
                        <SalesRepQueryRq/>
                </QBXMLMsgsRq>
        </QBXML>';

        \Log::info(print_r($xml, true));
        return $xml;


    }
}
