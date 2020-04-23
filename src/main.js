import Vue from 'vue'
import App from './App.vue'
import Axios from "axios";
import VueRouter from "vue-router";
import Vuex from 'vuex';
import storeData from './store/index';
import {routes} from "./routes/index";
import vuetify from './plugins/vuetify';

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.prototype.$http = Axios;

const store = new Vuex.Store(storeData);
const router = new VueRouter({
	mode: 'history',
	routes,
});

Vue.config.productionTip = true;

Vue.component('spinner', require('vue-simple-spinner'));

new Vue({
    render: h => h(App),
    router,
    vuetify,
    store
}).$mount('#app')
