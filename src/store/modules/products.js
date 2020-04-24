import axios from 'axios';

export default {
	state: {
		products: [],
		productsByMan: [],
		productsByCat: [],
		productOne: null,
		productError: '',
		productSuccess: '',
		searchResults: [],
		created: false,
		updated: false,
		productsLoading: false,
	},
	getters: {
		getProducts(state) {
			return state.products;
		},
		getProductsByMan(state) {
			return state.productsByMan;
		},
		getProductsByCat(state) {
			return state.productsByCat;
		},
		getProductOne(state) {
			return state.productOne;
		},
		getProductError(state) {
			return state.productError;
		},
		getProductSuccess(state) {
			return state.productSuccess;
		},
		getSearchResults(state) {
			return state.searchResults;
		},
		isCreated(state) {
			return state.created;
		},
		isUpdated(state) {
			return state.updated;
		},
		isProductsLoading(state) {
			return state.productsLoading;
		},
	},
	mutations: {
		setProducts(state, data) {
			state.products = data;
		},
		setProductOne(state, data) {
			state.productOne = data;
		},
		setProductError(state, value) {
			state.productError = value;
		},
		setProductSuccess(state, value) {
			state.productSuccess = value;
		},
		setSearchResults(state, data) {
			state.searchResults = data;
		},
		setProductsByMan(state, data) {
			state.productsByMan = data;
		},
		setProductsByCat(state, data) {
			state.productsByCat = data;
		},
		setCreated(state, value) {
			state.created = value;
		},
		setUpdated(state, value) {
			state.updated = value;
		},
		setProductsLoading(state, value) {
			state.productsLoading = value;
		}
	},
	actions: {
		readProducts(context, data) {
			context.commit('setProductsLoading', true);

			axios.post(context.getters.getUrl + 'api/product/read.php', JSON.stringify({
				username: btoa(data.username),
				password: btoa(data.password)
			})).then((response) => {
				if (response.data.records.length) {
					let arr = response.data.records.map((item) => Object.fromEntries(
						Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
					context.commit('setProducts', arr);
					context.commit('setProductsLoading', false);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		createProduct(context, data) {
			context.commit('setCreated', false);
			context.commit('setProductsLoading', true);

			axios.post(context.getters.getUrl + 'api/product/create.php', data, {
				headers: {
					'Content-Type': 'multipart/form-data',
				}
			}).then((response) => {
				if (response.data) {
					context.commit('setProductSuccess', response.data.message);
					context.commit('setCreated', true);
					context.commit('setProductsLoading', false);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		updateProduct(context, data) {
			context.commit('setUpdated', false);

			axios.post(context.getters.getUrl + 'api/product/update.php', data, {
				headers: {
					'Content-Type': 'multipart/form-data',
				}
			}).then((response) => {
				if (response.data) {
					console.log(response.data);
					context.commit('setProductSuccess', response.data.message);
					context.commit('setUpdated', true);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		deleteProduct(context, data) {
			axios.post(context.getters.getUrl + 'api/product/delete.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setProductSuccess', response.data.message);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		readByCat(context, data) {
			axios.post(context.getters.getUrl + 'api/product/read_by_cat.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setProductsByCat', response.data);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		readByMan(context, data) {
			axios.post(context.getters.getUrl + 'api/product/read_by_man.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setProductsByMan', response.data);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		search(context, data) {
			axios.post(context.getters.getUrl + 'api/product/search.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setSearchResults', response.data);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		},
		readOne(context, data) {
			context.commit('setProductsLoading', true);

			axios.post(context.getters.getUrl + 'api/product/read_one.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setProductOne', response.data);
					context.commit('setProductsLoading', false);
				}
			}).catch((error) => {
				context.commit('setProductError', error.message);
			});
		}
	}
};