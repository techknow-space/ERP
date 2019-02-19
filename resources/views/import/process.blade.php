@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Import Part Data
                </div>
                <div class="card-body">
                    <pre>
                        {{print_r($path)}}
                    </pre>

                </div>
            </div>
        </div>
    </div>
@endsection
