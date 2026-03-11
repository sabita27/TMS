<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $table = 'ticket_reply';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'staff_id',
        'reply_by',
        'replay'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }



    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
