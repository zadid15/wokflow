<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'queue_number',
        'status',
        'source',
        'customer_name',
        'customer_phone',
        'created_by',
        'updated_by',
    ];
}
