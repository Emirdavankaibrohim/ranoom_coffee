<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;
    //
    protected $fillable = ['total_amount','paid_amount','due_amount','payment_status','supplier_id'];
}
