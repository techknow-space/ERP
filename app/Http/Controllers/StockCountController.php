<?php

namespace App\Http\Controllers;

use App\Models\Location as Location;
use App\Models\StockCount;
use App\Models\StockCountStatus;
use Illuminate\Http\Request;

class StockCountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock_counts = StockCount::all();
        return view('stockcount')->with('stock_counts',$stock_counts);
    }

    /**
     * Create and Start a New StockCount
     */
    public function create()
    {
        $stock_count = new StockCount();

        $location = Location::where('location_code','S1')->firstOrFail();
        $status = StockCountStatus::where('status','started')->firstOrFail();
        $stock_count->number = $this->create_sc_number();
        $stock_count->Location()->associate($location);
        $stock_count->StockCountStatus()->associate($status);

        $stock_count->save();
        return $this->count($stock_count);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function show(StockCount $stockCount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function edit(StockCount $stockCount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockCount $stockCount)
    {
        //
    }

    /**
     * Count the specified SC in storage.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function count(StockCount $stockCount)
    {
        return view('count_stock_count')->with('stock_count',$stockCount);
    }

    public function details($id)
    {
        $stock_count = StockCount::find($id);
        return $this->count($stock_count);
    }

    /**
     * Add item to the specified SC in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function additem(Request $request, StockCount $stockCount)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockCount $stockCount)
    {
        //
    }

    /**
     * Generate the SC number.
     *
     * @return string
     */
    private function create_sc_number()
    {
        $date = date('mdy');
        $digits = 2;
        $random = rand(pow(10, $digits-1), pow(10, $digits)-1);
        return 'SC-'.$date.'-'.$random;
    }
}
