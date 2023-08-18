<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommissionController extends Controller
{
    //
    public function plan() {
        return view("pages.commission.plan");
    }
}
