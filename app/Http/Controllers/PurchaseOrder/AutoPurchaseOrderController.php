<?php
namespace App\Http\Controllers\PurchaseOrder;

use App\Models\PurchaseOrderItems;
use App\Models\PurchaseOrderStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\Supplier;
use App\Models\PurchaseOrder;

class AutoPurchaseOrderController extends PurchaseOrderController
{
    /**
     * @return Collection
     */
    public function getParttoOrderwithZeroStock()
    {
        $part_stock = PartStock::where('stock_qty',0)->get()->unique('part_id');

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

    public function insertItemsToPurchaseOrder(Collection $parts, PurchaseOrder $purchase_order, $qty)
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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


}
