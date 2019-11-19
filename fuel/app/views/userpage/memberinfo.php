<?php
use Fuel\Core\Session;

$member = Session::get('member');
if (is_null($member)) {
    header('Location: /apis/user/login');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>member page</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div id="msg">
        <h1>Member Page</h1>
        <hr>
        會員資料
        <br><br>
        <table class="table table-bordered">
            <tbody v-for="(item, index) in userData" :key="index">
                <tr>
                    <th scope="row">帳號</th>
                    <td>{{ item.account }}</td>
                </tr>
                <tr>
                    <th scope="row">密碼</th>
                    <td>••••••••••</td>
                </tr>
                <tr>
                    <th scope="row">目前餘額</th>
                    <td>{{ item.money }}</td>
                </tr>
                <tr>
                    <th scope="row">帳號狀態</th>
                    <td>{{ item.status }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        修改密碼
        <br><br>
        <label for="">輸入舊密碼: </label>
        <input type="password" maxlength="20" v-model="oldPwd">
        <br><br>
        <label for="">輸入新密碼: </label>
        <input type="password" maxlength="20" v-model="newPwd">
        <br><br>
        <button v-on:click="changePwd">修改密碼</button>
        <hr>
        <button v-on:click="logout">Logout</button>
        <hr>
    </div>
    

    <script>
        let msg = new Vue({
            el: "#msg",
            data: {
                result: "",
                userData: [],
                oldPwd: "",
                newPwd: "",
                chPwdResult: ""
            },
            mounted: function () {
                // mounted 時 ajax 取得目前登入帳號
                this.nowSession();
            },
            methods: {
                logout() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'logout');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.result = response.data;
                            alert('登出成功');
                            // console.log(typeof _this.result);
                            window.location.replace(_this.result);
                        }).catch(function (error) {
                            _this.result = error;
                        });
                },
                nowSession() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'nowSession');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.userData = response.data;
                            // console.log(_this.userData);
                        }).catch(function (error) {
                            _this.userData = error;
                        });
                },
                changePwd() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'changePwd');
                    formData.append('value', `${this.oldPwd}|${this.newPwd}`);
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.chPwdResult = response.data;
                            console.log(_this.chPwdResult);
                        }).catch(function (error) {
                            _this.chPwdResult = error;
                        });
                }
            },
        })
    </script>
</body>
</html>