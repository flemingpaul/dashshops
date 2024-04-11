<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    protected $table= 'categories';
    protected $fillable = ['name','badge','banner_image'];

    public function coupon(): BelongsTo{
        return $this->belongsTo(Coupon::class);
    }

    public function retailer(): BelongsTo{
        return $this->belongsTo(Retailer::class);
    }

}
