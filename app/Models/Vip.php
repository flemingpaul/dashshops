<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vip extends Model
{
    use HasFactory;

    protected $table = 'vips';
    protected $fillable = [
        'user_id', 'expiry_date'
    ];

    
}
