<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'alternative_brands',
        'logo',
        'description',
        'product_images'  // Add this line
    ];

    protected $casts = [
        'alternative_brands' => 'array',
        'product_images' => 'array',
    ];

}
