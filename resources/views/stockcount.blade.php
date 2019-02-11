@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;">
                        <div class="float-left">
                            <b>Stock Counts</b>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary">Start a New Stock Count</button>
                        </div>
                    </div>

                    <td class="card-body">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">SC #</th>
                                <th scope="col">Started At</th>
                                <th scope="col">Status</th>
                                <th scope="col">Ended At</th>
                                <th scope="col">Location</th>
                            </tr>
                            </thead>
                            <tbody>

                                @if($stock_counts->count() > 0)
                                    @foreach($stock_counts as $stock_count)
                                        <tr>
                                            <td><a href="/stockcount/count/id/{{ $stock_count->id  }}">{{ $stock_count->number }}</a></td>
                                            <td>{{ $stock_count->started_at }}</td>
                                            <td>{{ $stock_count->StockCountStatus->status }}</td>
                                            <td>{{ $stock_count->ended_at }}</td>
                                            <td>{{ $stock_count->Location->location }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Sorry, No Stock Counts as of Yet</td>
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
