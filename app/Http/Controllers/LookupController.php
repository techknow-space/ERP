<?php

namespace App\Http\Controllers;

use App\Models\Part as Part;
use Illuminate\Http\Request;
use App\Models\Device as Device;

class LookupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $devices = Device::all();
        return view('devices')->with('devices', $devices);
    }

    /**
     * @param $sku
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lookup_sku($sku)
    {

        // $data = [];
        // $part = [];

        $part = Part::where('sku',$sku)->with('devices.brand')->firstOrFail();

        // if($item){

        //     $data['part'] = $item;
        //     $data['price'] = $item->price;
        //     $data['stock'] = $item->stock;

        //     $part['sku'] = $item->sku;
        //     $part['name'] = $item->part_name;
        //     $part['price'] = $data['price']->selling_price_b2c;

        //     foreach ($data['stock'] as $stock){
        //         $stock_details['qty'] = $stock->stock_qty;
        //         $stock_details['location'] = $stock->location->location;
        //         $stock_details['sole_all_time'] = $stock->sold_all_time;
        //         $part['stock'][] = $stock_details;
        //     }

        // }

        // return view('part')->with('part',$part);
        return view('part')->with('part', $part);
    }
}
