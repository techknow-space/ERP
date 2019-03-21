<?php


namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\StockTransfer;
use App\Models\StockTransferStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    /**
     * @param null $filter
     * @return View
     */
    public function index($filter = null): View
    {
        if(null === $filter){
            $stockTransfers = StockTransfer::all();
        }
        else{
            $stockTransfers = $this->filter($filter);
        }

        return view('stockTransfer.index')->with('stockTransfers',$stockTransfers);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $locations = Location::all();

        return view(
            'stockTransfer.create',
            [
                'locations'=>$locations
            ]
        );
    }

    /**
     * @param StockTransfer $stockTransfer
     * @return View
     */
    public function edit(StockTransfer $stockTransfer): View
    {
        return view(
            'stockTransfer.edit',
            [
                'stockTransfer'=>$stockTransfer,
                'statuses'=>StockTransferStatus::all()->sortBy('seq_id')
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function insert(Request $request): RedirectResponse
    {
        try{

            $details = $request->input('stockTransferDescription');
            $from = Location::findorFail($request->input('stockTransferFrom'));
            $to = Location::findOrFail($request->input('stockTransferTo'));
            $status = StockTransferStatus::where('seq_id',1)->firstOrFail();

            $stockTransfer = new StockTransfer();
            $stockTransfer->description = $details;
            $stockTransfer->fromLocation()->associate($from);
            $stockTransfer->toLocation()->associate($to);
            $stockTransfer->Status()->associate($status);

            $stockTransfer->save();

            session()->flash('success',['New Transfer Order Created Successfully.']);

            return redirect('/stocktransfer/edit/'.$stockTransfer->id);

        }catch(ModelNotFoundException $e){

            session()->flash('error',['Sorry! There was an error creating this Transfer Order.']);
            $to = session('_previous')['url'];
            return redirect($to);
        }
    }

    public function update(Request $request, StockTransfer $stockTransfer)
    {
        try{

            $details = $request->input('stockTransferDescription');
            $status_id = $request->input('stockTransferStatus');

            $status = StockTransferStatus::findOrFail($status_id);

            $stockTransfer->description = $details;
            $stockTransfer->Status()->associate($status);

            $stockTransfer->save();

            session()->flash('success',['The Transfer Order was Updated Successfully.']);

            return redirect('/stocktransfer/edit/'.$stockTransfer->id);

        }catch (ModelNotFoundException $e){
            session()->flash('error',['Sorry! There was an error updating this Transfer Order.']);
            $to = session('_previous')['url'];
            return redirect($to);
        }
    }

    /**
     * @param $filter
     * @return Collection
     */
    public function filter($filter): Collection
    {
        $stockTransfers = StockTransfer::all();
        return $stockTransfers;
    }
}
