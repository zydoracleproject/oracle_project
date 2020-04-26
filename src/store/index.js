import {adminStore} from "./modules/admin";
import {userStore} from "./modules/user";
import products from "./modules/products";
import categories from "./modules/categories";
import manufacturers from "./modules/manufacturers";

export default {
	state: {
		baseUrl: 'http://localhost:8000/',
	},
	getters: {
		getUrl(state) {
			return state.baseUrl;
		},
	},
	modules: {
		adminStore,
		userStore,
		products,
		categories,
		manufacturers,
	},
};