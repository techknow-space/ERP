@extends('layouts.app')

@section('content')
    <script>
        let purchase_order_id = '{{$purchase_order->id}}';
    </script>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Purchase Order</b>-
                        ({{$purchase_order->number}})
                        <div class="float-right purchaseOrderEditScreenSummary">
                            <b>Total SKUs:</b> {{$purchase_order->PurchaseOrderItems->count()}} ||
                            <b>Total Qty:</b> {{$purchase_order->PurchaseOrderItems->sum('qty')}} ||
                            <b>Total Value:</b> ${{$purchase_order->PurchaseOrderItems->sum('total_cost')}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="/order/purchase/edit/{{$purchase_order->id}}" method="post" id="purchaseOrderForm">
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="poCreatedAt">Created At</label>
                                    <input name="poCreatedAt" id="poCreatedAt" value="{{$purchase_order->created_at}}" class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poSupplier">Supplier</label>
                                    <input name="poSupplier" id="poSupplier" value="{{$purchase_order->Supplier->name}} {{$purchase_order->Supplier->country}}" class="form-control" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="poStatus">Status</label>
                                    <select name="poStatus" id="poStatus" class="form-control"
                                        @if($purchase_order->PurchaseOrderStatus->seq_id >= 9)
                                            disabled
                                        @endif
                                    >
                                        @foreach ($purchase_order_statuses as $status)
                                            @if( ($purchase_order->PurchaseOrderStatus->seq_id < 10) && ($status->seq_id > 9))
                                                @php
                                                    continue;
                                                @endphp
                                            @endif
                                            <option
                                                @if($status->id == $purchase_order->PurchaseOrderStatus->id)
                                                selected
                                                @endif
                                                value="{{$status->id}}">
                                                {{$status->status}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="poPaymentStatus">Payment Status</label>
                                    <select name="poPaymentStatus" id="poPaymentStatus" class="form-control">
                                        @foreach ($purchase_order_payment_statuses as $status)
                                            <option
                                                @if($status->id == $purchase_order->PurchaseOrderPaymentStatus->id)
                                                selected
                                                @endif
                                                value="{{$status->id}}">
                                                {{$status->status}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            @csrf
                            <br>
                        </form>

                        @include('order.purchase.item.index')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
