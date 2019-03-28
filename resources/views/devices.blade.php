@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <b>{{ $device->brand->name  }} {{$device->model_name}}</b>
                        <div class="float-right">
                            @if($device->model_number)
                                ({{$device->model_number}})
                            @endif
                        </div>
                    </div>

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
                                    @if($part->is_child)
                                            <td>
                                                <a href="/itemlookup/sku/{{ $part->ParentPart->sku }}">{{ $part->part_name }}</a><br>
                                                Compatible Part: <a href="/itemlookup/sku/{{ $part->ParentPart->sku }}">{{ $part->ParentPart->devices->brand->name }} {{ $part->ParentPart->devices->model_name }} <b>{{ $part->ParentPart->part_name }}</b></a>
                                            </td>
                                            <td >
                                                @if(null !== $part->price)
                                                    ${{ $part->price->selling_price_b2c }}
                                                @else
                                                    ${{ $part->ParentPart->price->selling_price_b2c }}
                                                @endif
                                            </td>
                                            @foreach ($part->ParentPart->stock as $qty)
                                                 <td>{{ $qty->stock_qty }}</td>
                                            @endforeach
                                            <td >${{ $part->ParentPart->price->last_cost }}</td>
                                            <td>{{ $part->ParentPart->sku }}</td>
                                    @else
                                            <td><a href="/itemlookup/sku/{{ $part->sku }}">{{ $part->part_name }}</a></td>
                                            <td >${{ $part->price->selling_price_b2c }}</td>
                                            @foreach ($part->stock as $qty)
                                                <td>{{ $qty->stock_qty }}</td>
                                            @endforeach
                                            <td >${{ $part->price->last_cost }}</td>
                                            <td>{{ $part->sku }}</td>
                                    @endif
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
