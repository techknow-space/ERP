<?php
namespace App\Models;

class Location extends \App\Models\Base\Location
{
    public function part_stock()
    {
        return $this->hasMany('App\Models\PartStock','location_id');
    }
}
