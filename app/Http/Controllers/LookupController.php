<?php

namespace App\Http\Controllers;


use App\Models\Brand as Brand;
use App\Models\Part as Part;
use Illuminate\Http\Request as Request;
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
    public function lookup_part_sku($sku)
    {
        $part = Part::where('sku',$sku)->with('devices.brand')->firstOrFail();
        return view('part')->with('part', $part);
    }

    public function lookup_part_id($id)
    {
        $part = Part::find($id);
        return view('part')->with('part', $part);

    }

    public function lookup_device_id($id)
    {
        $device = Device::find($id);
        return view('devices')->with('device', $device);
    }

    public function lookup_master()
    {
        $brands = Brand::all();
        return view('lookup',compact('brands'));
    }

    public function findModelWithBrandID($id)
    {
        $models = Device::where('brand_id',$id)->get();
        return response()->json($models);
    }

    public function findPartWithDeviceID($id)
    {
        $parts = Part::where('device_id',$id)->orderBy('part_name', 'asc')->get();
        return response()->json($parts);
    }

    public function showDeviceParts($id)
    {
        $parts = Part::where('device_id',$id)->orderBy('part_name', 'asc')->get();
        return view('parts')->with('parts', $parts);;
    }

    public function getPartDetailsWithID($id)
    {
        $item = Part::find($id);

        $data['part'] = $item;
        $data['price'] = $item->price;
        $data['stock'] = $item->stock;
        $part['sku'] = $item->sku;
        $part['name'] = $item->part_name;
        $part['price'] = number_format((float)$data['price']->selling_price_b2c,2);
        $part['cost'] = number_format((float)$data['price']->last_cost,2);
        foreach ($data['stock'] as $stock){
            $stock_details['qty'] = $stock->stock_qty;
            $stock_details['location'] = $stock->location->location;
            $stock_details['sold_all_time'] = $stock->sold_all_time;
            $part['stock'][] = $stock_details;
        }

        return response()->json($part);
    }

    public function searchBarcode()
    {
        $barcode = request('part-barcode');
        return redirect('itemlookup/sku/'.$barcode);

    }

}
