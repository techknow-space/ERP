/*PO JS*/
$(document).ready(function() {



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


    let poItemsTable = $('#poItemsTable');


    poItemsTable.on('click','.poItemInlineFunctionButton',function () {
        let action = $(this).data('action');
        let po_item_id = $(this).data('poitemid');
        editPOItemRow(action,po_item_id);

    });

    /*
    $('.poItemInlineFunctionButton').on('click',function () {
        let action = $(this).data('action');
        let po_item_id = $(this).data('poitemid');
        editPOItemRow(action,po_item_id);

    });

     */

    poItemsTable.on('keydown','.poItemEditableField',function(e){
        if(13 === e.which){
            $(this).blur();
            let saveBtnID = '#poItemSaveBtn-'+$(this).data('poitemid');
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
        let saveBtnID = '#poItemSaveBtn-'+$(this).data('poitemid');
        $(saveBtnID).show();
    });

    poItemsTable.on('blur','.poItemEditableField',function(){
        $(this).prop("readonly",true);
    });




    /*

let poItemEditableField = $('.poItemEditableField');

    poItemEditableField.keypress(function (e) {
        if(13 === e.which){
            $(this).blur();
            let saveBtnID = '#poItemSaveBtn-'+$(this).data('poitemid');
            $(saveBtnID).click();
        }
    });



    poItemEditableField.on('click',function () {
        if(!poItemEditableField.is(":focus")){
            $(this).focus();
        }
    });



    poItemEditableField.on('focus',function () {
        $(this).prop("readonly",false);
        let saveBtnID = '#poItemSaveBtn-'+$(this).data('poitemid');
        $(saveBtnID).show();

    });



    poItemEditableField.on('blur',function () {
        $(this).prop("readonly",true);
    });

     */

});

function editPOItemRow(action,po_item_id) {
    if( 'delete' === action ){
        alert('Are You Sure ?');
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
                    $('#poItemSaveBtn-'+po_item_id).hide();
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
