import {adminRoutes} from "./admin_routes";
import Home from "../views/user/Home";
import {userRoutes} from "./user_routes";
import Admin from "../views/admin/Admin";

export const routes = [
	{
		path: '/admin',
		component: Admin,
		name: 'admin',
		children: adminRoutes,
	},
	{
		path: '/',
		component: Home,
		name: 'user_home',
		children: userRoutes,
	},
];