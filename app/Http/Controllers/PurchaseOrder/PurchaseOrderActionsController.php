<?php


namespace App\Http\Controllers\PurchaseOrder;

use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrderItemsDistribution;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class PurchaseOrderActionsController extends PurchaseOrderController
{
    public function verify(PurchaseOrder $purchaseOrder)
    {
        $this->createDistributionRecords($purchaseOrder);
        return view('order.purchase.verify.index',['purchaseOrder'=>$purchaseOrder]);
    }

    /**
     * @param $sku
     * @param $purchaseOrderID
     * @return JsonResponse
     */
    public function itemReceived($sku,$purchaseOrderID)
    {
        $response['error'] = false;

        try{
            $part = Part::where('sku',$sku)
                ->select('id')
                ->firstOrFail();

            $poItem = PurchaseOrderItems::where('part_id',$part->id)
                ->where('purchaseOrder_id',$purchaseOrderID)
                ->firstOrFail();

            $poItem->increment('qty_received');

            $poItem->save();

            $response['item']['id'] = $poItem->id;
            $response['item']['qty_received'] = $poItem->qty_received;
            $response['item']['diff'] = $poItem->qty_received - $poItem->qty;

            if ($response['item']['diff'] == 0) {
                $response['item']['class'] = 'table-success';
            } elseif (0 > $response['item']['diff']) {
                $response['item']['class'] = 'table-danger';
            } elseif (0 < $response['item']['diff']) {
                $response['item']['class'] = 'table-warning';
            }

        }catch (ModelNotFoundException $e){
            $response['error'] = true;
        }

        return response()->json($response);
    }

    public function createDistributionRecords(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder_last_item = $purchaseOrder->PurchaseOrderItems->last();

        if( !
            PurchaseOrderItemsDistribution::
            where('purchaseOrder_id',$purchaseOrder->id)
            ->where('purchaseOrder_item_id',$purchaseOrder_last_item->id)
            ->exists()
        ){
            foreach ($purchaseOrder->PurchaseOrderItems as $item){

                $locations = Location::all();

                foreach ($locations as $location){

                    $distribution_item = new PurchaseOrderItemsDistribution();
                    $distribution_item->Part()->associate($item->Part);
                    $distribution_item->PurchaseOrder()->associate($purchaseOrder);
                    $distribution_item->PurchaseOrderItem()->associate($item);
                    $distribution_item->Location()->associate($location);

                    $stock_location = PartStock::where('part_id',$item->Part->id)->where('location_id',$location->id)->firstOrFail();

                    $distribution_item->qty_on_hand = $stock_location->stock_qty;

                    $qty_to_receive = 0;

                    if('S1' == $location->location_code){
                        $qty_to_receive = round($item->qty / 2,0,PHP_ROUND_HALF_UP);
                    }
                    elseif('TO' == $location->location_code){
                        $qty_to_receive = round($item->qty / 2,0,PHP_ROUND_HALF_DOWN);
                    }

                    $distribution_item->qty_to_receive = $qty_to_receive;
                    $distribution_item->save();
                }

            }
        }
    }
}