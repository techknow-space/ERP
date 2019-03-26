@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Stock Transfers - {{strtoupper($filter)}}</b>
                        <div class="float-right">
                            <a href="/stocktransfer/create" class="btn">Start a New Transfer</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="card">
                            <div class="card-header">
                                <b>{{strtoupper($filter)}}</b>
                            </div>
                            <div class="card-body">
                                @include('stockTransfer.table',['filter' => $filter])
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
