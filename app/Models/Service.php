<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'subcategory_id',
        'price',
        'description',
        'status'
    ];

    /**
     * Get the category that owns the service.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the subcategory that owns the service.
     */
    public function subcategory()
    {
        return $this->belongsTo(ProductSubCategory::class, 'subcategory_id');
    }
}
