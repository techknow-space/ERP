<?php
namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDiff;
use App\Models\PurchaseOrderPaymentStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\Supplier;
use DebugBar\DebugBar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use Exception;

class PurchaseOrderController extends Controller
{
    public function index($filter = NULL)
    {
        if(NULL == $filter){
            $purchase_orders = PurchaseOrder::all();
        }
        else{
            $purchase_orders = $this->filter($filter);
        }

        $purchase_orders = PurchaseOrder::all();

        return view('order.purchase.index')->with('purchase_orders',$purchase_orders);
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('order.purchase.create')->with('suppliers',$suppliers);
    }

    public function insert(Request $request)
    {

        $supplier = Supplier::findOrFail($request->input('poSupplier'));

        $purchaseOrder = $this->createPurchaseOrder($supplier);

        session()->flash('success',['Created Successfully.']);

        return redirect('/order/purchase/edit/'.$purchaseOrder->id);

    }

    public function view($id)
    {

    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        app('debugbar')->disable();

        $purchase_order = $purchaseOrder;



        //$suppliers = Supplier::all();
        $purchase_order_statuses = PurchaseOrderStatus::all()->sortBy('seq_id');
        $purchase_order_payment_statuses = PurchaseOrderPaymentStatus::all();



        return view('order.purchase.edit',[
            'purchase_order' => $purchase_order,
            'purchase_order_statuses' => $purchase_order_statuses,
            'purchase_order_payment_statuses' => $purchase_order_payment_statuses
        ]);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $purchase_order = $purchaseOrder;

        $status = PurchaseOrderStatus::findOrFail($request->input('poStatus'));
        $payment_status = PurchaseOrderPaymentStatus::findOrFail($request->input('poPaymentStatus'));

        $purchase_order->PurchaseOrderStatus()->associate($status);
        $purchase_order->PurchaseOrderPaymentStatus()->associate($payment_status);
        $purchase_order->save();

        return $this->edit($purchaseOrder);
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return RedirectResponse
     */
    public function delete(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if(7 > $purchaseOrder->PurchaseOrderStatus->seq_id){
            try{
                $purchaseOrder->delete();
                session()->flash('success',['Deleted Successfully.']);

            }catch (Exception $exception){
                session()->flash('Error',['Error !!!, Sorry, there was an error deleting this Order.']);
            }

        }
        else{
            session()->flash('Error',['Error !!!, Sorry, Cannot delete this Order at this Stage.']);
        }

        return redirect('order/purchase');
    }


    /**
     * @param Supplier $supplier
     * @param PurchaseOrderStatus|NULL $in_status
     * @return PurchaseOrder
     */
    public function createPurchaseOrder(Supplier $supplier, PurchaseOrderStatus $in_status = NULL): PurchaseOrder
    {

        $location = Location::where('location_code','S1')->firstOrFail();
        //$status = PurchaseOrderStatus::where('status','InReview')->first();

        if(NULL == $in_status){
            $purchase_order = new PurchaseOrder();
            if(NULL === $in_status){
                $purchase_order_status = PurchaseOrderStatus::where('status','Created')->firstOrFail();
            }else{
                $purchase_order_status = $in_status;
            }

            $purchase_order_payment_status = PurchaseOrderPaymentStatus::where('status','In Queue')->firstOrFail();

            $purchase_order->Supplier()->associate($supplier);
            $purchase_order->PurchaseOrderStatus()->associate($purchase_order_status);
            $purchase_order->PurchaseOrderPaymentStatus()->associate($purchase_order_payment_status);
            $purchase_order->Location()->associate($location);

            $purchase_order->save();
        }
        elseif ('Generated' == $in_status->status){
            if('Generated' == $in_status->status){
                try{
                    $purchase_order = PurchaseOrder::
                    ofSupplier($supplier)
                        ->isOrBeforeStatus($in_status)
                        ->firstOrFail();
                }catch(ModelNotFoundException $e){
                    $purchase_order = new PurchaseOrder();
                    if(NULL === $in_status){
                        $purchase_order_status = PurchaseOrderStatus::where('status','Created')->firstOrFail();
                    }else{
                        $purchase_order_status = $in_status;
                    }

                    $purchase_order_payment_status = PurchaseOrderPaymentStatus::where('status','In Queue')->firstOrFail();

                    $purchase_order->Supplier()->associate($supplier);
                    $purchase_order->PurchaseOrderStatus()->associate($purchase_order_status);
                    $purchase_order->PurchaseOrderPaymentStatus()->associate($purchase_order_payment_status);

                    $purchase_order->save();
                }
            }else{
                $purchase_order = new PurchaseOrder();
                if(NULL === $in_status){
                    $purchase_order_status = PurchaseOrderStatus::where('status','Created')->firstOrFail();
                }else{
                    $purchase_order_status = $in_status;
                }

                $purchase_order_payment_status = PurchaseOrderPaymentStatus::where('status','In Queue')->firstOrFail();

                $purchase_order->Supplier()->associate($supplier);
                $purchase_order->PurchaseOrderStatus()->associate($purchase_order_status);
                $purchase_order->PurchaseOrderPaymentStatus()->associate($purchase_order_payment_status);
                $purchase_order->Location()->associate($location);

                $purchase_order->save();
            }
        }

        return $purchase_order;

    }

    /**
     * @param $id
     * @return mixed
     */
    public function exportPDF($id)
    {
        //TODO: Remove this shit.
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $parts = $purchaseOrder->PurchaseOrderItems->sortBy(function ($part,$key){
            return strtolower($part['Part']['devices']['brand']['name'].' '.$part['Part']['devices']['model_name'].' '.$part['Part']['part_name']);
        });


        $po_view = view('order.purchase.pdf.purchaseOrder',['purchaseOrder' => $purchaseOrder,'parts'=>$parts]);
        $pdf = app('dompdf.wrapper')->loadHTML($po_view);
        return $pdf->download('po.pdf');
    }

    public function exportCSV($id)
    {
        //TODO: Remove this shit.
        set_time_limit(0);
        ini_set('max_execution_time', 0);

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $items = $purchaseOrder->PurchaseOrderItems->sortBy(function ($part,$key){
            return strtolower($part['Part']['devices']['brand']['name'].' '.$part['Part']['devices']['model_name'].' '.$part['Part']['part_name']);
        });

        $csvListHeaders = [
            '#',
            'SKU',
            'Brand',
            'Model',
            'Part',
            'Qty'
        ];

        $csvPOHeader = [
            'Date: '.date("F j, Y"),
            'PO#: '.$purchaseOrder->number,
            'The TechKnow Space Inc.',
            '',
            'Total SKUs: '.$purchaseOrder->PurchaseOrderItems->count(),
            'Total Qty: '.$purchaseOrder->PurchaseOrderItems->sum('qty')
        ];

        $PO_ARRAY = [$csvPOHeader,$csvListHeaders];

        $i = 1;
        foreach ($items as $item){
            $PO_ARRAY[] = [
                $i,
                $item->Part->sku,
                $item->Part->devices->brand->name,
                $item->Part->devices->model_name,
                $item->Part->part_name,
                $item->qty
            ];
            $i++;
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$purchaseOrder->number.'.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $callback = function() use ($PO_ARRAY)
        {
            $FH = fopen('php://output', 'w');
            foreach ($PO_ARRAY as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback,200,$headers);
    }

    public function filter($criteria)
    {
        $purchaseOrders = PurchaseOrder::all();
        return $purchaseOrders;
    }

    public function viewDiff(PurchaseOrderDiff $purchaseOrderDiff)
    {
        return view('order.purchase.diff.index')->with('purchaseOrderDiff',$purchaseOrderDiff);
    }

}
