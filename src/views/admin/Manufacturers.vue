<template>
  <v-content>
    <v-alert
      class="white--text mx-5 mt-5 mb-10"
      type="info"
      elevation="5"
    >
      Центр управления производителями
    </v-alert>
    <v-snackbar
      v-model="snackbar"
      color="error"
    >
      {{ getManError }}
      <v-btn
        color="pink"
        text
        @click="snackbar = false"
      >
        Закрыть
      </v-btn>
    </v-snackbar>
    <v-container fluid>
      <div class="row justify-space-between align-center mb-10 mx-5">
        <h1>Производители</h1>
        <div class="btns">
          <v-dialog v-model="editDialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn
                v-if="checkAdmin || checkManager"
                :color="selected.length ? 'yellow darken-3' : ''"
                class="mx-2"
                dark
                v-on="selected.length ? on : ''"
                elevation="5">Редактировать
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Редактирование производителя</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field v-if="selected.length" v-model="selected[0].title" label="Название производителя"
                                    :rules="required"
                                    required></v-text-field>
                      <v-select
                        v-model="category"
                        :items="getCategories"
                        return-object
                        item-text="title"
                        item-value="id"
                        label="Категория производителя"
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="editDialog = false">Закрыть</v-btn>
                <v-btn color="success" text @click="updateMan(selected[0], category)">Подтвердить</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="deleteDialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn
                v-if="checkAdmin"
                :color="selected.length ? 'red' : ''"
                dark
                class="mx-2"
                v-on="selected.length ? on : ''"
                elevation="5">Удалить
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Удалить производителя ?</span>
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="deleteDialog = false">Нет</v-btn>
                <v-btn color="success" text @click="deleteMan(selected[0].id)">Да</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="createDialog" persistent max-width="600px">
            <template v-slot:activator="{ on }">
              <v-btn
                v-if="checkAdmin"
                color="success"
                class="mx-2"
                v-on="on"
                elevation="5">Создать производителя
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Создание производителя</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field v-model="manufacturer_title" label="Название производителя"
                                    :rules="required"
                                    required></v-text-field>
                      <v-select
                        v-model="category"
                        :items="getCategories"
                        return-object
                        item-text="title"
                        item-value="id"
                        label="Категория производителя"
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="createDialog = false">Закрыть</v-btn>
                <v-btn color="success" text @click="createMan(manufacturer_title, category.id)">Создать</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
      <v-alert
        v-if="!getManufacturers.length"
        type="info"
        dense
        class="my-2 pa-3 py-3"
        color="grey"
      >
        В магазине не имеется производителей!
      </v-alert>
      <spinner size="large" v-if="isLoadingMans"></spinner>
      <v-data-table
        v-if="getManufacturers.length"
        v-model="selected"
        :headers="headers"
        :items="getManufacturers"
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
		name: "Manufacturers",
		data: () => ({
			selected: [],
			editDialog: false,
			deleteDialog: false,
			createDialog: false,
			headers: [
				{text: 'ID', value: 'id'},
				{text: 'Название', value: 'title'},
				{text: 'Категория', value: 'category_title'},
				{text: 'Создано', value: 'created_at'},
				{text: 'Обновлено', value: 'updated_at'},
			],
			required: [v => !!v || 'Обязательное поле!'],
			snackbar: false,
			manufacturer_title: '',
			category: null,
		}),
		computed: {
			...mapGetters(['getAdmin', 'getManufacturers', 'isLoadingMans', 'getManError', 'getCategories']),
			getCategory() {
				return this.selected.length ? {
					id: this.selected[0].category_id,
					category_title: this.selected[0].category_title
				} : null;
			},
			checkAdmin() {
				return this.getAdmin.role === 'ADMIN_ROLE';
			},
			checkManager() {
				return this.getAdmin.role === 'MANAGER_ROLE';
			}
		},
		mounted() {
			this.readMan();
			this.$store.dispatch('readCategories', {
				username: btoa(this.getAdmin.username),
				password: btoa(this.getAdmin.password),
			});
		},
		methods: {
			readMan() {
				this.$store.dispatch('readMans', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
				});
			},
			deleteMan(id) {
				this.$store.dispatch('deleteMan', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					id,
				});
				this.deleteDialog = false;
				this.selected = [];
				setTimeout(() => {
					if (!this.getManError) {
						this.readMan();
					} else {
						this.snackbar = true;
					}
				}, 500);
			},
			updateMan(data) {
				this.$store.dispatch('updateMan', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					id: data.id,
					title: data.title,
          category_id: this.category ? this.category.id : data.category_id,
          keywords: data.keywords,
          description: data.description,
          created_at: data.created_at,
				});
				this.editDialog = false;
				this.selected = [];
				setTimeout(() => {
					if (this.getManError) {
						this.snackbar = true;
						this.readMan();
					} else {
						this.readMan();
					}
				}, 500);
			},
			createMan(title, id) {
				this.$store.dispatch('createMan', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					title,
					category_id: id,
				});
				this.createDialog = false;
				this.selected = [];
				setTimeout(() => {
					if (this.getManError) {
						this.snackbar = true;
					} else {
						this.readMan();
					}
				}, 500);
			}
		},
	}
</script>

<style scoped>

</style>