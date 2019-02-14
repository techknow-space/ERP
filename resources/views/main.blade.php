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
                            <input type="text" id="sc-barcode-entry" class="form-control">
                        </form>
                    </div>

                </div>
                <br>
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="text-center" ><b>Select Your Operation</b></div>
                    </div>

                    <div class="card-body">
                        <ol class="main-operations-ol">
                            <li>
                                <b>Inventory Count</b>
                            </li>
                            <br>
                            <li>
                                <b>Query Item Qty.</b>
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
