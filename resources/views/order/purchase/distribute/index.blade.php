@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Distribution List for <b>{{$purchaseOrder->number}}</b>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <b>Parts</b>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Distribution to Locations</h5>
                        @include('order.purchase.distribute.item.index')
                    </div>
                    <div class="card-footer text-center">
                        <a href="/order/purchase/mark/distributed/{{$purchaseOrder->id}}" class="btn btn-danger">Mark as Distributed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
