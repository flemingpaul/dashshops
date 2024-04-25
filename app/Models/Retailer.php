<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Retailer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'retailers';

    protected $fillable = [
        'business_name', 'business_address',
        'business_description', 'firstname', 'lastname',
        'phone_number',
        'email',
        'type_of_business',
        'business_hours_open',
        'business_hours_close',
        'banner_image', 'city',
        'state',
        'zip_code',
        'island',
        'web_url',
        'password',
        'approval_status',
        'latitude',
        'longitude',
        'from_mobile',
        'created_by',
        'modified_by'
    ];
    protected $hidden = [
        'password',
    ];

    public function category(): HasOne {
        return $this->hasOne(Category::class, 'id', 'type_of_business');
    }
    public function user(): HasOne {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function coupon(): HasMany {
        return $this->hasMany(Coupon::class, 'coupon_id', 'id');
    }
}
