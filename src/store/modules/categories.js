import axios from 'axios';

export default {
	state: {
		categories: [],
		categoryError: null,
		loadingCats: false,
		categoryCreated: false,
		categoryUpdated: false,
	},
	getters: {
		getCategories(state) {
			return state.categories;
		},
		getCategoryError(state) {
			return state.categoryError;
		},
		isLoadingCats(state) {
			return state.loadingCats;
		},
		isCategoryCreated(state) {
			return state.categoryCreated;
		},
		isCategoryUpdated(state) {
			return state.categoryUpdated;
		},
	},
	mutations: {
		setCategories(state, data) {
			state.categories = data;
		},
		setCategoryError(state, value) {
			state.categoryError = value;
		},
		setLoadingCats(state, value) {
			state.loadingCats = value;
		},
		setCategoryCreated(state, value) {
			state.categoryCreated = value;
		},
		setCategoryUpdated(state, value) {
			state.categoryUpdated = value;
		}
	},
	actions: {
		readCategories(context, data) {
			context.commit('setLoadingCats', true);
			context.commit('setCategoryCreated', false);

			axios.post(context.getters.getUrl + 'api/category/read.php', JSON.stringify(data)).then((response) => {
				if (response.data.records.length) {
					let arr = response.data.records.map((item) => Object.fromEntries(
						Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
					context.commit('setCategories', arr);
					context.commit('setLoadingCats', false);
				}
			}).catch((error) => {
				context.commit('setCategoryError', error.response.data);
			});
		},

		createCategory(context, data) {
			context.commit('setCategoryCreated', false);

			axios.post(context.getters.getUrl + 'api/category/create.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setCategoryCreated', true);
				}
			}).catch((error) => {
				context.commit('setCategoryError', error.response.data);
			});
		},

		updateCategory(context, data) {
			context.commit('setCategoryUpdated', false);
			axios.post(context.getters.getUrl + 'api/category/update.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setCategoryUpdated', true);
				}
			}).catch((error) => {
				context.commit('setCategoryError', error.response.data);
			});
		},

		deleteCategory(context, data) {
			axios.post(context.getters.getUrl + 'api/category/delete.php', JSON.stringify(data)).catch((error) => {
				console.log(error);
				context.commit('setCategoryError', error.response.data);
			});
		}
	},
};