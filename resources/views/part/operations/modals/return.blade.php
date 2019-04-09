
<div class="modal fade" id="return-part-from-repair-dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Return Part from Repair</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This action will increase the Stock of the Part by 1. <br>

                    <div class="form-group">
                        <label for="partOperationReturnWONumber">Work Order:</label>
                        <input min="0" step="1" type="number" placeholder="WO#" id="partOperationReturnWONumber" class="form-control">
                    </div>

                Please make sure that the Part is returned to Appropriate location.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary partButtonIncrease">Return Part</button>
            </div>
        </div>
    </div>
</div>
