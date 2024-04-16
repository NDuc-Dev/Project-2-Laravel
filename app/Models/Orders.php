<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'Orders';

    public $timestamps = false;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_date',
        'order_type',
        'order_by',
        'delivery_address',
        'customer_phone',
        'customer_email',
        'total',
        'order_status',
        'table_id',
        'prepared_at',
        'prepared_by',
        'delivery_code',
        'success_at',
        'receipt_path',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'order_by', 'user_id');
    }

    public function table()
    {
        return $this->belongsTo(Tables::class, 'table_id', 'table_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id', 'order_id');
    }
}
