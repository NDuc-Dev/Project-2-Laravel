<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'orderdetails';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'product_size_id', 'product_quantity', 'amount',
    ];

    public function order()
    {
        return $this->belongsTo(OrderDetails::class, 'order_id', 'order_id');
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSizes::class, 'product_size_id', 'product_size_id');
    }
}
