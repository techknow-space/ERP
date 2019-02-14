
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./mjr');

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
    $('body').on('click','.mic', function(){
        speechRecognition('search', $(this));
    });
});
