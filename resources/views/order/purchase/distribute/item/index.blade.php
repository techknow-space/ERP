<div class="row align-content-center">
    <table id="poItemsDistributeTable" class="table" data-poid="{{$purchaseOrder->id}}">
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
            @foreach($locations = \App\Models\Location::all() as $location)
                <th>
                    Allotted For {{$location->location_code}}
                </th>
            @endforeach
            @foreach($locations = \App\Models\Location::all() as $location)
                <th>
                    Send to {{$location->location_code}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($purchaseOrder->PurchaseOrderItems->sortBy(function ($part,$key){
            return strtolower($part['Part']['devices']['brand']['name'].' '.$part['Part']['devices']['model_name'].' '.$part['Part']['part_name']);
        }) as $po_item)

            <tr
                id="{{$po_item->id}}"
                class="poItemsDistributeTableItemRow table-success"
                style="color: black;"
            >

                <td class="poItemsDistributeTableItemRowSKU">
                    {{$po_item->Part->sku}}
                </td>
                <td class="poItemsDistributeTableItemRowBrand">
                    {{$po_item->Part->devices->brand->name}}
                </td>
                <td class="poItemsDistributeTableItemRowModel">
                    {{$po_item->Part->devices->model_name}}
                </td>
                <td class="poItemsDistributeTableItemRowPart">
                    {{$po_item->Part->part_name}}
                </td>
                <td class="poItemsDistributeTableItemRowQty">
                    {{$po_item->qty}}
                </td>
                <td class="poItemsDistributeTableItemRowQtyReceived">
                    {{$po_item->qty_received}}
                </td>
                <td class="poItemsDistributeTableItemRowQtyDiff">
                    {{$po_item->qty_received - $po_item->qty}}
                </td>
                @foreach($locations as $location)
                    <td class="poItemsDistributeTableItemRowtoReceive-{{$location->location_code}}">
                        {{$po_item->PurchaseOrderDistributionItems->where('location_id',$location->id)->first()->qty_to_receive}}
                    </td>
                @endforeach
                @foreach($locations as $location)
                    <td class="poItemsDistributeTableItemRowScanned-{{$location->location_code}}">
                        <input class="poItemDistributeEditableField form-control" data-oldValue="{{$po_item->PurchaseOrderDistributionItems->where('location_id',$location->id)->first()->qty_scanned}}" value="{{$po_item->PurchaseOrderDistributionItems->where('location_id',$location->id)->first()->qty_scanned}}" readonly>
                    </td>
                @endforeach
            </tr>

        @endforeach
        </tbody>
    </table>
</div>
