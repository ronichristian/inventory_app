/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter,
    {
        symbol : 'P',
        thousandsSeparator: ',',
        fractionCount: 2,
        fractionSeparator: '.',
        symbolPosition: 'front',
        symbolSpacing: true
    })
Vue.filter('capitalize', function (value) {
        if (!value) return ''
            value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
    })
Vue.use(require('vue-moment'));
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('products', require('./components/Product.vue').default);
Vue.component('category', require('./components/Category.vue').default);
Vue.component('transaction', require('./components/Transaction.vue').default);
Vue.component('sales_invoice', require('./components/SalesInvoice.vue').default);
Vue.component('orders', require('./components/Orders.vue').default);
Vue.component('order_invoice', require('./components/OrderInvoice.vue').default);
Vue.component('capital_and_gross', require('./components/CapitalAndGross.vue').default);
Vue.component('most_bought_graph', require('./components/MostBoughtProducts.vue').default);
Vue.component('net_worth', require('./components/Home.vue').default);
Vue.component('chats', require('./components/Chats.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
let app = new Vue({
    el: '#app',
});

// let category = new Vue({
//     el: '#category',
// });

// let products = new Vue({
//     el: '#products',
// });

// let transaction = new Vue({
//     el: '#transaction',
// });

// let sales_invoice = new Vue({
//     el: '#sales_invoice',
// });

// let orders = new Vue({
//     el: '#orders',
// });

// let order_invoice = new Vue({
//     el: '#order_invoice',
// });

// let capital_and_gross = new Vue({
//     el: '#capital_and_gross',
// });

// let net_worth = new Vue({
//     el: '#net_worth',
// });