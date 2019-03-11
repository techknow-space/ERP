@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Purchase Order - Payments</b>
                        <div class="float-right">
                            <a href="/order/purchase/payment/create" class="btn btn-primary">Add new Payment Details</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Transaction Date:</th>
                                <th scope="col">Amount USD</th>
                                <th scope="col">Amount CAD</th>
                                <th scope="col">Exchange Rate</th>
                                <th scope="col">Details</th>
                                <th scope="col">#PO</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (0 < $purchaseOrderPayments->count())
                                @foreach ($purchaseOrderPayments as $purchaseOrderPayment)
                                    <tr id="{{$purchaseOrderPayment->id}}">
                                        <td>
                                            <a href="/order/purchase/edit/{{$purchaseOrderPayment->id}}">{{$purchaseOrderPayment->transaction_date->format('m-d-Y')}}</a>
                                        </td>
                                        <td>
                                            {{$purchaseOrderPayment->amount_USD}}
                                        </td>
                                        <td>
                                            {{$purchaseOrderPayment->amount_USD}}
                                        </td>
                                        <td>
                                            {{$purchaseOrderPayment->exchange_rate_to_CAD}}
                                        </td>
                                        <td>
                                            {{$purchaseOrderPayment->transaction_details}}
                                        </td>
                                        <td>
                                            @foreach($purchaseOrderPayment->PurchaseOrders as $purchaseOrder)
                                                <a href="/order/purchase/edit/{{$purchaseOrder->id}}">{{$purchaseOrder->number}} {{$purchaseOrder->Supplier->name}}-{{$purchaseOrder->Supplier->country}}</a> <br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="/order/purchase/payment/edit/{{$purchaseOrderPayment->id}}">Edit</a>
                                            <a href="/order/purchase/payment/delete/{{$purchaseOrderPayment->id}}">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        No Purchase Orders Payment Records
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
