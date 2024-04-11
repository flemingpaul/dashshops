<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFavorite extends Model
{
    use HasFactory;

    protected $table = 'product_favorites';

    protected $fillable = [
        'user_id', 'product_variation_id'
    ];
}
