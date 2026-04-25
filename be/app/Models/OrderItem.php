<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    protected $fillable = ['order_id', 'menu_id', 'qty', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    public function orderItemAttributes()
    {
        return $this->hasMany(OrderItemAttribute::class, 'order_item_id', 'order_item_id');
    }
}
