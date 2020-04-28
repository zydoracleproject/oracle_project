import axios from 'axios';

export default {
	state: {
		visited: false
	},
	getters: {
		isVisited(state) {
			return state.visited;
		}
	},
	mutations: {
		setVisited(state, value) {
			state.visited = value;
		}
	},
	actions: {
		countVisit(context, data) {
			axios.post(context.getters.getUrl + 'api/statistics/count_visits.php', JSON.stringify(data))
				.then((response) => {
					if (response.data)  {
						context.commit('setVisited', true);
					}
				}).catch(() => {
					context.commit('setVisited', false);
			});
		}
	},
};