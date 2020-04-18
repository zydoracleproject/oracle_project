import Vue from 'vue'
import App from './App.vue'
import Axios from "axios";
import Vuetify from 'vuetify';
import VueRouter from "vue-router";
import Vuex from 'vuex';
import storeData from './store/index';
import {routes} from "./routes/index";

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(Vuetify);
Vue.prototype.$http = Axios;

const store = new Vuex.Store(storeData);
const router = new VueRouter({
	mode: 'history',
	routes,
});

Vue.config.productionTip = true;

new Vue({
	vuetify: new Vuetify(),
	render: h => h(App),
	router,
	store,
}).$mount('#app')
