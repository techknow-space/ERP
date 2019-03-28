@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Select task
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Inventory</div>

                <div class="card-body">
                    <div class="list-group">
                        <a href="/" class="list-group-item list-group-item-action">TASK</a>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
