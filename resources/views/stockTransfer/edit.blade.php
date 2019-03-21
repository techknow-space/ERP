@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Transfer Order: <b>{{$stockTransfer->number}}</b>
                        <div class="float-right">
                            Last Updated: {{$stockTransfer->updated_at}}
                        </div>
                    </div>
                    @include('stockTransfer.items.index')
                </div>
            </div>
        </div>
    </div>
@endsection
