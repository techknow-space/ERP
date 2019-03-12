@extends('layouts.app')

@section('content')
    <script>
        let purchase_order_id = '{{$purchaseOrder->id}}';
    </script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Purchase Order</b>-
                        ({{$purchaseOrder->number}})
                        <div class="float-right">
                            <b>Total SKUs:</b> {{$purchaseOrder->PurchaseOrderItems->count()}} ||
                            <b>Total Qty:</b> {{$purchaseOrder->PurchaseOrderItems->sum('qty')}} ||
                            <b>Total Value:</b> ${{$purchaseOrder->PurchaseOrderItems->sum('total_cost')}}
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <b>Scan</b>
                    </div>
                    <div class="card-body">
                        <div class="barcode-box">
                            <form id="purchaseOrderVerifyBarcodeEntryForm">
                                <label for="purchaseOrderVerifyBarcodeEntry">Barcode / SKU</label>
                                <input type="text" id="purchaseOrderVerifyBarcodeEntry" class="form-control">
                            </form>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <b>Parts</b>
                    </div>
                    <div class="card-body">
                        @include('order.purchase.verify.item.index',[''])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
