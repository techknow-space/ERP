$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let appLocationSelect = $('#appLocationSelect');

    appLocationSelect.on('change',function(){
        let location_id = appLocationSelect.val();
        window.location.replace('/setLocation/'+location_id);
    });

});
