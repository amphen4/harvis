<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopBrand extends Model
{
    use HasFactory;

    protected $table = 'shop_brands';
    protected $primaryKey = 'shop_brands_id';
}
