<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase_Item extends Model
{
    use HasFactory;
    protected $table = 'purchase__items'; // 👈 TAMBAHKAN INI

    protected $fillable = [
        'purchase_id',
        'ingredient_id',
        'quantity',
        'cost_price',
        'total_price'
    ];
}
