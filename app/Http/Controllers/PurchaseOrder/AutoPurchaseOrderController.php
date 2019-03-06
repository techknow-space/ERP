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
        $partToOrder = $this->reorderStrategy();
        $supplier = Supplier::where('name','Rewa Technologies')->firstOrFail();
        $po = $this->generatePurchaseOrderbySystem($partToOrder,$supplier);
        return $this->edit($po->id);
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


}
