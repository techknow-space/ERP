@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $part->part_name }} </div>

                <div class="card-body">
                    <div>Compatible with - {{ $part->devices->brand->name }} {{ $part->devices->model_name }} ({{ $part->devices->model_number }})</div>
                    <div>Last Cost - ${{ $part->price->last_cost }}</div>
                    <div>Price - ${{ $part->price->selling_price_b2c }}</div>
                    @foreach ($part->stock as $qty)
                        {{ $qty->location->location }} Qty - {{ $qty->stock_qty }}<br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
