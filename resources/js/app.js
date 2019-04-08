
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Select2 = require('select2');
window.moment = require('moment');
window.toastr = require('toastr');
window.datetimepicker = require('tempusdominus-bootstrap-4');

require('./generic');
require('./mjr');
require('./purchase_order');
require('./purchaseOrderStockDistribution');
require('./part_operation');
require('./stockTransfer');
require('./ajaxUpdateOperations');
require('tablesorter');

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

(function($){
	$.fn.tfh = function(){

		var method = (arguments.length === 2) ? arguments[0] : ((arguments.length === 1 && typeof arguments[0] === 'string' ? arguments[0] : undefined));
		var options = $.extend({
			trigger: 0,
			top: 0
		},(arguments.length === 2) ? arguments[1] : ((arguments.length === 1 && typeof arguments[0] === 'object' ? arguments[0] : {} )));

		this.width = function(){
			return this.find('thead').attr('data-tmp-width',parseInt(this.find('thead').css('width'))).find('*').each(function(){
				$(this).attr('data-tmp-width',parseInt($(this).css('width')));
			}).end().end();
		};

		this.fix = function(){
			return this.find('.table-fixed-head-thead').css({
				'top': options.top + 'px',
				'position': 'fixed'
			}).end();
		};

		this.clone = function(){
			return this.find('thead').clone(true).prependTo(this).addClass('table-fixed-head-thead').end().end().removeAttr('data-tmp-width').find('*').removeAttr('data-tmp-width').end().end();
		};

		this.build = function(){
			return this.tfh('width').tfh('clone').find('[data-tmp-width]').each(function(){
				$(this).css({
					'width': $(this).data('tmp-width') + 'px',
					'minWidth': $(this).data('tmp-width') + 'px',
					'maxWidth': $(this).data('tmp-width') + 'px',
				});
			}).removeAttr('data-tmp-width').end().tfh('fix', options);
		};

		this.kill = function(){
			this.find('.table-fixed-head-thead').remove();
		};

		this.show = function(){
			return this.addClass('fixed').find('thead').css('visibility','visible').not('.table-fixed-head-thead').css('visibility','hidden').end().end();
		};

		this.hide = function(){
			return this.removeClass('fixed').find('thead').css('visibility','hidden').not('.table-fixed-head-thead').css('visibility','visible').end().end();
		};

		if(method !== undefined){
			return this[method].call($(this));
		} else {
			var table = this.build.call($(this),options);
			var tableWidth = table.css('width');
			var tableScrollLeft = table.position().left;

			if($(document).scrollTop() > options.trigger) {
				table.tfh('show');
			} else {
				table.tfh('hide');
			}

			var resizeTimer;
			$(window).resize(function(){
				window.clearInterval(resizeTimer);
				resizeTimer = window.setInterval(function() {
					window.clearInterval(resizeTimer);
					if(tableWidth !== table.css('width')) {
						tableWidth = table.css('width');
						table.tfh('kill');
						table.tfh(options);
					}
				}, 1000);
			}).scroll(function(){
				if($(document).scrollTop() > options.trigger) {
					table.tfh('show');
					table.find('.table-fixed-head-thead').css('left',(tableScrollLeft - $(document).scrollLeft()) + 'px');
				} else {
					table.tfh('hide');
				}
			});
		}
	}
	$(document).ready(function(){
		$('table.table-fixed-head').each(function(){
			$(this).tfh({
				trigger: ($(this).data('table-fixed-head-trigger') !== undefined ? $(this).data('table-fixed-head-trigger') : 0),
				top: ($(this).data('table-fixed-head-top') !== undefined ? $(this).data('table-fixed-head-top') : $(this).position().top)
			});
		});
	});
}(jQuery));


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
        mic.css('color', '');
    };

    recognition.onend = function() {
        mic.css('color', '');
    };

    recognition.onresult = function (e) {
        var textarea = $('#'+form+' input');
        for (var i = e.resultIndex; i < e.results.length; ++i) {
            if (e.results[i].isFinal) {
                fullString += e.results[i][0].transcript;
                textarea.val(fullString);
                $('#search').submit();
            } else {
                draftString = e.results[i][0].transcript;
                textarea.val(fullString + ' ' + draftString);
            }
        }
    }

    recognition.start();
}

$(document).ready(function() {
    $("#sortable").tablesorter();

    $('body').on('click','.mic', function(){
        speechRecognition('search', $(this));
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*

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

     */

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
                        let part_data_row =
                            '<tr data-sku="'+sku+'">' +
                            '<td class="sc-partlist-name">'+data['part_name']+'</td>'+
                            '<td class="sc-partlist-device">'+data['devices']['brand']['name']+' '+data['devices']['model_name']+'</td>'+
                            '<td class="sc-partlist-sku">'+data['sku']+'</td>'+
                            '</tr>' +
                            '';
                        //last_row.after(part_data_row);
                        $('.sc-scanned-items').append(part_data_row);

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

    $('table').tfh({
        trigger: 300,
        top: 0
    });
});

