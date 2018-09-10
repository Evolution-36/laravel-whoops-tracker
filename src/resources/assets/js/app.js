window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');
const VueRouter = require('vue-router').default;

window.timeago = require('timeago.js');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);

const routes = [
    { path: '/', component: require('./components/LWTList.vue') },
    { path: '/:id', component: require('./components/LWTShow.vue') },
];

const router = new VueRouter({
    routes
});

const app = new Vue({
    router
}).$mount('#app');
