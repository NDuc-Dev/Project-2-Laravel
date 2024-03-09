<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizes extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'productsizes';

    protected $primaryKey = 'product_size_id';

    protected $fillable = [
        'product_id', 'size_id', 'unit_price',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(Sizes::class, 'size_id', 'size_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'product_size_id', 'product_size_id');
    }

}
