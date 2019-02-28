<?php
namespace App\Models;

use App\Http\Controllers\HelperController;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrder extends Base\PurchaseOrder
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();

            $model->number = HelperController::createSerialNumber('PurchaseOrder');
        });
    }

    /**
     * Scope a query to only include PurchaseOrders of a given Supplier.
     *
     * @param  Builder $query
     * @param  Supplier $supplier
     * @return Builder
     */
    public function scopeOfSupplier($query, $supplier)
    {
        return $query->where('supplier_id',$supplier->id);
    }

    /**
     * Scope a query to only include Purchase Orders having a given status.
     *
     * @param  Builder $query
     * @param  PurchaseOrderStatus $status
     * @return Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('purchaseOrderStatus_id',$status->id);
    }

    /**
     * Scope a query to only include Purchase Orders having past or at the given status.
     *
     * @param  Builder $query
     * @param  PurchaseOrderStatus $status
     * @return Builder
     */
    public function scopeIsOrAfterStatus($query, $status)
    {
        $status_id_list = PurchaseOrderStatus::where('seq_id','>=',$status->seq_id)->pluck('id')->toArray();
        return $query->whereIn('purchaseOrderStatus_id',$status_id_list);
    }

    /**
     * Scope a query to only include Purchase Orders before or at the given status.
     *
     * @param  Builder $query
     * @param  PurchaseOrderStatus $status
     * @return Builder
     */
    public function scopeIsOrBeforeStatus($query, $status)
    {
        $status_id_list = PurchaseOrderStatus::where('seq_id','<=',$status->seq_id)->get()->pluck('id')->toArray();
        return $query->whereIn('purchaseOrderStatus_id',$status_id_list);
    }

    /**
     * Scope a query to only include Purchase Orders having past the given status.
     *
     * @param  Builder $query
     * @param  PurchaseOrderStatus $status
     * @return Builder
     */
    public function scopeAfterStatus($query, $status)
    {
        $status_id_list = PurchaseOrderStatus::where('seq_id','>',$status->seq_id)->pluck('id')->toArray();
        return $query->whereIn('purchaseOrderStatus_id',$status_id_list);
    }

    /**
     * Scope a query to only include Purchase Orders before the given status.
     *
     * @param  Builder $query
     * @param  PurchaseOrderStatus $status
     * @return Builder
     */
    public function scopeBeforeStatus($query, $status)
    {
        $status_id_list = PurchaseOrderStatus::where('seq_id','<',$status->seq_id)->pluck('id')->toArray();
        return $query->whereIn('purchaseOrderStatus_id',$status_id_list);
    }




}
