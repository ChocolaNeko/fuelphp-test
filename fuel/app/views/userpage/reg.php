<?php

use Fuel\Core\Session;

$admin = Session::get('admin');
$member = Session::get('member');
if (!is_null($admin)) {
    header('Location: /apis/user/memberlist');
} elseif (!is_null($member)) {
    header('Location: /apis/user/memberinfo');
}

?>

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
        <label for="">Account: </label>
        <input type="text" maxlength="20" v-model="account">
        <br><br>
        <label for="">Password: </label>
        <input type="password" maxlength="20" v-model="password">
        <br><br>
        <button type="button" v-on:click="reg">reg</button>
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
                reg() {
                    // console.log('test');
                    let _this = this;
                    let formData = new FormData();
                    formData.append('account', this.account);
                    formData.append('password', this.password);

                    axios.post('/apis/ajax/regs', formData)
                        .then(function (response) {
                            _this.result = response.data;
                            _this.account = "";
                            _this.password = "";
                        }).catch(function (error) {
                            _this.result = error;
                        });
                }
            }
        })
    </script>
</body>
</html>