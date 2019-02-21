@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>{{$supplier->name}}</b>
                        <div class="float-right">
                            <a href="/order/supplier/edit/{{$supplier->id}}">Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                Country: {{$supplier->country}}
                            </div>
                            <div class="col-md-2">
                                Lead Time: {{$supplier->lead_time}} days
                            </div>
                            <div class="col-md-4">
                                Payment Details: {{$supplier->payment_details}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
