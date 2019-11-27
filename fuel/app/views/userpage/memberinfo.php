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
    <title>會員資料</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div id="msg">
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
                    <li class="nav-item">
                        <a class="nav-link" href="/apis/user/memberinfo">會員資料</a>
                    </li>
                    <!-- 以管理員登入 才顯示後台管理 -->
                    <li class="nav-item" v-if="!isAdmin">
                        <a class="nav-link" href="/apis/user/memberlist">會員管理(管理員後台)</a>
                    </li>
                </ul>
                <!-- 未登入顯示 登入 與 註冊帳號 (未登入也無法進入此頁面 此列不一定要設定) -->
                <ul class="navbar-nav ml-auto" v-if="!isLogin">
                    <li class="nav-item">
                        <a class="nav-link" href="/apis/user/login">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/apis/user/reg">註冊帳號</a>
                    </li>
                </ul>
                <!-- 已登入顯示 登出 -->
                <ul class="navbar-nav ml-auto" v-if="isLogin">
                    <li class="nav-item">
                        <a class="nav-link" href="#" v-on:click="logout">登出</a>
                    </li>
                </ul>
            </div>
        </nav>
        <br>
        <!-- 頁面內標籤 -->
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-info-tab" data-toggle="tab" href="#info" role="tab"
                    aria-controls="nav-home" aria-selected="true">基本資料</a>
                <a class="nav-item nav-link" id="nav-changePwd-tab" data-toggle="tab" href="#changePwd" role="tab"
                    aria-controls="nav-home" aria-selected="true">修改密碼</a>
                <a class="nav-item nav-link" id="nav-record-tab" data-toggle="tab" href="#record" role="tab"
                    aria-controls="nav-profile" aria-selected="false">交易紀錄</a>
                <a class="nav-item nav-link" id="nav-betRecord-tab" data-toggle="tab" href="#betRecord" role="tab"
                    aria-controls="nav-contact" aria-selected="false">下注紀錄</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="nav-info-tab">
                <br>
                基本資料
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
            </div>
            <div class="tab-pane fade" id="changePwd" role="tabpanel" aria-labelledby="nav-changePwd-tab">
                <!-- 修改密碼 -->
                <br>
                修改密碼
                <br><br>
                <label for="">輸入舊密碼: </label>
                <input type="password" maxlength="20" v-model="oldPwd">
                <br><br>
                <label for="">輸入新密碼: </label>
                <input type="password" maxlength="20" v-model="newPwd">
                <br><br>
                <button v-on:click="changePwd">修改密碼</button>
                <label for="">{{ chPwdResult }}</label>
            </div>
            <div class="tab-pane fade" id="record" role="tabpanel" aria-labelledby="nav-record-tab">
                <br>
                交易紀錄
                <br><br>
                <table class="table table-bordered">
                    <tr>
                        <th>Id</th>
                        <th>交易時間</th>
                        <th>交易類別</th>
                        <th>交易金額</th>
                        <th>交易後金額</th>
                        <th>交易敘述</th>
                    </tr>
                    <tr v-for="(item, index) in record" :key="index">
                        <td>{{ index + 1 }}</td>              
                        <td>{{ item.update_time }}</td>                 
                        <td>{{ item.status }}</td>           
                        <td>{{ item.update_money }}</td>
                        <td>{{ item.current_money }}</td>
                        <td>{{ item.desc }}</td>
                    </tr>
                </table>
            </div>
            <div class="tab-pane fade" id="betRecord" role="tabpanel" aria-labelledby="nav-betRecord-tab">
                <br>
                下注紀錄
                <br><br>
                <table class="table table-bordered">
                    <tr>
                        <th>下注時間</th>
                        <th>注單編號</th>
                        <th>開獎結果</th>
                        <th>下注組合</th>
                        <th>下注總金額</th>
                        <th>中獎(派彩)金額</th>
                        <th>盈虧</th>
                        <th>下注結果</th>
                    </tr>
                    <tr v-for="(item, index) in betRecord" :key="index">
                        <td>{{ item.bet_time }}</td>              
                        <td>{{ item.serialNum }}</td>                 
                        <td>{{ item.win_list }}</td>           
                        <td>{{ item.bet_list }}</td>
                        <td>{{ item.total_bet_money }}</td>
                        <td>{{ item.total_reward }}</td>
                        <td>{{ item.total_win_money }}</td>
                        <td>{{ item.bet_result }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <!-- <button v-on:click="logout">登出</button> -->
    </div>
    

    <script>
        let msg = new Vue({
            el: "#msg",
            data: {
                result: "",
                userData: [],
                oldPwd: "",
                newPwd: "",
                chPwdResult: "",
                record: [],
                betRecord: [],
                isLogin: false,
                isAdmin: false,
            },
            mounted: function () {
                // mounted 時 ajax 取得目前登入帳號 交易紀錄 下注紀錄
                this.nowSession();
                this.getRecord();
                this.getBetRecord();
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
                            _this.isLogin = false;
                            _this.isAdmin = false;
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
                            _this.isLogin = true;
                            _this.isAdmin = true;
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
                            // console.log(_this.chPwdResult);
                        }).catch(function (error) {
                            _this.chPwdResult = error;
                        });
                },
                getRecord() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'getRecord');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.record = response.data;
                            // console.log(_this.record);
                        }).catch(function (error) {
                            _this.record = error;
                        });
                },
                getBetRecord() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'getBetRecord');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.betRecord = response.data;
                            _this.betRecord.forEach(e => console.log(e.win_list));
                            console.log("------------------------------");
                            _this.betRecord.forEach(e => console.log(e.bet_list));
                        }).catch(function (error) {
                            _this.betRecord = error;
                        });
                }
            },
        })
    </script>
</body>
</html>