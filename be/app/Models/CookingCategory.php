<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookingCategory extends Model
{
    protected $table = 'cooking_categories';
    protected $primaryKey = 'cooking_category_id';
    protected $fillable = ['name'];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'cooking_category_id', 'cooking_category_id');
    }
}
