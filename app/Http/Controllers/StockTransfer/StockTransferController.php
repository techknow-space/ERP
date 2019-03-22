<?php

namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\StockTransferStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Exception;

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
                'statuses'=>StockTransferStatus::where('seq_id','>=',$stockTransfer->Status->seq_id)
                    ->get()
                    ->filter(function ($status,$key){
                        return $status['seq_id'] < 5 ;
                    })
                    ->sortBy('seq_id'),
                'is_editable' => ($stockTransfer->Status->seq_id < 5 ? true : false)
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

    /**
     * @param Request $request
     * @param StockTransfer $stockTransfer
     * @return RedirectResponse
     */
    public function update(Request $request, StockTransfer $stockTransfer): RedirectResponse
    {
        try{

            $details = $request->input('stockTransferDescription');
            $status_id = $request->input('stockTransferStatus');

            $status = StockTransferStatus::findOrFail($status_id);

            if($status->seq_id >= $stockTransfer->Status->seq_id){
                if(2 == $status->seq_id){
                    //$this->boxed($stockTransfer);
                }

                $stockTransfer->description = $details;
                $stockTransfer->Status()->associate($status);

                $stockTransfer->save();

                session()->flash('success',['The Transfer Order was Updated Successfully.']);
            }
            else{
                session()->flash('error',['Sorry!!! Going to a previous status is not permitted.']);
            }

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

    /**
     * @param StockTransfer $stockTransfer
     * @param Part $part
     * @param int $qty
     * @return StockTransferItem
     */
    public function addItem(StockTransfer $stockTransfer, Part $part, int $qty): StockTransferItem
    {
        try{
            $stockTransferItem = StockTransferItem::where('part_id',$part->id)
                ->where('stockTransfer_id',$stockTransfer->id)
                ->firstOrFail();
        }catch (ModelNotFoundException $e){
            $stockTransferItem = new StockTransferItem();
        }

        $stockTransferItem->qty = $qty;
        $stockTransferItem->Part()->associate($part);
        $stockTransferItem->StockTransfer()->associate($stockTransfer);
        $stockTransferItem->save();

        return $stockTransferItem;
    }

    /**
     * @param StockTransferItem $stockTransferItem
     * @return bool
     * @throws \Exception
     */
    public function deleteItem(StockTransferItem $stockTransferItem): bool
    {
        $result = true;
        try{
            $stockTransferItem->delete();
        }catch (Exception $e){
            $result = false;
        }

        return $result;
    }

    /**
     * @param StockTransferItem $stockTransferItem
     * @param int $qty
     * @return bool
     */
    public function updateItem(StockTransferItem $stockTransferItem, int $qty): bool
    {
        $result = true;
        try{

            $stockTransferItem->qty = $qty;
            $stockTransferItem->save();
        }catch (ModelNotFoundException $e){
            $result = false;
        }

        return $result;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function requestAddItem(Request $request): JsonResponse
    {
        $result = true;
        $item = [];
        $message = '';
        try{

            $part = Part::findOrFail($request->input('stItemsTablePartSelect'));
            $stockTransfer = StockTransfer::findOrFail($request->input('stoID'));
            $qty = intval($request->input('stItemsTablePartAddQty'));

            $current_stock = PartStock::where('part_id',$part->id)
                ->where('location_id',$stockTransfer->fromLocation->id)
                ->first();

            if($current_stock->stock_qty >= $qty){

                $is_added = $this->addItem($stockTransfer,$part,$qty);

                if(!$is_added){
                    $result = false;
                    $message = 'Sorry!!! There was an Error.';
                }

            }else{
                $result = false;
                $message = 'You can only send quantities that are inhand';
            }

        }catch (ModelNotFoundException $e){
            $result = false;
        }

        return response()->json([
            'result' => $result,
            'item' => $item,
            'message' => $message
        ]);

    }

    /**
     * @param StockTransferItem $stockTransferItem
     * @return JsonResponse
     * @throws Exception
     */
    public function requestDeleteItem(StockTransferItem $stockTransferItem): JsonResponse
    {
        $result = $this->deleteItem($stockTransferItem);

        return response()->json([
            'result' => $result
        ]);
    }

    /**
     * @param Request $request
     * @param StockTransferItem $stockTransferItem
     * @return JsonResponse
     */
    public function requestUpdateItem(Request $request, StockTransferItem $stockTransferItem): JsonResponse
    {
        $result = true;
        $message = '';

        $part_stock = PartStock::where('part_id',$stockTransferItem->Part->id)
            ->where(
                'location_id',
                $stockTransferItem->StockTransfer->fromLocation->id)
            ->first();

        $qty = intval($request->input('qty'));

        if($qty <= $part_stock->stock_qty){
            $stockTransferItem = $this->updateItem($stockTransferItem,$qty);

            if($qty !== $stockTransferItem->qty){
                $result = false;
                $message = 'Sorry!!! There was an error Updating this Item';
            }
        }
        else{
            $result = false;
            $message = 'Transfer Qty can only be equal to or less than stock in hand.';
        }

        return response()->json([
            'result'=>$result,
            'message' => $message
        ]);
    }

    /**
     * @param StockTransfer $stockTransfer
     * @return bool
     * @throws \Exception
     */
    public function verified(StockTransfer $stockTransfer): bool
    {
        $error = false;
        try{
            DB::beginTransaction();

            foreach ($stockTransfer->Items as $item){

                $partStockFrom = $item->Part->Stocks->where('location_id',$stockTransfer->fromLocation->id)->first();
                $partStockFrom->stock_qty = $partStockFrom->stock_qty - $item->qty;
                $partStockFrom->save();

                $partStockTo = $item->Part->Stocks->where('location_id',$stockTransfer->toLocation->id)->first();
                $partStockTo->stock_qty = $partStockTo->stock_qty + $item->qty;
                $partStockTo->save();

            }

            DB::commit();

        }catch(\Exception $e){

            DB::rollBack();
            session()->flash('error',['Sorry!!! There was an error. All the Stock levels are as they were before this Transfer.']);
            $error = true;

        }

        session()->flash('success',['All the Stock Levels have been updated']);

        return $error;
    }

    public function generateTransferOrder(): void
    {

    }
}
