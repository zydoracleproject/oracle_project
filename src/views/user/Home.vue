<template>
  <div class="home">
    <v-toolbar dense>
      <v-app-bar-nav-icon>
        <v-img src="@/assets/logo.jpeg" max-width="45px"></v-img>
      </v-app-bar-nav-icon>

      <v-toolbar-title>WARMHOUSE</v-toolbar-title>

      <v-spacer></v-spacer>
      <v-menu
        v-if="!isUserAuth"
        bottom
        origin="center center"
        transition="scale-transition"
      >
        <template v-slot:activator="{ on }">
          <v-btn
            color="primary"
            dark
            v-on="on"
            outlined
          >
            Вход
          </v-btn>
        </template>

        <v-list>
          <v-list-item :to="{name: 'user_login'}">
            <v-list-item-title>Войти</v-list-item-title>
          </v-list-item>
          <v-list-item :to="{name: 'user_register'}">
            <v-list-item-title>Зарегистрироваться</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <v-menu
        v-if="isUserAuth"
        bottom
        origin="center center"
        transition="scale-transition"
      >
        <template v-slot:activator="{ on }">
          <v-btn
            color="primary"
            dark
            v-on="on"
            outlined
          >
            {{getUser.username}}
          </v-btn>
        </template>

        <v-list>
          <v-list-item :to="{name: 'user_profile'}">
            <v-list-item-title>Мой профиль</v-list-item-title>
          </v-list-item>
          <v-list-item @click="logout">
            <v-list-item-title>Выйти</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-toolbar>
    <router-view></router-view>
  </div>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Home",
		data: () => ({}),
		computed: {
			...mapGetters(['getAdmin', 'getUser', 'isUserAuth']),
		},
		mounted() {
			if (!this.isUserAuth) {
				this.$store.dispatch('initUser', {
					username: btoa('guest'),
					password: btoa('1234'),
				});
			}

			this.$http.get('https://api.ipify.org/?format=json').then((response) => {
				if (response.data) {
					this.$store.dispatch('countVisit', {
						username: btoa('guest'),
            password: btoa('1234'),
            ip: response.data.ip,
          });
        }
      });
		},
		methods: {
			logout() {
				this.$store.dispatch('logoutUser');
			},
		},
	}
</script>

<style scoped>

</style>