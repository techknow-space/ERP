<?php


namespace App\Http\Controllers\PurchaseOrder;


use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderPayment;
use App\Models\PurchaseOrderPaymentStatus;
use Illuminate\Http\Request;

class PurchaseOrderPaymentController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $purchaseOrderPayments = PurchaseOrderPayment::all();
        return view('order.purchase.payment.index')->with('purchaseOrderPayments',$purchaseOrderPayments);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::doesntHave('PurchaseOrderPayment')->get();
        return view('order.purchase.payment.create')->with('purchaseOrders',$purchaseOrders );
    }

    public function insert(Request $request)
    {
        $purchaseOrderPayment = new PurchaseOrderPayment();

        $purchaseOrderPayment->transaction_date = strtotime($request->input('poPaymentTransactionDate'));
        $purchaseOrderPayment->transaction_details = $request->input('PoPaymentTransactionDetails');
        $purchaseOrderPayment->exchange_rate_to_CAD = (float)$request->input('poPaymentExchangeRateCAD');
        $purchaseOrderPayment->amount_CAD = (float)$request->input('poPaymentValueCAD');
        $purchaseOrderPayment->amount_USD = (float)$request->input('poPaymentValueUSD');

        $purchaseOrderPayment->save();

        foreach($request->input('poPaymentPOCheckBox') as $purchaseOrder_id){

            $purchaseOrder = PurchaseOrder::find($purchaseOrder_id);
            $purchaseOrder->PurchaseOrderPayment()->associate($purchaseOrderPayment);
            $puchaseOrderPaymentStatus = PurchaseOrderPaymentStatus::where('seq_id',3)->first();
            $purchaseOrder->PurchaseOrderPaymentStatus()->associate($puchaseOrderPaymentStatus);
            $purchaseOrder->save();
        }

        session()->flash('success',['PurchaseOrder Payment Transaction is Recorded']);

        return $this->edit($purchaseOrderPayment);
    }

    /**
     * @param PurchaseOrderPayment $purchaseOrderPayment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(PurchaseOrderPayment $purchaseOrderPayment)
    {
        $purchaseOrders = PurchaseOrder::doesntHave('PurchaseOrderPayment')->get();
        return view('order.purchase.payment.edit',['purchaseOrderPayment'=>$purchaseOrderPayment,'purchaseOrders'=>$purchaseOrders]);
    }

    /**
     * @param Request $request
     * @param PurchaseOrderPayment $purchaseOrderPayment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, PurchaseOrderPayment $purchaseOrderPayment)
    {
        $purchaseOrderPayment->transaction_date = strtotime($request->input('poPaymentTransactionDate'));
        $purchaseOrderPayment->transaction_details = $request->input('PoPaymentTransactionDetails');
        $purchaseOrderPayment->exchange_rate_to_CAD = (float)$request->input('poPaymentExchangeRateCAD');
        $purchaseOrderPayment->amount_CAD = (float)$request->input('poPaymentValueCAD');
        $purchaseOrderPayment->amount_USD = (float)$request->input('poPaymentValueUSD');



        $oldPurchaseOrders = $purchaseOrderPayment->PurchaseOrders;
        foreach($oldPurchaseOrders as $oldPurchaseOrder){
            $oldPurchaseOrder->purchaseOrderPayment_id = NULL;

            $puchaseOrderPaymentStatus = PurchaseOrderPaymentStatus::where('seq_id',2)->first();
            $oldPurchaseOrder->PurchaseOrderPaymentStatus()->associate($puchaseOrderPaymentStatus);

            $oldPurchaseOrder->save();
        }

        foreach ($request->input('poPaymentPOCheckBox') as $purchaseOrder_id){
            $purchaseOrder = PurchaseOrder::find($purchaseOrder_id);
            $purchaseOrder->PurchaseOrderPayment()->associate($purchaseOrderPayment);
            $puchaseOrderPaymentStatus = PurchaseOrderPaymentStatus::where('seq_id',3)->first();
            $purchaseOrder->PurchaseOrderPaymentStatus()->associate($puchaseOrderPaymentStatus);
            $purchaseOrder->save();
        }

        $purchaseOrderPayment->save();

        session()->flash('success',['PurchaseOrder Payment Transaction was successfully updated']);

        return $this->edit($purchaseOrderPayment);
    }

    /**
     * @param PurchaseOrderPayment $purchaseOrderPayment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function delete(PurchaseOrderPayment $purchaseOrderPayment)
    {
        foreach($purchaseOrderPayment->PurchaseOrders as $purchaseOrder){
            $purchaseOrder->purchaseOrderPayment_id = NULL;
            $purchaseOrder->save();
        }
        $result = $purchaseOrderPayment->delete();

        session()->flash('error',['PurchaseOrder Payment Transaction was successfully deleted']);

        return $this->index();
    }
}
