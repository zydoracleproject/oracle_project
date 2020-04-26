<template>
  <v-content>
    <v-form lazy-validation ref="form" v-model="valid" class="ma-2">
      <v-card elevation="6" max-width="600px" class="mx-auto">
        <v-card-title>Регистрация</v-card-title>
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
              label="Ваше имя"
              v-model="u_username"
              :rules="required"
              required
            ></v-text-field>
            <v-text-field
              label="Ваш номер телефона"
              v-model="phone"
              :rules="number"
              required
            ></v-text-field>
            <v-text-field
              label="Пароль"
              type="password"
              v-model="u_password"
              :rules="passRules"
              required
            ></v-text-field>
            <v-text-field
              label="Повторите пароль"
              type="password"
              v-model="u_password_r"
              :rules="passMatch"
              required
            ></v-text-field>
            <v-text-field
              label="Индекс почты"
              v-model="mail_index"
              type="number"
            ></v-text-field>
            <v-text-field
              label="Ваш адрес"
              v-model="address"
            ></v-text-field>
            <v-row class="align-center mt-10 pa-3">
              <v-btn @click="submit" color="success" class="ma-2">Создать</v-btn>
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
		name: "Register",
		data: () => ({
			valid: true,
			u_username: '',
			u_password: '',
			u_password_r: '',
			phone: '',
			mail_index: '',
			address: '',
			required: [v => !!v || 'Обязательное поле!'],
			number: [
				v => !!v || 'Обязательное поле!',
				v => /^[0-9]+$/.test(v) || 'Только цифры!'],
      loading: false,
		}),
		computed: {
			...mapGetters(['isUserAuth', 'getAdmin', 'getUserError', 'getUser']),
			passRules() {
				const rules = [];

				let rule = v => (v || '').indexOf(' ') < 0 ||
					'Пробелы запрещены!';
				rules.push(rule);

				rule = v => !!v || 'Обязательное поле!';
				rules.push(rule);

				rule = v => /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/.test(v) || 'Пароль не соответствует правилам!';
				rules.push(rule);

				return rules;
			},
			passMatch() {
				return [v => v === this.u_password || 'Пароли не соответствуют!'];
			}
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
					this.$store.dispatch('createUser', {
						username: btoa('admin'),
						password: btoa('1234'),
						u_username: this.u_username,
						u_password: this.u_password,
						phone: this.phone,
						mail_index: this.mail_index,
						address: this.address,
					});

					setTimeout(() => {
						this.loading = false;
						if (!this.getUserError) {
							this.$store.dispatch('loginUser', {
								username: btoa('admin'),
								password: btoa('1234'),
								phone: this.phone,
								u_password: this.u_password,
							});

							setTimeout(() => {
								if (!this.getUserError && this.getUser) {
									this.$router.push({name: 'user_home'});
								}
							}, 1000)
						}
					}, 1000);
				}
			},
		}
	}
</script>

<style scoped>

</style>