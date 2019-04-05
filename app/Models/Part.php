<?php
namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Laravel\Scout\Searchable as ScoutSearchable;
use Illuminate\Support\Facades\DB;

class Part extends Base\Part implements Searchable
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->id = Uuid::uuid4()->toString();
        });
    }
    use ScoutSearchable;
    protected $with = array('devices', 'price', 'stock', 'devices.brand');

    public function toSearchableArray()
    {
        $array = $this->toArray();

        return $array;
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
        return $this->hasMany('App\Models\PartStock','part_id')->orderBy('location_id', 'asc');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\PartCategory','partCategory_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\PartStatus','partStatus_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\PartType','partType_id');
    }

    public function colour()
    {
        return $this->belongsTo('App\Models\PartColour','partColour_id');
    }

    public function getTotalstockAttribute()
    {
        return DB::table('part_stocks')
            ->where('part_id',$this->id)
            ->sum('stock_qty');
    }

    public function getSoldpastyearAttribute()
    {
        $from = date('Y-m-d', strtotime('-1 year'));;
        $to = date('Y-m-d');

        return DB::table('wodevice_parts')
            ->where('part_id',$this->id)
            ->whereBetween('created_at',[$from,$to])
            ->count();
    }

    public function getSoldcurrentqtrAttribute()
    {
        $now = Carbon::now();
        $firstOfQuarter = $now->copy()->firstOfQuarter();

        $to = $now->format('Y-m-d');
        $from = $firstOfQuarter->format('Y-m-d');

        return DB::table('wodevice_parts')
            ->where('part_id',$this->id)
            ->whereBetween('created_at',[$from,$to])
            ->count();
    }

    public function getSoldpast3monthsAttribute()
    {
        $from = date('Y-m-d', strtotime('-3 month'));;
        $to = date('Y-m-d');

        return DB::table('wodevice_parts')
            ->where('part_id',$this->id)
            ->whereBetween('created_at',[$from,$to])
            ->count();
    }

    public function getSoldalltimespreadsheetAttribute()
    {
        return DB::table('part_stocks')
            ->where('part_id',$this->id)
            ->sum('sold_all_time');
    }

    public function getLastreceivedAttribute()
    {
        return '';
    }

}
