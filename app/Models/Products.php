<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'status', 'product_name','product_images', 'product_category', 'descriptions','status_in_stock','unit_name'
    ];

    public function productSizes()
    {
        return $this->hasMany(ProductSizes::class, 'product_id', 'product_id');
    }

}
