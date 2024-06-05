<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace;
use App\Models\Shop;

class MarketplaceConfig extends Model
{
    use HasFactory;

    protected $table = 'marketplace_configs';
    protected $primaryKey = 'marketplace_configs_id';

    public function marketplace()
    {
        return $this->belongsTo( Marketplace::class, 'marketplace_id', 'marketplaces_id' );
    }

    public function shops()
    {
        return $this->belongsToMany( Shop::class, 'shop_marketplace_configs', 'marketplace_config_id', 'shop_id' )->withPivot('value');
    }
}
