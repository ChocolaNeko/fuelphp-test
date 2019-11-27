<?php

use Fuel\Core\Session;

$admin = Session::get('admin');
$member = Session::get('member');
// 若已登入 進入此頁面會跳轉至 後台(管理員登入) 或 會員資料(一般會員登入)
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
    <title>登入</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>
<body>
    <div id="form">
        <!-- 頁面上方 navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/apis/user/home">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/apis/user/game">拉霸機</a>
                    </li>
                    <!-- 登入頁面 不顯示會員資料/後台管理按鈕 -->
                </ul>
                <!-- 登入頁面 不顯示登入/登出按鈕 -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/apis/user/reg">註冊帳號</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- 頁面內容 -->
        <br>
        <h1>登入</h1>
        <hr>
        <label for="">帳號: </label>
        <input type="text" maxlength="20" v-model="account">
        <br><br>
        <label for="">密碼: </label>
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