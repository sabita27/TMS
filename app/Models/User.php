<?php
namespace App\Models;

use App\Models\ClientDetail;
use App\Models\Role;
use App\Models\StaffDetail;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ✅ IMPORT MISSING MODELS
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role_id',
        'status',
        'password',
    ];

    /**
     * ⚠️ OPTIONAL: remove if not needed globally
     */
    protected $with = ['legacyRole', 'client_detail'];

    /**
     * 🔗 Legacy Role (your custom role_id)
     */
    public function legacyRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * 👤 Staff Details
     */
    public function staff_detail()
    {
        return $this->hasOne(StaffDetail::class);
    }

    /**
     * 👤 Client Details
     */
    public function client_detail()
    {
        return $this->hasOne(ClientDetail::class);
    }

    /**
     * 🎫 Assigned Tickets
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * 🔒 Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 📅 Casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
