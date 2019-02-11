<?php
namespace App\Models;
use Webpatser\Uuid\Uuid as Uuid;

class StockCount extends Base\StockCount
{
    public $timestamps = false;

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
