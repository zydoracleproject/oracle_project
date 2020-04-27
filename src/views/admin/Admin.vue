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
		name: "Admin",
		components: {
			Menu,
		},
		data: () => ({}),
		computed: {
			...mapGetters(['isAuthenticated', 'getAdmin'])
		},
		created() {
			this.$store.dispatch('initAdmin');
		},
		mounted() {
			if (!this.isAuthenticated) {
				this.$router.push({name: 'admin_login'});
			} else {
				this.$router.push({name: 'admin_main'});
      }
		}
	}
</script>

<style scoped>

</style>