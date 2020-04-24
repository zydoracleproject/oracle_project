import Login from "../../views/admin/Login";
import Products from "../../views/admin/Products";
import CreateProducts from "../../views/admin/CreateProducts";
import UpdateProducts from "../../views/admin/UpdateProducts";
import Categories from "../../views/admin/Categories";

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
];