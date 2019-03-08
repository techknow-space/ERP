<?php


namespace App\Http\Controllers\PurchaseOrder;


use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderPayment;
use Illuminate\Support\Facades\Request;

class PurchaseOrderPaymentController extends Controller
{
    public function test(Request $request)
    {
        dd($request->all());
    }

    public function index()
    {
        $purchaseOrderPayments = PurchaseOrderPayment::all();
        return view('order.purchase.payment.index')->with('purchaseOrderPayments',$purchaseOrderPayments);
    }

    public function delete($id)
    {
        $purchaseOrderPayment = PurchaseOrderPayment::findOrFail($id);

        foreach($purchaseOrderPayment->PurchaseOrders as $purchaseOrder){
            $purchaseOrder->purchaseOrderPayment_id = NULL;
            $purchaseOrder->save();
        }

        $result = $purchaseOrderPayment->delete();
        return $this->index();
    }
}
