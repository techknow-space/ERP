<?php


namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Models\WODevicePart;
use Carbon\Carbon;

class SalesAndTargetsController extends Controller
{
    public static function getSalesTargets(): array
    {
        $for_months = 3;
        $date = new Carbon('first day of March 2019');

        $all_parts = WODevicePart::where("created_at", ">", $date->subMonths(12))->get()->unique('part_id')->keyBy('part_id');
        $parts = [];

        foreach($all_parts as $item){
            $parts[] = $item->Part;
        }

        $summary = [];

        foreach ($parts as $part){

            $summary[$part->id]['total'] = 0;
            $summary[$part->id]['target'] = 0;
            $summary[$part->id]['locations'] = [];


            foreach ($part->WODeviceParts as $WODP) {
                $location_code = $WODP->WorkOrderDevice->WorkOrder->Location->id;

                if(array_key_exists($location_code,$summary[$part->id]['locations'])){
                    $summary[$part->id]['locations'][$location_code]['sales']++;
                }
                else{
                    $summary[$part->id]['locations'][$location_code]['sales'] = 1;
                }
                $summary[$part->id]['total']++;
            }

            foreach ($summary[$part->id]['locations'] as $location_code=>$data){
                $summary[$part->id]['locations'][$location_code]['average'] = ($data['sales'] / 12);
                $summary[$part->id]['locations'][$location_code]['target'] = ($summary[$part->id]['locations'][$location_code]['average'] * $for_months);
                $summary[$part->id]['target'] += $summary[$part->id]['locations'][$location_code]['target'];

            }

            foreach ($summary[$part->id]['locations'] as $location_code=>$data){
                $summary[$part->id]['locations'][$location_code]['share'] = (($data['target'] * 100) / $summary[$part->id]['target']);
            }

        }

        unset($parts);
        unset($all_parts);

        return $summary;
    }
}
