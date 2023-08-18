<?php
/**
 * Created by PhpStorm.
 * User: shoaibakram
 * Date: 07/06/2022
 * Time: 11:26 PM
 */

namespace App\Helpers;


use Illuminate\Support\Facades\DB;

class CommMethods{


    public function g_ClearTable($tableName){
        $query = 'DELETE FROM '.$tableName;
        DB::select($query);
    }


}