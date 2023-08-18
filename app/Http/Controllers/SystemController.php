<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Query\JobType;
use App\Models\QbQueue;

class SystemController extends Controller
{
    //
    public function option() {
        return view("pages.system.option");
    }

    public function utility() {
        return view("pages.system.utility");
    }

    public function getSalesrep()
    {
        DB::table('qb_queue')->insert([
            'queue_type' => 'Load_SalesRep',
            'param' => json_encode([

            ]),
            'status' => "ready",
            'RequestID' => "1",
            'iteratorID' => '0',
            'RemainingCount' => '0'
        ]);

        return view("pages.system.option");
    }
}
