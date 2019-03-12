@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Add New Supplier</b>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="/order/supplier/create" method="post">
                                <div class="form-group row">
                                    <label for="supplier-name" class="col-md-2 col-form-label">Name</label>
                                    <div class="col-md-8">
                                        <input name="supplier-name" type="text" class="form-control" required>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="supplier-country" class="col-md-2 col-form-label">Country</label>
                                    <div class="col-md-8">
                                        <input name="supplier-country" type="text" class="form-control" required>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="supplier-lead-time" class="col-md-2 col-form-label">Lead Time</label>
                                    <div class="col-md-8">
                                        <input name="lead-time" type="number" class="form-control" required>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="supplier-payment-details" class="col-md-2 col-form-label">Payment Details</label>
                                    <div class="col-md-8">
                                        <input name="supplier-payment-details" type="text" class="form-control" required>
                                    </div>
                                </div>
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
