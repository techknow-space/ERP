<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PurchaseOrderItems extends Base\PurchaseOrderItems
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
