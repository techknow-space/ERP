<?php
namespace App\Models;

class Brand extends \App\Models\Base\Brand
{
    public function manufacturer()
    {
        return $this->belongsTo('App\Models\Manufacturer','manufacturer_id');
    }

    public function devices()
    {
        return $this->hasMany('App\Models\Device','brand_id');
    }
}
