<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class DeviceType extends Base\DeviceType
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
    public function devices()
    {
        return $this->hasMany('App\Models\Device','deviceType_id');
    }
}
