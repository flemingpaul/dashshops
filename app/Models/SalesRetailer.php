<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRetailer extends Model
{
    use HasFactory;

    protected $table = 'sales_retailers';

    protected $fillable = ['sales_user_id', 'retailer_id'];
}
