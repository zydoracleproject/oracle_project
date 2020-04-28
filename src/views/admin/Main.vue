<template>
  <v-content>
    <v-alert
      class="white--text mx-5 mt-5"
      type="info"
      elevation="5"
    >
      Добро пожаловать в Админ Панель магазина
    </v-alert>
    <v-container fluid class="pa-5">
      <v-row class="mt-4">
        <div>
          <v-card
            v-if="getUsersPerWeek"
            class="mx-4"
            max-width="650"
            elevation="5"
          >
            <v-sheet
              class="v-sheet--offset mx-auto"
              color="success"
              elevation="12"
              max-width="calc(100% - 16px)"
            >
              <v-sparkline
                :labels="usersPerWeekLabels"
                :value="usersPerWeek"
                color="white"
                line-width="3"
                padding="24"
                smooth
                label-size="10"
              ></v-sparkline>
            </v-sheet>

            <v-card-text class="pt-0 mt-3">
              <div class="title mb-2">Новые пользователи в этом месяце</div>
              <div class="subheading grey--text">Кол-во пользователей за последние недели</div>
              <v-divider class="my-2"></v-divider>
              <v-icon
                class="mr-2"
                small
              >
                mdi-clock
              </v-icon>
              <span class="caption grey--text">
          <b>{{totalUsers}} новых пользователей</b> за последний месяц ({{date}})
        </span>
            </v-card-text>
          </v-card>
          <v-card
            v-if="getVisitsPerDay"
            class="mx-4 mt-5"
            max-width="650"
            elevation="5"
          >
            <v-sheet
              class="v-sheet--offset mx-auto"
              color="success"
              elevation="12"
              max-width="calc(100% - 16px)"
            >
              <v-sparkline
                :labels="visitsPerDayLabels"
                :value="visitsPerDay"
                color="white"
                line-width="3"
                padding="24"
                smooth
                label-size="10"
              ></v-sparkline>
            </v-sheet>

            <v-card-text class="pt-0 mt-3">
              <div class="title mb-2">Новые посетители</div>
              <div class="subheading grey--text">Кол-во посетителей за последние дни</div>
              <v-divider class="my-2"></v-divider>
              <span class="caption grey--text">
          <b>{{getVisits.hosts}} новых посетителей</b> за сегодня ({{today}})
        </span>
              <div class="caption grey--text">
                <b>{{getVisits.views}} новых посещений</b> за сегодня ({{today}})
              </div>
            </v-card-text>
          </v-card>
        </div>
        <v-card class="mx-2 pa-2" width="300" v-if="getTotalAmount" elevation="5">
          <v-card-title>Количество</v-card-title>
          <v-card-text>
            <v-simple-table>
              <template v-slot:default>
                <thead>
                <tr>
                  <th class="text-left">Название</th>
                  <th class="text-left">Кол-во</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td class="text-left">Продукты</td>
                  <td class="text-center">{{getTotalAmount.products}}</td>
                </tr>
                <tr>
                  <td class="text-left">Категории</td>
                  <td class="text-center">{{getTotalAmount.categories}}</td>
                </tr>
                <tr>
                  <td class="text-left">Производители</td>
                  <td class="text-center">{{getTotalAmount.manufacturers}}</td>
                </tr>
                <tr>
                  <td class="text-left">Пользователи</td>
                  <td class="text-center">{{getTotalAmount.users}}</td>
                </tr>
                <tr>
                  <td class="text-left">Аккаунты</td>
                  <td class="text-center">{{getTotalAmount.accounts}}</td>
                </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-card-text>
        </v-card>
        <v-card class="mx-2 pa-1" width="530" elevation="5">
          <v-card-title>
            Товары
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Поиск"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-card-text>
            <v-data-table
              :headers="headers"
              :items="getProducts"
              :search="search"
              items-per-page="5"
            ></v-data-table>
          </v-card-text>
        </v-card>
      </v-row>
    </v-container>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Main",
		data: () => ({
			months: [
				'Январь',
				'Февраль',
				'Март',
				'Апрель',
				'Май',
				'Июнь',
				'Июль',
				'Август',
				'Сентябрь',
				'Октябрь',
				'Ноябрь',
				'Декабрь',
			],
			search: '',
			headers: [
				{text: 'Название', value: 'title'},
				{text: 'Категория', value: 'category_title'},
				{text: 'Производитель', value: 'manufacturer_title'},
				{text: 'Цена', value: 'price'},
			],
		}),
		computed: {
			...mapGetters(['getAdmin', 'getUsersPerWeek',
				'getStatsErrors', 'getTotalAmount', 'getVisits', 'getVisitsPerDay', 'getProducts']),
			usersPerWeek() {
				return Object.values(Object.fromEntries(Object.entries(this.getUsersPerWeek)
					.filter(v => v[0] !== 'total'))).map(v => parseInt(v));
			},
			usersPerWeekLabels() {
				return [
					`1 нед.(${this.usersPerWeek[0]})`,
					`2 нед.(${this.usersPerWeek[1]})`,
					`3 нед.(${this.usersPerWeek[2]})`,
					`4 нед.(${this.usersPerWeek[3]})`,
				];
			},
			visitsPerDay() {
				return Object.entries(this.getVisitsPerDay).map(v => v[1]).map(v => parseInt(v.view));
			},
			visitsPerDayLabels() {
				return [
					`${this.getVisitsPerDay.d1.date} (${this.visitsPerDay[0]})`,
					`${this.getVisitsPerDay.d2.date} (${this.visitsPerDay[1]})`,
					`${this.getVisitsPerDay.d3.date} (${this.visitsPerDay[2]})`,
					`${this.getVisitsPerDay.d4.date} (${this.visitsPerDay[3]})`,
				];
			},
			totalUsers() {
				return this.getUsersPerWeek.total;
			},
			date() {
				const date = new Date();
				return `${this.months[date.getMonth()]} ${date.getFullYear()}`;
			},
			today() {
				const date = new Date();
				return `${date.getDay()} ${this.months[date.getMonth()]} ${date.getFullYear()}`;
			},
		},
		mounted() {
			this.$store.dispatch('readUsersPerWeek', {
				username: btoa(this.getAdmin.username),
				password: btoa(this.getAdmin.password),
			});
			this.$store.dispatch('readTotalAmount', {
				username: btoa(this.getAdmin.username),
				password: btoa(this.getAdmin.password),
			});
			this.$store.dispatch('readVisits', {
				username: btoa(this.getAdmin.username),
				password: btoa(this.getAdmin.password),
			});
			this.$store.dispatch('readVisitsPerDay', {
				username: btoa(this.getAdmin.username),
				password: btoa(this.getAdmin.password),
			});
			this.$store.dispatch('readProducts', this.getAdmin);
		},
	}
</script>

<style scoped>

</style>