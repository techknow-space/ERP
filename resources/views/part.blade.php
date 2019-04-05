@extends('layouts.app')

@section('content')
    @inject('statsController','App\Http\Controllers\Statistics\SalesAndTargetsController')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="font-size: 1.2em;"><b>{{ $part->devices->manufacturer  }} {{ $part->devices->brand->name }} {{ $part->devices->model_name }} {{ $part->part_name }}</b> @if($part->devices->model_number) <span class="float-right">Compatible Models: {{ $part->devices->model_number }}</span> @endif </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Selling Price</th>
                                @foreach($part->stock as $stock)
                                    <th scope="col">{{$stock->location->location_code}}</th>
                                @endforeach
                                <th scope="col">Avg. Cost</th>
                                <th scope="col">New Cost</th>
                                <th scope="col">On Order</th>
                                <th scope="col">Order ETA</th>
                                <th scope="col">First Received Date</th>
                                <th scope="col">Last Received Date</th>
                                @foreach(\App\Models\Location::all() as $location)
                                    <th scope="col">Sold {{$location->location_code}} 3 Months</th>
                                @endforeach
                                <th>SKU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-partid = "{{$part->id}}">
                                <td>{{ $part->part_name }}</td>
                                <td >${{ $part->price->selling_price_b2c }}</td>
                                @foreach ($part->stock as $qty)
                                    <td>{{ $qty->stock_qty }}</td>
                                @endforeach
                                <td>Avg. Cost</td>
                                <td >${{ $part->price->last_cost }}</td>
                                <td>{{$statsController::getTotalQuantityOnOrder($part)}}</td>
                                <td>{{$statsController::getETAForPartOnOrder($part)}}</td>
                                <td>{{$part->first_received}}</td>
                                <td>{{$part->last_received}}</td>
                                @foreach(\App\Models\Location::all() as $location)
                                    <td>{{$statsController::totalSalesPastFotMonthsForLocations($part,$location)}}</td>
                                @endforeach
                                <td>{{ $part->sku }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('part.operations.list')
</div>
@endsection
