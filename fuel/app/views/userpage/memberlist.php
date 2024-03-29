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
    <title>管理員後台 - 會員管理</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- use element-ui -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="//unpkg.com/element-ui/lib/umd/locale/zh-TW.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div id="controll">
        <!-- 頁面上方 navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/apis/user/home">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- 以管理員登入 才顯示後台管理 -->
                    <li class="nav-item">
                        <a class="nav-link" href="/apis/user/memberlist">會員管理(管理員後台)</a>
                    </li>
                </ul>
                <!-- 已登入顯示 登出 -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" v-on:click="logout">登出</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- 頁面內容 -->
        <br>
        <h2>會員管理</h2>
        <hr>
        <h5>歡迎管理員 <?php echo $admin; ?></h5>
        <br><br>
        <!-- 載入時用vfor刷出資料(ajax) -->
        <table class="table table-bordered">
            <tr>
                <th>Id</th>
                <th >帳號</th>
                <th>金額</th>
                <th>帳號狀態</th>
                <th>下注紀錄</th>
                <th>交易紀錄</th>
                <th>金額調整</th>
                <th>凍結</th>
                <th>停權</th>
                <th>解除凍結/停權</th>
            </tr>
            <tr v-for="(item, index) in members" :key="index">
                <td>{{ index + 1 }}</td>              
                <td>{{ item.account }}</td>             
                <td>{{ item.money }}</td>               
                <td>{{ item.status }}</td>
                <td><button v-on:click="betRecord(item.account)">下注紀錄</button></td>
                <td><button v-on:click="record(item.account)">交易紀錄</button></td>
                <td>
                    <!-- <el-input-number size="small" v-model="moneyChange" :min="0" :max="5000" :step="1" step-strictly> -->
                    <input type="text" maxlength="20" v-model="moneyChange">
                    <button v-on:click="addMoney(item.account)">加錢</button>
                    <button v-on:click="subMoney(item.account)">扣錢</button>
                </td>
                <td><button v-on:click="accBan(item.account)">凍結</button></td>               
                <td><button v-on:click="accLock(item.account)">停權</button></td>               
                <td><button v-on:click="accOn(item.account)">解除凍結/停權</button></td>               
            </tr>
        </table>
    </div>

    <script>
        ELEMENT.locale(ELEMENT.lang.zhTW); // ELEMENT 套件語言設定
        let controll = new Vue({
            el: "#controll",
            data: {
                members: [], // 存放取出的會員資料
                moneyChange: "", // 取得輸入的金額(金額調整用)
            },
            mounted: function () {
                // 載入時 顯示所有會員資料
                this.showMembers();
            },
            methods: {
                // 取得所有會員資料 (排除管理員)
                showMembers() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'showMembers');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            _this.members = response.data;
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
                            alert('登出成功');
                            window.location.replace(response.data);
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                accBan(val) {
                    // 帳號凍結 (無法登入)
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'accBan');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            alert(`帳號 ${val} 凍結成功`);
                            _this.showMembers();
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                accLock(val) {
                    // 帳號鎖定 (可登入 但無法玩拉霸)
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'accLock');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            alert(`帳號 ${val} 停權成功`);
                            _this.showMembers();
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                accOn(val) {
                    // 帳號恢復啟用
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'accOn');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            alert(`帳號 ${val} 恢復啟用`);
                            _this.showMembers();
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                addMoney(val) {
                    // 加錢
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'addMoney');
                    formData.append('value', `${this.moneyChange}|${val}`);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            alert(`${response.data}`);
                            _this.moneyChange = "";
                            _this.showMembers();
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                subMoney(val) {
                    // 扣錢
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'subMoney');
                    formData.append('value', `${this.moneyChange}|${val}`);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            alert(`${response.data}`);
                            _this.moneyChange = "";
                            _this.showMembers();
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                record(val) {
                    // 查看指定會員交易紀錄 (將帳號名稱帶入頁面)
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'record');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            window.location.replace(response.data);
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                betRecord(val) {
                    // 查看指定會員下注紀錄 (將帳號名稱帶入頁面)
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'betRecord');
                    formData.append('value', val);
                    axios.post('/apis/ajax/memberlist', formData)
                        .then(function (response) {
                            window.location.replace(response.data);
                        }).catch(function (error) {
                            alert(error);
                        });
                }
            },
        });
    </script>
</body>
</html>
