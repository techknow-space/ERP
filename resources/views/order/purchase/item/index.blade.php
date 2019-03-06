<div class="row align-content-center">
    <b>Parts</b>
    <table id="poItemsTable" class="table" data-poid="{{$purchase_order->id}}">
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
            <tr
                id="{{$po_item->id}}"
                @if($po_item->is_edited)
                    class="table-warning" style="color: black; background-color: #ffeeba"
                @endif
            >
                <td>
                    {{$po_item->Part->sku}}
                </td>
                <td>
                    {{$po_item->Part->devices->brand->name}}
                </td>
                <td>
                    {{$po_item->Part->devices->model_name}}
                </td>
                <td>
                     {{$po_item->Part->part_name}}
                </td>

                    <td>
                        <input type="number" step="0.01" min="0" class="form-control poItemEditableField" name="poItemCost" id="poItemCost-{{$po_item->id}}" data-value="{{$po_item->cost}}" value="{{$po_item->cost}}" readonly='readonly'>
                    </td>
                    <td>
                        <input type="number" step="1" min="0" class="form-control poItemEditableField" name="poItemQty" id="poItemQty-{{$po_item->id}}" data-value="{{$po_item->qty}}" value="{{$po_item->qty}}" readonly='readonly'>
                    </td>

                    <td>
                        <i class="fas fa-check-circle poItemInlineFunctionButton d-none" id="poItemSaveBtn-{{$po_item->id}}" data-action="save"></i>
                        <i class="fas fa-trash-alt poItemInlineFunctionButton" id="poItemDeleteBtn-{{$po_item->id}}" data-action="delete"></i>
                    </td>

            </tr>
        @endforeach
    </tbody>


</table>
    <div id="poItemsTablePartSearchAdd">
        <form class="form-inline" id="poItemsTablePartSearchAddForm" action="/order/purchase/item/create">
            <div class="form-group mb-2">
                <select name="poItemsTablePartSelect" id="poItemsTablePartSelect" class="form-control">
                    <option value="0">Please Search for a Part</option>
                </select>
            </div>
            <div class="form-group mb-2">
                <label class="sr-only" for="poItemsAddQty">Qty</label>
                <input type="number" class="form-control" id="poItemsAddQty" placeholder="Qty" required>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Add</button>

        </form>
    </div>
</div>
