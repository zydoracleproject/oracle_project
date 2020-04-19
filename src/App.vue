<template>
  <v-app id="app">
  </v-app>
</template>

<script>
	export default {
		name: 'App',
		data() {
			return {};
		},
		mounted() {
			this.search();
		},
		methods: {
			update() {
				const data = {
					username: btoa('admin'),
          password: btoa('1234'),
          id: 1,
					title: 'Category Title TITLE',
					keywords: 'Keywords',
					description: 'Description',
          created_at: '19-APR-20 12.00.00.000000000 AM',
				};

				// eslint-disable-next-line no-unused-vars
				const json = JSON.stringify(data);

				this.$http.post('http://localhost:8000/api/category/update.php', json).then((response) => {
					console.log(response);
				}).catch((error) => {
					console.log(error);
				});
			},
			search() {
				this.$http.post('http://localhost:8000/api/product/read_by_cat.php', JSON.stringify({
					cat_id: '1',
					username: btoa('admin'),
					password: btoa('1234'),
				})).then((res) => {
					console.log(res);
				});
			},
			read() {
				this.$http.post('http://localhost:8000/api/product/read_paging.php', JSON.stringify({
					username: btoa('admin'),
					password: btoa('1234'),
				})).then((response) => {
					console.log(response);
				});
			}
		},
	}
</script>

<style>
  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
  }
</style>
