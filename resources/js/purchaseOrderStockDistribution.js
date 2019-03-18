/* Start PO Distribute JS */

$(document).ready(function() {
    let poItemsDistributeTable  = $('#poItemsDistributeTable');

    poItemsDistributeTable.find('tbody tr').each(function(){

        let available = parseInt($(this).find('td.poItemsDistributeTableItemRowQtyReceived').text().trim());

        let distributed = 0;
        $(this).find('.poItemDistributeEditableField').each(function () {
            distributed += parseInt($(this).val());
        })

        if(available !== distributed){
            $(this).removeClass (function (index, className) {
                return (className.match (/(^|\s)table-\S+/g) || []).join(' ');
            });
            $(this).addClass('table-danger');
        }
    });

    poItemsDistributeTable.on('click','.poItemDistributeEditableField',function(){
        if(!$('.poItemDistributeEditableField').is(":focus")){
            $(this).focus();
        }
    });

    poItemsDistributeTable.on('focus','.poItemDistributeEditableField',function(){
        if(!$(this).hasClass('border-info')){
            $(this).addClass('border-info');
        }
        $(this).prop("readonly",false);
    });

    poItemsDistributeTable.on('blur','.poItemDistributeEditableField',function(){
        if($(this).hasClass('border-info')){
            $(this).removeClass('border-info');
            $(this).prop("readonly",true);
        }
    });

    poItemsDistributeTable.on('change','.poItemDistributeEditableField',function(){

        let oldValue = parseInt($(this).data('oldvalue'));
        let newValue = parseInt($(this).val());

        if(oldValue !== newValue){

            let row = $(this).closest('tr');

            let available = parseInt(row.find('td.poItemsDistributeTableItemRowQtyReceived').text().trim());

            if(newValue > -1){

                if((available - newValue) >= 0){
                    let locations = ['S1','TO'];
                    let currentLocation = $(this).parent().prop('className').split('-');
                    currentLocation = [currentLocation[1]];

                    let otherLocation = locations.filter(e=>!currentLocation.includes(e));

                    let otherLocationInput = row.find('td.poItemsDistributeTableItemRowScanned-'+otherLocation[0]+' input');

                    let otherLocationValue = available - newValue;
                    otherLocationInput.val(otherLocationValue);

                    $(this).data('oldvalue',newValue);
                    otherLocationInput.data('oldvalue',otherLocationValue);

                    let location_values = {};
                    location_values[currentLocation[0]] = newValue;
                    location_values[otherLocation[0]] = otherLocationValue;

                    editPODistributionRow(row.attr('id'),location_values);

                }else{
                    $(this).val(oldValue);
                    alert('Sending more Qty than received!!! Please Check');
                }
            }
            else{
                $(this).val(oldValue);
                alert('Error!!!');
            }
        }
    });
});

function editPODistributionRow(po_item_id,location_values) {

    let poid = $('table').data('poid');

    $.ajax({
        type: "PUT",
        url: '/order/purchase/distribute/item/edit',
        data: {
            "purchaseOrder_id":poid,
            "purchaseOrderItem_id": po_item_id,
            "location_values": location_values
        },
        success: function (data) {
            if(data.error){
                alert('There was an error updating the Database Entry');
            }
            else{
                let row = $('#'+po_item_id);
                row.removeClass (function (index, className) {
                    return (className.match (/(^|\s)table-\S+/g) || []).join(' ');
                });
                row.addClass('table-success');

                console.log(data);
            }
        }

    });


}

/* End PO Distribute JS */
