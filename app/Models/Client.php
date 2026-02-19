<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'country', 'state', 'status',
        'contact_person_name', 'contact_person_phone',
        'product_id', 'project_id', 'project_start_date', 'project_end_date', 'remarks',
        'contact_person1_name', 'contact_person1_phone', 'contact_person2_name', 'contact_person2_phone',
        'business_type', 'project_title', 'project_description', 'attachment'
    ];

    protected $casts = [
        'product_id' => 'array',
        'project_id' => 'array',
        'project_start_date' => 'date',
        'project_end_date' => 'date',
    ];

    public function services()
    {
        return $this->hasMany(ClientService::class);
    }
}
