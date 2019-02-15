@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>TTS - ERP</b></div>
                    </div>

                    <div class="card-body">
                        <form id="main-barcode-form">
                            <label for="main-barcode-entry">Barcode / SKU</label>
                            <input type="text" id="main-barcode-entry" class="form-control">
                        </form>
                    </div>

                </div>
                <br>
                <div class="card" id="main-part-details-card" style="display: none;">
                    <div class="card-header" style="font-size: 1.2em;">
                        <b>Part Details</b>
                    </div>
                    <div class="class-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="part-details">
                                    <table class="table" id="part-details-table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Part</th>
                                            <th scope="col">Last Cost</th>
                                            <th scope="col">Selling Price</th>
                                            <th scope="col">Qty-S1</th>
                                            <th scope="col">Qty-TO1</th>
                                            <th scope="col">SKU</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td class="part-details-table-col-part-name"></td>
                                            <td class="part-details-table-col-last-cost"></td>
                                            <td class="part-details-table-col-selling-price"></td>
                                            <td class="part-details-table-col-Qty-s1"></td>
                                            <td class="part-details-table-col-qty-to1"></td>
                                            <td class="part-details-table-col-sku"></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card main-operations-card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="text-center" ><b>Select Your Operation</b></div>
                    </div>

                    <div class="card-body">
                        <ol class="main-operations-ol">
                            <li>
                                <b><a href="/stockcount/create" id="main-initiate-stock-count-link">Stock Count</a></b>
                            </li>
                            <br>
                            <li>
                                <b>Location Transfer</b>
                            </li>
                            <br>
                            <li>
                                <b>Verify Shipment</b>
                            </li>
                        </ol>
                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>

@endsection
