<?php

namespace App\Http\Controllers;

use App\Models\Location as Location;
use App\Models\StockCount;
use App\Models\Part as Part;
use App\Models\StockCountItems;
use App\Models\StockCountItemsSeq;
use App\Models\StockCountStatus;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Null_;

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
     * @param null|String $part_id
     * @return \Illuminate\Http\Response
     */
    public function create($part_id = Null)
    {
        $location = Location::where('location_code','S1')->firstOrFail();
        $status_started = StockCountStatus::where('status','Started')->firstOrFail();
        $status_paused = StockCountStatus::where('status','Paused')->firstOrFail();

        $open_stock_count = StockCount::
            where('stockCountStatus_id',$status_started->id)
            ->OrWhere('stockCountStatus_id',$status_paused->id)
            ->where('location_id',$location->id)
            ->get();

        if($open_stock_count->count() == 0){

            $stock_count = new StockCount();
            $stock_count->number = $this->create_sc_number();
            $stock_count->Location()->associate($location);
            $stock_count->StockCountStatus()->associate($status_started);
            $stock_count->save();


        }
        else{
            $stock_count = $open_stock_count->first();
        }

        if(NULL !== $part_id){
            try{
                $part = Part::find($part_id);

                $sc = $stock_count;

                $sc_item_seq = new StockCountItemsSeq();
                $sc_item_seq->Part()->associate($part);
                $sc_item_seq->StockCount()->associate($sc);
                $sc_item_seq->qty = 1;
                $sc_item_seq->save();

            }catch (ModelNotFoundException $exception){
                $result['error'] = true;
            }

            /* Add to Agggregate TAble now */
            try{
                $part_in_aggregate = StockCountItems::where(
                    [
                        ['part_id',$part_id],
                        ['stockCount_id',$sc->id]
                    ]

                )->firstOrFail();
                $part_in_aggregate->qty++;
                $part_in_aggregate->save();

            } catch (ModelNotFoundException $exception){
                $part_in_aggregate = new StockCountItems();
                $part_in_aggregate->Part()->associate($part);
                $part_in_aggregate->qty = 1;
                $part_in_aggregate->StockCount()->associate($sc);

                $part_in_aggregate->save();
            }
        }

        return $this->count($stock_count);
    }

    /**
     * Count the specified SC in storage.
     *
     * @param  \App\Models\StockCount  $stockCount
     * @return \Illuminate\Http\Response
     */
    public function count(StockCount $stockCount)
    {

        if('Ended' == $stockCount->StockCountStatus->status){
            return $this->aggregate($stockCount->id);
        }

        $allowed_operations = [
            'restart'=>true,
            'pause'=>true,
            'end'=>true
        ];

        switch ($stockCount->StockCountStatus->status) {
            case 'Started':
                $allowed_operations['restart'] = false;
                break;
            case 'Ended':
                $allowed_operations = [
                    'restart'=>false,
                    'pause'=>false,
                    'end'=>false
                ];
                break;
            case 'Paused':
                $allowed_operations['pause'] = false;
                break;
        }

        return view('count_stock_count',compact('stockCount','allowed_operations'));
    }

    public function statusupdate($type,$id)
    {

        if('pause' == $type){
            $stock_count = StockCount::find($id);
            if($stock_count){
                $status = StockCountStatus::where('status','Paused')->firstORFail();
                if($status){
                    $stock_count->StockCountStatus()->associate($status);
                    $stock_count->save();
                }
            }
        }

        elseif ('restart' == $type){
            $stock_count = StockCount::find($id);
            if($stock_count){
                $status = StockCountStatus::where('status','Started')->firstORFail();
                if($status){
                    $stock_count->StockCountStatus()->associate($status);
                    $stock_count->save();
                }
            }
        }

        elseif ('end' == $type) {
            $stock_count = StockCount::find($id);
            if($stock_count){
                $status = StockCountStatus::where('status','Ended')->firstORFail();
                if($status){
                    $stock_count->StockCountStatus()->associate($status);
                    $stock_count->ended_at = Carbon::now()->format('Y-m-d H:i:s');
                    $stock_count->save();

                    return $this->aggregate($id);
                }
            }
        }

        return $this->count($stock_count);


    }

    public function details($id)
    {
        $stock_count = StockCount::with('StockCountItems.Part.devices')->find($id);
        return $this->count($stock_count);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function additem(Request $request)
    {

        try{
            $part = Part::where('sku',$request->get('sku'))->firstOrFail();

            $sc = StockCount::find($request->get('scid'));

            $sc_item_seq = new StockCountItemsSeq();
            $sc_item_seq->Part()->associate($part);
            $sc_item_seq->StockCount()->associate($sc);
            $sc_item_seq->qty = 1;
            $sc_item_seq->save();

            $result = ['error'=> false];

        }catch (ModelNotFoundException $exception){
            $result['error'] = true;
        }

        /* Add to Agggregate TAble now */
        try{
            $part_in_aggregate = StockCountItems::where(
                [
                    ['part_id',$part->id],
                    ['stockCount_id',$request->get('scid')]
                ]

            )->firstOrFail();
            $part_in_aggregate->qty++;
            $part_in_aggregate->save();

        } catch (ModelNotFoundException $exception){
            $part_in_aggregate = new StockCountItems();
            $part_in_aggregate->Part()->associate($part);
            $part_in_aggregate->qty = 1;
            $part_in_aggregate->StockCount()->associate(StockCount::find($request->get('scid')));

            $part_in_aggregate->save();
        }

        return response()->json($result);
    }

    public function aggregate($id)
    {
        $stockCount = StockCount::with('StockCountItems.Part.stock')->find($id);
        return view('aggregate_stock_count')->with('stock_count',$stockCount);
    }

    public function initiate($part_id)
    {
        return $this->create($part_id);
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
