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
}
