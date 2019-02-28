@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Imported Sales Data
                </div>
                <div class="card-body">
                    <pre>
                        {{var_dump($not_found)}}
                    </pre>

                </div>
            </div>
        </div>
    </div>
@endsection
