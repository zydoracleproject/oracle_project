import UserLogin from "../../views/user/UserLogin";
import Register from "../../views/user/Register";
import UserProfile from "../../views/user/UserProfile";

export const userRoutes = [
	{
		path: 'login',
		component: UserLogin,
		name: 'user_login',
	},
	{
		path: 'register',
		component: Register,
		name: 'user_register',
	},
	{
		path: 'profile',
		component: UserProfile,
		name: 'user_profile',
	}
];