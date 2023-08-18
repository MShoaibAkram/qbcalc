<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    //
    public function view() {
        return view("pages.bill.view");
    }

    public function sendToQB() {
        return view("pages.bill.send-to-qb");
    }
}
