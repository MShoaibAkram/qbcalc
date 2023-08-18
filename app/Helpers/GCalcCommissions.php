<?php
/**
 * Created by PhpStorm.
 * User: shoaibakram
 * Date: 07/06/2022
 * Time: 11:06 PM
 */

namespace App\Helpers;


use App\Models\ARDSalespersonMasterfile;
use App\Models\CommRpt;
use App\Models\CustomerComms;
use App\Models\CustomerMaster;
use App\Models\QbInvoice;
use App\Models\Setup;
use App\Models\MUpline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GCalcCommissions{
    private $filter = '';
    private $bException = false;
    private $Abort  = false;

    private $commMethods = null;

    private $m_CommMethod = null;
    private $m_CustomerComm = null;
    private $m_OverMethod = null;
    private $m_LimitLevels = null;
    private $m_OverLevels = null;


    private function GetSettings(){
        $setupTbl = Setup::first();

        $this->m_CommMethod = $setupTbl->CommMethod;
        $this->m_CustomerComm = $setupTbl->CustomerComm == 'Yes' ? true : false;
        $this->m_OverMethod = $setupTbl->CustomerComm;
        $this->m_LimitLevels = $setupTbl->LimitLevels;
        $this->m_OverMethod = $setupTbl->OverLevels;
        $this->commMethods = new CommMethods();


    }

    public function __construct($a_Filter){
        $this->filter = $a_Filter;
        $this->GetSettings();

        $this->commMethods->g_ClearTable('uplines');
        $this->commMethods->g_ClearTable('customer_comms');
        $this->commMethods->g_ClearTable('comm_rpt');

       $this->BuildUplineList();

        if($this->Abort){

            Log::error('Unable To Calculate Commissions');
        }else{

            $this->CopyRepComms();
            if($this->m_CustomerComm){
                $this->AdjustRepComms();
            }
            if(!$this->m_LimitLevels){
                $this->CalcOverrides();
            }
        }


    }


    private function BuildUplineList(){

        $this->Abort = false;
        $this->commMethods->g_ClearTable('uplines');

        $rsUL = null;//new MUpline();

        $rsRM = DB::table('a_r_d_salesperson_masterfiles as ardSalesMasterFile1')
            ->join('a_r_d_salesperson_masterfiles', 'ardSalesMasterFile1.salesManager', '=', 'a_r_d_salesperson_masterfiles.salespersonNumber')
            ->where('ardSalesMasterFile1.salesManager', '!=', null)
            ->selectRaw('ardSalesMasterFile1.salespersonNumber as RepID, 
            ardSalesMasterFile1.salesManager as MgrID, 
            ardSalesMasterFile1.name as MgrName, 
            ardSalesMasterFile1.salesManagerRate, 
            ardSalesMasterFile1.name as RepName')
            ->orderBy('ardSalesMasterFile1.salespersonNumber')
            ->get();

        //return var_dump($rsRM);

        $iPasses = 0;
        $iInsidePasses = 0;

        if($rsRM != null){
            foreach($rsRM as $arsRM){
                if($iPasses > 200){
                    Log::error('There is a loop in your upline managers.  Unable to continue');
                    $this->Abort = true;
                    return;
                }else{
                    $iInsidePasses = 0;

                    $currentrsRM = $arsRM;

                    $varBMs = ARDSalespersonMasterfile::where('salespersonNumber', '=', $currentrsRM->MgrID)->get();
                    $sRepIDs = $arsRM->RepID. ',';

                    //return var_dump($varBMs);

                    foreach($varBMs as $varBM){
                        if($iInsidePasses > 200){
                            Log::error('There is a loop in your upline managers.  Unable to continue');
                            $this->Abort = true;
                            return;
                        }
                        $iInsidePasses += 1;
                        $rsUL = new MUpline();
                        $rsUL->RepIDs = $sRepIDs; // contain chain of rep ids in it..
                        $rsUL->MgrID = $arsRM->MgrID;
                        $rsUL->MgrName = $arsRM->MgrName;
                        $rsUL->MgrRate = $arsRM->salesManagerRate;
                        $rsUL->save();
                        $sRepIDs .= $arsRM->MgrID . ',';
                    }
                    $iPasses += 1;
                }
            }
        }
    }


    private function CopyRepComms(){

        $resRsRm = DB::table('salespercommdetails')
            ->join('a_r_d_salesperson_masterfiles', 'salespercommdetails.SalespersonNumber', '=', 'a_r_d_salesperson_masterfiles.salespersonNumber')
            ->selectRaw('salespercommdetails.SalespersonNumber AS RepID, salespercommdetails.Name AS RepName, 
            salespercommdetails.CustomerNumber AS CustID, salespercommdetails.InvoiceNumber AS InvNo, 
            salespercommdetails.InvoiceDate AS InvDate, salespercommdetails.PayDate as PayDate, salespercommdetails.ApplyToNumberCMDM AS ApplyTo, salespercommdetails.CommRate as CommRate, salespercommdetails.InvoiceTotal AS InvTotal, 
            salespercommdetails.SalesSubjectToComm AS SalesSubject, salespercommdetails.CostSubjectToComm AS CostSubject, Round(salespercommdetails.CommAmount,2) AS CommAmt, salespercommdetails.InvoiceAmountPaid AS InvAmtPaid, salespercommdetails.SplitCommPercent AS Split, "D" AS CommType, salespercommdetails.Memo AS Memo, salespercommdetails.PONumber AS PONumber, salespercommdetails.TxnNumber')
            ->get();


       // return var_dump($resRsRm);

        foreach($resRsRm as $rsRM){
            $commRpt = new CommRpt();
            $commRpt->RepID = $rsRM->RepID;
            $commRpt->RepName = $rsRM->CustID;
            $commRpt->InvNo = $rsRM->InvNo;
            $commRpt->InvDate = $rsRM->InvDate;
            $commRpt->PayDate = $rsRM->PayDate;
            $commRpt->ApplyTo = $rsRM->ApplyTo;
            $commRpt->CommRate = $rsRM->CommRate;
            $commRpt->InvTotal = $rsRM->InvTotal;
            $commRpt->SalesSubject = $rsRM->SalesSubject;
            $commRpt->CostSubject = $rsRM->CostSubject;
            $commRpt->CommAmt = $rsRM->CommAmt;
            $commRpt->InvAmtPaid = $rsRM->InvAmtPaid;
            $commRpt->Split = $rsRM->Split;
            $commRpt->CommType = $rsRM->CommType;
            $commRpt->Memo = $rsRM->Memo;
            $commRpt->TxnNumber = $rsRM->TxnNumber;
            $commRpt->save();
        }
    }

    public function AdjustRepComms(){


        $customerMaster = CustomerMaster::all();
        foreach ($customerMaster as $cMaster){
            $customerComms = new CustomerComms();
            $customerComms->CustID = $cMaster->CustomerNumber;
            $customerComms->CustRate = $cMaster->ServiceChargeRate;
            $customerComms->save();
        }

        $rsCRs = CommRpt::all();

        $nAmtSubj = null;
        $nCommAmt = null;

        foreach($rsCRs as $rsCR){
            //$customerComms
            $nCustRate = CustomerComms::where('CustID', $rsCR->CustID)->first();
            if($nCustRate != null){
                $nCustRate = $nCustRate->CustRate;
                switch($this->m_CommMethod){
                    case 'Gross Profit':
                        $nAmtSubj = $rsCR->SalesSubject;
                        break;
                    case 'Sales':
                        $nAmtSubj = $rsCR->SalesSubject -  $rsCR->CostSubject;
                        break;
                }

                $nCommAmt = $nAmtSubj * $nCustRate / 100;
                if($rsCR->Split != 100){
                    $nCommAmt = $nCommAmt * $rsCR->Split / 100;
                }

                $rsCR->CommRate = $nCustRate;
                $rsCR->CommAmt = $nCommAmt;
                $rsCR->save();
            }
        }
    }

    public function CalcOverrides(){

        $rsRCs = CommRpt::all();
        $rsULs = MUpline::all();
        $rsCRs = CommRpt::all();

        $nAmtSubj = null;
        $nMgrRate = null;

        $nCommAmt = null;

        if($rsULs != null){
            foreach($rsRCs as $rsRC){
                $rsUL = MUpline::where('RepIDs', 'LIKE', '%'.$rsRC.'%')->get();
                if($rsUL != null){
                    $rsCR = new CommRpt();
                    $rsCR->RepID = $rsUL->MgrID;
                    $rsCR->RepName = $rsUL->MgrName;
                    $rsCR->DownlineIDs = $rsUL->RepIDs;
                    $rsCR->CustID = $rsRC->CustID;
                    $rsCR->InvNo = $rsRC->InvNo;
                    $rsCR->InvDate = $rsRC->InvDate;
                    $rsCR->PayDate = $rsRC->PayDate;
                    $rsCR->ApplyTo = $rsRC->ApplyTo;
                    $rsCR->InvTotal = $rsRC->InvTotal;
                    $rsCR->SalesSubject = $rsRC->SalesSubject;
                    $rsCR->CostSubject = $rsRC->CostSubject;

                    switch($this->m_CommMethod){
                        case 'Sales':
                            $nAmtSubj = $rsRC->SalesSubject;
                            break;
                        case 'Gross Profit':
                            $nAmtSubj = $rsRC->SalesSubject - $rsRC->CostSubject;
                            break;
                    }

                    switch($this->m_CustomerComm){
                        case True:
                            $nMgrRate = CustomerComms::where('CustID', $rsRC->CustID)->firt();
                            $nMgrRate = $nMgrRate->CustRate;
                            break;
                        case False:
                            $nMgrRate = $rsRC->MgrRate;
                            break;
                    }

                    switch($this->m_OverMethod){
                        case 'Amount Subject':
                            $nCommAmt = $nAmtSubj * $nMgrRate / 100;
                            if($rsRC->Split != 100){
                                $nCommAmt = $nCommAmt * $rsRC->Split / 100;
                            }
                            break;
                        case False:
                            $nCommAmt = $rsRC->CommAmt * $nMgrRate / 100;
                            break;
                    }

                    $rsCR->CommRate = $nMgrRate;
                    $rsCR->CommAmt = $nCommAmt;

                    $tempQbInvoice = QbInvoice::where('txn_number', $rsRC->TxnNumber)->first();
                    $rsCR->CommAmt = $tempQbInvoice->percent_paid;

                    $rsCR->InvAmtPaid = $rsRC->InvAmtPaid;
                    $rsCR->Split = $rsRC->Split;
                    $rsCR->CommType = 'O';
                    $rsCR->save();
                }
            }
        }

    }
}