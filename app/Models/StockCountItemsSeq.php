<?php
namespace App\Models;
use Webpatser\Uuid\Uuid as Uuid;
class StockCountItemsSeq extends Base\StockCountItemsSeq
{

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = (string)self::generateUuid();
        });
    }
    public static function generateUuid()
    {
        return Uuid::generate(4);
    }
}
