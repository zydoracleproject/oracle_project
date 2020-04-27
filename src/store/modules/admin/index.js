import {loginAdmin} from "./modules/login";
import users from "./modules/users";
import accounts from "./modules/accounts";
import stats from "./modules/stats";

export const adminStore = {
	modules: {
		loginAdmin,
		users,
		accounts,
		stats,
	}
}