<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\WODevicePart;
use Carbon\Carbon;

class SalesDataController extends Controller
{
    public function index()
    {
	//$sales = App\Models\
        $count = DB::table('wodevice_parts')->select(DB::raw('count(part_id) AS count, part_id'))->groupBy('part_id')->get();
        //$count = DB::table('wodevice_parts')->select(DB::raw('count(part_id) AS count, part_id'))->groupBy('part_id')->toSql();
        //die($count);
	//$count = WODevicePart::raw('count(part_id) AS count, part_id')->groupBy('part_id')->get();
        return view('reports.sales')->with('sales', $count);
    }

    public function part($id)
    {
        $count = WODevicePart::where('part_id', $id)->orderBy('created_at', 'desc')->get();
        return view('reports.part')->with('sales', $count);
    }

    public function listByMonth()
    {
        $count = DB::table('wodevice_parts')->select(DB::raw('count(part_id) as `count`'), DB::raw('part_id'), DB::raw("DATE_FORMAT(created_at, '%m-%Y') new_date"),  DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->groupby('year','month','part_id','created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.monthly.sales')->with('sales', $count);
    }

    public function partByMonth($id)
    {
        $count = WODevicePart::select(DB::raw('count(part_id) as `data`'), DB::raw("CONCAT_WS('-',MONTH(created_at),YEAR(created_at)) as monthyear"))->where('part_id', $id)
        ->groupby('monthyear')
        ->get();

        return view('reports.monthly.part')->with('sales', $count)->with('part', $id);
    }

    public function reorderStrategy()
    {
        $parts = WODevicePart::select(DB::raw('((count(part_id) / 3) * 3) AS count, part_id'))->where("created_at", ">", Carbon::now()->subMonths(3))->groupBy('part_id')->get();
        $partIds = $parts->pluck('part_id')->toArray();
        $stock = \App\Models\PartStock::whereIn('part_id', $partIds)->get();

        $summed = $stock->groupBy('part_id')->map(function ($row) {
            return $row->sum('stock_qty');
        });

        foreach ($parts as $part) {
            foreach($summed as $summedPartId => $count) {
                if ($summedPartId == $part->part_id) {
                    $part->count -= $count;
                }
            }
        }

        foreach ($parts as $partId => $part) {
            if($part->count < 1) {
                unset($parts[$partId]);
            }
        }

        return $parts;
    }

}
