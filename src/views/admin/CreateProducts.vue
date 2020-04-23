<template>
  <v-content>
    <v-row class="align-center pl-10">
      <v-btn fab small color="primary" :to="{name: 'admin_products'}">
        <v-icon>mdi-arrow-left</v-icon>
      </v-btn>
      <v-alert
        class="white--text ma-5 col-10"
        type="info"
        elevation="5"
      >
        Создание товара
      </v-alert>
    </v-row>
    <v-container fluid>
      <v-form lazy-validation ref="form">
        <v-card elevation="6">
          <v-row class="pa-2">
            <v-col class="pa-5" cols="col-12 col-md-4">
              <v-text-field
                label="Название"
                v-model="title"
                :rules="required"
                required
              ></v-text-field>
              <v-text-field
                label="Модель"
                v-model="model"
              ></v-text-field>
              <v-text-field
                label="Цена"
                v-model="price"
                :rules="required"
                required
                type="number"
              ></v-text-field>
              <v-switch v-model="pop_status" class="ma-2" label="Популярный товар"></v-switch>
              <v-text-field
                label="Количество"
                v-model="amount"
                type="number"
              ></v-text-field>
              <v-select
                v-model="category"
                :items="categories"
                return-object
                item-text="title"
                item-value="id"
                label="Категория товара"
              ></v-select>
              <v-select
                v-model="manufacturer"
                :items="manufacturers"
                label="Производитель товара"
              ></v-select>
              <v-row class="align-center mt-10 pa-3">
                <v-btn @click="submit" color="success" class="ma-2">Создать</v-btn>
                <v-btn @click="clear" class="ma-2">Очистить</v-btn>
              </v-row>
            </v-col>
            <v-col class="pa-5" cols="col-12 col-md-8">
              <v-row>
                <v-text-field
                  label="Исполнение"
                  v-model="execution"
                  class="col-6"
                ></v-text-field>
                <v-text-field
                  label="Назначение"
                  v-model="appointment"
                  class="col-6"
                ></v-text-field>
                <v-text-field
                  label="Мощность (кВт)"
                  v-model="power"
                  type="number"
                  class="col-6"
                ></v-text-field>
                <v-text-field
                  label="Тип камеры сгорания"
                  v-model="chamber"
                  class="col-6"
                ></v-text-field>
                <v-text-field
                  label="Площадь помещения (кв.м.)"
                  v-model="premises"
                  type="number"
                  class="col-6"
                ></v-text-field>
                <v-text-field
                  label="Высота (см)"
                  v-model="height"
                  type="number"
                  class="col-6"
                ></v-text-field>
                <v-text-field
                  label="Ширина (см)"
                  v-model="width"
                  type="number"
                  class="col-4"
                ></v-text-field>
                <v-text-field
                  label="Глубина (см)"
                  v-model="depth"
                  type="number"
                  class="col-4"
                ></v-text-field>
                <v-text-field
                  label="Гарантия"
                  v-model="warranty"
                  class="col-4"
                ></v-text-field>
                <v-textarea
                  v-model="content"
                  name="input-7-4"
                  label="Описание"
                  class="col-12"
                ></v-textarea>
                <v-file-input
                  label="Картинка товара 1"
                  filled
                  prepend-icon="mdi-camera"
                  class="col-4 pa-2"
                  v-model="image_1"
                ></v-file-input>
                <v-file-input
                  label="Картинка товара 2"
                  filled
                  prepend-icon="mdi-camera"
                  class="col-4 pa-2"
                  v-model="image_2"
                ></v-file-input>
                <v-file-input
                  label="Картинка товара 3"
                  filled
                  prepend-icon="mdi-camera"
                  class="col-4 pa-2"
                  v-model="image_3"
                ></v-file-input>
              </v-row>
            </v-col>
          </v-row>
        </v-card>
      </v-form>
    </v-container>
  </v-content>
</template>

<script>
  import {mapGetters} from 'vuex';

	export default {
		name: "CreateProducts",
		data: () => ({
			required: [v => !!v || 'Обязательное поле!'],
			title: '',
			model: '',
			price: '',
			content: '',
			pop_status: false,
			amount: '',
			category: '',
			manufacturer: '',
			execution: '',
			appointment: '',
      power: '',
			premises: '',
			height: '',
			width: '',
			depth: '',
			chamber: '',
			warranty: '',
      image_1: null,
      image_2: null,
      image_3: null,
      categories: [],
      manufacturers: [],
		}),
    computed: {
			...mapGetters(['getAdmin', 'isCreated']),
    },
    mounted() {
			this.clear();
    },
		methods: {
			clear() {
				this.title = '';
				this.model = '';
				this.price = '';
				this.content = '';
				this.pop_status = false;
				this.amount = '';
				this.category = '';
				this.manufacturer = '';
				this.execution = '';
				this.appointment = '';
				this.power = '';
				this.premises = '';
				this.height = '';
				this.width = '';
				this.depth = '';
				this.chamber = '';
				this.warranty = '';
				this.image_1 = null;
				this.image_2 = null;
				this.image_3 = null;
      },
      submit() {
				this.$refs.form.validate();
				let formData = new FormData();
				if (this.image_1) formData.append('image_1', this.image_1);
				if (this.image_2) formData.append('image_2', this.image_2);
				if (this.image_3) formData.append('image_3', this.image_3);

				const data = {
					username: btoa(this.getAdmin.username),
          password: btoa(this.getAdmin.password),
					title: this.title,
					model: this.model,
					price: this.price,
					content: this.content,
					pop_status: this.pop_status,
					amount: this.amount,
					category: this.category,
					manufacturer: this.manufacturer,
					execution: this.execution,
					appointment: this.appointment,
          power: this.power,
					premises: this.premises,
					height: this.height,
					width: this.width,
					depth: this.depth,
					chamber: this.chamber,
					warranty: this.warranty,
					image_1: this.image_1,
					image_2: this.image_2,
					image_3: this.image_3,
        };

				formData.append('data', JSON.stringify(data));

				this.$store.dispatch('createProduct', formData);

				setTimeout(() => {
					if (this.isCreated) {
						this.$router.push({name: 'admin_products'});
					}
        }, 500)
      },
    },
	}
</script>

<style scoped>

</style>