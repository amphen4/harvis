<?php

namespace App\DataTranslators;

class Falabella {

    public static function translateProduct( $data )
    {
        $finalArray = array();
        $finalArray['name'] = $data->Name;
        $finalArray['sku'] = $data->SellerSku;
        $finalArray['stock'] = intval($data->BusinessUnits->BusinessUnit->Stock);
        $finalArray['price'] = floatval($data->BusinessUnits->BusinessUnit->Price);
        $finalArray['active'] = $data->BusinessUnits->BusinessUnit->Status == 'active' ? true : false;
        return $finalArray;
    }

}