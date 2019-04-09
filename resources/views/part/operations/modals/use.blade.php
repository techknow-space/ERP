<div class="modal fade" id="use-part-in-repair-dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Use Part in Repair</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This action will reduce the Stock of the Part by 1. <br><br>

                    <div class="form-group">
                        <label for="partOperationUseWONumber">Work Order:</label>
                        <input min="0" step="1" type="number" placeholder="WO#" id="partOperationUseWONumber" class="form-control">
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary partButtonDecrease" >Use Part</button>
            </div>
        </div>
    </div>
</div>
