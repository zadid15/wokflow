<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $primaryKey = 'attribute_id';
    protected $fillable = ['name'];

    public function orderItemAttributes()
    {
        return $this->hasMany(OrderItemAttribute::class, 'attribute_id', 'attribute_id');
    }
}
