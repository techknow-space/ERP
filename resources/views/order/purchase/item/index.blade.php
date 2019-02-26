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
    </tr>
    </thead>
    <tbody>
        @foreach($purchase_order->PurchaseOrderItems as $po_item)
            <tr data-po_line_id="{{$po_item->id}}">
                <td>
                    {{$po_item->Part->sku}}
                </td>
                <td>
                    {{$po_item->Part->devices->brand->name}} {{$po_item->Part->devices->model_name}} {{$po_item->Part->part_name}}
                </td>
                <td>
                    {{$po_item->cost}}
                </td>
                <td>
                    {{$po_item->qty}}
                </td>
            </tr>
        @endforeach
    </tbody>


</table>
    <div id="poItemsTablePartSearchAdd">
        <form class="form-inline" id="poItemsTablePartSearchAddForm" action="/order/purchase/item/create">

            <select name="poItemsTablePartSelect" id="poItemsTablePartSelect" class="form-control">
                <option value="0669b13f-2b34-4ccd-86b8-0864798b873b">Xiaomi Redmi Note 4X Screen Assembly Black</option>
                <option value="0c89dd54-29bb-4299-b803-306131e51324">Microsoft Surface Pro 2 Screen Assembly</option>
                <option value="115f275b-cfc8-45bb-9fed-b996ea4a70d3">Blackberry Classic Q20 Screen AssemblyÃ Black</option>
            </select>

            &nbsp;&nbsp;&nbsp;
            <label class="sr-only" for="poItemsAddQty">Qty</label>
            <input type="number" class="form-control" id="poItemsAddQty" placeholder="Qty">

            &nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary mb-2">Add</button>

        </form>
    </div>
</div>
