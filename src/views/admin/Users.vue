<template>
  <v-content>
    <v-container fluid>
      <v-alert
        class="white--text mx-5 mt-5 mb-10"
        type="info"
        elevation="5"
      >
        Центр управления пользователями
      </v-alert>
      <div class="row justify-space-between align-center mb-10 mx-5">
        <h1>Пользователи</h1>
      </div>
      <v-alert
        v-if="!getUsers.length"
        type="info"
        dense
        class="my-2 pa-3 py-3"
        color="grey"
      >
        В магазине не имеется пользователей!
      </v-alert>
      <v-data-table
        v-if="getUsers.length"
        :headers="headers"
        :items="getUsers"
        item-key="id"
        class="elevation-5"
      >
      </v-data-table>
    </v-container>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Users",
		data: () => ({
			headers: [
				{text: 'ID', value: 'id'},
				{text: 'Имя', value: 'username'},
				{text: 'Телефон', value: 'phone'},
				{text: 'Индекс почты', value: 'mail_index'},
				{text: 'Адрес', value: 'address'},
				{text: 'Создано', value: 'created_at'},
				{text: 'Обновлено', value: 'updated_at'},
			],
    }),
		computed: {
			...mapGetters(['getUsers', 'getAdmin']),
		},
		mounted() {
			this.$store.dispatch('readUsers', {
				username: btoa(this.getAdmin.username),
        password: btoa(this.getAdmin.password),
      });
		},
	}
</script>

<style scoped>

</style>