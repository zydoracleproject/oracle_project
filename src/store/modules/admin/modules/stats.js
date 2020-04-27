import axios from 'axios';

export default {
	state: {
		usersPerWeek: null,
		statsErrors: [],
	},
	getters: {
		getUsersPerWeek(state) {
			return state.usersPerWeek;
		},
		getStatsErrors(state) {
			return state.statsErrors;
		}
	},
	mutations: {
		setUsersPerWeek(state, data) {
			state.usersPerWeek = data;
		},
		addStatsErrors(state, error) {
			state.statsErrors.push(error);
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
				})
				.catch((error) => {
					context.commit('addStatsError', error.response.data.message);
				});
		}
	},
};