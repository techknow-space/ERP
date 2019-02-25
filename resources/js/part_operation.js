/* Start Part Operation JS*/
$(document).ready(function() {
    $('.partButtonDecrease').on('click', function(){
        start_part_stock_decrease();
    })

    $('.partButtonIncrease').on('click', function(){
        start_part_stock_increase();
    })
});
function start_part_stock_increase() {
    let part_id = $('tbody tr').data('partid');
    increase_stock(part_id);
}

function increase_stock(part_id) {
    $.ajax({
        url: '/api/part/stock/increase/'+part_id,
        type: "PUT",
        dataType: "json",
        success:function(data) {
            $('#return-part-from-repair-dialog').modal('hide');
            location.reload();
            console.log(data);
        }
    });
}

function start_part_stock_decrease() {
    let part_id = $('tbody tr').data('partid');
    decrease_stock(part_id);
}

function decrease_stock(part_id) {
    $.ajax({
        url: '/api/part/stock/decrease/'+part_id,
        type: "PUT",
        dataType: "json",

        success:function(data) {
            $('#use-part-in-repair-dialog').modal('hide');
            location.reload();
            console.log(data);
        }
    });
}
/* End Part Operation JS*/
