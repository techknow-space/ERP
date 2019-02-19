<?php
namespace App\Models;

use Ramsey\Uuid\Uuid;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Device extends Base\Device implements Searchable
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
