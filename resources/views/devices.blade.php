@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;"><b>{{ $device->brand->manufacturer->manufacturer  }} {{ $device->brand->name  }} {{$device->model_name}} {{$device->model_number}}</b></div>

                    <td class="card-body">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Selling Price</th>
                                @foreach($device->parts[0]->stock as $stock)
                                    <th scope="col">{{$stock->location->location_code}}</th>
                                @endforeach
                                <th scope="col">Last Cost</th>
                                <th>SKU</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($device->parts as $part)
                                    <tr>
                                        <td>{{ $part->part_name }}</td>
                                        <td >${{ $part->price->selling_price_b2c }}</td>
                                        @foreach ($part->stock as $qty)
                                            <td>{{ $qty->stock_qty }}</td>
                                        @endforeach
                                        <td >${{ $part->price->last_cost }}</td>
                                        <td>{{ $part->sku }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
