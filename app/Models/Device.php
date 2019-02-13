<?php
namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Device extends Base\Device implements Searchable
{
    public function getSearchResult(): SearchResult
    {
        // $url = route('categories.show', $this->id);

        // return new SearchResult(
        //     $this,
        //     $this->name,
        //     $url
        //  );
    }

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
