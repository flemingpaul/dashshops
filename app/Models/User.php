<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_name',
        'business_address',
        'retailer_id',
        'firstname',
        'lastname',
        'email',
        'city',
        'state',
        'zip_code',
        'admin',
        'is_vip',
        'phone_number',
        'photo',
        'user_type',
        'user_status',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function state(): HasOne{
        return $this->hasOne(State::class, 'id', 'state');
    }
    public function retailer(): HasOne{
        return $this->hasOne(Retailer::class, 'id', 'retailer_id');
    }
}
