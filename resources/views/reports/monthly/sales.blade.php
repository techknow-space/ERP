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
                            <thead><tr><th>#</th><th>Brand</th><th>Model</th><th>Part</th><th>Sold</th></tr><thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($sales as $part)
                                    <?php $partModel = App\Models\Part::find($part->part_id); ?>
                                    
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $partModel->device->brand->name }}</td>
                                        <td>{{ $partModel->device->model_name }}</td>
                                        <td>{{ $partModel->part_name }}</td>
                                        <td><a href="/sales/part/{{ $part->part_id }}">{{ $part->count }}</a></td>
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
