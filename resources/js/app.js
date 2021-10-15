/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
require('./my-vue');
require('@fortawesome/fontawesome-free/js/all');
require('owl.carousel');
require('soft-ui-design-system');
require('soft-ui-dashboard/assets/js/soft-ui-dashboard.min.js?v=1.0.1');
// require('soft-ui-dashboard/assets/js/plugins/smooth-scrollbar.min');
require('lity');
window.swal = require('sweetalert2');

window.toastr = require('toastr/build/toastr.min');

window.showDialog = (type, message, onclick = null) => {

    // 0  ready for save
    // 1  success  save
    // else show errors
    if (type === 'confirm')
        swal.fire({
            title: "<h3 class='text-danger'>" + message + "</h3>",
            text: ' ',
            icon: 'error',
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'انصراف',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'تایید',
        }).then((result) => {
            if (result.value && onclick !== null) {
                onclick();
            }
        });
    else if (type === 1) {
        swal.fire({
            title: "<h3 class='text-success'>" + message + "</h3>",
            text: ' با موفقیت حذف شد!',
            confirmButtonColor: '#60aa2f',
            icon: 'success',
            confirmButtonText: ' باشه',
        }).then((result) => {
            if (result.value) {
//                            location.reload();
                this.$root.$emit('search');
            }
        });

    } else {
        swal.fire({
            title: 'خطایی رخ داد',
            html: ` <p   class="text-danger">` + message + `</p>`,
//                        text: this.errors,
            confirmButtonColor: '#d33',
            icon: 'error',
            confirmButtonText: ' باشه',
        });
    }

};

// require('unitegallery/dist/js/unitegallery');
// require('unitegallery/dist/themes/tiles/ug-theme-tiles');
// console.log('loaded');
// require('@glidejs/glide/dist/glide.min');

// import Glide from '@glidejs/glide'  ;
//
// new Glide('.glide', {
//
//     type: 'carousel',
//     focusAt: 'center',
//     startAt: 0,
//     perView: 3,
//     autoplay: 2000
// }).mount();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


