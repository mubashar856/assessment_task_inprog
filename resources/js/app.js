require('./bootstrap');

window.Vue = require('vue');

Vue.component('stats', require('./components/Stats.vue').default);

const app = new Vue({
    el: '#app',
});