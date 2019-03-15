@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Short Excess Report: {{$purchaseOrderDiff->number}} for {{$purchaseOrderDiff->PurchaseOrder->number}}</b>
                        <div class="float-right">
                            <b>Total SKUs:</b> {{$purchaseOrderDiff->PurchaseOrderDiffItems->count()}} ||
                            <b>Total Qty:</b> {{$purchaseOrderDiff->qty_diff}} ||
                            <b>Total Value:</b> ${{$purchaseOrderDiff->value_diff_CAD}}
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <b>Parts</b>
                    </div>
                    <div class="card-body">
                        @include('order.purchase.diff.item.index')
                    </div>
                </div>

                @if($purchaseOrderDiff->PurchaseOrder->PurchaseOrderStatus->seq_iq > 9)
                    <div class="card">
                        <div class="card-header align-content-center">
                            <div class="text-center">
                                <a href="/order/purchase/mark/verified/{{$purchaseOrderDiff->PurchaseOrder->id}}" type="button" class="btn btn-danger">Mark As Verified</a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
