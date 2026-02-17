<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'category_id', 
        'subcategory_id', 
        'status_id', 
        'priority_id', 
        'start_date', 
        'end_date', 
        'description', 
        'attachment', 
        'remarks', 
        'status'
    ];

    /**
     * Get the category that owns the project.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the subcategory that owns the project.
     */
    public function subcategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'subcategory_id');
    }

    /**
     * Get the status that owns the project.
     */
    public function projectStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    /**
     * Get the priority that owns the project.
     */
    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }
}
