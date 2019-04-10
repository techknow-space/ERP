<div class="card">
    <!--
    <div class="card-header">
        <b>Scan - {{$barcodeBoxTitle}}</b>
    </div>
    -->
    <div class="card-body">
        <div class="barcode-box">
            <form
                id="stockTransferVerifyBarcodeEntryForm"
                action="{{$barcodeBoxFormActionUrl}}"
                data-stoid="{{$stockTransfer->id}}"
                data-direction="{{$barcodeBoxDirection}}"
            >
                <label for="stockTransferVerifyBarcodeEntry">Barcode / SKU</label>
                <input type="number" min="99999999" step="1" id="stockTransferVerifyBarcodeEntry" class="form-control">
            </form>
        </div>
    </div>
</div>
<br>
