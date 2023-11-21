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
        'address',
        'message',
        'status',
        'code',
        'payment_status',
        'payment_method',
    ];
}
