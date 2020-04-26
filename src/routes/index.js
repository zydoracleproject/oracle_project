import Main from "../views/admin/Main";
import {adminRoutes} from "./admin_routes";
import Home from "../views/user/Home";
import {userRoutes} from "./user_routes";

export const routes = [
	{
		path: '/admin',
		component: Main,
		name: 'admin_main',
		children: adminRoutes,
	},
	{
		path: '/',
		component: Home,
		name: 'user_home',
		children: userRoutes,
	},
];