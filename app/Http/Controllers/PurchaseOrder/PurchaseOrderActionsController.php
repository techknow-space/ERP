<?php


namespace App\Http\Controllers\PurchaseOrder;

use App\Models\Part;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItems;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class PurchaseOrderActionsController extends PurchaseOrderController
{
    public function verify(PurchaseOrder $purchaseOrder)
    {
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
        //
    }
}
