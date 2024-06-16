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
}
