import axios from 'axios';
import {deleteCookie, getCookie, setCookie} from "../../../../functions/cookies";

export const loginAdmin = {
	state: {
		authenticated: false,
		admin: null,
		loading: null,
		loginError: null,
	},
	getters: {
		isAuthenticated(state) {
			return state.authenticated;
		},
		getAdmin(state) {
			return state.admin;
		},
		isLoading(state) {
			return state.loading;
		},
		getLoginError(state) {
			return state.loginError;
		}
	},
	mutations: {
		setAuthenticated(state, value) {
			state.authenticated = value;
		},
		setAdmin(state, data) {
			state.admin = data;
		},
		setLoading(state, value) {
			state.loading = value;
		},
		setLoginError(state, value) {
			state.loginError = value;
		},
		deleteAdmin(state, value) {
			state.admin = value;
			state.authenticated = false;
		},
	},
	actions: {
		initAdmin(context) {
			const admin = JSON.parse(getCookie('admin'));
			if (admin) {
				context.commit('setAdmin', admin);
				context.commit('setAuthenticated', true);
			}
		},
		loginAdmin(context, data) {
			context.commit('setLoading', true);
			context.commit('setLoginError', null);

			axios.post(context.getters.getUrl + 'api/admin/connection.php', JSON.stringify({
				username: btoa(data.username),
				password: btoa(data.password),
			})).then((response) => {
				if (response.data) {
					context.commit('setLoading', false);
					const arr = response.data.admin.map((item) => Object.fromEntries(
						Object.entries(item).map(([key, value]) => [key.toLowerCase(), value])));
					context.commit('setAdmin', arr[0]);
					setCookie('admin', JSON.stringify(arr[0]), {
						'samesite': 'lax',
						'max-age': 3600
					});
					context.commit('setAuthenticated', true);
				}
			}).catch(() => {
				context.commit('setLoginError', 'Неправильный логин и/или пароль');
				context.commit('setLoading', false);
			});
		},
		logoutAdmin(context) {
			deleteCookie('admin');
			context.commit('deleteAdmin', null);
		}
	}
}