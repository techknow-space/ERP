<?php

namespace App\Http\Controllers;

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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $supplier = new Supplier();
        return view('order.supplier.view')->with('supplier',$supplier);
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
        $supplier = Supplier::findOrFail($id);
        return view('order.supplier.edit')->with('supplier',$supplier);
    }
}
