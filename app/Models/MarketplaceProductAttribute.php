<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'marketplace_product_attributes';
    protected $primaryKey = 'marketplace_product_attributes_id';
}
