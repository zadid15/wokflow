<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wok extends Model
{
    protected $table = 'woks';
    protected $primaryKey = 'wok_id';
    protected $fillable = ['stove_id', 'capacity', 'is_active'];

    public function stove()
    {
        return $this->belongsTo(Stove::class, 'stove_id', 'stove_id');
    }
}
