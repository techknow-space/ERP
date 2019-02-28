<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class WODevicePart extends \App\Models\Base\WODevicePart
{
    protected $table = 'wodevice_parts';

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
