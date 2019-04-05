@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;"><b>Search - {{ request('query') }}</b></div>

                    <div class="card-body">

                        <table class="table table-sm table-dark table-bordered table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Price</th>
                                <th scope="col">Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)

                                    <tr>
                                        @if($result->is_child)
                                            <td>
                                                <a href="/itemlookup/sku/{{ $result->ParentPart->sku }}">{{ $result->devices->brand->name }} {{ $result->devices->model_name }} <b>{{ $result->part_name }}</b></a> <br>
                                                Compatible Part is: <a href="/itemlookup/sku/{{ $result->ParentPart->sku }}">{{ $result->ParentPart->devices->brand->name }} {{ $result->ParentPart->devices->model_name }} <b>{{ $result->ParentPart->part_name }}</b></a>
                                            </td>
                                            <td>
                                                @if(null !== $result->price)
                                                    ${{ $result->price->selling_price_b2c }}
                                                @elseif(null !== $result->ParentPart->price)
                                                    ${{$result->ParentPart->price->selling_price_b2c}}
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($result->ParentPart->stock as $stock)
                                                    {{ $stock->location->location_code }} - <b>{{ $stock->stock_qty }}</b><br>
                                                @endforeach
                                            </td>
                                        @else
                                            <td><a href="/itemlookup/sku/{{ $result->sku }}">{{ $result->devices->brand->name }} {{ $result->devices->model_name }} <b>{{ $result->part_name }}</b></a></td>
                                            <td>
                                                @if(null !== $result->price)
                                                    ${{ $result->price->selling_price_b2c }}
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($result->stock as $stock)
                                                    {{ $stock->location->location_code }} - <b>{{ $stock->stock_qty }}</b><br>
                                                @endforeach
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 1em">
            <div class="col-md-12">
                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection
