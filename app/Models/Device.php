<?php
namespace App\Models;

class Device extends \App\Models\Base\Device
{
    public function brand()
    {
        return $this->belongsTo('App\Brand','brand_id');
    }
}
