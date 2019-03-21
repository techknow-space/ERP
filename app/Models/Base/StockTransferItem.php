<?php


namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_transfer_items';

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts=[
        'id'=>'string',
        'stockTransfer_id'=>'string',
        'part_id'=>'string',
        'qty' => 'integer',
        'qty_sent' => 'integer',
        'qty_received' => 'integer',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function StockTransfer()
    {
        return $this->belongsTo('\App\Models\StockTransfer','stockTransfer_id','id');
    }

    public function Part()
    {
        return $this->belongsTo('\App\Models\Part','part_id','id');
    }
}
