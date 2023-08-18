<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARDSalespersonMasterfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'division',
        'salespersonNumber',
        'name',
        'addressLine1',
        'addressLine2',
        'city',
        'state',
        'zipCode',
        'rxtension',
        'salesManagerDivision',
        'salesManager',
        'telephoneNo',
        'addressLine3',
        'countryCode',
        'emailAddress',
        'commRate',
        'salesPTD',
        'salesYTD',
        'salesPYR',
        'profitPTD',
        'profitYTD',
        'profitPYR',
        'commPTD',
        'commYTD',
        'commPYR',
        'salesManagerRate',
        'salesNextPeriod',
        'profitNextPeriod',
        'commNextPeriod',
        'multipleReps',
        'splitRep1',
        'splitRep2',
        'splitRep3',
        'splitRepPct1',
        'splitRepPct2',
        'splitRepPct3',
        'srGroup',
        'splitRep4',
        'splitRep5',
        'splitRepPct4',
        'splitRepPct5',
        'export',
        'drawAmount',
        'dateLastProcessed'
    ];

    protected $hidden = [];
}
