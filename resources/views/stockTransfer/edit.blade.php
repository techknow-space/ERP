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
                    <div class="card-body">
                        <form action="/stocktransfer/update/{{$stockTransfer->id}}" class="row" method="post" id="stockTransferEditForm">
                            @method('put')
                            <div class="form-group col-md-4">
                                <label for="stockTransferDescription">Details</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="stockTransferDescription"
                                    name="stockTransferDescription"
                                    data-oldvalue="{{$stockTransfer->description}}"
                                    value="{{$stockTransfer->description}}"
                                >
                            </div>

                            <div class="form-group col-md-4">
                                <label for="stockTransferStatus">Status</label>
                                <select name="stockTransferStatus" id="stockTransferStatus" class="form-control">
                                    @foreach($statuses as $status)
                                        <option
                                            value="{{$status->id}}"
                                            @if($status->id == $stockTransfer->Status->id)
                                            selected
                                            @endif
                                        >
                                            {{$status->status}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @csrf

                        </form>
                        @include('stockTransfer.items.index')
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
