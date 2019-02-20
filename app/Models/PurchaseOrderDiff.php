<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PurchaseOrderDiff extends Base\PurchaseOrderDiff
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
