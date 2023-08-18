<?php
namespace App\QB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\QB\QBXML;
use App\QB\QBResponseHandler;
use App\QB\Qb;
use App\Query\JobType;

class QBClient extends Qb
{    

    public function sendRequestXML($param = '')
    {                
        //check queue status        
        
       $qbxml = new QBXML();
       $request = $qbxml->getXML($param);
       $this->response->sendRequestXMLResult = $request;    
        
       return $this->response;
    }

    
    /**
     * Function get response from QB
     *
     * @return  string
     * @param   object $param
     * @access  public
     * @version 2013-03-15
     */
    public function receiveResponseXML($param = '')
    {     
        $qbhandler = new QBResponseHandler();         
        $result = $qbhandler->save($param);
        $this->response->receiveResponseXMLResult = $result;
        return $this->response;       
    }
}
