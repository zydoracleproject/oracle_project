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
			this.read();
		},
		methods: {
			update() {
				const data = {
					id: 2,
					title: 'Product product Title',
					content: 'Product Content p p',
					images_id: null,
					model: 'Model',
					price: 12.25,
					status: '1',
					pop_status: '1',
					amount: 25,
					options_id: null,
					keywords: 'Keywords',
					description: 'Description',
					manufacturer_id: null,
					category_id: null,
					alias: 'Alias',
					created_at: '16-APR-20 07.57.03.000000000 PM',
				};

				// eslint-disable-next-line no-unused-vars
				const json = JSON.stringify(data);

				this.$http.post('http://localhost:8000/api/product/update.php', json).then((response) => {
					console.log(response);
				}).catch((error) => {
					console.log(error);
				});
			},
			search() {
				this.$http.post('http://localhost:8000/api/product/search.php', JSON.stringify({
					s: 'Product Title',
					username: 'admin',
					password: '1234',
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
