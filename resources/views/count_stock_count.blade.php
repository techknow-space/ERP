@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Stock Count - {{$stock_count->number}}</b></div>
                        <div class="float-right">
                            <b>Location - {{$stock_count->location->location}}</b>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="barcode-box">
                            <form id="sc-barcode-form">
                                <label for="sc-barcode-entry">Barcode / SKU</label>
                                <input type="text" id="sc-barcode-entry" class="form-control">
                            </form>

                        </div>
                    </div>

                </div>

                <br>

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Items Scanned</b></div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm" id="sc-partlist-table">
                            <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Device Model</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Scanned Qty.</th>
                            </tr>
                            </thead>
                            <tbody class="sc-scanned-items">
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
