

    <div class="card">

        <div class="card-body">
            <div class="row align-content-center">
                <table id="stoItemsTable" class="table editableDataItems" data-stid="{{$stockTransfer->id}}">
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
                            Total Sales - <b>{{$stockTransfer->fromLocation->location_code}}</b>
                        </th>
                        <th>
                            % Sales - <b>{{$stockTransfer->fromLocation->location_code}}</b>
                        </th>
                        <th>
                            InHand <b>{{$stockTransfer->fromLocation->location_code}}</b>
                        </th>
                        <th>
                            Total Sales - <b>{{$stockTransfer->toLocation->location_code}}</b>
                        </th>
                        <th>
                            % Sales - <b>{{$stockTransfer->toLocation->location_code}}</b>
                        </th>
                        <th>
                            InHand <b>{{$stockTransfer->toLocation->location_code}}</b>
                        </th>
                        <th>
                            Transfer Qty
                        </th>
                        @if(true)
                            <th>
                                Qty Sent
                            </th>
                        @endif
                        @if($status_id > 3)
                            <th>
                                Qty Received
                            </th>
                        @endif
                        @if($status_id < 5)
                            <th>
                                Actions
                            </th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    @inject('stats_controller','App\Http\Controllers\Statistics\SalesAndTargetsController')
                    @inject('stockTransferController','App\Http\Controllers\StockTransfer\StockTransferController')

                    @php
                        $items = $stockTransfer->Items->sortBy(function ($part,$key){
                                                                return strtolower($part['Part']['devices']['brand']['name'].' '.$part['Part']['devices']['model_name'].' '.$part['Part']['part_name']);
                                                            });

                        if(4 == $stockTransfer->Status->seq_id){
                            $items = $items->filter(function($value,$key){
                                return $value['qty_sent'] > 0;
                            });
                        }
                    @endphp

                    @foreach($items as $item)

                        <tr id="{{$item->id}}"
                            style="color:black;"
                            data-entity="StockTransferItem"
                            class="
                            @if(4 > $stockTransfer->Status->seq_id)
                                {{$stockTransferController::getStockTransferItemDisplayLineColourClassSending($item)}}
                            @else
                                {{$stockTransferController::getStockTransferItemDisplayLineColourClassReceiving($item)}}
                            @endif
                            ">
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
                                {{$stats_controller::totalSalesPastFotMonthsForLocations($item->Part, $stockTransfer->fromLocation)}}
                            </td>
                            <td>
                                {{$stats_controller::getSalesShareForMonthsForLocations($item->Part, $stockTransfer->fromLocation)}} %
                            </td>

                            <td>
                                {{$item->Part->Stocks->where('location_id',$stockTransfer->fromLocation->id)->first()->stock_qty}}
                            </td>

                            <td>
                                {{$stats_controller::totalSalesPastFotMonthsForLocations($item->Part, $stockTransfer->toLocation)}}
                            </td>
                            <td>
                                {{$stats_controller::getSalesShareForMonthsForLocations($item->Part, $stockTransfer->toLocation)}} %
                            </td>

                            <td>
                                {{$item->Part->Stocks->where('location_id',$stockTransfer->toLocation->id)->first()->stock_qty}}
                            </td>

                            <td>
                                {{$item->qty}}
                            </td>

                            <td>
                                @if($status_id <= 3)
                                    <input type="number" step="1" min="0" class="form-control ajaxOperationEditBox stoItemQtySentField" data-entity="StockTransferItem" data-entity_id="{{$item->id}}" data-attributename="qty_sent" name="stoItemQtySent" id="stoItemQtySent-{{$item->id}}" data-value="{{$item->qty_sent}}" value="{{$item->qty_sent}}" readonly='readonly'>
                                @else
                                    {{$item->qty_sent}}
                                @endif
                            </td>

                            @if($status_id > 3)
                                <td>
                                    <input type="number" step="1" min="0" class="form-control ajaxOperationEditBox stoItemQtyReceivedField" name="stoItemQtyReceived" data-entity="StockTransferItem" data-entity_id="{{$item->id}}" data-attributename="qty_received" id="stoItemQtyReceived-{{$item->id}}" data-value="{{$item->qty_received}}" value="{{$item->qty_received}}" readonly='readonly'>
                                </td>
                            @endif

                            <td>
                                @if($status_id < 5)
                                    <i class="fas fa-check-circle stoItemInlineFunctionButton d-none" id="stoItemSaveBtn-{{$item->id}}" data-action="save"></i>
                                    @if($status_id < 3)
                                        <i class="fas fa-trash-alt stoItemInlineFunctionButton" id="stoItemDeleteBtn-{{$item->id}}" data-action="delete"></i>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            @if($status_id < 2)
            <div id="stItemsTablePartSearchAdd">
                <form class="form-inline row" id="stItemsTablePartSearchAddForm" action="/stocktransfer/item/add">

                    <div class="form-group col-md-6">
                        <select name="stItemsTablePartSelect" id="stItemsTablePartSelect" class="form-control" style="width: 100%" required>
                            <option value="">Please Search for a Part</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="sr-only" for="stItemsTablePartAddQty">Qty</label>
                        <input type="number" step="1" min="1" class="form-control" name="stItemsTablePartAddQty" id="stItemsTablePartAddQty" placeholder="Qty" required>
                    </div>

                    <input type="hidden" name="stoID" value="{{$stockTransfer->id}}">
                    @csrf
                    <button type="submit" class="btn btn-primary mb-2">Add</button>

                </form>
            </div>
            @endif
        </div>
    </div>

