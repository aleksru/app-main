
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');

//шина событий
window.VueBus = new Vue();

require('./bootstrap');

/**
 * Глобально подключаем аксиос
 */
Object.defineProperty(Vue.prototype, '$axios', { value: window.axios });

window.toast = require('./notifications');
require('./modules/fake-link');
//подключаем селекты чистый и обертку
import vSelect from 'vue-select';

import vmodal from 'vue-js-modal';

Vue.use(vmodal);

Vue.component('v-select', vSelect)

Vue.component('vue-select', require('./components/VueSelect.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('PeriodsForm', require('./components/Admin/Form/PeriodsForm.vue'));

Vue.component('ProductsTable', require('./components/ProductsTable.vue'));

Vue.component('SearchProduct', require('./components/SearchProduct.vue'));

Vue.component('GetOrder', require('./components/GetOrder.vue'));

Vue.component('OrderForm', require('./components/OrderForm.vue'));

Vue.component('MassStatuses', require('./components/MassChangeStatuses.vue'));

Vue.component('Notifications', require('./components/Front/Notifications.vue'));

Vue.component('IncomingCall', require('./components/Front/IncomingCall.vue'));

Vue.component('CallBack', require('./components/Front/CallBack.vue'));

Vue.component('MissedCalls', require('./components/Front/MissedCalls.vue'));

Vue.component('SelectInput', require('./components/Front/SelectCityMetro.vue'));

Vue.component('CallsQueue', require('./components/Front/CallsQueue.vue'));

Vue.component('Chat', require('./components/Front/Chat/Chat.vue'));

Vue.component('FastOrder', require('./components/Front/Logistic/FastOrder.vue'));

Vue.component('ProductsTableOperator', require('./components/Front/ProductsTableOperator.vue'));

Vue.component('CourierPrint', require('./components/Front/Logistic/CourierPrint.vue'));

Vue.component('FastChanges', require('./components/Front/Logistic/FastChanges.vue'));

const app = new Vue({
    el: '#app'
});
