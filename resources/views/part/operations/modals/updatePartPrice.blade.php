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
                <form action="" class="form ajaxUpdateForm" data-entity_id="{{$part->id}}" data-entity="PartPrice">
                    <div class="form-group">
                        <label for="current_selling_price_b2c">Current Price: &nbsp;</label>
                        <input min="1" step="0.01" type="number" placeholder="Price" data-attributename="selling_price_b2c" data-value="{{$part->price->selling_price_b2c}}" name="selling_price_b2c" class="form-control" value="{{$part->price->selling_price_b2c}}" readonly>
                        <label for="selling_price_b2c">New Price: &nbsp;</label>
                        <input min="1" step="0.01" type="number" placeholder="Price" data-attributename="selling_price_b2c" data-value="{{$part->price->selling_price_b2c}}" name="selling_price_b2c" class="form-control ajaxFormUpdateInput" value="{{$part->price->selling_price_b2c}}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
