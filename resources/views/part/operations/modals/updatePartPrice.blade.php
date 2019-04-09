<div class="modal fade" id="update-part-price-dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Part Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" class="form-inline ajaxUpdateForm" data-entity_id="{{$part->id}}" data-entity="PartPrice">
                <div class="modal-body">
                    <div class="form-group">
                        <div>
                            <label for="selling_price_b2c">Price: &nbsp;</label>
                            <input min="1" step="0.01" type="number" placeholder="Price" data-attributename="selling_price_b2c" data-value="{{$part->price->selling_price_b2c}}" name="selling_price_b2c" class="form-control" value="{{$part->price->selling_price_b2c}}">
                        </div>
                        <!--
                        <div>
                            <label for="selling_price_b2b">Selling Price: &nbsp;</label>
                            <input min="1" step="0.01" type="number" placeholder="Price" data-attributename="selling_price_b2b" data-value="{{$part->price->selling_price_b2b}}" name="selling_price_b2b" class="form-control" value="{{$part->price->selling_price_b2b}}">
                        </div>
                        -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
