<template>
  <v-content>
    <v-alert
      class="white--text mx-5 mt-5 mb-10"
      type="info"
      elevation="5"
    >
      Центр управления товарами
    </v-alert>
    <v-container>
      <div class="row justify-space-between align-center mb-10 mx-5">
        <h1>Товары</h1>
        <v-btn
          v-if="getAdmin.username === 'admin'"
          :to="{name: 'product_create'}"
          color="success"
          large
          elevation="5">Создать товар</v-btn>
      </div>
      <spinner size="large" v-if="isProductsLoading"></spinner>
      <div class="row">
        <v-alert
          v-if="!getProducts.length"
          type="info"
          dense
          class="my-2 pa-3 py-3"
          color="grey"
        >
          В магазине не имеется товаров!
        </v-alert>
        <div class="wrapper col-12 col-sm-6 col-md-4 p-2"
             v-for="product in getProducts"
             :key="product.id">
          <v-card elevation="8">
            <v-row class="justify-space-between align-center mx-0 py-2">
              <v-chip
                color="pink"
                label
                text-color="white"
                v-if="product.pop_status"
              >
                <v-icon left>mdi-star</v-icon>
                Популярный
              </v-chip>
              <v-chip
                right
                label
                class="ml-auto"
                color="pink"
                text-color="white"
                style="font-size: 18px;"
              >
                <v-icon left>mdi-cash-100</v-icon>
                {{product.price}} тг.
              </v-chip>
            </v-row>
            <v-carousel
              cycle
              height="250"
              hide-delimiter-background
              show-arrows-on-hover
              light
            >
              <v-carousel-item
                v-for="(image, i) in [product.image_1, product.image_2, product.image_3]"
                :key="i"
              >
                <v-img :src="'/images/' + (image || 'notimage.png')" height="200"></v-img>
              </v-carousel-item>
            </v-carousel>
            <v-card-title>{{product.title}}</v-card-title>
            <v-card-subtitle style="font-size: 16px;">
              <div v-if="product.category_title" class="item">Категория - {{product.category_title}}</div>
              <div v-if="product.manufacturer_title" class="item">Производитель - {{product.manufacturer_title}}</div>
              <v-alert
                v-if="!product.category_title && !product.manufacturer_title"
                type="info"
                dense
                class="my-2 pa-3 py-3"
                color="grey"
              >
                Товар не имеет категорию и производителя
              </v-alert>
            </v-card-subtitle>
            <v-card-text>
              <div v-if="product.content">{{product.content}}</div>
              <v-alert
                v-if="!product.content"
                type="info"
                dense
                class="my-2 pa-3 py-3"
                color="grey"
              >
                Товар не имеет описание
              </v-alert>
              <v-divider></v-divider>
              <v-subheader>
                Создано - {{product.created_at}}
              </v-subheader>
              <v-subheader v-if="product.updated_at">
                Обновлено - {{product.updated_at}}
              </v-subheader>
              <v-subheader>
                Количество - {{product.amount || 0}}
              </v-subheader>
            </v-card-text>
            <v-expansion-panels class="mb-3">
              <v-expansion-panel>
                <v-expansion-panel-header class="subheading font-weight-bold">Характеристики</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-alert
                    v-if="!product.execution && !product.appointment && !product.power
                          && !product.premises && !product.height && !product.width
                          && !product.depth && !product.chamber && !product.warranty"
                    type="info"
                    dense
                    class="my-2 pa-3 py-3"
                    color="grey"
                  >
                    Товар не имеет характеристику
                  </v-alert>
                  <div class="px-3">
                    <div class="row justify-space-between" v-if="product.execution">
                      <div class="col col-6">Исполнение:</div>
                      <div class="col col-6 text-end">{{ product.execution}}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.appointment">
                      <div class="col col-6">Назначение:</div>
                      <div class="col col-6 text-end">{{ product.appointment }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.power">
                      <div class="col col-6">Мощность:</div>
                      <div class="col col-6 text-end">{{ product.power }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.premises">
                      <div class="col col-6">Площадь помещения (кв.м):</div>
                      <div class="col col-6 text-end">{{ product.premises }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.height">
                      <div class="col col-6">Высота (см):</div>
                      <div class="col col-6 text-end">{{ product.height }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.width">
                      <div class="col col-6">Ширина (см):</div>
                      <div class="col col-6 text-end">{{ product.width }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.depth">
                      <div class="col col-6">Глубина:</div>
                      <div class="col col-6 text-end">{{ product.depth }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.chamber">
                      <div class="col col-6">Тип камеры сгорания:</div>
                      <div class="col col-6 text-end">{{ product.chamber }}</div>
                    </div>

                    <div class="row justify-space-between" v-if="product.warranty">
                      <div class="col col-6">Гарантия (мес):</div>
                      <div class="col col-6 text-end">{{ product.warranty }}</div>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
            <v-divider></v-divider>
            <v-row class="mx-0">
              <v-btn
                :to="{name: 'product_update', params: {id: product.id}}"
                color="yellow darken-3"
                class="white--text ma-3">Редактировать</v-btn>
              <v-btn color="red" class="white--text ma-3" @click="deleteProduct(product.id)">Удалить</v-btn>
            </v-row>
          </v-card>
        </div>
      </div>
    </v-container>
  </v-content>
</template>

<script>
  import {mapGetters} from 'vuex';

	export default {
		name: "Products",
    data: () => ({
    }),
    computed: {
			...mapGetters(['getProducts', 'getAdmin', 'isProductsLoading']),
    },
    mounted() {
			this.$store.dispatch('readProducts', this.getAdmin);
    },
    methods: {
			deleteProduct(id) {
				this.$store.dispatch('deleteProduct', {
					username: btoa(this.getAdmin.username),
          password: btoa(this.getAdmin.password),
          id,
        });
				location.reload();
      }
    },
	}
</script>

<style scoped>

</style>