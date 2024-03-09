<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sizes extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'sizes'; // Tên bảng thực tế

    protected $primaryKey = 'size_id';

    protected $fillable = [
        'size_name',
    ];

    public function productSizes()
    {
        return $this->hasMany(ProductSizes::class, 'size_id', 'size_id');
    }
}
