<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_transfers';

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
        'number'=>'string',
        'description'=>'string',
        'stockTransferStatus_id'=>'string',
        'fromLocation_id'=>'string',
        'toLocation_id'=>'string',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function Status()
    {
        return $this->belongsTo('\App\Models\StockTransferStatus','stockTransferStatus_id','id');
    }

    public function fromLocation()
    {
        return $this->belongsTo('App\Models\Location','fromLocation_id','id');
    }

    public function toLocation()
    {
        return $this->belongsTo('App\Models\Location','toLocation_id','id');
    }

    public function Items()
    {
        return $this->hasMany('App\Models\StockTransferItem','stockTransfer_id','id');
    }
}
