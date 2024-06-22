<?php

namespace App\DataTranslators;

class WalmartCL {

    public static function translateProduct( $data )
    {
        $finalArray = array();
        $finalArray['name'] = $data->productName;
        $finalArray['sku'] = $data->sku;
        $finalArray['stock'] = 0;
        $finalArray['price'] = $data->price->amount;
        $finalArray['active'] = strtolower($data->publishedStatus) == 'published' ? true : false;
        return $finalArray;
    }

}