@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Stock Count - Report <span>{{$stock_count->number}}</span></b></div>
                        <div class="float-right">
                            <b>Location - {{$stock_count->location->location}}</b>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <b>Started:</b> {{$stock_count->started_at}} <br><br>
                                <b>Ended: </b>{{$stock_count->ended_at}}
                            </div>
                            <div class="col-md-2">
                                <b>Total SKUs:</b> {{$stock_count->StockCountItems->count()}} <br>
                                <b>Total Qty:</b> {{$stock_count->count_qty}}
                            </div>
                            <div class="col-md-2">
                               <b>InHand</b> <br>
                                <u>Qty:</u> {{$stock_count->inhand_qty}} <br>
                                <u>Value:</u> &dollar; {{$stock_count->inhand_value}}
                            </div>
                            <div class="col-md-2">
                                <b>Counted</b> <br>
                                <u>Qty:</u> {{$stock_count->count_qty}} <br>
                                <u>Value:</u>  &dollar; {{$stock_count->count_value}}
                            </div>
                            <div class="col-md-2">
                                <b>Diff</b> <br>
                                <u>Qty:</u> {{$stock_count->diff_qty}} <br>
                                <u>Value:</u> {{$stock_count->diff_value}} &dollar;
                            </div>
                        </div>
                    </div>

                </div>

                <br>

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Items</b></div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm" id="sc-partlist-table" data-scid="{{ $stock_count->id }}">
                            <thead>
                            <tr>
                                <th scope="col">Device Model</th>
                                <th scope="col">Part</th>
                                <th scope="col">Cost</th>
                                <th scope="col">In Hand <br> {{$stock_count->location->location_code}}</th>
                                <th scope="col">In Hand <br> {{$stock_count->location->location_code}} $</th>
                                <th scope="col">Count</th>
                                <th scope="col">Count $</th>
                                <th scope="col" class="text-right">Diff #</th>
                                <th scope="col" class="text-right">Diff &dollar;</th>
                                <th scope="col" class="text-right">SKU</th>
                            </tr>
                            </thead>
                            <tbody class="sc-scanned-items">
                            @foreach($stock_count->StockCountItems as $item)
                                <tr data-sku="{{ $item->part->sku }}">
                                    <td class="sc-partlist-device">{{$item->part->devices->brand->name}} {{$item->part->devices->model_name}}</td>
                                    <td class="sc-partlist-name">{{ $item->part->part_name }}</td>
                                    <td class="sc-partlist-cost">&dollar; {{$item->cost}}</td>
                                    <td class="sc-partlist-reported-qty"> {{$item->inhand_qty}}</td>
                                    <td class="sc-partlist-reported-qty-value">{{ $item->inhand_value }}</td>
                                    <td class="sc-partlist-qty">{{ $item->qty }}</td>
                                    <td class="sc-partlist-qty-value">{{$item->count_value}}</td>
                                    <td class="sc-partlist-qty-discrepancy text-right">{{ $item->diff_qty }}</td>
                                    <td class="sc-partlist-cost-discrepancy text-right">{{$item->diff_value}}&dollar;</td>
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
