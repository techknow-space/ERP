<?php


namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class StockTransferStatus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_transfer_statuses';

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
        'status'=>'string',
        'description'=>'string',
        'seq_id'=>'integer',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];
    public function StockTransfers()
    {
        return $this->hasMany('\App\Models\StockTransfer','stockTransferStatus_id','id');
    }
}
