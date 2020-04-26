import axios from 'axios';
import {deleteCookie, getCookie, setCookie} from "../../../../functions/cookies";
// import {getCookie, deleteCookie, setCookie} from "../../../../functions/cookies";

export const userLogin = {
	state: {
		user: null,
		userAuthenticated: false,
		userError: null,
	},
	getters: {
		getUser(state) {
			return state.user;
		},
		isUserAuth(state) {
			return state.userAuthenticated;
		},
		getUserError(state) {
			return state.userError;
		}
	},
	mutations: {
		setUser(state, data) {
			state.user = data;
		},
		setUserAuth(state, value) {
			state.userAuthenticated = value;
		},
		setUserError(state, value) {
			state.userError = value;
		}
	},
	actions: {
		initUser(context, data) {
			const userToken = getCookie('remember_token');
			if (userToken) {
				axios.post(context.getters.getUrl + 'api/user/login_by_token.php', JSON.stringify(Object.assign({
					remember_token: btoa(JSON.parse(userToken)),
				}, data))).then((response) => {
					if (response.data) {
						context.commit('setUser', response.data);
						context.commit('setUserAuth', true);
					}
				});
			}
		},
		loginUser(context, data) {
			context.commit('setUserError', null);

			axios.post(context.getters.getUrl + 'api/user/login.php', JSON.stringify(data)).then((response) => {
				if (response.data) {
					let arr = Object.fromEntries(Object.entries(response.data).map(([k, v]) => [k.toLowerCase(), v]));
					context.commit('setUser', arr);
					setCookie('remember_token', JSON.stringify(arr.remember_token), {
						'samesite': 'lax',
						'max-age': 3600 * 12
					});
					context.commit('setUserAuth', true);
				}
			}).catch(() => {
				context.commit('setUserError', 'Неправильный логин или пароль, повторите попытку!');
			});
		},
		createUser(context, data) {
			context.commit('setUserError', null);

			axios.post(context.getters.getUrl + 'api/user/create.php', JSON.stringify(data)).catch((error) => {
				context.commit('setUserError', error.response.data);
			});
		},
		logoutUser(context) {
			context.commit('setUser', null);
			context.commit('setUserAuth', false);
			deleteCookie('remember_token');
		},
		updateUser(context, data) {
			context.commit('setUserError', null);

			axios.post(context.getters.getUrl + 'api/user/update.php', JSON.stringify(data)).catch((error) => {
				context.commit('setUserError', error.response.data);
			});
		},
		deleteUser(context, data) {
			context.commit('setUserError', null);

			axios.post(context.getters.getUrl + 'api/user/delete.php', JSON.stringify(data)).catch((error) => {
				context.commit('setUserError', error.response.data);
			});
		}
	},
};