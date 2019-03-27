<?php


namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Part;
use App\Models\WODevicePart;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Exception;

class SalesAndTargetsController extends Controller
{
    public static function getSalesTargets(): array
    {
        $for_months = 3;
        $date = new Carbon('first day of March 2019');

        $all_parts = WODevicePart::where("created_at", ">", $date->subMonths(3))->get()->unique('part_id')->keyBy('part_id');
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
                $summary[$part->id]['locations'][$location_code]['average'] = ($data['sales'] / 3);
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

    /**
     * @param Part $part
     * @param Location $location
     * @return int
     */
    public static function totalSalesPast3MonthsForLocations(Part $part, Location $location = null): int
    {
        $total_sales = 0;

        //TODO: Change it to Carbon Now when Sales History is Upto Date
        $date = new Carbon('first day of March 2019');
        $date = $date->subMonths(3);

        foreach ($part->WODeviceParts as $WODP){

            if($WODP->created_at > $date){

                if(null !== $location){
                    if($location->id == $WODP->WorkOrderDevice->WorkOrder->Location->id){
                        $total_sales++;
                    }
                }
                else{
                    $total_sales++;
                }

            }
        }

        return $total_sales;
    }

    /**
     * @return Collection
     */
    public static function getPartsSoldPast12Months(): Collection
    {
        //TODO: Change it to Carbon Now when Sales History is Upto Date
        $date = new Carbon('first day of March 2019');

        $date = $date->subMonths(12);
        $parts = Collect([]);

        $WODeviceParts = WODevicePart::where("created_at", ">", $date)->get()->unique('part_id');

        foreach ($WODeviceParts as $WODevicePart){
            $parts->push($WODevicePart->Part);
        }

        unset($WODeviceParts);

        return $parts;
    }

    /**
     * @param Part $part
     * @param Location $location
     * @return float
     */
    public static function getSalesShare3MonthsForLocations(Part $part, Location $location): float
    {
        $total_sales = self::totalSalesPast3MonthsforLocations($part);
        $total_sales_current_location = self::totalSalesPast3MonthsforLocations($part,$location);

        try{
            $share = round( ($total_sales_current_location / $total_sales) * 100 , 2 );
        }catch (Exception $exception){
            $share = 0.00;
        }

        return $share;
    }
}
