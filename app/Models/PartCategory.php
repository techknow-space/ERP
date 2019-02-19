<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PartCategory extends Base\PartCategory
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
