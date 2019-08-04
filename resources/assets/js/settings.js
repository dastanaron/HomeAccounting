import Vuex from "vuex";

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';

Vue.use(VueRouter);
Vue.use(Vuetify);
Vue.use(Vuex);

import SettingsApp from './components/settings/SettingsApp';
let MobileDetect = require('mobile-detect');
let md = new MobileDetect(window.navigator.userAgent);

const mobile = md.mobile();

const store = new Vuex.Store({
    state: {
        preloader: false,

        mobile: mobile,

        alertControl: {
            show: false,
            type: 'success',
            message: '',
        },
    },
    mutations: {
        setPreloader(state, status=true) {

            if(status === false) {
                setTimeout(() => {
                    state.preloader = status;
                }, 1000)
            }
            else {
                state.preloader = status;
            }

        },
        setAlert(state, object) {
            state.alertControl.type = object.type;
            state.alertControl.show = object.status;
            state.alertControl.message = object.message;
        },
        AlertError(state, error) {
            state.alertControl.type = 'error';
            state.alertControl.show = true;
            state.alertControl.message = 'Ошибка: ' + error;
        },
        closeAlert(state) {
            state.alertControl.show = false;
        },
    },
    getters: {
        getPreloader: state=> {
            return state.preloader;
        },
        getAlert: state => {
            return state.alertControl;
        },
        mobile: state => {
            return state.mobile;
        },
    },
});

import router from './components/settings/routes.js';

const APP = new Vue({
    el: '#settings-spa',
    template: '<settings-app></settings-app>',
    components: {SettingsApp},
    store,
    router,
});