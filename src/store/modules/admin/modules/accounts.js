import axios from 'axios';

export default {
	state: {
		accounts: [],
		accountsError: null,
	},
	getters: {
		getAccounts(state) {
			return state.accounts;
		},
		getAccountsError(state) {
			return state.accountsError;
		},
	},
	mutations: {
		setAccounts(state, data) {
			state.accounts = data;
		},
		setAccountsError(state, value) {
			state.accountsError = value;
		}
	},
	actions: {
		readAccounts(context, data) {
			context.commit('setAccountsError', null);

			axios.post(context.getters.getUrl + 'api/admin/read_users.php', JSON.stringify(data)).then((response) => {
				if(response.data.records) {
					const arr = response.data.records.map((item) => Object.fromEntries(
						Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
					context.commit('setAccounts', arr);
				}
			}).catch((error) => {
				context.commit('setAccountsError', error.response.data);
			});
		},
		updateAccount(context, data) {
			context.commit('setAccountsError', null);

			axios.post(context.getters.getUrl + 'api/admin/update_user.php', JSON.stringify(data)).catch((error) => {
				context.commit('setAccountsError', error.response.data);
			});
		},
		createAccount(context, data) {
			context.commit('setAccountsError', null);

			axios.post(context.getters.getUrl + 'api/admin/create_user.php', JSON.stringify(data)).catch((error) => {
				context.commit('setAccountsError', error.response.data);
			});
		},
		deleteAccount(context, data) {
			context.commit('setAccountsError', null);

			axios.post(context.getters.getUrl + 'api/admin/delete_user.php', JSON.stringify(data)).catch((error) => {
				context.commit('setAccountsError', error.response.data);
			});
		}
	},
}