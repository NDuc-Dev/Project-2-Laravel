<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'user_name',
        'password',
        'role',
        'email',
        'phone',
        'status',
        'token',
        'address'
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;


    public function order()
    {
        return $this->hasMany(Orders::class, 'user_id', 'order_by');
    }

    public function invoice()
    {
        return $this->hasMany(Invoices::class, 'user_id', 'print_by');
    }
}

