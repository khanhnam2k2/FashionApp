<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_order',
        'full_name',
        'phone',
        'email',
        'address_details',
        'city',
        'district',
        'ward',
        'message',
        'status',
        'code',
        'complete_date'
    ];
}
