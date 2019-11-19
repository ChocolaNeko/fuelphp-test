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
                            if (_this.result == 'memberlist') {
                                alert("管理員登入成功，將跳轉至會員管理頁面");
                                window.location.replace(`/apis/user/${_this.result}`);
                            } else if (_this.result == 'memberinfo') {
                                alert("會員登入成功，將跳轉至會員資訊頁面");
                                window.location.replace(`/apis/user/${_this.result}`);
                            } else if (_this.result == 'ban') {
                                alert("此帳號已被停權");
                                window.location.replace('/apis/user/login');
                            } else {
                                alert(_this.result);
                            }
                        }).catch(function (error) {
                            _this.result = error;
                        });
                }
            }
        })
    </script>
</body>
</html>