<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HelperController extends Controller
{
    public static function getCurrentLocation(): Location
    {
        $request = request();
        $location = false;
        if($request->session()->has('location_id')){
            try{
                $location = Location::findOrFail(session('location_id'));
            }catch (ModelNotFoundException $e){
                $location = Location::where('location_code','S1')->firstOrFail();
                session(['location_id'=>$location->id]);
            }
        }
        else{
            $location = Location::where('location_code','S1')->firstOrFail();
            session(['location_id'=>$location->id]);
        }

        return $location;
    }

    public static function setCurrentLocation($location_id): void
    {
        session(['location_id'=>$location_id]);
    }


    public static function createSerialNumber($entity): string
    {
        $date = date('Md/y');
        $digits = 2;
        $random = rand(pow(10, $digits-1), pow(10, $digits)-1);

        $entity_short_code = '#';

        switch ($entity){
            case 'PurchaseOrder':
                $entity_short_code = 'PO';
                break;
            case 'StockCount':
                $entity_short_code = 'SC';
                break;
            case 'PurchaseOrderPayment':
                $entity_short_code = 'PAY';
                break;
            case 'Supplier':
                $entity_short_code = 'SUPP';
                break;
        }

        return $entity_short_code.'/'.$date.'/'.$random;
    }
}
