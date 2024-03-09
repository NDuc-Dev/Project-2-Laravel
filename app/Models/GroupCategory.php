<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCategory extends Model
{
    use HasFactory;
    protected $table = 'group_category';

    protected $primaryKey = 'group_id';
    protected $fillable = [
        'group_name'
    ];
}
