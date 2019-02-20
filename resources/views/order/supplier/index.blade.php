@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Suppliers</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Lead Time</th>
                                    <th scope="col">Payment Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (0 < $suppliers->count())
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td>
                                                {{$supplier->name}}
                                            </td>
                                            <td>
                                                {{$supplier->country}}
                                            </td>
                                            <td>
                                                {{$supplier->lead_time}}
                                            </td>
                                            <td>
                                                {{$supplier->payment_details}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            No Suppliers
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
