import Vue from 'vue';
import Vuex from 'vuex';
import Vuetify from 'vuetify';
import Organizer from './components/Organizer/Organizer';


Vue.use(Vuex);
Vue.use(Vuetify);

const store = new Vuex.Store({
    state: {
        preloader: false,
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
            state.alertControl.message = 'Ошибка передачи транзакции, попробуйте перезапустить страницу. Ответ сервера: '+error;
        },
    },
    getters: {
        getPreloader: state=> {
            return state.preloader;
        },
        getAlert: state => {
            return state.alertControl;
        },
    },
});

const app = new Vue({
    el: '#organizerApp',
    template: '<organizer></organizer>',
    components: {
        Organizer
    },
    store
});
