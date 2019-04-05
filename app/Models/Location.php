<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class Location extends Base\Location
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
    public function part_stock()
    {
        return $this->hasMany('App\Models\PartStock','location_id');
    }
}
