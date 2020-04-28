import axios from 'axios';

export default {
	state: {
		usersPerWeek: null,
		statsErrors: [],
		totalAmount: null,
		visits: [],
		visitsPerDay: null,
	},
	getters: {
		getUsersPerWeek(state) {
			return state.usersPerWeek;
		},
		getStatsErrors(state) {
			return state.statsErrors;
		},
		getTotalAmount(state) {
			return state.totalAmount;
		},
		getVisits(state) {
			return state.visits;
		},
		getVisitsPerDay(state) {
			return state.visitsPerDay;
		}
	},
	mutations: {
		setUsersPerWeek(state, data) {
			state.usersPerWeek = data;
		},
		addStatsErrors(state, error) {
			state.statsErrors.push(error);
		},
		setTotalAmount(state, data) {
			state.totalAmount = data;
		},
		setVisits(state, data) {
			state.visits = data;
		},
		setVisitsPerDay(state, data) {
			state.visitsPerDay = data;
		}
	},
	actions: {
		readUsersPerWeek(context, data) {
			axios.post(context.getters.getUrl + 'api/statistics/users_per_week.php', JSON.stringify(data))
				.then((response) => {
					if (response.data) {
						const data = Object.fromEntries(Object.entries(response.data).map(v => ([v[0].toLowerCase(), v[1]])));
						context.commit('setUsersPerWeek', data);
					}
				}).catch((error) => {
				context.commit('addStatsError', error.response.data.message);
			});
		},
		readTotalAmount(context, data) {
			axios.post(context.getters.getUrl + 'api/statistics/total_amount.php', JSON.stringify(data))
				.then((response) => {
					const data = Object.fromEntries(Object.entries(response.data).map(v => ([v[0].toLowerCase(), v[1]])));
					context.commit('setTotalAmount', data);
				}).catch((error) => {
				context.commit('addStatsError', error.response.data.message);
			});
		},
		readVisits(context, data) {
			axios.post(context.getters.getUrl + 'api/statistics/read_visits.php', JSON.stringify(data))
				.then((response) => {
					if (response.data.records) {
						const arr = response.data.records.map((item) => Object.fromEntries(
							Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
						context.commit('setVisits', arr[0]);
					}
				}).catch((error) => {
				context.commit('addStatsError', error.response.data.message);
			});
		},
		readVisitsPerDay(context, data) {
			axios.post(context.getters.getUrl + 'api/statistics/visits_per_day.php', JSON.stringify(data))
				.then((response) => {
					context.commit('setVisitsPerDay', response.data);
				});
		},
	},
};