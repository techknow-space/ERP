/*AjaxOperationsJS*/
$(document).ready(function() {
    let editableTable = $('.editableDataItems');
    let editableInputSelector = '.ajaxOperationEditBox';

    editableTable.on('focus',editableInputSelector,function () {
        $(this).prop("readonly",false);
    });

    editableTable.on('click',editableInputSelector,function () {
        if(!$(this).is(":focus")){
            $(this).prop("readonly",false);
        }
    });

    editableTable.on('blur',editableInputSelector,function () {
        $(this).prop("readonly",true);
    });

    editableTable.on('change',editableInputSelector,function () {

    });

    editableTable.on('keydown',editableInputSelector,function (e) {
        if(13 === e.which){
            if($(this).val() !== $(this).data('value')){
                triggerUpdate($(this));
            }
            $(this).blur();
        }
    })
});

function addRemoveBorder(payload) {
    if(payload.val() !== payload.data('value')){
        payload.addClass('border');
       payload.addClass('border-danger')
    }
    else{
        payload.removeClass('border');
        payload.removeClass('border-danger')
    }
}

function triggerUpdate(payload) {
    let entity = payload.data('entity');
    let entity_id = payload.data('entity_id');
    let attribute_name = payload.data('attributename');
    let value = payload.val();
    let attributes = {[attribute_name]: value};

    ajaxUpdateOperation(entity,entity_id,attributes);

}

function ajaxUpdateOperation(entity,entity_id,attributes) {

    $.ajax({
        type: "PUT",
        url: '/ajax/operation',
        data: {
            "entity" : entity,
            "entity_id" : entity_id,
            "attributes" : attributes
        },
        success: function (response) {
            console.log(response);
        }
    });
}
