<?php

namespace App\Http\ApiResolvers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Marketplace;
use App\Models\Shop;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;

class Mercadolibre {

    protected $marketplace;
    protected $shop;

    function __construct(Marketplace $mercadolibre, Shop $shop){
        $this->marketplace = $mercadolibre;
        $this->shop = $shop;
    }

    function getEnviosFlexConfigs(){
        $outputArray = ['hagoEnvios' => false];
        $classNameConnector = 'App\\Http\Connectors\\'.$this->marketplace->connector_class_name;
        $connector = new $classNameConnector($this->marketplace, $this->shop);
        $SITE_ID = 'MLC';
        $USER_ID = $connector->getShopConfigKey('UserId');
        // Obtener Suscripción Flex
        $responseSuscripciones = $connector->makeRequest('GET', "shipping/flex/sites/$SITE_ID/users/$USER_ID/subscriptions/v1");
        $responseSuscripcionesJsonDecoded = json_decode($responseSuscripciones);
        $flexSubscription = null;
        foreach( $responseSuscripcionesJsonDecoded as $itemSuscripciones ){
            if( $itemSuscripciones->mode == 'FLEX' ){
                $flexSubscription = $itemSuscripciones;
                break;
            }
        }
        if( $flexSubscription ){
            $outputArray['hagoEnvios'] = true;
            $SERVICE_ID = $flexSubscription->service_id;
            $body = json_encode(['query' => 
                "{ configuration (user_id: $USER_ID, service_id: $SERVICE_ID) { address { id address_line city { id name } zip_code } subscription { site_id user_id service_id status { id } creation_date training_times { original { value unit } remaining { value unit } activation_date } } delivery_window is_moderated delivery_ranges { week { capacity type processing_time from to et_hour calculated_cutoff } saturday { capacity type processing_time from to et_hour calculated_cutoff } sunday { capacity type processing_time from to et_hour calculated_cutoff } } working_days zones { enabled id is_mandatory label neighborhoods price { cents currency_id decimal_separator fraction symbol } selected } availables { cutoff capacity_range { from to } ranges } disabled_features } }"
            ]);
            $responseConfigSuscripcion = $connector->makeRequest('POST', "shipping/flex/sites/$SITE_ID/configuration/v3", $body);
            $responseConfigSuscripcionDecoded = json_decode($responseConfigSuscripcion);
            $outputArray['zones'] = [];
            foreach($responseConfigSuscripcionDecoded->configuration->zones as $zone){
                $outputArray['zones'][] = [
                    'enabled' => $zone->selected, // verificar si debe ser $zone->enabled
                    'id' => $zone->id,
                    'name' => $zone->label,
                    'cost' => intval($zone->price->fraction)
                ];
            }
            $outputArray['saturdayEnabled'] = $responseConfigSuscripcionDecoded->configuration->delivery_ranges->saturday ? true : false;
            $outputArray['sundayEnabled'] = $responseConfigSuscripcionDecoded->configuration->delivery_ranges->sunday ? true : false;
            $outputArray['rangehours'] = $responseConfigSuscripcionDecoded->configuration->availables->ranges;
            $outputArray['rangecutoff'] = $responseConfigSuscripcionDecoded->configuration->availables->cutoff;
            $outputArray['capacityMin'] = $responseConfigSuscripcionDecoded->configuration->availables->capacity_range->from;
            $outputArray['capacityMax'] = $responseConfigSuscripcionDecoded->configuration->availables->capacity_range->to;
            $outputArray['apiConfiguration'] = [
                'delivery_ranges' => []
            ];
            if( !!$responseConfigSuscripcionDecoded->configuration->delivery_ranges->week ){
                $outputArray['apiConfiguration']['delivery_ranges']['week'] = [
                    'from' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->week[0]->from,
                    'to' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->week[0]->to,
                    'capacity' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->week[0]->capacity,
                    'cutoff' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->week[0]->calculated_cutoff
                ];
            }
            if( !!$responseConfigSuscripcionDecoded->configuration->delivery_ranges->saturday ){
                $outputArray['apiConfiguration']['delivery_ranges']['saturday'] = [
                    'from' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->saturday[0]->from,
                    'to' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->saturday[0]->to,
                    'capacity' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->saturday[0]->capacity,
                    'cutoff' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->saturday[0]->calculated_cutoff
                ];
            }
            if( !!$responseConfigSuscripcionDecoded->configuration->delivery_ranges->sunday ){
                $outputArray['apiConfiguration']['delivery_ranges']['sunday'] = [
                    'from' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->sunday[0]->from,
                    'to' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->sunday[0]->to,
                    'capacity' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->sunday[0]->capacity,
                    'cutoff' => $responseConfigSuscripcionDecoded->configuration->delivery_ranges->sunday[0]->calculated_cutoff
                ];
            }
            $outputArray['meta'] = ['updated_at' => (new Carbon)->toISOString()];
        }
        return $outputArray;
    }
    public function applyApiConfiguration($request){
        
        $classNameConnector = 'App\\Http\Connectors\\'.$this->marketplace->connector_class_name;
        $connector = new $classNameConnector($this->marketplace, $this->shop);
        $SITE_ID = 'MLC';
        $USER_ID = $connector->getShopConfigKey('UserId');
        // Obtener Suscripción Flex
        $responseSuscripciones = $connector->makeRequest('GET', "shipping/flex/sites/$SITE_ID/users/$USER_ID/subscriptions/v1");
        $responseSuscripcionesJsonDecoded = json_decode($responseSuscripciones);
        $flexSubscription = null;
        foreach( $responseSuscripcionesJsonDecoded as $itemSuscripciones ){
            if( $itemSuscripciones->mode == 'FLEX' ){
                $flexSubscription = $itemSuscripciones;
                break;
            }
        }
        if( $flexSubscription ){
            $outputArray['hagoEnvios'] = true;
            $SERVICE_ID = $flexSubscription->service_id;
            // Configurar Plazo de Entrega y Límite de Envíos
            $bodyRequest = [
                "delivery_window" => "same_day",
                "delivery_ranges" => [],
            ];        
            if( $request['payload']['apiConfiguration']['delivery_ranges']['week'] ){
                if( !!$request['payload']['apiConfiguration']['delivery_ranges']['week']['from'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['week']['to'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['week']['capacity'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['week']['cutoff']
                ){
                    $bodyRequest['delivery_ranges']['week'] = [[
                        'from' => intval($request['payload']['apiConfiguration']['delivery_ranges']['week']['from']),
                        'to' => intval($request['payload']['apiConfiguration']['delivery_ranges']['week']['to']),
                        'capacity' => intval($request['payload']['apiConfiguration']['delivery_ranges']['week']['capacity']),
                        'cutoff' => intval($request['payload']['apiConfiguration']['delivery_ranges']['week']['cutoff'])
                    ]];
                }
            }
            if( $request['payload']['apiConfiguration']['delivery_ranges']['saturday'] ){
                if( !!$request['payload']['apiConfiguration']['delivery_ranges']['saturday']['from'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['saturday']['to'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['saturday']['capacity'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['saturday']['cutoff']
                ){
                    $bodyRequest['delivery_ranges']['saturday'] = [[
                        'from' => intval($request['payload']['apiConfiguration']['delivery_ranges']['saturday']['from']),
                        'to' => intval($request['payload']['apiConfiguration']['delivery_ranges']['saturday']['to']),
                        'capacity' => intval($request['payload']['apiConfiguration']['delivery_ranges']['saturday']['capacity']),
                        'cutoff' => intval($request['payload']['apiConfiguration']['delivery_ranges']['saturday']['cutoff'])
                    ]];
                }
            }
            if( $request['payload']['apiConfiguration']['delivery_ranges']['sunday'] ){
                if( !!$request['payload']['apiConfiguration']['delivery_ranges']['sunday']['from'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['sunday']['to'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['sunday']['capacity'] &&
                    !!$request['payload']['apiConfiguration']['delivery_ranges']['sunday']['cutoff']
                ){
                    $bodyRequest['delivery_ranges']['sunday'] = [[
                        'from' => intval($request['payload']['apiConfiguration']['delivery_ranges']['sunday']['from']),
                        'to' => intval($request['payload']['apiConfiguration']['delivery_ranges']['sunday']['to']),
                        'capacity' => intval($request['payload']['apiConfiguration']['delivery_ranges']['sunday']['capacity']),
                        'cutoff' => intval($request['payload']['apiConfiguration']['delivery_ranges']['sunday']['cutoff'])
                    ]];
                }
            }
            $body = json_encode($bodyRequest);
            $responseConfig1 = $connector->makeRequest('PUT', "shipping/flex/sites/$SITE_ID/users/$USER_ID/services/$SERVICE_ID/configuration/delivery/custom/v3", $body);
            if( env('APP_ENV') == 'local' ){ \Log::info('test1:'); \Log::info($responseConfig1); }
            // Ampliar área de cobertura Flex
            $bodyRequest = [
                "zones" => [],
            ];
            foreach( $request['payload']['zones'] as $zone ){
                if( $zone['enabled'] === true ){
                    $bodyRequest['zones'][] = [ 'id' => $zone['id'] ];
                }
            }
            $body = json_encode($bodyRequest);
            $responseConfig2 = $connector->makeRequest('PUT', "shipping/flex/sites/$SITE_ID/users/$USER_ID/services/$SERVICE_ID/configuration/coverage/zone/v3", $body);
            if( env('APP_ENV') == 'local' ){ \Log::info('test2:'); \Log::info($responseConfig2); }

            return ['message' => 'Configuración aplicada con éxito'];
        }
    }
}



