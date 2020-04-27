import {loginAdmin} from "./modules/login";
import users from "./modules/users";
import accounts from "./modules/accounts";

export const adminStore = {
	modules: {
		loginAdmin,
		users,
		accounts,
	}
}