/*PO JS*/
$(document).ready(function() {
    $('#poItemsTablePartSearchAddForm').submit(function (e) {

        let form = $(this);
        let url = form.attr('action');
        let part_id = $('#poItemsTablePartSelect').val();
        let qty = $('#poItemsAddQty').val();

        $.ajax({
            type: "POST",
            url: url,
            data: {"po_id":purchase_order_id,"part_id":part_id,"qty":qty}, // serializes the form's elements.
            success: function(data)
            {
                console.log(data);
                location.reload();
            }
        });
        e.preventDefault();
    });
});

