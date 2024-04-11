<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';
    protected $fillable = [
        'name', 'abbreviation'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'id', 'state');
    }
}
