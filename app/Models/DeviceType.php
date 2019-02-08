<?php
namespace App\Models;

class DeviceType extends Base\DeviceType
{
    public function devices()
    {
        return $this->hasMany('App\Models\Device','deviceType_id');
    }
}
