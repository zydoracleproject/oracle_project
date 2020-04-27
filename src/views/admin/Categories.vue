<template>
  <v-content>
    <v-alert
      class="white--text mx-5 mt-5 mb-10"
      type="info"
      elevation="5"
    >
      Центр управления категориями
    </v-alert>
    <v-snackbar
      v-model="snackbar"
      color="error"
    >
      {{ getCategoryError }}
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
        <h1>Катогории</h1>
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
                <span class="headline">Редактирование категорий</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field v-if="selected.length" v-model="selected[0].title" label="Название категорий"
                                    :rules="required"
                                    required></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="editDialog = false">Закрыть</v-btn>
                <v-btn color="success" text @click="updateCategory(selected[0])">Подтвердить</v-btn>
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
                <span class="headline">Удалить категорию ?</span>
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="deleteDialog = false">Нет</v-btn>
                <v-btn color="success" text @click="deleteCategory(selected[0].id)">Да</v-btn>
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
                elevation="5">Создать категорию
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Создание категорий</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field v-model="category_title" label="Название категорий"
                                    :rules="required"
                                    required></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="createDialog = false">Закрыть</v-btn>
                <v-btn color="success" text @click="createCategory(category_title)">Создать</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
      <v-alert
        v-if="!getCategories.length"
        type="info"
        dense
        class="my-2 pa-3 py-3"
        color="grey"
      >
        В магазине не имеется категорий!
      </v-alert>
      <spinner size="large" v-if="isLoadingCats"></spinner>
      <v-data-table
        v-if="getCategories.length"
        v-model="selected"
        :headers="headers"
        :items="getCategories"
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
		name: "Categories",
		data: () => ({
			selected: [],
			editDialog: false,
			deleteDialog: false,
			createDialog: false,
			headers: [
				{text: 'ID', value: 'id'},
				{text: 'Название', value: 'title'},
				{text: 'Создано', value: 'created_at'},
				{text: 'Обновлено', value: 'updated_at'}
			],
			required: [v => !!v || 'Обязательное поле!'],
			snackbar: false,
			category_title: '',
		}),
		computed: {
			...mapGetters(['getAdmin', 'getCategories', 'isLoadingCats', 'getCategoryError']),
			checkAdmin() {
				return this.getAdmin.role === 'ADMIN_ROLE';
			},
      checkManager() {
				return this.getAdmin.role === 'MANAGER_ROLE';
      }
		},
		mounted() {
			this.readCategory();
		},
		methods: {
			readCategory() {
				this.$store.dispatch('readCategories', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
				});
			},
			deleteCategory(id) {
				this.$store.dispatch('deleteCategory', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					id,
				});
				this.deleteDialog = false;
				this.selected = [];
				setTimeout(() => {
					if (!this.getCategoryError) {
						this.readCategory();
					} else {
						this.snackbar = true;
					}
				}, 500);
			},
			updateCategory(data) {
				this.$store.dispatch('updateCategory', Object.assign({
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
				}, data));
				this.editDialog = false;
				this.selected = [];
				setTimeout(() => {
					if (this.getCategoryError) {
						this.snackbar = true;
						this.readCategory();
					} else {
						this.readCategory();
					}
				}, 500);
			},
			createCategory(title) {
				this.$store.dispatch('createCategory', {
					username: btoa(this.getAdmin.username),
					password: btoa(this.getAdmin.password),
					title,
				});
				this.createDialog = false;
				this.selected = [];
				setTimeout(() => {
					if (this.getCategoryError) {
						this.snackbar = true;
					} else {
						this.readCategory();
					}
				}, 500);
			}
		},
	}
</script>

<style scoped>

</style>