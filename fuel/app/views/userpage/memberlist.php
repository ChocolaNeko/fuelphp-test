<?php

use Fuel\Core\Session;

$admin = Session::get('admin');
if (is_null($admin)) {
    // echo $member;
    header('Location: /apis/user/login');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Member List</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <!-- 載入時用vfor刷出資料(ajax) -->
    <h1>Member List</h1>
    <hr>
    <h5>歡迎管理員 <?php echo $admin; ?></h5>
    <br>
    <div id="controll">
        <button v-on:click="logout">管理員登出</button>
        <hr>

        <table class="table table-bordered">
            <tr>
                <th>Id</th>
                <th >Account</th>
                <th>Money</th>
                <th>Status</th>
                <th>ban_btn</th>
                <th>lock_btn</th>
            </tr>
            <tr v-for="(item, index) in members" :key="index">
                <td>{{ index + 1 }}</td>              
                <td>{{ item.account }}</td>             
                <td>{{ item.money }}</td>               
                <td>{{ item.status }}</td>
                <td><button v-on:click="accBan(item.account)">凍結</button></td>               
                <td><button v-on:click="accLock(item.account)">停權</button></td>               
            </tr>
        </table>
    </div>

    <script>
        let controll = new Vue({
            el: "#controll",
            data: {
                result: "",
                members: [],
                id: "",
                ban: "",
                lock: ""
            },
            mounted: function () {
                this.showMembers();
            },
            methods: {
                showMembers() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'showMembers');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            _this.members = response.data;
                            // console.log(_this.members);
                            // console.log(typeof _this.members);
                        }).catch(function (error) {
                            _this.members = error;
                        });
                },
                logout() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'logout');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            _this.result = response.data;
                            alert('登出成功');
                            // console.log(typeof _this.result);
                            window.location.replace(_this.result);
                        }).catch(function (error) {
                            _this.result = error;
                        });
                },
                accBan(val) {
                    // set account ban (can't login)
                    // console.log(val);
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'accBan');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            _this.ban = response.data;
                            alert(`帳號 ${val} 凍結成功`);
                            _this.showMembers();
                        }).catch(function (error) {
                            _this.ban = error;
                            alert(_this.ban);
                        });
                },
                accLock(val) {
                    // set account lock (login OK, can't play)
                    // console.log(val);
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'accLock');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            _this.lock = response.data;
                            alert(`帳號 ${val} 停權成功`);
                            _this.showMembers();
                        }).catch(function (error) {
                            _this.lock = error;
                            alert(_this.lock);
                        });
                }
            },
        });
    </script>
</body>
</html>
