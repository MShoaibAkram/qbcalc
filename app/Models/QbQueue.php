<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QbQueue extends Model
{
    use HasFactory;

    protected $table = "qb_queue";
    
    protected $fillable = [
        "queue_type", "param", "status", 'RequestID', 'iteratorID', 'RemainingCount'
    ];
    
    protected $hidden = [];
    
}
