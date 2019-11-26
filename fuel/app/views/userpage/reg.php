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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script></head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/apis/user/home">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/reg">Registration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/game">Slot Game</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/memberinfo">Member Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/memberlist">Member List</a>
                </li>
            </ul>
        </div>
    </nav>
    <br>
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