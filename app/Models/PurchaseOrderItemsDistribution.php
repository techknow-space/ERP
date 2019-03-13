<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;

class PurchaseOrderItemsDistribution extends Base\PurchaseOrderItemsDistribution
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
