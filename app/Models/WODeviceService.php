<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class WODeviceService extends \App\Models\Base\WODeviceService
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
