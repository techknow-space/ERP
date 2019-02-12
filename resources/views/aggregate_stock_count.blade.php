@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Aggregate of Stock Count - {{$stock_count->number}}</b></div>
                        <div class="float-right">
                            <b>Location - {{$stock_count->location->location}}</b>
                        </div>
                    </div>

                </div>

                <br>

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Items Scanned</b></div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm" id="sc-partlist-table" data-scid="{{ $stock_count->id }}">
                            <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Device Model</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Scanned Qty.</th>
                                <th scope="col">Reported Stock at {{$stock_count->location->location}}</th>
                            </tr>
                            </thead>
                            <tbody class="sc-scanned-items">
                            @foreach($stock_count->StockCountItems as $item)
                                <tr data-sku="{{ $item->part->sku }}">
                                    <td class="sc-partlist-name">{{ $item->part->part_name }}</td>
                                    <td class="sc-partlist-device">{{$item->part->devices->model_name}}</td>
                                    <td class="sc-partlist-sku">{{ $item->part->sku }}</td>
                                    <td class="sc-partlist-qty">{{ $item->qty }}</td>
                                    <td class="sc-partlist-reported-qty">
                                        @foreach($item->part->stock as $stock)
                                            @if($stock->location_id == $stock_count->location->id)
                                                {{$stock->stock_qty}}
                                            @endif
                                        @endforeach</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
