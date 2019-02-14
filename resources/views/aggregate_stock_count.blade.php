@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Stock Count - Report {{$stock_count->number}}</b></div>
                        <div class="float-right">
                            <b>Location - {{$stock_count->location->location}}</b>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                Started: {{$stock_count->started_at}} <br>
                                Ended: {{$stock_count->ended_at}}
                            </div>
                            <div class="col-md-3">
                                Total SKUs: {{$stock_count->StockCountItems->count()}} <br>
                                Total Qty: {{$stock_count->StockCountItemsSeqs->count()}}
                            </div>
                            <div class="col-md-3">
                                Delta Qty.: <br>

                            </div>
                            <div class="col-md-3">
                                Delta CAD: <br>

                            </div>
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
                                <th scope="col">Device Model</th>
                                <th scope="col">Part</th>
                                <th scope="col">Cost</th>
                                <th scope="col">In Hand at <br> {{$stock_count->location->location_code}}</th>
                                <th scope="col">Count</th>
                                <th scope="col" class="text-right">Discrepancy #</th>
                                <th scope="col" class="text-right">Discrepancy &dollar;</th>
                                <th scope="col" class="text-right">SKU</th>
                            </tr>
                            </thead>
                            <tbody class="sc-scanned-items">
                            @foreach($stock_count->StockCountItems as $item)
                                <tr data-sku="{{ $item->part->sku }}">
                                    <td class="sc-partlist-device">{{$item->part->devices->brand->name}} {{$item->part->devices->model_name}}</td>
                                    <td class="sc-partlist-name">{{ $item->part->part_name }}</td>
                                    <td class="sc-partlist-cost">&dollar; {{$item->part->price->last_cost}}</td>
                                    <td class="sc-partlist-reported-qty">
                                        @foreach($item->part->stock as $stock)
                                            @if($stock->location_id == $stock_count->location->id)
                                                {{ $inhand= $stock->stock_qty}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="sc-partlist-qty">{{ $item->qty }}</td>
                                    <td class="sc-partlist-qty-discrepancy text-right">{{ $discrepancy_qty = $item->qty - $inhand }}</td>
                                    <td class="sc-partlist-cost-discrepancy text-right">
                                        {{
                                          (( ($item->qty) * $item->part->price->last_cost) - ($inhand * $item->part->price->last_cost) )
                                        }}
                                        CAD
                                    </td>
                                    <td class="sc-partlist-sku text-right" >{{ $item->part->sku }}</td>
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
