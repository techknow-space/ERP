<?php

namespace App\Http\Controllers;

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
}
