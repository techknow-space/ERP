<?php
namespace App\Models;

class Part extends \App\Models\Base\Part
{
    public function devices()
    {
        return $this->belongsTo('App\Models\Device', 'device_id');
    }

    public function price()
    {
        return $this->hasOne('App\Models\PartPrice','part_id');
    }

    public function stock()
    {
        return $this->hasMany('App\Models\PartStock','part_id');
    }
}
