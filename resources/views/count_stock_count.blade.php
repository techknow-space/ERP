@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Stock Count - {{$stockCount->number}}</b></div>
                        <div class="float-right">
                            <b>Location - {{$stockCount->location->location}}</b>
                        </div>
                    </div>

                    <div class="card-body">
                        Stock Count Operations :

                        @if($allowed_operations['restart'])
                            <a href="/stockcount/restart/id/{{$stockCount->id}}" class="btn btn-success">Re-Start</a>
                        @endif
                        @if($allowed_operations['pause'])
                            <a href="/stockcount/pause/id/{{$stockCount->id}}" class="btn btn-secondary">Pause</a>
                        @endif
                        @if($allowed_operations['end'])
                            <a href="/stockcount/end/id/{{$stockCount->id}}" class="btn btn-danger">End</a>
                        @endif

                    </div>

                </div>

                <br>

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left"><b>Scan</b></div>
                    </div>

                    <div class="card-body">
                        <div class="barcode-box">
                            <form id="sc-barcode-form">
                                <label for="sc-barcode-entry">Barcode / SKU</label>
                                <input type="text" id="sc-barcode-entry" class="form-control"
                                    @if($stockCount->StockCountStatus->status !== 'Started')
                                        disabled
                                    @endif
                                >
                            </form>

                        </div>
                    </div>
                </div>

                <br>

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left">
                            <b>Items</b>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm" id="sc-partlist-table" data-scid="{{ $stockCount->id }}">
                            <thead>
                            <tr>
                                <th scope="col">Part</th>
                                <th scope="col">Device Model</th>
                                <th scope="col">SKU</th>
                            </tr>
                            </thead>
                            <tbody class="sc-scanned-items">
                            @foreach($stockCount->StockCountItemsSeqs->sortBy('updated_at') as $item)
                                <tr data-sku="{{ $item->part->sku }}">
                                    <td class="sc-partlist-name">{{ $item->part->part_name }}</td>
                                    <td class="sc-partlist-device">{{$item->part->devices->brand->name}} {{$item->part->devices->model_name}}</td>
                                    <td class="sc-partlist-sku">{{ $item->part->sku }}</td>
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
