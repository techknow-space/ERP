@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Add PO Payment Transaction</b>
                    </div>
                    <div class="card-body">
                        <form action="/order/purchase/payment/edit/{{$purchaseOrderPayment->id}}" method="post">
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="poPaymentTransactionDate" class="col-form-label">Transaction Date:</label>
                                    <input type="text" class="form-control" name="poPaymentTransactionDate" id="poPaymentTransactionDate" data-toggle="datetimepicker" data-target="#poPaymentTransactionDate" value="{{$purchaseOrderPayment->transaction_date->format('m-d-Y')}}">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="poPaymentValueUSD" class="col-form-label">Value CAD:</label>
                                    <input class="form-control" type="number" step="0.01" min="0" name="poPaymentValueCAD" id="poPaymentValueCAD" value="{{$purchaseOrderPayment->amount_CAD}}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poPaymentValueUSD" class="col-form-label">Value USD:</label>
                                    <input class="form-control" type="number" step="0.01" min="0" name="poPaymentValueUSD" id="poPaymentValueUSD" value="{{$purchaseOrderPayment->amount_USD}}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poPaymentExchangeRateCAD" class="col-form-label">Exchange Rate to CAD:</label>
                                    <input class="form-control" type="number" step="0.0001" min="0" name="poPaymentExchangeRateCAD" id="poPaymentExchangeRateCAD" value="{{$purchaseOrderPayment->exchange_rate_to_CAD}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="PoPaymentTransactionDetails" class="col-form-label">Transaction Details</label>
                                <textarea class="form-control" name="PoPaymentTransactionDetails" id="PoPaymentTransactionDetails">{{$purchaseOrderPayment->transaction_details}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Related Purchase Orders: </label>
                                @foreach($purchaseOrderPayment->PurchaseOrders as $purchaseOrder)
                                    <div class="form-check form-check-inline">
                                        <input id="poPaymentPOCheckBox-{{$purchaseOrder->id}}" name="poPaymentPOCheckBox[]" class="form-check-input" type="checkbox" value="{{$purchaseOrder->id}}" checked>
                                        <label class="form-check-label" for="poPaymentPOCheckBox-{{$purchaseOrder->id}}">{{$purchaseOrder->number}}</label>
                                    </div>
                                @endforeach
                                <br/>
                                <label>Other Purchase Orders: </label>
                                @foreach($purchaseOrders as $purchaseOrder)
                                    <div class="form-check form-check-inline">
                                        <input id="poPaymentPOCheckBox-{{$purchaseOrder->id}}" name="poPaymentPOCheckBox[]" class="form-check-input" type="checkbox" value="{{$purchaseOrder->id}}">
                                        <label class="form-check-label" for="poPaymentPOCheckBox-{{$purchaseOrder->id}}">{{$purchaseOrder->number}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @csrf
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
