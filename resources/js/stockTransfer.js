/* Start stockTransfer.js */
$(document).ready(function() {
    let STcreateFormLocationFromSelect = $('#stockTransferFrom');
    let STcreateFormLocationToSelect = $('#stockTransferTo');

    STcreateFormLocationFromSelect.on('change',function () {
        if(STcreateFormLocationFromSelect.val() === STcreateFormLocationToSelect.val()){
            STcreateFormLocationToSelect.prop('selectedIndex',0);
        }
    });

    STcreateFormLocationToSelect.on('change',function () {
        if(STcreateFormLocationToSelect.val() === STcreateFormLocationFromSelect.val()){
            STcreateFormLocationFromSelect.prop('selectedIndex',0);
        }
    });

    let STeditFormTransferStatus = $('#stockTransferStatus');

    STeditFormTransferStatus.on('change',function () {
        submitStockTransferEditForm();
    });

    $('#stItemsTablePartSelect').select2({
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

    $('#stItemsTablePartSearchAddForm').submit(function (e) {

        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let data = getFormDataJson(form);

        $.ajax({
            type: "POST",
            url: url,
            async : false,
            data: data,
            success: function(data)
            {
                if(!data.result){
                    alert(data.message);
                }else{
                    window.location.reload();
                }
            }
        });

    });

    let stoItemsTable = $('#stoItemsTable');

    stoItemsTable.on('click','.stoItemInlineFunctionButton',function () {
        let action = $(this).data('action');
        let po_item_id = $(this).closest('tr').attr('id');
        editSTOItemRow(action,po_item_id);
    });

    stoItemsTable.on('keydown','.stoItemEditableField',function(e){
        if(13 === e.which){
            $(this).blur();
            let saveBtnID = '#stoItemSaveBtn-'+$(this).closest('tr').attr('id');
            $(saveBtnID).click();
        }
    });

    stoItemsTable.on('click','.stoItemEditableField',function(){
        if(!$('.stoItemEditableField').is(":focus")){
            $(this).focus();
        }
    });

    stoItemsTable.on('focus','.stoItemEditableField',function(){
        $(this).prop("readonly",false);
    });

    stoItemsTable.on('blur','.stoItemEditableField',function(){
        $(this).prop("readonly",true);
    });

    stoItemsTable.on('change','.stoItemEditableField',function(){
        if($(this).val() !== $(this).data('value')){
            let saveBtnID = '#stoItemSaveBtn-'+$(this).closest('tr').attr('id');
            $(saveBtnID).removeClass('d-none');
        }
        else {
            let saveBtn = $('#stoItemSaveBtn-'+$(this).closest('tr').attr('id'));
            if(!saveBtn.hasClass('d-none')){
                saveBtn.addClass('d-none');
            }
        }
    });

    $('.stockTransferDeleteButton').on('click',function (e) {
        if(!confirm('Are you sure ?. This action cannot be reversed')){
            e.preventDefault();
        }
    });

    let stoVerifyBarcodeEntryForm = $('#stockTransferVerifyBarcodeEntryForm');

    stoVerifyBarcodeEntryForm.on('submit',function (e) {

        let barcode_box = $('#stockTransferVerifyBarcodeEntry');
        barcode_box.attr("disabled", "disabled");

        let sku = barcode_box.val();
        let sto_id = $(this).data('stoid');
        let direction = $(this).data('direction');
        let input_class_to_update = '';

        $.ajax({
            type: "PUT",
            url: $(this).attr('action'),
            data: {"sto_id":sto_id,"sku":sku},
            success: function(data)
            {
                if(data.error){
                    alert('There was an Error !!!')
                }
                else{
                    let row = $('#'+data.item.id);

                    if('send' === direction){
                        input_class_to_update = 'stoItemQtySentField';
                        $('.stockTransferTotalQty').text(data.summary.total_qty);
                        $('.stockTransferTotalQtyNotSent').text(data.summary.total_qty_not_sent);
                    }
                    else{
                        input_class_to_update = 'stoItemQtyReceivedField';
                    }

                    row.find('.'+input_class_to_update).val(data.item.qty_sent);

                    if(!row.hasClass(data.item.class)){

                        row.removeClass (function (index, className) {
                            return (className.match (/(^|\s)table-\S+/g) || []).join(' ');
                        });

                        row.addClass(data.item.class);
                    }

                    row.scroll();

                }
                barcode_box.removeAttr("disabled");
                barcode_box.val('');
                barcode_box.focus();
            }
        });

        e.preventDefault();
    });


});

function submitStockTransferEditForm() {
    let STeditForm = $('#stockTransferEditForm');
    STeditForm.submit();
}

function getFormDataJson(form) {
    let unindexedArray = form.serializeArray();
    let indexedArray = {};

    $.map(unindexedArray, function (n ,i) {
        indexedArray[n['name']] = n['value'];
    });

    return indexedArray;
}

function editSTOItemRow(action,sto_item_id) {
    if( 'delete' === action ){
        if(confirm('Are You Sure to Delete this Item?')){
            $.ajax({
                type: "DELETE",
                url: '/stocktransfer/item/delete/'+sto_item_id,
                success: function(data)
                {
                    if(true === data){
                        alert('There was an error in Deleting this ROW. Please try again');
                    }
                    else{
                        $("#"+sto_item_id).remove();
                    }
                }
            });
        }
    }
    else if( 'save' === action ){

        let qty = parseInt($('#stoItemQty-'+sto_item_id).val());

        if(0 === qty){
            editSTOItemRow('delete',sto_item_id);
        }else{
            $.ajax({
                type: "PUT",
                url: '/stocktransfer/item/update/'+sto_item_id,
                data: {"qty":qty}, // serializes the form's elements.
                success: function(data)
                {
                    let qty_input_box = $('#stoItemQty-'+sto_item_id);
                    let save_btn = $('#stoItemSaveBtn-'+sto_item_id);

                    if(data.result){
                        qty_input_box.data('value',qty);
                        save_btn.addClass('d-none');
                    }
                    else{
                        alert(data.message);
                        qty_input_box.val(qty_input_box.data('value'));
                        qty_input_box.focus();
                        save_btn.addClass('d-none');

                    }
                }
            });
        }

    }
}
/* End stockTransfer.js */
