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

    <div class="card">
        <div class="card-header">
            <b>Parts</b>
        </div>
        <div class="card-body">
            <div class="row align-content-center">
                <table id="stItemsTable" class="table" data-stid="{{$stockTransfer->id}}">
                    <thead>
                    <tr>
                        <th>
                            SKU
                        </th>
                        <th>
                            Part
                        </th>
                        <th>
                            Qty
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockTransfer->Items->sortBy(function ($part,$key){
                    return strtolower($part['Part']['devices']['brand']['name'].' '.$part['Part']['devices']['model_name'].' '.$part['Part']['part_name']);
                })  as $item)
                        <tr>
                            <td>
                                {{$item->sku}}
                            </td>
                            <td>
                                {{$item->Part->devices->brand->name}} {{$item->Pat->devices->model_name}} {{$item->Part->part_name}}
                            </td>
                            <td>
                                {{$item->qty}}
                            </td>
                            <td>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div id="stItemsTablePartSearchAdd">
                <form class="form-inline row" id="stItemsTablePartSearchAddForm" action="/stocktransfer/item/create">
                    <div class="form-group col-md-6">
                        <select name="stItemsTablePartSelect" id="stItemsTablePartSelect" class="form-control" style="width: 100%">
                            <option value="">Please Search for a Part</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="sr-only" for="stItemsTablePartAddQty">Qty</label>
                        <input type="number" class="form-control" id="stItemsTablePartAddQty" placeholder="Qty" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add</button>

                </form>
            </div>
        </div>
    </div>

</div>
