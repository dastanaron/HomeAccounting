import VueRouter from 'vue-router';
import NalogIntegration from './NalogIntegration'
import Index from './Index'
export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/settings',
            name: 'Index',
            component: Index,
            default: true
        },
        {
            path: '/settings/nalog-ru-integration',
            name: 'NalogIntegration',
            component: NalogIntegration
        },
    ]

})