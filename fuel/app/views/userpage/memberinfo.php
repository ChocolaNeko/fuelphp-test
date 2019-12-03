<?php
use Fuel\Core\Session;

// 非會員無法進入此頁面
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
    <!-- use element-ui -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="//unpkg.com/element-ui/lib/umd/locale/zh-TW.js"></script>
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
        <!-- 主要內容 - 頁面內標籤 -->
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
        <!-- 各標籤實際內容 -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="nav-info-tab">
                <!-- 基本資料 -->
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
                <!-- 交易紀錄 -->
                <br>
                交易時間：
                <el-date-picker v-model="startDateR" type="datetime" value-format="yyyy-MM-dd HH:mm:ss" placeholder="start date time"></el-date-picker>
                 至 
                <el-date-picker v-model="endDateR" type="datetime" value-format="yyyy-MM-dd HH:mm:ss" placeholder="end date time"></el-date-picker>
                <br><br>
                每頁顯示：
                <el-input-number size="small" v-model="listLengthR" :min="20" :max="100" :step="20" step-strictly></el-input-number>
                 筆資料
                <br><br>
                <button v-on:click="showRecord">查詢</button>
                <hr>
                <button v-on:click="prevR">Prev.</button>
                | 第 {{ countR }} 頁, 共 {{ totalPageR }} 頁 | 共 {{ totalR }} 筆資料 |
                <button v-on:click="nextR">Next.</button>
                <hr>
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
                <!-- 下注紀錄 -->
                <br>
                下注時間：
                <el-date-picker v-model="startDateB" type="datetime" value-format="yyyy-MM-dd HH:mm:ss" placeholder="start date time"></el-date-picker>
                 至 
                <el-date-picker v-model="endDateB" type="datetime" value-format="yyyy-MM-dd HH:mm:ss" placeholder="end date time"></el-date-picker>
                <br><br>
                每頁顯示：
                <el-input-number size="small" v-model="listLengthB" :min="20" :max="100" :step="20" step-strictly></el-input-number>
                 筆資料
                <br><br>
                <button v-on:click="showBetRecord">查詢</button>
                <hr>
                <button v-on:click="prevB">Prev.</button>
                | 第 {{ countB }} 頁, 共 {{ totalPageB }} 頁 | 共 {{ totalB }} 筆資料 |
                <button v-on:click="nextB">Next.</button>
                <br><br>
                <table class="table table-bordered">
                    <tr>
                        <th>Id</th>
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
                        <td>{{ index + 1 }}</td>
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
    </div>
    

    <script>
        ELEMENT.locale(ELEMENT.lang.zhTW); // ELEMENT 套件語言設定
        let msg = new Vue({
            el: "#msg",
            data: {
                result: "", // 登出後頁面導向
                userData: [], // 使用者基本資料
                oldPwd: "",
                newPwd: "",
                chPwdResult: "", // 密碼修改結果回傳
                // 取得的交易/下注紀錄
                record: [], 
                betRecord: [],
                // 是否登入 及 帳號類別判斷
                isLogin: false,
                isAdmin: false,
                // 交易紀錄 頁面計算
                countR: 1, // 目前頁數
                listLengthR: 20,  // 每頁顯示資料筆數
                totalPageR: 0, // 總資料頁數
                totalR: 0,  // 總資料筆數
                startDateR: "",  // 開始日期
                endDateR: "",  // 結束日期
                // 下注紀錄 頁面計算
                countB: 1, // 目前頁數
                listLengthB: 20,  // 每頁顯示資料筆數
                totalPageB: 0, // 總資料頁數
                totalB: 0,  // 總資料筆數
                startDateB: "",  // 開始日期
                endDateB: "",  // 結束日期
            },
            mounted: function () {
                // 載入時 取得目前登入帳號
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
                            _this.isLogin = false;
                            _this.isAdmin = false;
                            window.location.replace(_this.result);
                        }).catch(function (error) {
                            _this.result = error;
                        });
                },
                nowSession() {
                    // 取得目前登入帳號 以及帳號基本資料
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
                    // 修改密碼
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'changePwd');
                    formData.append('value', `${this.oldPwd}|${this.newPwd}`);
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.chPwdResult = response.data;
                        }).catch(function (error) {
                            _this.chPwdResult = error;
                        });
                },
                showRecord() {
                    // 取得交易紀錄
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'showRecord');
                    // POST 起始時間 結束時間 每頁顯示資料數
                    formData.append('value', `${this.startDateR}|${this.endDateR}|${this.listLengthR}`);
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.record = response.data; // 取得回傳資料
                            
                            // 查到資料 與 查不到資料 的顯示內容
                            if (_this.record.length != 0) {
                                _this.totalR = _this.record[0].totalColumnR; // 取得此次查詢 得到的總資料數
                                _this.totalPageR = Math.ceil(_this.totalR / _this.listLengthR); // 取得此次查詢 得到的總頁數
                                _this.countR = 1;
                            } else {
                                _this.totalR = 0;
                                _this.countR = 1;
                                _this.totalPageR = 1;
                            }
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                showBetRecord() {
                    // 取得下注紀錄
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'showBetRecord');
                    // POST 起始時間 結束時間 每頁顯示資料數
                    formData.append('value', `${this.startDateB}|${this.endDateB}|${this.listLengthB}`);
                    axios.post('/apis/ajax/memberinfo', formData)
                        .then(function (response) {
                            _this.betRecord = response.data; // 取得回傳資料

                            // 查到資料 與 查不到資料 的顯示內容
                            if (_this.betRecord.length != 0) {
                                _this.totalB = _this.betRecord[0].totalColumnB; // 取得此次查詢 得到的總資料數
                                _this.totalPageB = Math.ceil(_this.totalB / _this.listLengthB); // 取得此次查詢 得到的總頁數
                                _this.countB = 1;
                            } else {
                                _this.totalB = 0;
                                _this.countB = 1;
                                _this.totalPageB = 1;
                            }
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                // 交易紀錄換頁(往前)
                prevR() {
                    if (this.countR > 1) {
                        this.countR--;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spageR');
                        // POST 起始時間 結束時間 欲切換頁碼 一頁幾筆資料 總資料量
                        formData.append('value', `${this.startDateR}|${this.endDateR}|${this.countR}|${this.listLengthR}|${this.totalR}`);
                        axios.post('/apis/ajax/memberinfo', formData)
                            .then(function (response){
                                _this.record = response.data;  // 取得回傳資料
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                },
                // 交易紀錄換頁(往後)
                nextR() {
                    if (this.countR < this.totalPageR) {
                        this.countR++;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spageR');
                        // POST 起始時間 結束時間 欲切換頁碼 一頁幾筆資料 總資料量
                        formData.append('value', `${this.startDateR}|${this.endDateR}|${this.countR}|${this.listLengthR}|${this.totalR}`);
                        axios.post('/apis/ajax/memberinfo', formData)
                            .then(function (response){
                                _this.record = response.data;  // 取得回傳資料
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                },
                // 下注紀錄換頁(往前)
                prevB() {
                    if (this.countB > 1) {
                        this.countB--;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spageB');
                        // POST 起始時間 結束時間 欲切換頁碼 一頁幾筆資料 總資料量
                        formData.append('value', `${this.startDateB}|${this.endDateB}|${this.countB}|${this.listLengthB}|${this.totalB}`);
                        axios.post('/apis/ajax/memberinfo', formData)
                            .then(function (response){
                                _this.betRecord = response.data;  // 取得回傳資料
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                },
                // 下注紀錄換頁(往後)
                nextB() {
                    if (this.countB < this.totalPageB) {
                        this.countB++;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spageB');
                        // POST 起始時間 結束時間 欲切換頁碼 一頁幾筆資料 總資料量
                        formData.append('value', `${this.startDateB}|${this.endDateB}|${this.countB}|${this.listLengthB}|${this.totalB}`);
                        axios.post('/apis/ajax/memberinfo', formData)
                            .then(function (response){
                                _this.betRecord = response.data;  // 取得回傳資料
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                }
            },
        })
    </script>
</body>
</html>