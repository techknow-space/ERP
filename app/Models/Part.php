<?php
namespace App\Models;

class Part extends \App\Models\Base\Part
{
    public function devices(){
        return $this->belongsTo('App\Models\Device', 'device_id');
    }
}
