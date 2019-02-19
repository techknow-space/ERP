<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Brand extends Base\Brand implements Searchable
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
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
