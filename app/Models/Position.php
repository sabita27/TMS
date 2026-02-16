<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['designation_id', 'name'];

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
