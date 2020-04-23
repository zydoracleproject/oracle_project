<template>
  <div id="main">
    <Menu v-if="isAuthenticated" :admin="getAdmin"></Menu>
    <router-view></router-view>
  </div>
</template>

<script>
	import {mapGetters} from 'vuex';
	import Menu from "../../components/admin/Menu";

	export default {
		name: "Main",
		components: {
			Menu,
		},
		data: () => ({}),
		computed: {
			...mapGetters(['isAuthenticated', 'isLoading', 'getAdmin'])
		},
		created() {
			this.$store.dispatch('initAdmin');
		},
		mounted() {
			if (!this.isAuthenticated) {
				this.$router.push({name: 'admin_login'});
			}
		}
	}
</script>

<style scoped>

</style>