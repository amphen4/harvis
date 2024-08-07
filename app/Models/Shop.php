<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace;
use App\Models\User;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';
    protected $primaryKey = 'shops_id';

    public function marketplaces()
    {
        return $this->belongsToMany( Marketplace::class, 'shop_marketplace', 'shop_id', 'marketplace_id' )->withPivot('shop_alias');
    }

    public function users()
    {
        return $this->belongsToMany( User::class, 'user_shop', 'shop_id', 'user_id' )->withTimestamps();
    }

    public function hasThisMarketplace($marketplaceId){
        return $this->marketplaces()->where('marketplaces_id', $marketplaceId)->count() ? true : false;
    }

    public function hasThisMarketplaceByName($marketplaceName){
        return $this->marketplaces()->where('name', $marketplaceName)->count() ? true : false;
    }
}
