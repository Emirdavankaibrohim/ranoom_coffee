<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [ 'product_id','user_id','status',
                            'order_code','quantity','totalprice',
                            'payment_method','order_type','size','notes',
                            'delivery_location_id', 'customer_name', 'customer_phone'
                          ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentRecords()
    {
        return $this->hasMany(PaymentRecord::class, 'order_code', 'order_code'); // Assuming link via order_code
    }
}
