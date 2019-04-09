<div class="row secondrow">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header" style="font-size: 1.2em;"><b>Operations</b></div>

            <div class="card-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#use-part-in-repair-dialog">Use Part in Repair</a>
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#return-part-from-repair-dialog">Return Part from Repair</a>
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="">Store in Defective Bin</a>
                    <div class="input-group list-group-item list-group-item-action">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Part Edit Operations</button>
                            <div class="dropdown-menu ">
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#update-part-price-dialog">Update Part Price</a>
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#update-part-name-dialog">Update Part Name</a>
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#update-part-stock-dialog">Update Part Stock</a>
                            </div>
                        </div>
                    </div>

                    <!-- <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#return-part-from-repair-dialog">Return Part to Defective</a> -->

                    <!-- <a href="#" class="list-group-item list-group-item-action">Begin Stock Count</a> -->
                    <!-- <a href="#" class="list-group-item list-group-item-action">Edit Part Details</a> -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('part.operations.modals.use')
@include('part.operations.modals.return')
@include('part.operations.modals.updatePartPrice')
@include('part.operations.modals.updatePartName')
@include('part.operations.modals.updatePartStock')
