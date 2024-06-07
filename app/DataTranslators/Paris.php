<?php

namespace App\DataTranslators;

class Paris {

    public static function translateProduct( $data )
    {
        $finalArray = array();
        $finalArray['name'] = $data->name;
        $finalArray['sku'] = $data->sellerSku;
        $finalArray['stock'] = 0;
        $finalArray['price'] = 0;
        $finalArray['active'] = strtolower($data->status) == 'enabled' ? true : false;
        return $finalArray;
    }

    public static function translateProductVariant( $data )
    {
        $finalArray = array();
        $finalArray['name'] = '';
        $finalArray['sku'] = $data->sku; // skuSeller es el mismo del producto padre?
        $finalArray['stock'] = 0;
        if( property_exists($data, 'prices') && $data->prices && count($data->prices) ){
            $finalArray['price'] = floatval($data->prices[0]->value);
        }else{
            $finalArray['price'] = 0;
        }
        if( property_exists($data, 'status') && $data->status ){
            $finalArray['active'] = strtolower($data->status) == 'enabled' ? true : false;
        }else{
            $finalArray['active'] = false;
        }
        
        foreach( $data->attributes as $attribute )
        {
            if( strtolower($attribute->name) == 'talla' && $attribute->optionName ){
                $finalArray['talla'] = $attribute->optionName;
            }
            if( strtolower($attribute->name) == 'color' && $attribute->optionName ){
                $finalArray['color'] = $attribute->optionName;
            }
        }
        return $finalArray;
    }
}