<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponClicks extends Model
{
    use HasFactory;
    protected $table = 'coupons_clicks';

    protected $fillable = [
        'coupon_id', 'clicks','state','city'
    ];
}
