<div class="modal fade" id="update-part-name-dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Part Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" class="form ajaxUpdateForm" data-entity_id="{{$part->id}}" data-entity="Part">
                    <div class="form-group">
                        <label for="current_part_name">Current Name: &nbsp;</label>
                        <input type="text" placeholder="Name" name="current_part_name" class="form-control" value="{{$part->part_name}}" readonly>

                        <label for="part_name">Updated Name: &nbsp;</label>
                        <input type="text" placeholder="Name" data-attributename="part_name" data-value="{{$part->part_name}}" name="part_name" class="form-control ajaxFormUpdateInput" value="{{$part->part_name}}">
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
