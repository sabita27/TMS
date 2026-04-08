<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'phone',

        // Relations (JSON / array fields)
        'product_id',
        'project_id',

        // Project Info
        'project_title',
        'project_description',
        'attachment',
        'project_start_date',
        'project_end_date',

        // Extra Info
        'remarks',
        'address',
        'country',
        'state',

        // Contact Persons
        'contact_person1_name',
        'contact_person1_phone',
        'contact_person2_name',
        'contact_person2_phone',

        // Business
        'business_type',
        'status'
    ];

    /**
     * Cast JSON fields properly
     */
    protected $casts = [
        'product_id' => 'array',
        'project_id' => 'array',
        'project_start_date' => 'date',
        'project_end_date' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Relationship: Client Services
     */
    public function services()
    {
        return $this->hasMany(ClientService::class);
    }
}