<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script></head>
<body>
    <h1>Registration</h1>
    <hr>
    <div id="form">
        <label for="">Name: </label>
        <input type="text" maxlength="20" v-model="name">
        <br><br>
        <label for="">Account: </label>
        <input type="text" maxlength="20" v-model="account">
        <br><br>
        <label for="">Password: </label>
        <input type="password" maxlength="20" v-model="password">
        <br><br>
        <label for="">Email: </label>
        <input type="text" v-model="email">
        <br><br>
        <label for="">Phone: </label>
        <input type="text" maxlength="20" v-model="phone">
        <br><br>
        <button type="button" v-on:click="reg">reg</button>
        <hr>
        {{ result }}
    </div>

    <script>
        let form = new Vue({
            el: "#form",
            data: {
                name: "",
                account: "",
                password: "",
                email: "",
                phone: "",
                result: ""
            },
            methods: {
                reg() {
                    // console.log('test');
                    let _this = this;
                    let formData = new FormData();
                    formData.append('name', this.name);
                    formData.append('account', this.account);
                    formData.append('password', this.password);
                    formData.append('email', this.email);
                    formData.append('phone', this.phone);

                    axios.post('/apis/ajax/regs', formData)
                        .then(function (response) {
                            _this.result = response.data;
                            _this.name = "";
                            _this.account = "";
                            _this.password = "";
                            _this.email = "";
                            _this.phone = "";
                        }).catch(function (error) {
                            _this.result = error;
                        });
                }
            }
        })
    </script>
</body>
</html>