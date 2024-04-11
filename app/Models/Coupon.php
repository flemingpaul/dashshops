<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupons';
    protected $fillable = [
        'image', 'name', 'price', 'category_id', 'download_limit', 'retailer_id', 'retail_price',
        'discount_percentage', 'discount_now_price', 'discount_description', 'start_date', 'end_date', 'sku_code', 'discount_code', 'offer_type', 'approval_status'
    ];

    public function category(): HasOne {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function retailer(): BelongsTo {
        return $this->belongsTo(Retailer::class, 'retailer_id', 'id');
    }
    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
