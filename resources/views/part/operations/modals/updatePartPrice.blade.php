<div class="modal fade" id="update-part-price-dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Part Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" class="form-inline ajaxOperationUpdate" data-entity_id="{{$part->id}}" data-entity="PartPrice">
                    <div class="form-group">
                        <label for="partOperationUseWONumber">Price: &nbsp;</label>
                        <input min="1" step="0.1" type="number" placeholder="Price" id="partOperationUseWONumber" name="selling_price_b2c" class="form-control" data-entity_id="{{$part->id}}" >
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
