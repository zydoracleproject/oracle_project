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
      <v-card
        v-if="getUsersPerWeek"
        class="mt-4"
        max-width="450"
      >
        <v-sheet
          class="v-sheet--offset mx-auto"
          color="cyan"
          elevation="12"
          max-width="calc(100% - 32px)"
        >
          <v-sparkline
            :labels="usersPerWeekLabels"
            :value="usersPerWeek"
            color="white"
            line-width="2"
            padding="16"
            smooth
          ></v-sparkline>
        </v-sheet>

        <v-card-text class="pt-0 mt-3">
          <div class="title font-weight-light mb-2">Новые пользователи</div>
          <div class="subheading font-weight-light grey--text">Кол-во пользователей за последние недели</div>
          <v-divider class="my-2"></v-divider>
          <v-icon
            class="mr-2"
            small
          >
            mdi-clock
          </v-icon>
          <span class="caption grey--text font-weight-light">
          <b>{{totalUsers}} новых пользователей</b> за последний месяц ({{date}})
        </span>
        </v-card-text>
      </v-card>
    </v-container>
  </v-content>
</template>

<script>
	import {mapGetters} from 'vuex';

	export default {
		name: "Main",
		data: () => ({
			usersPerWeekLabels: [
				'1 неделя',
				'2 неделя',
				'3 неделя',
				'4 неделя',
			],
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
		}),
		computed: {
			...mapGetters(['getAdmin', 'getUsersPerWeek', 'getStatsErrors']),
			usersPerWeek() {
				return Object.values(Object.fromEntries(Object.entries(this.getUsersPerWeek)
					.filter(v => v[0] !== 'total'))).map(v => parseInt(v));
			},
			totalUsers() {
				return this.getUsersPerWeek.total;
			},
      date() {
				const date = new Date();
				return `${this.months[date.getMonth()]} ${date.getFullYear()}`;
      }
		},
		mounted() {
			this.$store.dispatch('readUsersPerWeek', {
				username: btoa(this.getAdmin.username),
				password: btoa(this.getAdmin.password),
			});
		}
	}
</script>

<style scoped>

</style>