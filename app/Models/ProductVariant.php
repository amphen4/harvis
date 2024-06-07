<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';
    protected $primaryKey = 'product_variants_id';

    protected $fillable = [
        'name',
        'sku',
        'stock',
        'price',
        'active',
        'created_at',
        'updated_at',
        'product_id',
        'talla',
        'color'
    ];

    public function product()
    {
        return $this->belongsTo( Product::class, 'product_id', 'products_id' );
    }
}
