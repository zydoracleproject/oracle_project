<template>
  <v-content>
    <v-alert
      class="white--text mx-5 mt-5 mb-10"
      type="info"
      elevation="5"
    >
      Центр управления аккаунтами
    </v-alert>
    <v-alert
      type="error"
      elevation="5"
      class="ma-5"
      v-if="getAdmin.role !== 'ADMIN_ROLE'"
    >Только администратор может управлять аккаунтами!
    </v-alert>
    <v-snackbar
      v-model="snackbar"
      color="error"
    >
      {{ getAccountsError }}
      <v-btn
        color="pink"
        text
        @click="snackbar = false"
      >
        Закрыть
      </v-btn>
    </v-snackbar>
    <v-container fluid v-if="getAdmin.role === 'ADMIN_ROLE'">
      <div class="row justify-space-between align-center mb-10 mx-5">
        <h1>Аккаунты</h1>
        <div class="btns">
          <v-dialog v-model="editDialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn
                :color="selected.length ? 'yellow darken-3' : ''"
                class="mx-2"
                dark
                v-on="selected.length ? on : ''"
                elevation="5">Редактировать
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Редактирование аккаунта</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-label>Старый пароль: {{getAdmin.password}}</v-label>
                      <v-text-field v-if="selected.length" v-model="password" label="Новый пароль"
                                    :rules="required"
                                    type="password"
                                    required></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        v-if="selected.length && selected[0].username !== getAdmin.username"
                        v-model="selected[0].role"
                        :items="roles"
                        item-text="text"
                        item-value="role"
                        label="Роль аккаунта"
                        :rules="required"
                        required
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeEditDialog">Закрыть</v-btn>
                <v-btn color="success" text @click="updateAccount(selected[0].username, selected[0].role, password)">
                  Подтвердить
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="deleteDialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn
                :color="selected.length ? 'red' : ''"
                dark
                class="mx-2"
                v-on="selected.length ? on : ''"
                elevation="5">Удалить
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <v-alert
                  type="error"
                  v-if="selected.length && selected[0].username === getAdmin.username">
                  Вы не можете удалить администратора!
                </v-alert>
                <span v-else class="headline">Удалить аккаунт ?</span>
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="deleteDialog = false">Закрыть</v-btn>
                <v-btn
                  v-if="selected.length && selected[0].username !== getAdmin.username"
                  color="success"
                  text @click="deleteAccount(selected[0].username)">Да
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="createDialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn
                color="success"
                class="mx-2"
                dark
                v-on="on"
                elevation="5">Создать аккаунт
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Создание аккаунта</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field v-model="createUsername" label="Имя"
                                    :rules="required"
                                    required></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field v-model="createPassword" label="Пароль"
                                    :rules="required"
                                    type="password"
                                    required></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        v-model="selectedRole"
                        :items="roles"
                        item-text="text"
                        item-value="role"
                        label="Роль аккаунта"
                        :rules="required"
                        required
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeCreateDialog">Закрыть</v-btn>
                <v-btn color="success" text @click="createUser(createUsername, selectedRole, createPassword)">
                  Создать
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
      <v-data-table
        v-if="getAccounts.length"
        v-model="selected"
        :headers="headers"
        :items="getAccounts"
        single-select
        item-key="id"
        show-select
        class="elevation-5"
      >
      </v-data-table>
    </v-container>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Accounts",
		data: () => ({
			selected: [],
			snackbar: false,
			editDialog: false,
			deleteDialog: false,
			createDialog: false,
			password: '',
			headers: [
				{text: 'ID', value: 'id'},
				{text: 'Имя', value: 'username'},
				{text: 'Роль', value: 'role'},
				{text: 'Создано', value: 'created_at'},
			],
			roles: [
				{text: 'Админ', role: 'ADMIN_ROLE'},
				{text: 'Менеджер', role: 'MANAGER_ROLE'},
				{text: 'Гость', role: 'GUEST_ROLE'}
			],
			required: [v => !!v || 'Обязательное поле!'],
			selectedRole: '',
			createPassword: '',
			createUsername: '',
		}),
		computed: {
			...mapGetters(['getAdmin', 'getAccountsError', 'getAccounts']),
		},
		mounted() {
			if (this.getAdmin.role === 'ADMIN_ROLE') {
				this.$store.dispatch('readAccounts', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
				});
			}
		},
		methods: {
			closeEditDialog() {
				this.$store.dispatch('readAccounts', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
				});
				this.editDialog = false;
			},
			closeCreateDialog() {
				this.selectedRole = '';
				this.createUsername = '';
				this.createPassword = '';
				this.createDialog = false;
			},
			updateAccount(username, role, password) {
				this.$store.dispatch('updateAccount', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					u_username: username,
					u_password: password,
					role,
				});

				setTimeout(() => {
					if (!this.getAccountsError) {
						this.editDialog = false;
						if (username === this.getAdmin.username) {
							this.$store.dispatch('logoutAdmin');
							this.$router.push({name: 'admin_login'});
						}
					}
				}, 1000);
			},
			createUser(username, role, password) {
				this.$store.dispatch('createAccount', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					u_username: username,
					u_password: password,
					role,
				});

				setTimeout(() => {
					if (!this.getAccountsError) {
						this.$store.dispatch('readAccounts', {
							username: btoa(this.getAdmin.username),
							password: btoa(this.getAdmin.password),
						});
						this.closeCreateDialog();
					}
				}, 1000);
			},
			deleteAccount(username) {
				this.$store.dispatch('deleteAccount', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					u_username: username,
				});

				setTimeout(() => {
					if (!this.getAccountsError) {
						this.$store.dispatch('readAccounts', {
							username: btoa(this.getAdmin.username),
							password: btoa(this.getAdmin.password),
						});
						this.deleteDialog = false;
					}
				}, 1000);
			},
		},
	}
</script>

<style scoped>

</style>