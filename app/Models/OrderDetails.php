<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $table = 'orderdetails';

    public $timestamps = false;

    protected $primaryKey = 'details_id';

    protected $fillable = [
        'order_id','product_size_id', 'quantity', 'amount','product_name'
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
