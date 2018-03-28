
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vuex from 'vuex'
import Vuetify from 'vuetify';

Vue.use(Vuex);
Vue.use(Vuetify);

Vue.component('private-area', require('./components/PrivateArea.vue'));


const store = new Vuex.Store({
    state: {
        preloader: false,
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
    },
    getters: {
        getPreloader: state=> {
            return state.preloader;
        }
    },
});

const app = new Vue({
    el: '#privateArea',
    store
});
