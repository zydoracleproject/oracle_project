import {loginAdmin} from "./modules/login";
import users from "./modules/users";

export const adminStore = {
	modules: {
		loginAdmin,
		users,
	}
}