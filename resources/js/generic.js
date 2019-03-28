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



    $('.dropdown-submenu').hover(function(e) {

        let submenu = $(this).children('a');
        let current_active = $('ul.active-device-list');

        current_active.attr('style','display: none !important;');
        current_active.removeClass('active-device-list');

        submenu.next().removeAttr("style");
        submenu.next().addClass("active-device-list");

        e.stopPropagation();
    });

    $('.dropdown').on("hidden.bs.dropdown", function() {
        // hide any open menus when parent closes
        $('.dropdown-menu.show').removeClass('show');
    });



});


