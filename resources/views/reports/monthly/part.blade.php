@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="font-size: 1.2em;"><b>Sales History</b></div>

                    <div class="card-body">
                        <h4>{{ App\Models\WODevicePart::oldest()->first()->created_at }} - {{ App\Models\WODevicePart::latest()->first()->created_at }}</h4>
                        <table class="table table-sm table-dark table-bordered table-hover" id="sortable">
                            <thead><tr><th>Month</th><th>Brand</th><th>Model</th><th>Part</th><th>Count</th></tr><thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($sales as $month)
                                    <?php $partModel = App\Models\Part::find($part); ?>
                                    <tr>
                                        <td>{{ $month->monthyear }}</td>
                                        <td>{{ $partModel->device->brand->name }}</td>
                                        <td>{{ $partModel->device->model_name }}</td>
                                        <td>{{ $partModel->part_name }}</td>
                                        <td>{{ $month->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
