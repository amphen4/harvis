<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MarketplaceConfig;
use App\Models\Shop;
class Marketplace extends Model
{
    use HasFactory;

    protected $table = 'marketplaces';
    protected $primaryKey = 'marketplaces_id';

    public function marketplaceConfigs()
    {
        return $this->hasMany( MarketplaceConfig::class, 'marketplace_id' , 'marketplaces_id' );
    }

    public function shops()
    {
        return $this->belongsToMany( Shop::class, 'shop_marketplace', 'marketplace_id', 'shop_id' )->withPivot('shop_alias');
    }

    public static function getAvailableApiActionsByMarketplaceName($marketplaceName){
        switch($marketplaceName){
            case 'Mercadolibre':
                return ['getEnviosFlexConfigs','applyApiConfiguration'];
            break;
            default:
                return [];
            break;
        }
        
    }
}
