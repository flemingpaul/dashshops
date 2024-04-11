<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdClick extends Model
{
    use HasFactory;

    protected $table = 'ad_clicks';

    protected $fillable = [
        'ad_id', 'user_id', 'latitude','longitude','city','state','country'
    ];
}
