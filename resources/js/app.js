require('./bootstrap');

window.Vue = require('vue');

Vue.component('create-shorten-url', require('./components/createShortenUrlComponent.vue').default);
Vue.component('last-shorten-url', require('./components/lastGeneratedShortenUrlComponent.vue').default);

const app = new Vue({
    el: '#app',
});
