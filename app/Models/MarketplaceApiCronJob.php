<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class MarketplaceApiCronJob extends Model
{
    use HasFactory;

    protected $table = 'marketplace_api_cron_jobs';
    protected $primaryKey = 'marketplace_api_cron_jobs_id';

    protected $fillable = [
        'frequency_type',
        'weekdays', // JSON
        'dayhours', // JSON
        'cron_name',
        'payload' // JSON
    ];

    public function shop (){
        return $this->belongsTo(Shop::class, 'shop_id', 'shops_id');
    }
    public function marketplace (){
        return $this->belongsTo(Marketplace::class, 'marketplace_id', 'marketplaces_id');
    }

}
