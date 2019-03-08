<?php


namespace App\Http\Controllers\PurchaseOrder;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class PurchaseOrderPaymentController extends Controller
{
    public function test(Request $request)
    {
        dd($request->all());
    }

    public function index()
    {

    }
}
