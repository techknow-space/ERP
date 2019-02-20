<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PurchaseOrderStatus extends Base\PurchaseOrderStatus
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
