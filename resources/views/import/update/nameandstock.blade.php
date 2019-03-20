@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Import Part Data
                </div>
                <div class="card-body">
                    <form action="/import/upload/stock_name" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="csv_upload_file">Select the File</label>
                            <input type="file" name="csv_upload_file">
                        </div>

                        @csrf

                        <button type="submit">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
