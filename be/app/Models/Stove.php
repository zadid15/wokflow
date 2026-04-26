<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stove extends Model
{
    protected $table = 'stoves';
    protected $primaryKey = 'stove_id';
    protected $fillable = ['name', 'is_active'];

    public function woks()
    {
        return $this->hasMany(Wok::class, 'stove_id', 'stove_id');
    }
}
