<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemAttribute extends Model
{
    protected $table = 'order_item_attributes';
    protected $primaryKey = 'order_item_attribute_id';
    protected $fillable = ['order_item_id', 'attribute_id', 'value'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'attribute_id');
    }
}
