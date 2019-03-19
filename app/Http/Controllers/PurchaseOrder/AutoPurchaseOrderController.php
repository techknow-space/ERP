<?php
namespace App\Http\Controllers\PurchaseOrder;

use App\Models\PartPrice;
use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrderStatus;
use App\Models\WODevicePart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AutoPurchaseOrderController extends PurchaseOrderController
{
    /**
     * @return Collection
     */
    public function getParttoOrderwithZeroStock()
    {
        $part_stock = PartPrice::where('last_cost',0)->get();

        $parts = [];

        foreach ($part_stock as $ps){
            $parts[] = $ps->part;
        }

        return collect($parts);
    }

    /**
     * @param Collection $parts
     * @param Supplier $supplier
     * @param array $quatity
     * @return PurchaseOrder|void
     */
    public function generatePurchaseOrder(Collection $parts,Supplier $supplier, Array $quatity)
    {
        $status = $purchase_order_status = PurchaseOrderStatus::where('status','Generated')->firstOrFail();

        $purchase_order = $this->createPurchaseOrder($supplier,$status);

        $purchase_order = $this->insertItemsToPurchaseOrder($parts,$purchase_order,$quatity);

        return $purchase_order;
    }


    /**
     * @param Collection $parts
     * @param PurchaseOrder $purchase_order
     * @param array $qty
     * @return PurchaseOrder
     */
    public function insertItemsToPurchaseOrder(Collection $parts, PurchaseOrder $purchase_order, Array $qty)
    {
        $error = false;

        foreach ($parts as $part){
            try{
                $po_item = PurchaseOrderItems::
                where('part_id',$part->id)
                    ->where('purchaseOrder_id',$purchase_order->id)
                    ->firstOrFail();

                if($po_item->is_edited){
                    continue;
                }

                $po_item->qty = $qty[$part->id];
                $po_item->save();

            }catch (ModelNotFoundException $e){
                $po_item = new PurchaseOrderItems();
                $po_item->PurchaseOrder()->associate($purchase_order);
                $po_item->Part()->associate($part);

                $po_item->cost_currency = 'CAD';

                $po_item->qty = $qty[$part->id];
                $po_item->cost = $part->price->last_cost;

                $po_item->save();
            }
        }

        return $purchase_order;


    }

    public function getPurchaseOrderItemsQty(Collection $parts)
    {
        $part_qty = [];

        foreach ($parts as $part){
            $part_qty[$part->id] = 2;
        }

        return $part_qty;

    }

    /**
     * @return Collection
     */
    public function getPartsToOrder()
    {
        return $this->getParttoOrderwithZeroStock();
    }

    /**
     * @return View
     */
    public function initiatePurchaseOrder()
    {
        //TODO: Remove this shit.
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $parts = $this->getPartsToOrder();
        $qty = $this->getPurchaseOrderItemsQty($parts);
        $supplier = Supplier::first();
        $po = $this->generatePurchaseOrder($parts,$supplier,$qty);

        return $this->edit($po->id);
    }

    public function reorderStrategy()
    {
        $parts = WODevicePart::select(DB::raw('((count(part_id) / 3) * 3) AS count, part_id'))->where("created_at", ">", Carbon::now()->subMonths(3))->groupBy('part_id')->get();
        $partIds = $parts->pluck('part_id')->toArray();
        $stock = PartStock::whereIn('part_id', $partIds)->get();

        $summed = $stock->groupBy('part_id')->map(function ($row) {
            return $row->sum('stock_qty');
        });

        foreach ($parts as $part) {
            foreach($summed as $summedPartId => $count) {
                if ($summedPartId == $part->part_id) {
                    $part->count -= $count;
                }
            }
        }

        foreach ($parts as $partId => $part) {
            if($part->count < 1) {
                unset($parts[$partId]);
            }
        }

        foreach ($parts as $part){
            $item = Part::find($part->part_id);

            $item->qty = $part->count;

            $items[] = $item;
        }

        return collect($items);
    }

    public function createPurchaseOrderForNewItems()
    {
        $parts =  $this->getParttoOrderwithZeroStock();
        $qty = $this->getPurchaseOrderItemsQty($parts);

        $supplier = Supplier::where('name','Rewa Technologies')->firstOrFail();

        $po = $this->generatePurchaseOrder($parts,$supplier,$qty);

        return $this->edit($po->id);
    }

    public function createPurchaseOrderForReplishment()
    {
        //$partToOrder = $this->reorderStrategy();
        $partToOrder = $this->partsToReplenish();
        $supplier = Supplier::where('name','Rewa Technologies')->firstOrFail();
        $purchaseOrder = $this->generatePurchaseOrderbySystem($partToOrder,$supplier);

        session()->flash('success',['This Order is System Generated PO.']);
        return redirect('/order/purchase/edit/'.$purchaseOrder->id);
    }

    public function generatePurchaseOrderbySystem(Collection $parts,Supplier $supplier)
    {
        $status = $purchase_order_status = PurchaseOrderStatus::where('status','Generated')->firstOrFail();

        $purchase_order = $this->createPurchaseOrder($supplier,$status);

        $purchase_order = $this->insertItemsToPurchaseOrderbySystem($parts,$purchase_order);

        return $purchase_order;
    }

    public function insertItemsToPurchaseOrderbySystem(Collection $parts, PurchaseOrder $purchase_order)
    {
        $error = false;

        foreach ($parts as $part){
            try{
                $po_item = PurchaseOrderItems::
                where('part_id',$part->id)
                    ->where('purchaseOrder_id',$purchase_order->id)
                    ->firstOrFail();

                if($po_item->is_edited){
                    continue;
                }

                $po_item->qty = $part->qty;
                $po_item->save();

            }catch (ModelNotFoundException $e){
                $po_item = new PurchaseOrderItems();
                $po_item->PurchaseOrder()->associate($purchase_order);
                $po_item->Part()->associate($part);

                $po_item->cost_currency = 'CAD';

                $po_item->qty = $part->qty;
                $po_item->cost = $part->price->last_cost;

                $po_item->save();
            }
        }

        return $purchase_order;


    }

    /**
     * @return Collection
     */
    public function partsToReplenish(): Collection
    {
        $for_months = 3;
        $required_if_no_sale_in_3_months = 2;
        $date = new Carbon('first day of March 2019');
        $percentage_for_buffer = 10;

        $requirement = collect();

        $to_order = collect();

        $parts_all_sales = WODevicePart::select(DB::raw('(count(part_id)/12) AS avg_sold, part_id'))->groupBy('part_id')->get()->keyBy('part_id');

        $parts_last_3_month_sales = WODevicePart::select(DB::raw('(count(part_id)/3) AS avg_sold, part_id'))->where("created_at", ">", $date->subMonths(3))->groupBy('part_id')->get()->keyBy('part_id');

        $not_used_in_last_3_months = $parts_all_sales->diffKeys($parts_last_3_month_sales);

        foreach ($not_used_in_last_3_months->keys() as $part_id){
            $requirement->push(
                [
                    'part_id' => $part_id,
                    'stock_req' => $required_if_no_sale_in_3_months
                ]
            );
        }

        foreach ($parts_last_3_month_sales as $key=>$value){

            $stock_req = $parts_all_sales->get($key)->avg_sold * $for_months;
            $stock_req *= (1 + $percentage_for_buffer / 100);

            $requirement->push(
                [
                    'part_id' => $key,
                    'stock_req' => round( $stock_req )
                    //'stock_req' => round( ($value['avg_sold'] * $for_months) )
                ]
            );
        }

        $parts_in_required = $requirement->keyBy('part_id')->keys();

        $stock = PartStock::whereIn('part_id', $parts_in_required)->get();

        $current_stock = $stock->groupBy('part_id')->map(function ($row) {
            return $row->sum('stock_qty');
        });


        foreach ($requirement as $item){
            $part_id = $item['part_id'];
            $required_stock_level = $item['stock_req'];

            $current_stock_level = $current_stock->get($part_id);

            if($required_stock_level > $current_stock_level){

                $item= Part::find($part_id);

                $partPrice = PartPrice::where('part_id',$part_id)->first();

                if($partPrice->last_cost > 0){
                    $item->qty = $required_stock_level - $current_stock_level;
                    $to_order->push($item);
                }
            }
        }

        return $to_order;
    }


}
