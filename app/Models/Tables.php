<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    use HasFactory;

    protected $table = 'tables'; // Tên bảng thực tế

    public $timestamps = false;

    protected $primaryKey = 'table_id';

    protected $fillable = [
        'table_status',
    ];

    public function orders()
    {
        return $this->hasMany(Orders::class, 'table_id', 'table_id');
    }
}
