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
                        <table class="table" id="poVerifySummaryTable">
                            <thead>
                                <tr>
                                    <th># SKUs Scanned</th>
                                    <th># Qty Scanned</th>
                                    <th># Diff</th>
                                    <th>$ Diff</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="poVerifySummaryRow">
                                    <td class="poVerifySummarySKUScanned">
                                        {{$purchaseOrder->PurchaseOrderItems->filter(function($item, $key){
                                                return $item['qty_received'] > 0;
                                        })->count()}}
                                    </td>
                                    <td class="poVerifySummaryQtyScanned">
                                        {{ $purchaseOrder->PurchaseOrderItems->sum('qty_received') }}
                                    </td>
                                    <td class="poVerifySummaryDiffQty">
                                        {{ $purchaseOrder->PurchaseOrderItems->sum('qty_received') - $purchaseOrder->PurchaseOrderItems->sum('qty') }}
                                    </td>
                                    <td class="poVerifySummaryDiffDollar">
                                        {{$diff_dollar}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        @include('order.purchase.verify.item.index')
                    </div>
                </div>
                <div class="card">
                    <div class="card-header align-content-center">
                        <div class="text-center">
                            <b>All items scanned ? <br> Click below to Generate a Short/Excess report.</b><br><br>
                            <a href="/order/purchase/finalize/{{$purchaseOrder->id}}" type="button" class="btn btn-danger">Create Short Excess Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
