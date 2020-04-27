<template>
  <v-content>
    <v-form lazy-validation ref="form" v-model="valid" class="ma-2">
      <v-card elevation="6" max-width="600px" class="mx-auto">
        <v-card-title>Вход</v-card-title>
        <v-alert
          v-if="getUserError"
          class="white--text ma-5 col-10"
          type="error"
          elevation="5"
        >
          {{getUserError}}
        </v-alert>
        <spinner v-if="loading" class="ma-2"></spinner>
        <v-row class="pa-2">
          <v-col class="pa-5" cols="col-12">
            <v-text-field
              label="Ваш номер телефона"
              v-model="phone"
              :rules="required"
              required
            ></v-text-field>
            <v-text-field
              label="Пароль"
              type="password"
              v-model="u_password"
              :rules="required"
              required
            ></v-text-field>
            <v-row class="align-center mt-2 pa-3">
              <v-btn @click="submit" color="success" class="ma-2">Войти</v-btn>
              <v-btn @click="clear" class="ma-2">Очистить</v-btn>
            </v-row>
          </v-col>
        </v-row>
      </v-card>
    </v-form>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Login",
		data: () => ({
			valid: true,
			phone: '',
			u_password: '',
			required: [v => !!v || 'Обязательное поле!'],
      loading: false,
		}),
		computed: {
			...mapGetters(['getUser', 'getUserError', 'isUserAuth', 'getAdmin']),
		},
		mounted() {
			this.$store.commit('setUserError', null);

			setTimeout(() => {
				if (this.isUserAuth) {
					this.$router.push({name: 'user_home'});
				}
      }, 500);
		},
		methods: {
			clear() {
				this.$refs.form.reset();
			},
			submit() {
				if (this.$refs.form.validate()) {
					this.loading = true;
					this.$store.dispatch('loginUser', {
						username: btoa('guest'),
						password: btoa('1234'),
						phone: this.phone,
						u_password: this.u_password,
					});

					setTimeout(() => {
						this.loading = false;
						if (!this.getUserError && this.getUser) {
							this.$router.push({name: 'user_home'});
						}
					}, 1000);
				}
			},
		},
	}
</script>

<style scoped>

</style>