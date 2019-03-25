<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\WODevicePart;
use App\Models\WorkOrder;
use App\Models\WorkOrderDevice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartOperationController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function reduceStock(Request $request, $id): JsonResponse
    {
        $wo_number = $request->input('wo');

        try{
            DB::beginTransaction();

            $part_stock = $this->getPartStockByID($id);

            $part_stock->decrement('stock_qty');
            $part_stock->increment('sold_all_time');
            $part_stock->save();
            $wo_device_part = $this->setWorkOrderDetails($wo_number,$id);
            $result = true;

            DB::commit();
        }catch (ModelNotFoundException $e){
            DB::rollBack();
            $result = false;
        }

        return response()->json($result);

    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function increaseStock(Request $request, $id): JsonResponse
    {
        $wo_number = $request->input('wo');
        try{
            DB::beginTransaction();
            $part_stock = $this->getPartStockByID($id);

            $part_stock->increment('stock_qty');
            $part_stock->decrement('sold_all_time');
            $part_stock->save();

            $wo_device_part = $this->setWorkOrderDetails($wo_number,$id);
            $wo_device_part->delete();

            $result = true;
            DB::commit();
        }catch (ModelNotFoundException $e){
            DB::rollBack();
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
     * @return PartStock|null
     */
    public function getPartStock(Part $part, Location $location): ?PartStock
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
     * @return PartStock|null
     */
    public function getPartStockByID($part_id): ?PartStock
    {
        $part = $this->getPartwithID($part_id);
        $current_location = HelperController::getCurrentLocation();

        return $this->getPartStock($part,$current_location);
    }

    /**
     * @param $number
     * @return WorkOrder
     */
    public function getOrCreateWOByNumber($number): WorkOrder
    {
        try{
            $wo = WorkOrder::where('number',$number)->firstOrFail();
        }catch (ModelNotFoundException $e){
            $wo = new WorkOrder();
            $wo->number = $number;
            $wo->Location()->associate(HelperController::getCurrentLocation());
            $wo->save();
        }

        return $wo;
    }

    /**
     * @param WorkOrder $wo
     * @param Device $device
     * @return WorkOrderDevice
     */
    public function getOrCreateWODeviceByDevice(WorkOrder $wo, Device $device): WorkOrderDevice
    {
        try{
            $wo_device = WorkOrderDevice::
            where('work_order_id',$wo->id)
                ->where('device_id',$device->id)
                ->firstOrFail();
        }catch(ModelNotFoundException $e){
            $wo_device = new WorkOrderDevice();
            $wo_device->Device()->associate($device);
            $wo_device->WorkOrder()->associate($wo);
            $wo_device->save();
        }

        return $wo_device;
    }

    /**
     * @param WorkOrderDevice $workOrderDevice
     * @param Part $part
     * @return WODevicePart
     */
    public function getOrCreateWODevicePart(WorkOrderDevice $workOrderDevice, Part $part): WODevicePart
    {
        $wo_device_part = new WODevicePart();
        $wo_device_part->WorkOrderDevice()->associate($workOrderDevice);
        $wo_device_part->Part()->associate($part);
        $wo_device_part->save();

        return $wo_device_part;
    }

    /**
     * @param $number
     * @param $part_id
     * @return WODevicePart
     */
    public function setWorkOrderDetails($number,$part_id): WODevicePart
    {
        $part = Part::find($part_id);

        $wo = $this->getOrCreateWOByNumber($number);

        $wo_device = $this->getOrCreateWODeviceByDevice($wo,$part->devices);

        $wo_device_part = $this->getOrCreateWODevicePart($wo_device, $part);

        return $wo_device_part;
    }
}
