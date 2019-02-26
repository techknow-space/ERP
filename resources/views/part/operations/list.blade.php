<div class="row secondrow">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header" style="font-size: 1.2em;"><b>Operations</b></div>

            <div class="card-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#use-part-in-repair-dialog">Use Part in Repair</a>
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#return-part-from-repair-dialog">Return Part to Inventory</a>
                    <a href="#" class="list-group-item list-group-item-action" data-toggle="modal" data-target="#return-part-from-repair-dialog">Return Part to Defective</a>
                    <a href="#" class="list-group-item list-group-item-action">Begin Stock Count</a>
                    <!-- <a href="#" class="list-group-item list-group-item-action">Edit Part Details</a> -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('part.operations.modals.use')
@include('part.operations.modals.return')
