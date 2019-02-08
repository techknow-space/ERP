
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



$(document).ready(function() {
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

});
