@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Purchase Orders</b>
                        <div class="float-right">
                            <a href="/order/purchase/create" class="btn btn-primary">Create New Purchase Order</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">PO#</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">#SKUs</th>
                                    <th scope="col">#Qty</th>
                                    <th scope="col">#Value</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (0 < $purchase_orders->count())
                                @foreach ($purchase_orders as $purchase_order)
                                    <tr data-poid="{{$purchase_order->id}}">
                                        <td>
                                            {{$purchase_order->number}}
                                        </td>
                                        <td>
                                            {{$purchase_order->created_at}}
                                        </td>
                                        <td>
                                            {{$purchase_order->Supplier->name}} {{$purchase_order->Supplier->country}}
                                        </td>
                                        <td>
                                            {{$purchase_order->PurchaseOrderStatus->status}}
                                        </td>
                                        <td>
                                            {{$purchase_order->PurchaseOrderItems->count()}}
                                        </td>
                                        <td>
                                            {{$purchase_order->PurchaseOrderItems->sum('qty')}}
                                        </td>
                                        <td>
                                            {{$purchase_order->PurchaseOrderItems->sum('total_cost')}}
                                        </td>
                                        <td>
                                            <a href="/order/purchase/edit/{{$purchase_order->id}}">Edit</a>
                                            <a href="/order/purchase/export/PDF/{{$purchase_order->id}}">PDF</a>
                                            <a href="/order/purchase/export/CSV/{{$purchase_order->id}}">CSV</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        No Purchase Orders
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
