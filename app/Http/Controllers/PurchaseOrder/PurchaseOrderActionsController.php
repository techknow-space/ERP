<?php


namespace App\Http\Controllers\PurchaseOrder;

use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDiff;
use App\Models\PurchaseOrderDiffItems;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrderItemsDistribution;
use App\Models\PurchaseOrderStatus;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\StockTransferStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function finalizeShipment(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if(!$purchaseOrder->PurchaseOrderDiffs()->exists()){
            $diff_dollar = 0;
            foreach($purchaseOrder->PurchaseOrderItems as $item){
                $diff_dollar += ($item->qty_received - $item->qty) * $item->cost;
            }

            $purchaseOrder_diff = new PurchaseOrderDiff();
            $purchaseOrder_diff->PurchaseOrder()->associate($purchaseOrder);
            $purchaseOrder_diff->qty_diff = $purchaseOrder->PurchaseOrderItems->sum('qty_received') - $purchaseOrder->PurchaseOrderItems->sum('qty');
            $purchaseOrder_diff->value_diff_CAD = $diff_dollar;

            $purchaseOrder_diff->save();

            foreach ($purchaseOrder->PurchaseOrderItems as $item){
                if($item->qty !== $item->qty_received){
                    $purchaseOrder_diff_item = new PurchaseOrderDiffItems();
                    $purchaseOrder_diff_item->PurchaseOrderDiff()->associate($purchaseOrder_diff);
                    $purchaseOrder_diff_item->Part()->associate($item->Part);
                    $purchaseOrder_diff_item->qty_paid_for = $item->qty;
                    $purchaseOrder_diff_item->cost = $item->cost;
                    $purchaseOrder_diff_item->qty_received = $item->qty_received;

                    $purchaseOrder_diff_item->save();
                }
            }


            $status = PurchaseOrderStatus::where('seq_id',10)->first();
            $purchaseOrder->PurchaseOrderStatus()->associate($status);
            $purchaseOrder->save();

        }
        else{
            $purchaseOrder_diff = $purchaseOrder->PurchaseOrderDiffs;
        }

        return redirect('/order/purchase/shortexcess/'.$purchaseOrder_diff->id);
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function markVerified(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $status = PurchaseOrderStatus::where('seq_id',10)->first();
        $purchaseOrder->PurchaseOrderStatus()->associate($status);
        $purchaseOrder->save();

        session()->flash('success',['This Order is Marked as Verified.']);

        return redirect('/order/purchase/shortexcess/'.$purchaseOrder->PurchaseOrderDiffs->id);
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function distributeShipment(PurchaseOrder $purchaseOrder): View
    {
        return view('order.purchase.distribute.index',['purchaseOrder'=>$purchaseOrder]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function editDistributionRecord(Request $request): JsonResponse
    {
        $response['error'] = false;

        $purchaseOrder_id = $request->input('purchaseOrder_id');
        $purchaseOrderItem_id = $request->input('purchaseOrderItem_id');

        $location_values = $request->input('location_values');

        foreach ($location_values as $location_code => $qty_scanned){

            try{
                $location = Location::where('location_code',$location_code)->firstOrFail();
                $purchaseOrderDistributionItem = PurchaseOrderItemsDistribution::
                where('purchaseOrder_id',$purchaseOrder_id)
                    ->where('purchaseOrder_item_id',$purchaseOrderItem_id)
                    ->where('location_id',$location->id)
                    ->firstOrFail();

                $purchaseOrderDistributionItem->qty_scanned = $qty_scanned;
                $purchaseOrderDistributionItem->save();
            }catch (ModelNotFoundException $e){
                $response['error'] = true;
            }

        }

        return response()->json($response);
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return RedirectResponse
     * @throws Exception
     */
    public function generateStockTransfer(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        try{

            DB::beginTransaction();

            $stockTransferStatus = StockTransferStatus::where('seq_id',1)->firstOrFail();

            $status = PurchaseOrderStatus::where('seq_id',11)->firstOrFail();
            $orderLocation = $purchaseOrder->Location;
            $itemLocation = Location::where('seq_id',2)->firstOrFail();
            $stockTransfer = new StockTransfer();
            $stockTransfer->fromLocation()->associate($orderLocation);
            $stockTransfer->toLocation()->associate($itemLocation);
            $stockTransfer->is_po = true;
            $stockTransfer->description = 'From PO: '.$purchaseOrder->number;
            $stockTransfer->Status()->associate($stockTransferStatus);

            $stockTransfer->save();

            foreach ($purchaseOrder->PurchaseOrderDistributionItems as $item){

                if($item->Location->id !== $orderLocation->id ){

                    if(0 < $item->qty_to_receive){
                        $stockTransferItem = new StockTransferItem();
                        $stockTransferItem->StockTransfer()->associate($stockTransfer);
                        $stockTransferItem->Part()->associate($item->Part);
                        $stockTransferItem->qty = $item->qty_to_receive;
                        $stockTransferItem->save();
                    }

                }
            }

            $purchaseOrder->PurchaseOrderStatus()->associate($status);
            $purchaseOrder->save();

            DB::commit();
            $message = 'The Transfer List is Successfully Created. <a href="/stocktransfer/edit/'.$stockTransfer->id.'">Click Here</a> to See it. <br>Stock in hand for the current Location will be updated when the Mark as Complete is Clicked.';
            session()->flash(
                'success',
                [$message]);

        }catch (Exception $exception){

            session()->flash('error',['Sorry!!! there was an error.', $exception->getMessage()]);
            DB::rollBack();

        }

        return redirect('/order/purchase');

    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return RedirectResponse
     * @throws Exception
     */
    public function markCompleted(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        try{
            DB::beginTransaction();

            $status = PurchaseOrderStatus::where('seq_id',12)->firstOrFail();
            $orderLocation = $purchaseOrder->Location;

            foreach ($purchaseOrder->PurchaseOrderDistributionItems as $item){

                if($item->Location->id == $orderLocation->id ){
                    $partStock = PartStock::where('part_id',$item->part_id)->where('location_id',$orderLocation->id)->firstOrFail();
                    $partStock->stock_qty += $item->qty_to_receive;
                    $partStock->save();
                }

            }

            $purchaseOrder->PurchaseOrderStatus()->associate($status);
            $purchaseOrder->save();

            session()->flash('success',['The PO has be completed now and Stock have been updated. Items going to other location is in the StockTransfer List.']);
            DB::commit();
        }catch (Exception $exception){

            session()->flash('error',['<b>Sorry!!! there was an error.</b>', $exception->getMessage()]);
            DB::rollBack();
        }

        return redirect('/order/purchase');
    }
}
