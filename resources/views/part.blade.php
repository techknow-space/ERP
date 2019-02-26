@extends('layouts.app')

@section('content')
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
                                <th scope="col">Last Cost</th>
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
                                <td >${{ $part->price->last_cost }}</td>
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
