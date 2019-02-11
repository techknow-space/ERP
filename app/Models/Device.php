<?php
namespace App\Models;

class Device extends Base\Device
{
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand','brand_id');
    }

    public function devicetype()
    {
        return $this->belongsTo('App\Models\DeviceType','deviceType_id');
    }

    public function parts()
    {
        return $this->hasMany('App\Models\Part','device_id')->orderBy('part_name', 'asc');
    }
}
