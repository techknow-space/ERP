@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card row">
                    <div class="card-header">LookUp Master</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="brand">Brand <span class="required">*</span></label>
                                <select name="brand" class="form-control" id="brand">
                                    <option value="">-- Select Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ ucfirst($brand->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="model">Model </label>
                                <select name="model" class="form-control" id="model">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="part">Parts </label>
                                <select name="part" class="form-control" id="part">
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <br>
                <div class="card row">
                    <div class="card-header">Part Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="part-details">
                                    <table class="table" id="part-details-table" style="display: none;">
                                        <thead>
                                        <tr>
                                            <th scope="col">Part</th>
                                            <th scope="col">Last Cost</th>
                                            <th scope="col">Selling Price</th>
                                            <th scope="col">Qty-S1</th>
                                            <th scope="col">Qty-TO1</th>
                                            <th>SKU</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td class="part-details-table-col-part-name"></td>
                                                <td class="part-details-table-col-last-cost"></td>
                                                <td class="part-details-table-col-selling-price"></td>
                                                <td class="part-details-table-col-Qty-s1"></td>
                                                <td class="part-details-table-col-qty-to1"></td>
                                                <td class="part-details-table-col-sku"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

