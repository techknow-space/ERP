@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Stock Transfers - Active</b>
                        <div class="float-right">
                            <a href="/stocktransfer/create" class="btn">Start a New Transfer</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="card">
                            <div class="card-header">
                                <b>Outbound</b>
                            </div>
                            <div class="card-body">
                                @include('stockTransfer.table',['filter' => 'outbound'])
                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <b>InBound</b>
                            </div>
                            <div class="card-body">
                                @include('stockTransfer.table',['filter' => 'inbound'])
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <b>Stock Transfers - Completed</b>
                    </div>
                    <div class="card-body">

                        <div class="card">
                            <div class="card-header">
                                <b>Sent</b>
                            </div>
                            <div class="card-body">
                                @include('stockTransfer.table',['filter' => 'sent'])
                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <b>Received</b>
                            </div>
                            <div class="card-body">
                                @include('stockTransfer.table',['filter' => 'received'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
