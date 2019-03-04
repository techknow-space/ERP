<?php

namespace App\Http\Controllers\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('order.supplier.index')->with('suppliers',$suppliers);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('order.supplier.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function insert(Request $request)
    {
        $form_data = $request->all();

        $supplier = new Supplier();
        $supplier->name = $form_data['supplier-name'];
        $supplier->country = $form_data['supplier-country'];
        $supplier->lead_time = $form_data['lead-time'];
        $supplier->payment_details = $form_data['supplier-payment-details'];
        $supplier->save();
        return view('order.supplier.edit')->with('supplier',$supplier);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('order.supplier.view')->with('supplier',$supplier);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('order.supplier.edit')->with('supplier',$supplier);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $form_data = $request->all();

        $supplier = Supplier::findOrFail($id);
        $supplier->name = $form_data['supplier-name'];
        $supplier->country = $form_data['supplier-country'];
        $supplier->lead_time = $form_data['lead-time'];
        $supplier->payment_details = $form_data['supplier-payment-details'];
        $supplier->save();

        return view('order.supplier.edit')->with('supplier',$supplier);
    }
}
