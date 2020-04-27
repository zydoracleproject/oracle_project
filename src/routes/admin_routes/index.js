import Login from "../../views/admin/Login";
import Products from "../../views/admin/Products";
import CreateProducts from "../../views/admin/CreateProducts";
import UpdateProducts from "../../views/admin/UpdateProducts";
import Categories from "../../views/admin/Categories";
import Manufacturers from "../../views/admin/Manufacturers";
import Users from "../../views/admin/Users";
import Accounts from "../../views/admin/Accounts";
import Main from "../../views/admin/Main";

export const adminRoutes = [
	{
		path: 'main',
		component: Main,
		name: 'admin_main',
	},
	{
		path: 'login',
		component: Login,
		name: 'admin_login',
	},
	{
		path: 'products',
		component: Products,
		name: 'admin_products',
	},
	{
		path: 'products/create',
		component: CreateProducts,
		name: 'product_create',
	},
	{
		path: 'products/update/:id',
		component: UpdateProducts,
		name: 'product_update',
		props: true,
	},
	{
		path: 'categories',
		component: Categories,
		name: 'admin_categories'
	},
	{
		path: 'manufacturers',
		component: Manufacturers,
		name: 'admin_manufacturers',
	},
	{
		path: 'users',
		component: Users,
		name: 'admin_users',
	},
	{
		path: 'accounts',
		component: Accounts,
		name: 'admin_accounts',
	}
];