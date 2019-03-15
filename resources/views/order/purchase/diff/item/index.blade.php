<div class="row align-content-center">
    <table id="poItemsDiffTable" class="table" data-podiffid="{{$purchaseOrderDiff->id}}">
        <thead>
            <tr>
                <th>
                    SKU
                </th>
                <th>
                    Brand
                </th>
                <th>
                    Model
                </th>
                <th>
                    Part
                </th>
                <th>
                    Qty Ordered
                </th>
                <th>
                    Qty Received
                </th>
                <th>
                    Diff Qty
                </th>
                <th>
                    Diff Value
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrderDiff->PurchaseOrderDiffItems as $item)
                <tr>
                    <td>
                        {{$item->Part->sku}}
                    </td>
                    <td>
                        {{$item->Part->devices->brand->name}}
                    </td>
                    <td>
                        {{$item->Part->devices->model_name}}
                    </td>
                    <td>
                        {{$item->Part->part_name}}
                    </td>
                    <td>
                        {{$item->qty_paid_for}}
                    </td>
                    <td>
                        {{$item->qty_received}}
                    </td>
                    <td>
                        {{$item->qty_diff}}
                    </td>
                    <td>
                        {{$item->value_diff}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
