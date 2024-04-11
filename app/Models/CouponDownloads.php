<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponDownloads extends Model
{
    use HasFactory;
    protected $table = 'coupons_download';

    protected $fillable = [
      'coupon_id', 'downloads', 'user_id', 'coupon_code'
    ];
}
