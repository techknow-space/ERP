@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left">
                            <b>Stock Counts - Active</b>
                        </div>
                        <div class="float-right">
                            <a href="/stockcount/create" type="button" class="btn btn-primary">Start a New Stock Count</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">SC #</th>
                                <th scope="col">Started At</th>
                                <th scope="col">Status</th>

                                <th scope="col">Location</th>

                            </tr>
                            </thead>
                            <tbody>

                            @if($stock_counts_active->count() > 0)
                                @foreach($stock_counts_active as $stock_count)
                                    <tr>
                                        <td><a href="/stockcount/count/id/{{ $stock_count->id  }}">{{ $stock_count->number }}</a></td>
                                        <td>{{ $stock_count->started_at }}</td>
                                        <td>{{ $stock_count->StockCountStatus->status }}</td>

                                        <td>{{ $stock_count->Location->location }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Sorry, No Active Stock Counts</td>
                                </tr>
                            @endif

                            </tbody>
                        </table>

                    </div>
                </div>

                <br>

                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left">
                            <b>Stock Counts - Completed</b>
                        </div>
                    </div>


                    <div class="card-body">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">SC #</th>
                                <th scope="col">Started At</th>

                                <th scope="col">Ended At</th>
                                <th scope="col">Location</th>
                                <th scope="col">InHand #</th>
                                <th scope="col">InHand $</th>
                                <th scope="col">Count #</th>
                                <th scope="col">Count $</th>
                                <th scope="col">Diff #</th>
                                <th scope="col">Diff $</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($stock_counts_ended->count() > 0)
                                @foreach($stock_counts_ended as $stock_count)
                                    <tr>
                                        <td><a href="/stockcount/count/id/{{ $stock_count->id  }}">{{ $stock_count->number }}</a></td>
                                        <td>{{ $stock_count->started_at }}</td>

                                        <td>{{ $stock_count->ended_at }}</td>
                                        <td>{{ $stock_count->Location->location_code }}</td>
                                        <td>{{ $stock_count->inhand_qty }}</td>
                                        <td>{{ $stock_count->inhand_value }}</td>
                                        <td>{{ $stock_count->count_qty }}</td>
                                        <td>{{ $stock_count->count_value }}</td>
                                        <td>{{ $stock_count->diff_qty }}</td>
                                        <td>{{ $stock_count->diff_value }}</td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Sorry, No Completed Stock Counts</td>
                                </tr>
                            @endif

                            </tbody>
                        </table>

                    </div>
                </div>
        </div>
    </div>
    </div>

@endsection
