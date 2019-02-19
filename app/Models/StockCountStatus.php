<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class StockCountStatus extends Base\StockCountStatus
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
