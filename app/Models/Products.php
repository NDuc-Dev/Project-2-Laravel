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
        'product_name',
        'descriptions',
        'product_images',
        'product_category',
        'unit',
        'status',
        'status_in_stock',
    ];

    public function productSizes()
    {
        return $this->hasMany(ProductSizes::class, 'product_id', 'product_id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Sizes::class, 'productsizes', 'product_id', 'size_id')->withPivot('unit_price');
    }

}
