@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Create Stock Transfer Order
                    </div>
                    <div class="card-body">
                        <form action="/stocktransfer/create" method="post" class="row">

                            <div class="form-group col-md-4">
                                <label for="stockTransferDescription">Details</label>
                                <input type="text" class="form-control" id="stockTransferDescription" name="stockTransferDescription">
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="stockTransferFrom">From</label>
                                <select class="form-control" id="stockTransferFrom" name="stockTransferFrom" required="required">
                                    <option value="" disabled="" selected="">Please select a Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->location_code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="stockTransferTo">To</label>
                                <select class="form-control" id="stockTransferTo" name="stockTransferTo" required="required">
                                    <option value="" disabled="" selected="">Please select a Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->location_code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @csrf
                            <button type="submit">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
