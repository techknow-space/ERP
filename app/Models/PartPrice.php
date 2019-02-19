<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PartPrice extends Base\PartPrice
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
        return $this->belongsTo('App\Models\Part', 'part_id');
    }
}
