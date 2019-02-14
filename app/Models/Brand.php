<?php
namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Brand extends Base\Brand implements Searchable
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

    public function manufacturer()
    {
        return $this->belongsTo('App\Models\Manufacturer','manufacturer_id');
    }

    public function devices()
    {
        return $this->hasMany('App\Models\Device','brand_id');
    }
}
