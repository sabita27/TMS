<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'product_id',
        'category_id',
        'sub_category_id',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'forwarded_to',
        'closed_at',
        'attachment'
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'sub_category_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function forwardedTo()
    {
        return $this->belongsTo(User::class, 'forwarded_to');
    }
}
