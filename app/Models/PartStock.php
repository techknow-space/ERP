<?php
namespace App\Models;

class PartStock extends Base\PartStock
{
    public function part()
    {
        return $this->belongsTo('App\Models\Part','part_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id');
    }
}
