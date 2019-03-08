<div class="modal fade" id="purchaseOrderPaymentFormModal" tabindex="-1" role="dialog" aria-labelledby="purchaseOrderPaymentForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <form action="/order/purchase/payment/create" method="post" id="purchaseOrderPaymentForm">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="poPaymentTransactionDate" class="col-form-label">Transaction Date:</label>
                                    <input type="text" class="form-control" name="poPaymentTransactionDate" id="poPaymentTransactionDate" data-toggle="datetimepicker" data-target="#poPaymentTransactionDate">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="poPaymentValueUSD" class="col-form-label">Value CAD:</label>
                                    <input type="number" step="0.01" min="0" name="poPaymentValueCAD" id="poPaymentValueCAD">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poPaymentValueUSD" class="col-form-label">Value USD:</label>
                                    <input type="number" step="0.01" min="0" name="poPaymentValueUSD" id="poPaymentValueUSD">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="poPaymentExchangeRateCAD" class="col-form-label">Exchange Rate to CAD:</label>
                                    <input type="number" step="0.01" min="0" name="poPaymentExchangeRateCAD" id="poPaymentExchangeRateCAD">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="PoPaymentTransactionDetails" class="col-form-label">Transaction Details</label>
                                <textarea class="form-control" name="PoPaymentTransactionDetails" id="PoPaymentTransactionDetails"></textarea>
                            </div>
                            @csrf

                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary">Update</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#purchaseOrderPaymentFormModal">Payment Details</button>
