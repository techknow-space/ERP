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
    let STeditFormDescription = $('#stockTransferDescription');

    STeditFormTransferStatus.on('change',function () {
        submitStockTransferEditForm();
    });

});

function submitStockTransferEditForm() {
    let STeditForm = $('#stockTransferEditForm');
    STeditForm.submit();
}
/* End stockTransfer.js */
