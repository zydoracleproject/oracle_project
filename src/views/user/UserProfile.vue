<template>
  <v-content>
    <v-card v-if="getUser" max-width="400px" elevation="5" class="ma-5">
      <v-card-title>Ваш профиль</v-card-title>
      <v-simple-table>
        <template v-slot:default>
          <tbody>
          <tr>
            <td>Ваше имя</td>
            <td>{{getUser.username}}</td>
          </tr>
          <tr>
            <td>Ваше телефон</td>
            <td>{{getUser.phone}}</td>
          </tr>
          <tr>
            <td>Почтовый индекс</td>
            <td>{{getUser.mail_index}}</td>
          </tr>
          <tr>
            <td>Ваш адрес</td>
            <td>{{getUser.address}}</td>
          </tr>
          <tr>
            <td>Было создано</td>
            <td>{{getUser.created_at}}</td>
          </tr>
          <tr>
            <td>Было обновлено</td>
            <td>{{getUser.updated_at}}</td>
          </tr>
          </tbody>
        </template>
      </v-simple-table>
      <v-row class="pa-2 mt-2 mx-0 justify-space-between" align="center">
        <v-dialog v-model="editDialog" persistent max-width="600px">
          <template v-slot:activator="{ on }">
            <v-btn outlined small color="yellow darken-4" dark v-on="on">Редактировать</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Редактирование</span>
            </v-card-title>
            <v-card-text>
              <v-alert
                v-if="getUserError"
                class="white--text ma-5 col-10"
                type="error"
                elevation="5"
              >
                {{getUserError}}
              </v-alert>
              <spinner v-if="loading"></spinner>
              <v-container>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="getUser.username"
                      :rules="required"
                      required
                      label="Ваше имя"
                    >
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="getUser.phone"
                      :rules="required"
                      required
                      label="Ваш телефон"
                    >
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="getUser.mail_index"
                      :rules="required"
                      required
                      label="Индекс почты"
                    >
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      v-model="getUser.address"
                      :rules="required"
                      required
                      label="Ваш адрес"
                    >
                    </v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="editDialog = false">Закрыть</v-btn>
              <v-btn color="success" text @click="updateUser">Подтвердить</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="deleteDialog" persistent max-width="600px">
          <template v-slot:activator="{ on }">
            <v-btn x-small outlined color="red darken-2" v-on="on">Удалить мой аккаунт</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Удалить ваш аккаунт ?</span>
            </v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="deleteDialog = false">Нет</v-btn>
              <v-btn color="success" text @click="deleteUser">Да</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
    </v-card>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "UserProfile",
		data: () => ({
			valid: true,
			editDialog: false,
			deleteDialog: false,
			required: [v => !!v || 'Обязательное поле!'],
			loading: false,
		}),
		computed: {
			...mapGetters(['getAdmin', 'getUser', 'isUserAuth', 'getUserError']),
		},
		mounted() {
			if (!this.isUserAuth) {
				this.$store.dispatch('initUser', {
					username: btoa('admin'),
					password: btoa('1234'),
				});
			}
			setTimeout(() => {
				if (!this.isUserAuth) {
					this.$router.push({name: 'user_login'});
				}
			}, 1000);
		},
		methods: {
			updateUser() {
				this.loading = true;
				this.$store.dispatch('updateUser', {
					username: btoa('admin'),
					password: btoa('1234'),
					id: this.getUser.id,
					u_username: this.getUser.username,
					phone: this.getUser.phone,
					mail_index: this.getUser.mail_index,
					address: this.getUser.address,
					created_at: this.getUser.created_at,
				});

				setTimeout(() => {
					this.loading = false;
					if (!this.getUserError) {
						this.editDialog = false;
					}
				}, 1000);
			},
			deleteUser() {
				this.$store.dispatch('deleteUser', {
					username: btoa('admin'),
					password: btoa('1234'),
					id: this.getUser.id,
				});
				this.$store.dispatch('logoutUser');

				setTimeout(() => {
					if (!this.getUserError) {
						this.deleteDialog = false;
						this.$router.push({name: 'user_login'});
					}
				}, 1000)
			}
		},
	}
</script>

<style scoped>

</style>