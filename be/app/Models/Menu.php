<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'menu_id';
    protected $fillable = ['business_category_id', 'cooking_category_id', 'name', 'price'];

    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id', 'business_category_id');
    }

    public function cookingCategory()
    {
        return $this->belongsTo(CookingCategory::class, 'cooking_category_id', 'cooking_category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'menu_id', 'menu_id');
    }
}
