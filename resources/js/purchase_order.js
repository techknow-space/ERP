/*PO JS*/
$(document).ready(function() {


    let poTableAcive = $('#purchaseOrderTableActive');
    poTableAcive.on('click','.purchaseOrderDeleteButton',function (e) {
        if(!confirm('Sure ?')){
            e.preventDefault();
        }
    });


    $('#poItemsTablePartSelect').select2({
        ajax:{
            url: "/search/ajax",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // search term
                };
            },
            processResults: function (data, params) {
                console.log(data);
                let parsed = [];
                data.forEach(function (part) {
                    parsed.push({id: part.id,text: part.devices.brand.name+' '+part.devices.model_name+' '+part.part_name+' '+part.sku});
                });
                return {
                    results: parsed,
                };
            },
            cache: true
        },
        placeholder: 'Search for a Part',
        minimumInputLength: 4,
    });

    $('#poItemsTablePartSearchAddForm').submit(function (e) {

        let form = $(this);
        let url = form.attr('action');
        let part_id = $('#poItemsTablePartSelect').val();
        let qty = $('#poItemsAddQty').val();

        $.ajax({
            type: "POST",
            url: url,
            async : false,
            data: {"po_id":purchase_order_id,"part_id":part_id,"qty":qty}, // serializes the form's elements.
            success: function(data)
            {
                console.log(data);
                location.reload();
            }
        });
        e.preventDefault();
    });

    let poStatus = $('#poStatus');
    let poPaymentStatus = $('#poPaymentStatus');
    let purchaseOrderForm = $('#purchaseOrderForm');

    poPaymentStatus.on('change',function () {
       purchaseOrderForm.submit();
    });

    poStatus.on('change',function () {
        purchaseOrderForm.submit();
    });

    let poItemsTable = $('#poItemsTable');


    poItemsTable.on('click','.poItemInlineFunctionButton',function () {
        let action = $(this).data('action');
        let po_item_id = $(this).closest('tr').attr('id');
        editPOItemRow(action,po_item_id);

    });

    poItemsTable.on('keydown','.poItemEditableField',function(e){
        if(13 === e.which){
            $(this).blur();
            let saveBtnID = '#poItemSaveBtn-'+$(this).closest('tr').attr('id');
            $(saveBtnID).click();
        }
    });

    poItemsTable.on('click','.poItemEditableField',function(){
        if(!$('.poItemEditableField').is(":focus")){
            $(this).focus();
        }
    });

    poItemsTable.on('focus','.poItemEditableField',function(){
        $(this).prop("readonly",false);
    });

    poItemsTable.on('blur','.poItemEditableField',function(){
        $(this).prop("readonly",true);
    });

    poItemsTable.on('change','.poItemEditableField',function(){
        if($(this).val() !== $(this).data('value')){
            let saveBtnID = '#poItemSaveBtn-'+$(this).closest('tr').attr('id');
            $(saveBtnID).removeClass('d-none');
        }
        else {
            let saveBtn = $('#poItemSaveBtn-'+$(this).closest('tr').attr('id'));
            if(!saveBtn.hasClass('d-none')){
                saveBtn.addClass('d-none');
            }
        }
    });

    let poPaymentTransactionDate = $('#poPaymentTransactionDate');
    poPaymentTransactionDate.datetimepicker({
        format: 'L'
    });

    let poPaymentForm = $('#purchaseOrderPaymentForm');

    poPaymentForm.on('submit',function (e) {
        var v = poPaymentForm.serialize();
        console.log(v);
        e.preventDefault();
    });

    let poVerifyBarcodeEntryForm = $('#purchaseOrderVerifyBarcodeEntryForm');

    poVerifyBarcodeEntryForm.on('submit',function (e) {

        let barcode_box = $('#purchaseOrderVerifyBarcodeEntry');
        barcode_box.attr("disabled", "disabled");
        let sku = barcode_box.val();

        $.ajax({
            type: "POST",
            url: '/order/purchase/receiveItem/'+sku+'/'+purchase_order_id,
            success: function(data)
            {
                if(data.error){
                    alert('There was an Error !!!')
                }
                else{
                    let row = $('#'+data.item.id);
                    row.find('.poItemsVerifyTableItemRowQtyReceived').text(data.item.qty_received);
                    row.find('.poItemsVerifyTableItemRowQtyDiff').text(data.item.diff);
                    if(!row.hasClass(data.item.class)){

                        row.removeClass (function (index, className) {
                            return (className.match (/(^|\s)table-\S+/g) || []).join(' ');
                        });

                        row.addClass(data.item.class);
                    }
                    if(data.distribution.error){
                        alert('Issue with Stock Allocation!!!');
                    }
                    else{
                        row.find('.poItemsVerifyTableItemRowScanned-'+data.distribution.location_code).text(data.distribution.scanned);
                        toastr.success(data.item.name+' is going to '+data.distribution.location_code, 'For Location: '+data.distribution.location_code,
                                {
                                    timeOut: 1000,
                                    progressBar: true,
                                    positionClass: "toast-top-center",
                                    closeButton: true
                                }
                            );
                        $('.poVerifySummarySKUScanned').text(data.summary.sku_scanned);
                        $('.poVerifySummaryQtyScanned').text(data.summary.qty_scanned);
                        $('.poVerifySummaryDiffQty').text(data.summary.diff_qty);
                        $('.poVerifySummaryDiffDollar').text(data.summary.diff_dollar);
                    }
                }
                barcode_box.removeAttr("disabled");
                barcode_box.val('');
                barcode_box.focus();
            }
        });

        e.preventDefault();
    });

    let poItemsVerifyTable = $('#poItemsVerifyTable');
    poItemsVerifyTable.on('change','.poItemsVerifyTableItemRowQtyReceived > input',function(){
        alert('Qty Changed');
    });

});

function editPOItemRow(action,po_item_id) {
    if( 'delete' === action ){
        if(confirm('Are You Sure to Delete this Item?')){
            $.ajax({
                type: "DELETE",
                url: '/order/purchase/item/delete/'+po_item_id,
                success: function(data)
                {
                    if(true === data){
                        alert('There was an error in Deleting this ROW. Please try again');
                    }
                    else{
                        $("#"+po_item_id).remove();
                    }
                }
            });
            console.log('Delete Entered');
        }
    }
    else if( 'save' === action ){


        let cost = $('#poItemCost-'+po_item_id).val();
        let qty = $('#poItemQty-'+po_item_id).val();

        $.ajax({
            type: "PUT",
            url: '/order/purchase/item/edit/'+po_item_id,
            data: {"cost":cost,"qty":qty}, // serializes the form's elements.
            success: function(data)
            {
                if(true === data){
                    alert('There was an error in updating this ROW. Please try again');
                }
                else{

                    $('#poItemCost-'+po_item_id).data('value',cost);
                    $('#poItemQty-'+po_item_id).data('value',qty);

                    $('#poItemSaveBtn-'+po_item_id).addClass('d-none');
                    let row = $('#'+po_item_id);
                    if(!row.hasClass('table-warning')){
                        row.addClass('table-warning');
                        row.css({
                            "color": "black",
                            "background-color": "#ffeeba"
                        });
                    }
                }
            }
        });
    }
}

/* End PO JS */
