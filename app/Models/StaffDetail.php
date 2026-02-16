<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDetail extends Model
{
    protected $fillable = ['user_id', 'designation_id', 'position_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
