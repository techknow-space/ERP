<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class Service extends \App\Models\Base\Service
{
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
