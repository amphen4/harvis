<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';
    protected $primaryKey = 'shops_id';

    public function marketplaces()
    {
        return $this->belongsToMany( Marketplace::class, 'shop_marketplace', 'shop_id', 'marketplace_id' )->withPivot('shop_alias');
    }
}
