<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponRedeemed extends Model
{
    use HasFactory;
    protected $table = 'coupon_redemption';
    protected $fillable = [
        'coupon_id', 'user_id', 'coupon_download_id', 'redemption_code'
    ];
}
