@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;"><b>Search</b></div>

                    <td class="card-body">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Price</th>
                                <th scope="col">Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td>{{ $result->devices->brand->name }} {{ $result->devices->model_name }} {{ $result->part_name }}</td>
                                        <td>${{ $result->price->selling_price_b2c }}</td>
                                        <td>
                                            @foreach($result->stock as $stock)
                                                {{$stock->location->location_code}} - <b>{{ $stock->stock_qty }}</b><br>
                                            @endforeach
                                        </td>
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
