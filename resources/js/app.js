
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//const app = new Vue({
//    el: '#app'
//});

function speechRecognition(form, mic)
{
    var recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = "en-US";

    draftString = '';
    fullString = '';

    recognition.onstart = function() {
        mic.css('color', 'red');
    };

    recognition.onaudioend = function() {
        mic.css('color', 'black');
    };

    recognition.onend = function() {
        mic.css('color', 'black');
    };

    recognition.onresult = function (e) {
        var textarea = $('#'+form+' textarea');
        for (var i = e.resultIndex; i < e.results.length; ++i) {
            // console.log(e.results[i]);
            if (e.results[i].isFinal) {
                  fullString += e.results[i][0].transcript;
                  textarea.val(fullString);
            } else {
                draftString = e.results[i][0].transcript;
                textarea.val(fullString + ' ' + draftString);
            }
        }
    }

    recognition.start();
}


$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $( '.dropdown-menu a.dropdown-toggle' ).on( 'click', function ( e ) {
        var $el = $( this );
        var $parent = $( this ).offsetParent( ".dropdown-menu" );
        if ( !$( this ).next().hasClass( 'show' ) ) {
            $( this ).parents( '.dropdown-menu' ).first().find( '.show' ).removeClass( "show" );
        }
        var $subMenu = $( this ).next( ".dropdown-menu" );
        $subMenu.toggleClass( 'show' );

        $( this ).parent( "li" ).toggleClass( 'show' );

        $( this ).parents( 'li.nav-item.dropdown.show' ).on( 'hidden.bs.dropdown', function ( e ) {
            $( '.dropdown-menu .show' ).removeClass( "show" );
        } );

        if ( !$parent.parent().hasClass( 'navbar-nav' ) ) {
            $el.next().css( { "top": $el[0].offsetTop, "left": $parent.outerWidth() - 4 } );
        }

        return false;
    } );

    $('#brand').on('change', function() {
        var brandID = $(this).val();
        if(brandID) {
            $.ajax({
                url: '/findModelWithBrandID/'+brandID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //console.log(data);
                    if(data){
                        $('#model').empty();
                        $('#model').focus;
                        $('#model').append('<option value="">-- Select Model --</option>');
                        $.each(data, function(key, value){
                            $('select[name="model"]').append('<option value="'+ value.id +'">' + value.model_name+ '</option>');
                        });
                    }else{
                        $('#model').empty();
                    }
                }
            });
        }else{
            $('#model').empty();
        }
    });

    $('#model').on('change', function () {
        var modelID = $(this).val();
        if(modelID){
            $.ajax({
                url: '/findPartWithDeviceID/'+modelID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //console.log(data);
                    if(data){
                        $('#part').empty();
                        $('#part').focus;
                        $('#part').append('<option value="">-- Select Part --</option>');
                        $.each(data, function(key, value){
                            $('select[name="part"]').append('<option value="'+ value.id +'">' + value.part_name +'</option>');
                        });
                    }else{
                        $('#part').empty();
                    }
                }
            });
        }
        else {
            $('#part').empty();
        }
    });

    $('#part').on('change', function () {
        var partID = $(this).val();
        if(partID){
            $.ajax({
                url: '/getPartDetailsWithID/'+partID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    //console.log(data);
                    if(data){

                        $('.part-details-table-col-part-name').html(data.name);
                        $('.part-details-table-col-last-cost').html(data.cost);
                        $('.part-details-table-col-selling-price').html(data.price);
                        $('.part-details-table-col-Qty-s1').html(data.stock[0].qty);
                        $('.part-details-table-col-qty-to1').html(data.stock[1].qty);
                        $('.part-details-table-col-sku').html(data.sku);

                        $('#part-details-table').show();

                    }else{
                        $('#part-details-table').hide();
                    }
                }
            });
        }
        else{
            $('#part-details-table').hide();
        }
    });

    $('#sc-barcode-form').on('submit', function () {

        let barcode_box = $('#sc-barcode-entry');
        let sku = barcode_box.val();

        if(sku){
            /*Get Part Details*/
            $.ajax({
                url: '/getPartDetailsWithSKU/'+sku,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    if(data['error'] !== true){

                        let last_row = $('#sc-partlist-table tr:last');
                        let last_sku = last_row.data("sku");
                        let sku = data['sku'];



                        if(sku == last_sku){
                            let qty = last_row.find('.sc-partlist-qty').text();
                            qty++;
                            last_row.find('.sc-partlist-qty').text(qty);
                        }
                        else{

                            let part_data_row =
                                '<tr data-sku="'+sku+'">' +
                                '<td class="sc-partlist-name">'+data['part_name']+'</td>'+
                                '<td class="sc-partlist-device">'+data['devices']['brand']['name']+' '+data['devices']['model_name']+' '+data['devices']['model_number']+'</td>'+
                                '<td class="sc-partlist-sku">'+data['sku']+'</td>'+
                                '<td class="sc-partlist-qty">1</td>'+
                                '</tr>' +
                                '';
                            //last_row.after(part_data_row);
                            $('.sc-scanned-items').append(part_data_row);
                        }

                        barcode_box.val('');

                    }
                    else {
                        alert('Unknown SKU');
                        barcode_box.focus();
                    }
                }
            });

            /*Persist Scan in the DB */

            $.ajax({
                url: '/stockcount/additem',
                type: 'POST',
                data: {"sku":sku,"scid":$('#sc-partlist-table').data("scid")},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                }
            });
        }
        return false
    });

});
