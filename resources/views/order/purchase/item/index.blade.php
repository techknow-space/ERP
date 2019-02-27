<div class="row align-content-center">
    <b>Parts</b>
    <table id="poItemsTable" class="table" data-poid="{{$purchase_order->id}}">
    <thead>
    <tr>
        <th>
            SKU
        </th>
        <th>
            Part Name
        </th>
        <th>
            Cost
        </th>
        <th>
            Qty
        </th>
        <th>
            Change
        </th>
    </tr>
    </thead>
    <tbody>
        @foreach($purchase_order->PurchaseOrderItems as $po_item)
            <tr id="{{$po_item->id}}" data-po_line_id="{{$po_item->id}}">
                <td>
                    {{$po_item->Part->sku}}
                </td>
                <td>
                    {{$po_item->Part->devices->brand->name}} {{$po_item->Part->devices->model_name}} {{$po_item->Part->part_name}}
                </td>
                <form data-poItemID="{{$po_item->id}}">
                    <td>
                        <input class="form-control poItemEditableField" data-poitemid="{{$po_item->id}}" name="poItemCost" id="poItemCost-{{$po_item->id}}" value="{{$po_item->cost}}" readonly='readonly'>
                    </td>
                    <td>
                        <input class="form-control poItemEditableField" data-poitemid="{{$po_item->id}}" name="poItemQty" id="poItemQty-{{$po_item->id}}" value="{{$po_item->qty}}" readonly='readonly'>
                    </td>

                    <td>
                        @csrf
                        <i class="fas fa-check-circle poItemInlineFunctionButton" id="poItemSaveBtn-{{$po_item->id}}" data-action="save" data-poItemID="{{$po_item->id}}" style="display: none"></i>
                        <i class="fas fa-trash-alt poItemInlineFunctionButton" id="poItemDeleteBtn-{{$po_item->id}}" data-action="delete" data-poItemID="{{$po_item->id}}"></i>
                    </td>
                </form>
            </tr>
        @endforeach
    </tbody>


</table>
    <div id="poItemsTablePartSearchAdd">
        <form class="form-inline" id="poItemsTablePartSearchAddForm" action="/order/purchase/item/create">

            <select name="poItemsTablePartSelect col-md-3" id="poItemsTablePartSelect" class="form-control">
                <option value="0">Please Search for a Part</option>
            </select>

            &nbsp;&nbsp;&nbsp;
            <label class="sr-only" for="poItemsAddQty">Qty</label>
            <input type="number" class="form-control" id="poItemsAddQty" placeholder="Qty" required>

            &nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary mb-2">Add</button>

        </form>
    </div>
</div>
