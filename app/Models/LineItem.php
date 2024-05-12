<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'product_id', 'variation_id', 'quantity', 'tax_class', 'subtotal', 'subtotal_tax', 'total', 'total_tax', 'sku', 'price', 'image_src', 'parent_name'
    ];

    protected $casts = [
        'image_src' => 'array', // To cast JSON string to array
    ];
}
