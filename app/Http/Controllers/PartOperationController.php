<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PartOperationController extends Controller
{
    public function reduceStock($id)
    {
        try{
            $part_stock = $this->getPartStockByID($id);

            $part_stock->decrement('stock_qty');
            $part_stock->increment('sold_all_time');
            $part_stock->save();

            $result = true;
        }catch (ModelNotFoundException $e){
            $result = false;
        }

        return response()->json($result);

    }

    public function increaseStock($id)
    {
        try{
            $part_stock = $this->getPartStockByID($id);

            $part_stock->increment('stock_qty');
            $part_stock->decrement('sold_all_time');
            $part_stock->save();

            $result = true;
        }catch (ModelNotFoundException $e){
            $result = false;
        }
        return response()->json($result);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPartwithID($id)
    {
        return Part::findOrFail($id);
    }

    /**
     * @param Part $part
     * @param Location $location
     * @return mixed
     */
    public function getPartStock(Part $part, Location $location)
    {
        return PartStock::
            where(
                [
                    'part_id' => $part->id,
                    'location_id' => $location->id
                ]
            )
            ->firstOrFail();
    }

    /**
     * @param $part_id
     * @return mixed
     */
    public function getPartStockByID($part_id)
    {
        $part = $this->getPartwithID($part_id);
        $current_location = HelperController::getCurrentLocation();

        return $this->getPartStock($part,$current_location);
    }
}
