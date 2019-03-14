<?php


namespace App\Http\Controllers\PurchaseOrder;

use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrderItemsDistribution;
use function foo\func;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PurchaseOrderActionsController extends PurchaseOrderController
{
    public function verify(PurchaseOrder $purchaseOrder): View
    {
        $this->createDistributionRecords($purchaseOrder);

        $diff_dollar = 0;
        foreach($purchaseOrder->PurchaseOrderItems as $item){
            $diff_dollar += ($item->qty_received - $item->qty) * $item->cost;
        }

        return view('order.purchase.verify.index',['purchaseOrder'=>$purchaseOrder,'diff_dollar'=>round($diff_dollar,2)]);
    }

    /**
     * @param $sku
     * @param $purchaseOrderID
     * @return JsonResponse
     */
    public function itemReceived($sku,$purchaseOrderID): JsonResponse
    {
        $response['error'] = false;

        try{
            $part = Part::where('sku',$sku)
                ->firstOrFail();

            $poItem = PurchaseOrderItems::where('part_id',$part->id)
                ->where('purchaseOrder_id',$purchaseOrderID)
                ->firstOrFail();

            $poItem->increment('qty_received');

            $poItem->save();

            $purchaseOrder = PurchaseOrder::findorFail($purchaseOrderID);

            $sku_scanned = $purchaseOrder->PurchaseOrderItems->filter(function($item, $key){
                return $item['qty_received'] > 0;
            })->count();

            //$sku_scanned = $purchaseOrder->PurchaseOrderItems->whereNotIn('qty_received',[0])->count();
            $qty_scanned = $purchaseOrder->PurchaseOrderItems->sum('qty_received');
            $diff_qty = $purchaseOrder->PurchaseOrderItems->sum('qty_received') - $purchaseOrder->PurchaseOrderItems->sum('qty');
            $diff_dollar = 0;
            foreach($purchaseOrder->PurchaseOrderItems as $item){
                $diff_dollar += ($item->qty_received - $item->qty) * $item->cost;
            }

            $response['item']['id'] = $poItem->id;
            $response['item']['qty_received'] = $poItem->qty_received;
            $response['item']['diff'] = $poItem->qty_received - $poItem->qty;
            $response['item']['name'] = $part->devices->brand->name.' '.$part->devices->model_name.' '.$part->part_name;

            if ($response['item']['diff'] == 0) {
                $response['item']['class'] = 'table-success';
            } elseif (0 > $response['item']['diff']) {
                $response['item']['class'] = 'table-danger';
            } elseif (0 < $response['item']['diff']) {
                $response['item']['class'] = 'table-warning';
            }

            $response['summary']['sku_scanned'] = $sku_scanned;
            $response['summary']['qty_scanned'] = $qty_scanned;
            $response['summary']['diff_qty'] = $diff_qty;
            $response['summary']['diff_dollar'] = round($diff_dollar,2);

            $response['distribution'] = $this->updateDistributionRecord($poItem);

        }catch (ModelNotFoundException $e){
            $response['error'] = true;
        }

        return response()->json($response);
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     */
    public function createDistributionRecords(PurchaseOrder $purchaseOrder): void
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

    /**
     * @param PurchaseOrderItems $purchaseOrderItem
     * @return array
     */
    public function updateDistributionRecord(PurchaseOrderItems $purchaseOrderItem): array
    {
        $response['error'] = true;

        foreach($purchaseOrderItem->PurchaseOrderDistributionItems->sortBy(function($item,$key){
            return $item['Location']['seq_id'];
        }) as $distributionItem){
            if(0 == $distributionItem->qty_to_receive){
                continue;
            }
            if($distributionItem->qty_to_receive > $distributionItem->qty_scanned){
                $distributionItem->increment('qty_scanned');
                $response['location_code'] = $distributionItem->Location->location_code;
                $response['scanned'] = $distributionItem->qty_scanned;
                $response['error'] = false;
                break;
            }
        }

        return $response;

    }
}
