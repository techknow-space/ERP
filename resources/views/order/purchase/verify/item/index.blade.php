<div class="row align-content-center">
    <table id="poItemsVerifyTable" class="table" data-poid="{{$purchaseOrder->id}}">
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
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->PurchaseOrderItems->sortBy(function ($part,$key){
                return strtolower($part['Part']['devices']['brand']['name'].' '.$part['Part']['devices']['model_name'].' '.$part['Part']['part_name']);
            }) as $po_item)

                <tr
                    id="{{$po_item->id}}"
                    class="poItemsVerifyTableItemRow
                    @if(0 == $po_item->qty_received - $po_item->qty)
                        table-success
                    @elseif(0 > $po_item->qty_received - $po_item->qty)
                        table-danger
                    @elseif(0 < $po_item->qty_received - $po_item->qty)
                        table-warning
                    @endif
                    "
                    style="color: black;"
                >

                    <td class="poItemsVerifyTableItemRowSKU">
                        {{$po_item->Part->sku}}
                    </td>
                    <td class="poItemsVerifyTableItemRowBrand">
                        {{$po_item->Part->devices->brand->name}}
                    </td>
                    <td class="poItemsVerifyTableItemRowModel">
                        {{$po_item->Part->devices->model_name}}
                    </td>
                    <td class="poItemsVerifyTableItemRowPart">
                        {{$po_item->Part->part_name}}
                    </td>
                    <td class="poItemsVerifyTableItemRowQty">
                        {{$po_item->qty}}
                    </td>
                    <td class="poItemsVerifyTableItemRowQtyReceived">
                        {{$po_item->qty_received}}
                    </td>
                    <td class="poItemsVerifyTableItemRowQtyDiff">
                        {{$po_item->qty_received - $po_item->qty}}
                    </td>

                </tr>

            @endforeach
        </tbody>
    </table>
</div>
