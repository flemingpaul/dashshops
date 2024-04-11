<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variation';

    protected $fillable = [
        'product_id', 'variant_types', 'variant_type_values', 'price', 'on_sale', 'sale_price', 'quantity', 'low_stock_value', 'sku','status'
    ];
}
