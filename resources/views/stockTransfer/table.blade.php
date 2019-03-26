@php
    $stockTransfers = \App\Http\Controllers\StockTransfer\StockTransferController::getFilteredStockTransfers($filter);
@endphp
<table id="stockTransfersActive" class="table">
    <thead>
    <tr>
        <th>
            #
        </th>
        <th>
            Details
        </th>
        <th>
            From
        </th>
        <th>
            To
        </th>
        <th>
            Status
        </th>
        <th>
            Total SKUs
        </th>
        <th>
            Total Qty
        </th>
        <th>
            Created At
        </th>
        <th>
            Last Edited At
        </th>
        <th>
            Actions
        </th>
    </tr>
    </thead>
    <tbody>
    @if(0 < $stockTransfers->count())
        @foreach($stockTransfers as $stockTransfer)
            <tr id="{{$stockTransfer->id}}">

                <td>
                    {{$stockTransfer->number}}
                </td>
                <td>
                    {{$stockTransfer->description}}
                </td>
                <td>
                    {{$stockTransfer->fromLocation->location_code}}
                </td>
                <td>
                    {{$stockTransfer->toLocation->location_code}}
                </td>
                <td>
                    {{$stockTransfer->Status->status}}
                </td>
                <td>
                    {{$stockTransfer->Items->count()}}
                </td>
                <td>
                    {{$stockTransfer->Items->sum('qty')}}
                </td>
                <td>
                    {{$stockTransfer->created_at}}
                </td>
                <td>
                    {{$stockTransfer->updated_at}}
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="/stocktransfer/edit/{{$stockTransfer->id}}" class="btn btn-info">
                            Edit
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>
                No Transfers to List
            </td>
        </tr>
    @endif
    </tbody>
</table>
