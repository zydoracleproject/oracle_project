import axios from 'axios';

export default {
	state: {
		manufacturers: [],
		manError: null,
		loadingMan: false,
		manCreated: false,
		manUpdated: false,
	},
	getters: {
		getManufacturers(state) {
			return state.manufacturers;
		},
		getManError(state) {
			return state.manError;
		},
		isLoadingMans(state) {
			return state.loadingMans;
		},
		isManCreated(state) {
			return state.manCreated;
		},
		isManUpdated(state) {
			return state.manUpdated;
		},
	},
	mutations: {
		setManufacturers(state, data) {
			state.manufacturers = data;
		},
		setManError(state, value) {
			state.manError = value;
		},
		setLoadingMans(state, value) {
			state.loadingMans = value;
		},
		setManCreated(state, value) {
			state.manCreated = value;
		},
		setManUpdated(state, value) {
			state.manUpdated = value;
		}
	},
	actions: {
		readMans(context, data) {
			context.commit('setLoadingMans', true);
			context.commit('setManCreated', false);

			axios.post(context.getters.getUrl + 'api/manufacturer/read.php', JSON.stringify(data)).then((response) => {
				if (response.data.records.length) {
					let arr = response.data.records.map((item) => Object.fromEntries(
						Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
					context.commit('setManufacturers', arr);
					context.commit('setLoadingMans', false);
				}
			}).catch((error) => {
				context.commit('setManError', error.response.data);
			});
		},

		createMan(context, data) {
			context.commit('setManCreated', false);

			axios.post(context.getters.getUrl + 'api/manufacturer/create.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setManCreated', true);
				}
			}).catch((error) => {
				context.commit('setManError', error.response.data);
			});
		},

		updateMan(context, data) {
			context.commit('setManUpdated', false);
			axios.post(context.getters.getUrl + 'api/manufacturer/update.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					context.commit('setManUpdated', true);
				}
			}).catch((error) => {
				context.commit('setManError', error.response.data);
			});
		},

		deleteMan(context, data) {
			axios.post(context.getters.getUrl + 'api/manufacturer/delete.php', JSON.stringify(data)).catch((error) => {
				console.log(error);
				context.commit('setManError', error.response.data);
			});
		}
	},
};