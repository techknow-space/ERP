<?php
namespace App\Models;

use App\Http\Controllers\HelperController;
use Ramsey\Uuid\Uuid;

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
}
