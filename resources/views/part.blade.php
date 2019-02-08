@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ERP</div>

                <div class="card-body">
                    <pre>
                        <?php var_dump($part); ?>
                    </pre>

                </div>
            </div>
        </div><br>

    </div>
</div>
@endsection
