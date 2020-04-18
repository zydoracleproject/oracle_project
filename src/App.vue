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
				this.$http.get('http://localhost:8000/api/product/search.php?s=Product').then((res) => {
					console.log(res);
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
