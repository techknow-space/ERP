<?php

namespace App\Http\Controllers;

use App\Models\Location;

class HelperController extends Controller
{
    public static function getCurrentLocation()
    {
        return Location::findOrFail('acc39f12-68bb-4c9a-8791-d52ab49fcd12');
    }

    public static function createSerialNumber($entity)
    {
        $date = date('Md/y');
        $digits = 2;
        $random = rand(pow(10, $digits-1), pow(10, $digits)-1);

        switch ($entity){
            case 'PurchaseOrder':
                $entity_short_code = 'PO';
                break;
            case 'StockCount':
                $entity_short_code = 'SC';
                break;
        }

        return $entity_short_code.'/'.$date.'/'.$random;
    }
}
