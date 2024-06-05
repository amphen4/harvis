<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProductCategory extends Model
{
    use HasFactory;

    protected $table = 'shop_product_categories';
    protected $primaryKey = 'shop_product_categories_id';
}
