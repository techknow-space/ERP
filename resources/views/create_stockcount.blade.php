@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;"><b>Stock Count - {{$stock_count->number}}</b></div>

                    <div class="card-body">

                        {{$stock_count->number}} <br> {{$stock_count->id}}

                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
