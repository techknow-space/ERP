@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Add PO Payment Transaction</b>
                    </div>
                    <div class="card-body">
                        <form action="/order/purchase/payment/create" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-4">

                                </div>
                            </div>
                            @csrf
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
