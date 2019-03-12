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
                        <form action="/order/purchase/payment/create/" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="poPaymentTransactionDate" class="col-form-label">Transaction Date:</label>
                                    <input type="text" class="form-control" name="poPaymentTransactionDate" id="poPaymentTransactionDate" data-toggle="datetimepicker" data-target="#poPaymentTransactionDate"">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="poPaymentValueUSD" class="col-form-label">Value CAD:</label>
                                    <input class="form-control" type="number" step="0.01" min="0" name="poPaymentValueCAD" id="poPaymentValueCAD">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poPaymentValueUSD" class="col-form-label">Value USD:</label>
                                    <input class="form-control" type="number" step="0.01" min="0" name="poPaymentValueUSD" id="poPaymentValueUSD">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poPaymentExchangeRateCAD" class="col-form-label">Exchange Rate to CAD:</label>
                                    <input class="form-control" type="number" step="0.01" min="0" name="poPaymentExchangeRateCAD" id="poPaymentExchangeRateCAD">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="PoPaymentTransactionDetails" class="col-form-label">Transaction Details</label>
                                <textarea class="form-control" name="PoPaymentTransactionDetails" id="PoPaymentTransactionDetails"></textarea>
                            </div>
                            <div class="form-group">
                                <label>For Purchase Orders: </label>
                                @foreach($purchaseOrders as $purchaseOrder)
                                    <div class="form-check form-check-inline">
                                        <input id="poPaymentPOCheckBox-{{$purchaseOrder->id}}" name="poPaymentPOCheckBox[]" class="form-check-input" type="checkbox" value="{{$purchaseOrder->id}}">
                                        <label class="form-check-label" for="poPaymentPOCheckBox-{{$purchaseOrder->id}}">{{$purchaseOrder->number}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @csrf
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary">Insert</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
