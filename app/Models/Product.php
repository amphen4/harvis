<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'products_id';

    protected $fillable = [
        'name',
        'sku',
        'stock',
        'price',
        'active',
        'shop_id',
        'marketplace_id',
        'created_at',
        'updated_at'
    ];

    public function productVariants()
    {
        return $this->hasMany( ProductVariant::class, 'product_id', 'products_id' );
    }
}
