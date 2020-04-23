<template>
  <v-content>
    <v-card
      class="col-10 col-md-6 col-lg-4 mx-auto p-4"
      elevation="8"
    >
      <v-card-title>
        Вход в Админ панель
      </v-card-title>

      <v-alert v-if="getLoginError" type="error">
        {{ getLoginError }}
      </v-alert>

      <spinner size="medium" v-if="isLoading"></spinner>

      <form
        @submit.prevent="login"
      >
        <v-text-field
          v-model="username"
          label="Логин"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          label="Пароль"
          required
          type="password"
        ></v-text-field>
        <v-btn type="submit" color="light-blue darken-4" dark>Войти</v-btn>
      </form>
    </v-card>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Login",
		data() {
			return {
				username: '',
				password: '',
			};
		},
		computed: {
			...mapGetters(['isAuthenticated', 'getAdmin', 'isLoading', 'getLoginError']),
		},
		watch: {
			isAuthenticated(newValue) {
				if (newValue) {
					this.$router.push({name: 'admin_main'});
				}
			}
		},
		mounted() {
			if (this.isAuthenticated) {
				this.$router.push({name: 'admin_main'});
			}
		},
		methods: {
			login() {
				this.$store.dispatch('loginAdmin', {
					username: this.username,
					password: this.password,
				});
			},
		},
	}
</script>

<style scoped>

</style>