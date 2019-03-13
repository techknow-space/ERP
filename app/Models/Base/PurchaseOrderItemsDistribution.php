<?php


namespace App\Models\Base;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItemsDistribution extends Model
{
    /**
     * Primary key type.
     *
     * @var string
     */
    protected $keyType='uuid';

    /**
     * Primary key is non-autoincrementing.
     *
     * @var bool
     */
    public $incrementing=false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes=['qty_scanned'=>0];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=[
        'id'=>'string',
        'part_id'=>'string',
        'purchaseOrder_id'=>'string',
        'purchaseOrder_item_id' => 'string',
        'location_id'=>'string',
        'qty_on_hand'=>'integer',
        'qty_to_receive'=>'integer',
        'qty_scanned'=>'integer'
    ];

    public function Part()
    {
        return $this->belongsTo('\App\Models\Part','part_id','id');
    }

    public function PurchaseOrder()
    {
        return $this->belongsTo('\App\Models\PurchaseOrder','purchaseOrder_id','id');
    }

    public function PurchaseOrderItem()
    {
        return $this->belongsTo('\App\Models\PurchaseOrderItems','purchaseOrder_item_id','id');
    }
    public function Location()
    {
        return $this->belongsTo('\App\Models\Location','location_id','id');
    }


}
