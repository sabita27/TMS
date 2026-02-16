<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function subCategories()
    {
        return $this->hasMany(ProductSubCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
