<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>
<body>
    <h1>Login</h1>
    <hr>
    <div id="form">
        <label for="">Account: </label>
        <input type="text" maxlength="20" v-model="account">
        <br><br>
        <label for="">Password: </label>
        <input type="password" maxlength="20" v-model="password">
        <br><br>
        <button type="button" v-on:click="login">login</button>
        <hr>
        {{ result }}
    </div>

    <script>
        let form = new Vue({
            el: "#form",
            data: {
                account: "",
                password: "",
                result: ""
            },
            methods: {
                login() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('account', this.account);
                    formData.append('password', this.password);

                    axios.post('/apis/ajax/login', formData)
                        .then(function (response) {
                            _this.result = response.data;
                            // console.log(_this.result.length);
                        }).catch(function (error) {
                            _this.result = error;
                        });
                }
            }
        })
    </script>
</body>
</html>