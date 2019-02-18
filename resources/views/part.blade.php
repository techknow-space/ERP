@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="font-size: 1.2em;"><b>{{ $part->devices->manufacturer  }} {{ $part->devices->brand->name }} {{ $part->devices->model_name }} {{ $part->part_name }}</b> ({{ $part->devices->model_number }})</div>

                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Selling Price</th>
                                @foreach($part->stock as $stock)
                                    <th scope="col">{{$stock->location->location_code}}</th>
                                @endforeach
                                <th scope="col">Last Cost</th>
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $part->part_name }}</td>
                                <td >${{ $part->price->selling_price_b2c }}</td>
                                @foreach ($part->stock as $qty)
                                    <td>{{ $qty->stock_qty }}</td>
                                @endforeach
                                <td >${{ $part->price->last_cost }}</td>
                                <td>{{ $part->sku }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row secondrow">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header" style="font-size: 1.2em;"><b>Select Operation</b></div>

                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">Use Part in Repair</a>
                        <a href="#" class="list-group-item list-group-item-action">Return Part from Repair</a>
                        <a href="#" class="list-group-item list-group-item-action">Begin Stock Count</a>
                        <a href="#" class="list-group-item list-group-item-action">Edit Part Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
