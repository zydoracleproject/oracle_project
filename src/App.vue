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
			login() {
				this.$http.post('http://localhost:8000/api/user/login.php', JSON.stringify({
          username: btoa('admin'),
          password: btoa('1234'),
          phone: '12',
          u_password: 'PassUser',
        })).then((res) => {
          // eslint-disable-next-line no-mixed-spaces-and-tabs
        	console.log(res);
        });
      },
      loginByToken() {
				this.$http.post('http://localhost:8000/api/user/login_by_token.php', JSON.stringify({
          username: btoa('admin'),
          password: btoa('1234'),
          remember_token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMjIifQ.x5s9vzSvYJwh-wfpuRIAJrd0ckt0Q20rmRVdcs_INk4',
        })).then((res) => {
          // eslint-disable-next-line no-mixed-spaces-and-tabs
        	console.log(res);
        });
      },
			createUser() {
				const data = {
					username: btoa('admin'),
          password: btoa('1234'),
          u_username: 'Username',
          u_password: 'PassUser',
          phone: '123',
        };

				const json = JSON.stringify(data);

				this.$http.post('http://localhost:8000/api/user/create.php', json).then((res) => {
					console.log(res);
				});
      },
			create() {
				const data = {
					username: btoa('admin'),
					password: btoa('1234'),
					id: 45,
					title: 'title title',
					price: 25.5,
					image_1: 'image2',
					image_2: 'image1',
					created_at: '04/17/2020 12:12:25'
				};

				const json = JSON.stringify(data);

				this.$http.post('http://localhost:8000/api/product/update.php', json).then((res) => {
					console.log(res);
				});
			},
			update() {
				const data = {
					username: btoa('admin'),
					password: btoa('1234'),
					id: 56,
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
				this.$http.post('http://localhost:8000/api/product/read.php', JSON.stringify({
					username: btoa('admin'),
					password: btoa('1234'),
				})).then((response) => {
					console.log(response);
				});
			},
			readOne() {
				this.$http.post('http://localhost:8000/api/product/read_one.php', JSON.stringify({
          username: btoa('admin'),
          password: btoa('1234'),
          id: '45'
        })).then((res) => {
          // eslint-disable-next-line no-mixed-spaces-and-tabs
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
