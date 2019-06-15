
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify';

Vue.use(Vuetify);

import QrcodeScanner from './components/QRcodeScanner/QrcodeScanner.vue';

const APP = new Vue({
    el: '#qrCodeScanner',
    template: '<qrcode-scanner></qrcode-scanner>',
    components: {QrcodeScanner},
});
