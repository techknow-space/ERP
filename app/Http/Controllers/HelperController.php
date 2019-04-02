<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use App\Models\Location;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HelperController extends Controller
{
    /**
     * @return Location
     */
    public static function getCurrentLocation(): Location
    {
        $request = request();

        if($request->session()->has('location_id')){
            try{
                $location = Location::findOrFail(session('location_id'));
            }catch (ModelNotFoundException $e){
                $location = self::getDefaultLocation();
                self::setCurrentLocation($location);
            }
        }
        else{
            $location = self::getDefaultLocation();
            self::setCurrentLocation($location);
        }

        return $location;
    }

    /**
     * @return Location
     */
    public static function getDefaultLocation(): Location
    {
        return Location::where('location_code','S1')->firstOrFail();
    }

    /**
     * @param Location $location
     */
    public static function setCurrentLocation(Location $location): void
    {
        session(['location_id'=>$location->id]);
    }

    /**
     * @param $entity
     * @return string
     */
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
            case 'PurchaseOrderDiff':
                $entity_short_code = 'PI/DIFF';
                break;
            case 'StockTransfer':
                $entity_short_code = 'STO';
                break;
        }

        return $entity_short_code.'/'.$date.'/'.$random;
    }

    /**
     * @return array
     */
    public static function getDeviceListForNavigationMenu(): array
    {
        $devices = [];

        $device_types = DeviceType::orderBy('import_ref')->get();

        foreach($device_types as $device_type){

            $devices[$device_type->id]['label'] = $device_type->type;
            $devices[$device_type->id]['brands'] = [];

            foreach ($device_type->Devices->sortBy('model_name') as $device){

                if(array_key_exists($device->brand->id, $devices[$device_type->id]['brands'])){
                    $devices[$device_type->id]['brands'][$device->brand->id]['devices'][] = $device;
                }
                else{
                    $devices[$device_type->id]['brands'][$device->brand->id]['name'] = $device->brand->name;
                    $devices[$device_type->id]['brands'][$device->brand->id]['devices'][] = $device;
                }
            }

            $devices[$device_type->id]['brands'] = collect($devices[$device_type->id]['brands'])->sortBy(function ($brand, $key) {
                return strtolower($brand['name']);
            })->toArray();
        }

        return $devices;
    }
}
