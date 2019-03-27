<?php

namespace App\Http\Controllers\StockTransfer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\Statistics\SalesAndTargetsController;
use App\Models\Location;
use App\Models\Part;
use App\Models\PartStock;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\StockTransferStatus;
use App\Models\WODevicePart;
use Carbon\Carbon;
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
        if(null == $filter){
            return view('stockTransfer.index');
        }
        else{
            return view('stockTransfer.filteredIndex',['filter'=>$filter]);
        }
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
                'is_editable' => ($stockTransfer->Status->seq_id < 5 ? true : false),
                'status_id' => $stockTransfer->Status->seq_id
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function requestInsert(Request $request): RedirectResponse
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
     * @param Location $fromLocation
     * @param Location $toLocation
     * @param string|null $details
     * @return StockTransfer|null
     */
    public function insert(Location $fromLocation , Location $toLocation, string $details=null): ?StockTransfer
    {
        try{
            $status = StockTransferStatus::where('seq_id',1)->firstOrFail();

            $stockTransfer = new StockTransfer();
            $stockTransfer->description = $details;
            $stockTransfer->fromLocation()->associate($fromLocation);
            $stockTransfer->toLocation()->associate($toLocation);
            $stockTransfer->Status()->associate($status);

            $stockTransfer->save();

        }catch (Exception $e){

            $stockTransfer = null;
            session()->flash('error',[$e->getMessage()]);

        }

        return $stockTransfer;
    }

    /**
     * @param Request $request
     * @param StockTransfer $stockTransfer
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, StockTransfer $stockTransfer): RedirectResponse
    {
        try{

            $details = $request->input('stockTransferDescription');
            $status_id = $request->input('stockTransferStatus');

            $status = StockTransferStatus::findOrFail($status_id);

            if($status->seq_id >= $stockTransfer->Status->seq_id){
                if(3 == $status->seq_id){
                    $this->markShipped($stockTransfer,$status);
                }
                else{
                    $stockTransfer->description = $details;
                    $stockTransfer->Status()->associate($status);

                    $stockTransfer->save();

                    session()->flash('success',['The Transfer Order was Updated Successfully.']);
                }
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
        return self::getFilteredStockTransfers($filter);
    }

    /**
     * @param $filter
     * @return Collection
     */
    public static function getFilteredStockTransfers($filter): Collection
    {
        $stockTransfers = collect([]);
        if(is_string($filter)){

            switch ($filter){
                case 'outbound':
                    $stockTransfers = StockTransfer::
                    where(
                        'fromLocation_id',
                        HelperController::getCurrentLocation()->id
                    )
                        ->where(
                            'stockTransferStatus_id',
                            '!=',
                            StockTransferStatus::where('seq_id',5)->first()->id
                        )
                        ->get();

                    break;

                case 'inbound':
                    $stockTransfers = StockTransfer::
                    where(
                        'toLocation_id',
                        HelperController::getCurrentLocation()->id
                    )
                        ->where(
                            'stockTransferStatus_id',
                            '!=',
                            StockTransferStatus::where('seq_id',5)->first()->id
                        )
                        ->get();
                    break;

                case 'sent':
                    $stockTransfers = StockTransfer::
                    where(
                        'fromLocation_id',
                        HelperController::getCurrentLocation()->id
                    )
                        ->where(
                            'stockTransferStatus_id',
                            StockTransferStatus::where('seq_id',5)->first()->id
                        )
                        ->get();
                    break;

                case 'received':
                    $stockTransfers = StockTransfer::
                    where(
                        'toLocation_id',
                        HelperController::getCurrentLocation()->id
                    )
                        ->where(
                            'stockTransferStatus_id',
                            StockTransferStatus::where('seq_id',5)->first()->id
                        )
                        ->get();
                    break;
            }

        }
        else{
            $stockTransfers = StockTransfer::all();
        }

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
     * @throws Exception
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

            if(!$this->updateItem($stockTransferItem,$qty)){
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
     * @throws Exception
     */
    public function markVerified(StockTransfer $stockTransfer): bool
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

            $status = StockTransferStatus::where('seq_id',5)->firstOrFail();

            $stockTransfer->Status()->associate($status);
            $stockTransfer->save();


            DB::commit();

            session()->flash('success',['All the Stock Levels have been updated']);

        }catch(Exception $e){

            DB::rollBack();
            session()->flash('error',['Sorry!!! There was an error. All the Stock levels are as they were before this Operation.']);
            $error = true;

        }

        return $error;
    }

    /**
     * @param StockTransfer $stockTransfer
     * @return bool
     * @throws Exception
     */
    public function markAllItemsReceived(StockTransfer $stockTransfer): bool
    {
        $error = false;
        try{
            DB::beginTransaction();

            foreach ($stockTransfer->Items as $item){

                $item->qty_received = $item->qty_sent;
                $item->save();

            }

            DB::commit();

            session()->flash('success',['All Items Received Qty Updated']);
        }catch (Exception $exception){
            DB::rollBack();
            session()->flash('error',['Sorry!!! There was an error. The received Quantities are unchanged']);
            $error = true;
        }
        return $error;
    }


    /**
     * @param StockTransfer $stockTransfer
     * @param StockTransferStatus $stockTransferStatus
     * @return bool
     * @throws Exception
     */
    public function markShipped(StockTransfer $stockTransfer, StockTransferStatus $stockTransferStatus): bool
    {
        $error = false;

        if($stockTransfer->Status->id !== $stockTransferStatus->id){
            try{
                DB::beginTransaction();

                foreach ($stockTransfer->Items as $item){

                    $item->qty_sent = $item->qty;
                    $item->save();

                }

                $stockTransfer->Status()->associate($stockTransferStatus);
                $stockTransfer->save();


                DB::commit();

                session()->flash('success',['The Stock Transfer is Marked as Shipped.']);
            }catch (Exception $exception){
                DB::rollBack();
                session()->flash('error',['Sorry!!! There was an error. The Status is unchanged']);
                $error = true;
            }
        }

        return $error;
    }

    /**
     * @param array $partTargets
     * @return array
     */
    public function generateStockTransferList(array $partTargets): array
    {
        $tos = [];
        foreach ($partTargets as $partID=>$partTarget){

            $partStock = PartStock::where('part_id',$partID)->get();
            $stockInHand = $partStock->sum('stock_qty');

            if( (1 >= $stockInHand) || (count($partTarget['locations']) < 2) ) {
                unset($partTargets[$partID]);
                continue;
            }

            $partTargets[$partID]['inHand'] = $stockInHand;

            foreach ($partTarget['locations'] as $locationID=>$data) {
                $partTargets[$partID]['locations'][$locationID]['shareInHand'] = ceil(($data['share'] / 100) * $stockInHand);
                $partTargets[$partID]['locations'][$locationID]['InHand'] = $partStock->where('location_id',$locationID)->first()->stock_qty;
            }
        }

        $locations = Location::all();

        //dd($locations);

        foreach ($partTargets as $partID=>$partTarget){

            foreach ($partTarget['locations'] as $locationID=>$data){

                $otherLocation_id = $locations->whereNotIn('id',$locationID)->first()->id;

                $inhand_current_location = $data['shareInHand'];
                $inhand_other_location = $partTarget['locations'][$otherLocation_id]['shareInHand'];
                $inhand_total = $partTarget['inHand'];

                if( ($inhand_current_location + $inhand_other_location) > $inhand_total ){
                    $diff = ($inhand_current_location + $inhand_other_location) - $inhand_total;

                    if($inhand_current_location > $inhand_other_location){
                        $partTargets[$partID]['locations'][$locationID]['shareInHand'] -= $diff;
                        break;
                    }
                    else{
                        $partTargets[$partID]['locations'][$otherLocation_id]['shareInHand'] -= $diff;
                        break;
                    }
                }
            }
        }

        foreach ($partTargets as $partID=>$partTarget){



            foreach ($partTarget['locations'] as $locationID=>$data){

                $otherLocation_id = $locations->whereNotIn('id',$locationID)->first()->id;

                if($data['shareInHand'] > $data['InHand']){
                    if( $partTargets[$partID]['locations'][$otherLocation_id]['shareInHand'] >= $partTargets[$partID]['locations'][$otherLocation_id]['InHand']){
                        break;
                    }
                    elseif( $partTargets[$partID]['locations'][$otherLocation_id]['shareInHand'] < $partTargets[$partID]['locations'][$otherLocation_id]['InHand']){
                        $diff = $data['shareInHand'] - $data['InHand'];

                        $tos['from-'.$locations->where('id',$locationID)->first()->location_code.':to-'.$locations->where('id',$otherLocation_id)->first()->location_code][] = ['part_id'=>$partID,'qty'=>$diff];
                        break;
                    }
                }
            }
        }

        return [$partTargets,$tos];
    }

    public function generateTransferOrder(): void
    {
        $partsTargets = SalesAndTargetsController::getSalesTargets();

        $partsTargets = $this->generateStockTransferList($partsTargets);

        $transfer_list = $partsTargets[1];

        $this->createAutoTransferOrders($transfer_list);

        //echo "<pre>";
        //var_dump($partsTargets);
        //echo "</pre>";
        //die();
        dd($partsTargets);
    }

    public function createAutoTransferOrders(array $transferList): void
    {
        $location_s1 = Location::where('location_code','S1')->firstOrFail();
        $location_to = Location::where('location_code','TO')->firstOrFail();

        $sto_s1_to_to = $this->insert($location_s1,$location_to,'System Generated');

        $sto_to_to_ts1= $this->insert($location_to,$location_s1,'System Generated');

        foreach ($transferList as $direction=>$parts){

            if('from-S1:to-TO' == $direction){
                foreach ($parts as $item) {
                    $part = Part::find($item['part_id']);
                    $this->addItem($sto_to_to_ts1,$part,$item['qty']);
                }
            }
            else{
                foreach ($parts as $item) {
                    $part = Part::find($item['part_id']);
                    $this->addItem($sto_s1_to_to,$part,$item['qty']);
                }
            }

        }
    }
}
