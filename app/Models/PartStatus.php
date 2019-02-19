<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PartStatus extends Base\PartStatus
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
