<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MarketplaceConfig;

class Marketplace extends Model
{
    use HasFactory;

    protected $table = 'marketplaces';
    protected $primaryKey = 'marketplaces_id';

    public function marketplaceConfigs()
    {
        return $this->hasMany( MarketplaceConfig::class, 'marketplace_id' , 'marketplaces_id' );
    }
}
