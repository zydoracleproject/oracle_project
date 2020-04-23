import Main from "../views/admin/Main";
import {adminRoutes} from "./admin_routes";

export const routes = [
	{
		path: '/admin',
		component: Main,
		name: 'admin_main',
		children: adminRoutes,
	},
];