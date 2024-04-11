<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    use HasFactory;

    protected $table = 'login_tokens';

    protected $fillable = [
        'token', 'user_id', 'device_token','device_type'
    ];
}
