<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $table = 'business_categories';
    protected $primaryKey = 'business_category_id';
    protected $fillable = ['name'];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'business_category_id', 'business_category_id');
    }
}
