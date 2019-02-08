<?php
namespace App\Models;

class PartPrice extends Base\PartPrice
{
    public function part()
    {
        return $this->belongsTo('App\Models\Part', 'part_id');
    }
}
