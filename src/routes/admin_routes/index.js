import Login from "../../views/admin/Login";
import Products from "../../views/admin/Products";
import CreateProducts from "../../views/admin/CreateProducts";

export const adminRoutes = [
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
];