<?php
namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItems;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function index()
    {
        return view('order.purchase.index');
    }

    public function create()
    {

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(Request $request)
    {
        $data['poid'] = $request->input('po_id');
        $data['part_id'] = $request->input('part_id');
        $data['qty'] = $request->input('qty');

        $error = false;
        $p_res = [];


        try{
            $part = Part::find($data['part_id']);
            $purchase_order = PurchaseOrder::find($data['poid']);

            $po_item = new PurchaseOrderItems();

            $po_item->PurchaseOrder()->associate($purchase_order);
            $po_item->Part()->associate($part);

            $po_item->cost_currency = 'CAD';

            $po_item->qty = $data['qty'];
            $po_item->cost = $part->price->last_cost;

            $po_item->save();




        }catch (ModelNotFoundException $e) {
            $error = true;
        }

        $result['error'] = $error;

        return response()->json($result);
    }

    public function view()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }
}
