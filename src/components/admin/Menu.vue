<template>
  <v-navigation-drawer
    v-model="drawer"
    color="primary"
    :mini-variant="miniVariant"
    dark
    app
  >
    <v-list
      dense
      nav
      class="py-0"
    >
      <v-list-item two-line :class="miniVariant && 'px-0'">
        <v-list-item-avatar>
          <img src="@/assets/logo.jpeg">
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>{{ admin.username.toUpperCase() }}</v-list-item-title>
          <v-list-item-subtitle>Warmhouse Dashboard</v-list-item-subtitle>
          <v-btn x-small max-width="50%" @click="logout">Выйти</v-btn>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list-item-group>
        <v-list-item
          exact
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          link
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
	export default {
		name: "Menu",
		props: ['admin'],
		data: () => ({
			miniVariant: false,
			drawer: true,
			items: [
				{title: 'Главная', icon: 'mdi-home ', to: {name: 'admin_main'}},
				{title: 'Товары', icon: 'mdi-basket', to: {name: 'admin_products'}},
				{title: 'Категории', icon: 'mdi-shape', to: {name: 'admin_categories'}},
				{title: 'Производители', icon: 'mdi-briefcase-check', to: {name: 'admin_manufacturers'}},
				{title: 'Заказы', icon: 'mdi-cash-usd', to: {name: 'admin_orders'}},
				{title: 'Пользователи', icon: 'mdi-account-multiple', to: {name: 'admin_users'}},
				{title: 'Аккаунты', icon: 'mdi-account ', to: {name: 'admin_accounts'}},
			],
		}),
		computed: {
			currentPage() {
				return this.$route.name;
			},
		},
		methods: {
			logout() {
				this.$store.dispatch('logoutAdmin');
				this.$router.push({name: 'admin_login'});
			},
		},
	}
</script>

<style scoped>
</style>