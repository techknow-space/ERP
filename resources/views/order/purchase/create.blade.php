@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Create Purchase Order</b>
                    </div>
                    <div class="card-body">
                        <form action="/order/purchase/create" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="poSupplier">Select Supplier</label>
                                    <select name="poSupplier" id="poSupplier" class="form-control">
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}} {{$supplier->country}}</option>
                                        @endforeach
                                    </select>
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
