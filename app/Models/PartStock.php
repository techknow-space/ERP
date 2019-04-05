<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PartStock extends Base\PartStock
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
    public function part()
    {
        return $this->belongsTo('App\Models\Part','part_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id');
    }
}
