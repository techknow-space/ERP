<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;

class PartMvmntTo extends Base\PartMvmntTo
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function sublocation()
    {
        return $this->morphTo();
    }




}
