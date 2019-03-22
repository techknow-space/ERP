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




});

function submitStockTransferEditForm() {
    let STeditForm = $('#stockTransferEditForm');
    STeditForm.submit();
}
/* End stockTransfer.js */
