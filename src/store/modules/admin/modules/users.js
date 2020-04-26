import axios from 'axios';

export default {
	state: {
		users: [],
	},
	getters: {
		getUsers(state) {
			return state.users;
		}
	},
	mutations: {
		setUsers(state, data) {
			state.users = data;
		}
	},
	actions: {
		readUsers(context, data) {
			axios.post(context.getters.getUrl + 'api/user/read.php', JSON.stringify(data)).then((response) => {
				if (response.data.records) {
					const arr = response.data.records.map((item) => Object.fromEntries(
						Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
					context.commit('setUsers', arr);
				}
			});
		}
	},
};