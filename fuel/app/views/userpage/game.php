<?php
use Fuel\Core\Session;

$member = Session::get('member');
$admin = Session::get('admin');
if (!is_null($admin)) {
    // 拉霸機頁面只讓一般會員進入 管理員無法進入
    header('Location: /apis/user/home');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>拉霸機</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- use element-ui -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="//unpkg.com/element-ui/lib/umd/locale/zh-TW.js"></script>
    
    <style>
        /* 拉霸動畫效果 */
        @-webkit-keyframes flip-horizontal-bottom {
            0% {
                -webkit-transform: rotateX(0);
                transform: rotateX(0);
            }
            25% {
                -webkit-transform: rotateX(90deg);
                transform: rotateX(90deg);
            }
            50% {
                -webkit-transform: rotateX(180deg);
                transform: rotateX(180deg);
            }
            75% {
                -webkit-transform: rotateX(270deg);
                transform: rotateX(270deg);
            }
            100% {
                -webkit-transform: rotateX(0);
                transform: rotateX(0);
            }
        }
        @keyframes flip-horizontal-bottom {
            0% {
                -webkit-transform: rotateX(0);
                transform: rotateX(0);
            }
            25% {
                -webkit-transform: rotateX(90deg);
                transform: rotateX(90deg);
            }
            50% {
                -webkit-transform: rotateX(180deg);
                transform: rotateX(180deg);
            }
            75% {
                -webkit-transform: rotateX(270deg);
                transform: rotateX(270deg);
            }
            100% {
                -webkit-transform: rotateX(0);
                transform: rotateX(0);
            }
        }
    </style>
</head>
<body>
    <div id="bar">
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
                    <!-- 以一般會員登入 才顯示會員資料頁面連結 -->
                    <li class="nav-item" v-if="isLogin">
                        <a class="nav-link" href="/apis/user/memberinfo">會員資料</a>
                    </li>
                    <!-- 以管理員登入 才顯示後台管理頁面連結 -->
                    <li class="nav-item" v-if="isAdmin">
                        <a class="nav-link" href="/apis/user/memberlist">會員管理(管理員後台)</a>
                    </li>
                </ul>
                <!-- 未登入顯示 登入 與 註冊帳號 -->
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
        <!-- 頁面內容 -->
        <br>
        <h3>BBIN 拉霸機</h3>
        <hr>
        <!-- 判斷是否為會員登入 -->
        <?php
            if (!is_null($member)) {
                echo "歡迎 " . $member; 
            } else {
                echo "歡迎 訪客"; 
            }
        ?>
        <br><br>
        <!-- 顯示帳號狀態 -->
        {{ accountStatus }}
        <br><br>
        錢包餘額：
        <label for="">{{ wallet }}</label>
        <br><br>
        <!-- 操作說明區 -->
        <div class="accordion col-md-6" id="gameIntroduce">
            <div class="card">
                <div class="card-header" id="first">
                    <h2>
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            遊戲說明
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="first" data-parent="#gameIntroduce">
                    <div class="card-body">
                        1. 在想下注的注項，填上想下注的金額，不下注的注項請填"0"
                        <br><br>
                        2. 確認下注注項與下注金額後，按"下注"按鈕，鎖定此次下注
                        <br><br>
                        3. 若要修改，可點選重新下注進行重選，選好後一樣按下"下注"按鈕，鎖定此次下注
                        <br><br>
                        4. 鎖定完成後，按下"GO"按鈕進行拉霸，即可馬上得知結果與派彩獎金
                        <br><br>
                        5. 若要再次遊玩，則再依照步驟1~4操作即可
                    </div>
                </div>
            </div>
        </div>

        <!-- 拉霸開始按鈕 與 盤面 -->
        <br>
        <button class="btn btn-primary" v-on:click="go" v-bind:disabled="goBtn">GO!</button>
        <br><br>

        <div class="row">
            <div class="col-sm-2 table-bordered text-center" v-bind:style="{ animation: aniKeyframe, backgroundColor: setColor, fontWeight: fWeight }">{{ barA }}</div>
            <div class="col-sm-2 table-bordered text-center" v-bind:style="{ animation: aniKeyframe, backgroundColor: setColor, fontWeight: fWeight }">{{ barB }}</div>
            <div class="col-sm-2 table-bordered text-center" v-bind:style="{ animation: aniKeyframe, backgroundColor: setColor, fontWeight: fWeight }">{{ barC }}</div>
            <div class="col-sm-2 table-bordered text-center" v-bind:style="{ animation: aniKeyframe, backgroundColor: setColor, fontWeight: fWeight }">{{ barD }}</div>
        </div>
        <br>
        <!-- 測試用訊息 -->
        <label for="">是否中獎 - 拉霸盤面 - 中獎注項 - 此次下注中獎注項 - 此次下注中獎金額 - 是否出現BONUS - SQL儲存狀況</label>
        <br>
        <label for="">{{ result }}</label>
        <br>
        
        <!-- 重新下注(重設) 下注(鎖定注項) 及 注項賠率顯示 金額輸入區 -->
        <button class="btn btn-primary" v-on:click="clearAll" v-bind:disabled="resetDisabled">重新下注</button>
        <br><br>
        <button class="btn btn-primary" v-on:click="saveAll" v-bind:disabled="editDisabled">下注</button>
        <br><br>
        <table class="bet table-bordered w-auto">
            <tr>
                <th class="text-center">注項</th>
                <th class="text-center"><label>0個B</label></th>
                <th class="text-center"><label>1個B</label></th>
                <th class="text-center"><label>2個B</label></th>
                <th class="text-center"><label>3個B</label></th>
                <th class="text-center"><label>4個B</label></th>
                <th class="text-center"><label>BBBB</label></th>
                <th class="text-center"><label>IIII</label></th>
                <th class="text-center"><label>NNNN</label></th>
                <th class="text-center"><label>****</label></th>
            </tr>
            <tr>
                <th class="text-center">賠率</th>
                <th class="text-center">20</th>
                <th class="text-center">12</th>
                <th class="text-center">4</th>
                <th class="text-center">12</th>
                <th class="text-center">20</th>
                <th class="text-center">30</th>
                <th class="text-center">50</th>
                <th class="text-center">50</th>
                <th class="text-center">100</th>
            </tr>
            <tr>
                <th class="text-center">金額</th>
                <th class="text-center"><el-input-number size="small" v-model="betA" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betB" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betC" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betD" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betE" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betF" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betG" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betH" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
                <th class="text-center"><el-input-number size="small" v-model="betI" :min="0" :max="5000" :step="1" step-strictly :disabled="editDisabled"></el-input-number></th>
            </tr>
        </table>
        <hr>

        <!-- 各注項投注金額 及 派彩結果 顯示區 -->
        <table class="table-bordered w-75">
            <tr>
                <th class="text-center"></th>
                <th class="text-center"><label>0個B</label></th>
                <th class="text-center"><label>1個B</label></th>
                <th class="text-center"><label>2個B</label></th>
                <th class="text-center"><label>3個B</label></th>
                <th class="text-center"><label>4個B</label></th>
                <th class="text-center"><label>BBBB</label></th>
                <th class="text-center"><label>IIII</label></th>
                <th class="text-center"><label>NNNN</label></th>
                <th class="text-center"><label>****</label></th>
                <th class="text-center"><label>投注/派彩總金額</label></th>
            </tr>
            <tr>
                <th class="text-center">各注項投注金額</th>
                <th class="text-center"><label>{{ betList[0] }}</label></th>
                <th class="text-center"><label>{{ betList[1] }}</label></th>
                <th class="text-center"><label>{{ betList[2] }}</label></th>
                <th class="text-center"><label>{{ betList[3] }}</label></th>
                <th class="text-center"><label>{{ betList[4] }}</label></th>
                <th class="text-center"><label>{{ betList[5] }}</label></th>
                <th class="text-center"><label>{{ betList[6] }}</label></th>
                <th class="text-center"><label>{{ betList[7] }}</label></th>
                <th class="text-center"><label>{{ betList[8] }}</label></th>
                <th class="text-center"><label>{{ totalBetMoney }}</label></th>
            </tr>
            <tr>
                <th class="text-center">各注項派彩結果</th>
                <th class="text-center"><label>{{ winMoney[0] }}</label></th>
                <th class="text-center"><label>{{ winMoney[1] }}</label></th>
                <th class="text-center"><label>{{ winMoney[2] }}</label></th>
                <th class="text-center"><label>{{ winMoney[3] }}</label></th>
                <th class="text-center"><label>{{ winMoney[4] }}</label></th>
                <th class="text-center"><label>{{ winMoney[5] }}</label></th>
                <th class="text-center"><label>{{ winMoney[6] }}</label></th>
                <th class="text-center"><label>{{ winMoney[7] }}</label></th>
                <th class="text-center"><label>{{ winMoney[8] }}</label></th>
                <th class="text-center"><label>{{ totalWinMoney }}</label></th>
            </tr>
        </table>
        <br>
    </div>

    <script>
        ELEMENT.locale(ELEMENT.lang.zhTW); // ELEMENT 套件語言設定
        let bar = new Vue({
            el: "#bar",
            data: {
                betList: [], // 儲存各注項投注金額
                // barA ~ barB 為 拉霸盤面
                barA: "B",
                barB: "B",
                barC: "I",
                barD: "N",
                // betA ~ betI 為 各注項下注金額
                betA: "0",
                betB: "0",
                betC: "0",
                betD: "0",
                betE: "0",
                betF: "0",
                betG: "0",
                betH: "0",
                betI: "0",
                result: [], // 結果回傳暫存
                editDisabled : false, // 處理 下注按鈕 與 各注項金額輸入區 狀態 (按下下注按鈕後 將下注 與 金額輸入區鎖定)
                goBtn: true, // 處理 拉霸開始按鈕 狀態 (未完成下注 無法開始拉霸) 
                resetDisabled: false, // 處理 重新下注按鈕 狀態
                winMoney: [], // 儲存各注項派彩結果
                // 投注 / 派彩總金額 / 目前餘額
                totalBetMoney: "",
                totalWinMoney: "",
                wallet: "",
                accountStatus: "", // 取得帳號狀態
                // 判斷登入狀態 (未登入/一般使用者登入/管理員登入)
                isLogin: false,
                isAdmin: false,
                aniKeyframe: "", // 存放拉霸動畫效果
                setColor: "rgb(255, 255, 255)", // 拉霸盤面顏色
                fWeight: "bold", // 拉霸盤面字體粗細
                randBBIN: ['B', 'B', 'I', 'N', '*', '*'], // 拉霸轉動時 隨機產生值
            },
            mounted() {
                // 進入(或重整)頁面時 取得登入與帳號狀態 以及 目前錢包餘額
                this.checkStatus();
                this.getMoney();
            },
            methods: {
                // 拉霸開獎
                go() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'go');
                    formData.append('value', this.betList);
                    axios.post('/apis/ajax/game', formData)
                        .then(function (response) {
                            _this.result = response.data;

                            let temp = _this.result.split('-');
                                
                            /* 取得回傳拉霸盤面 */ 
                            let barView = temp[1];
                            barView = barView.replace(/[[]/gm,"");
                            barView = barView.replace(/["]]/gm,"");
                            barView = barView.replace(/["]/gm,"");
                            barView = barView.split(',');

                            // 啟動拉霸動畫效果 (原地翻轉 0.5 * 10 = 5sec)
                            _this.aniKeyframe = "flip-horizontal-bottom 0.5s cubic-bezier(0.25, 0.1, 0.25, 1) 10 both";
                            _this.setColor = "rgb(10, 227, 245)";

                            // 動畫進行過程 拉霸盤面隨機生成值 
                            let timer = 0;
                            let interval = setInterval(function () {
                                if (timer === 18) {
                                    clearInterval(interval);
                                }
                                _this.barA = _this.randBBIN[Math.floor(Math.random() * 6)];
                                _this.barB = _this.randBBIN[Math.floor(Math.random() * 6)];
                                _this.barC = _this.randBBIN[Math.floor(Math.random() * 6)];
                                _this.barD = _this.randBBIN[Math.floor(Math.random() * 6)];
                                timer++;
                            }, 250);

                            // 拉霸盤面 與 派彩結果 直到動畫結束才顯示(5sec)
                            setTimeout(function () {
                                // 顯示拉霸盤面
                                _this.barA = barView[0];
                                _this.barB = barView[1];
                                _this.barC = barView[2];
                                _this.barD = barView[3];
                                

                                // 顯示派彩結果與派彩總金額
                                _this.winMoney = temp[4];
                                _this.winMoney = _this.winMoney.replace(/[[]/gm,"");
                                _this.winMoney = _this.winMoney.replace(/["]]/gm,"");
                                _this.winMoney = _this.winMoney.replace(/["]/gm,"");
                                _this.winMoney = _this.winMoney.split(',');
                                const reducer = (accumulator, currentValue) => parseInt(accumulator) + parseInt(currentValue);
                                _this.totalWinMoney = _this.winMoney.reduce(reducer);

                                _this.aniKeyframe = ""; // 動畫效果結束 移除Keyframe
                                _this.setColor = "rgb(255, 128, 128)"; // 顯示開獎結果時 變換盤面顏色
                                _this.getMoney(); // 完成開獎 更新會員目前餘額
                            }, 5000);
                                
                            _this.goBtn = true; // 下一次遊玩 須重新下注 => 將GO按鈕禁用
                        }).catch(function (error){
                            alert(error);
                        });
                },
                // 重新下注： 清空下注金額 重設各欄位狀態 並再次取得目前餘額
                clearAll() {
                    this.betList = [];
                    this.winMoney = [];
                    this.betA = "0";
                    this.betB = "0";
                    this.betC = "0";
                    this.betD = "0";
                    this.betE = "0";
                    this.betF = "0";
                    this.betG = "0";
                    this.betH = "0";
                    this.betI = "0";
                    this.totalBetMoney = "";
                    this.totalWinMoney = "";
                    this.editDisabled = false;
                    this.goBtn = true;
                    this.barA = "B";
                    this.barB = "B";
                    this.barC = "I";
                    this.barD = "N";
                    this.result = [];
                    this.getMoney();
                    this.setColor = "rgb(255, 255, 255)";
                },
                // 鎖定下注注項
                saveAll() {
                    if (this.betA + this.betB + this.betC + this.betD + this.betE + this.betF + this.betG + this.betH + this.betI <= 0) {
                        // 下注金額總和小於或等於零 代表使用者未下注 跳出提示
                        alert('至少下一注');
                    } else {
                        // 儲存各注項投注金額
                        this.betList.push(this.betA);
                        this.betList.push(this.betB);
                        this.betList.push(this.betC);
                        this.betList.push(this.betD);
                        this.betList.push(this.betE);
                        this.betList.push(this.betF);
                        this.betList.push(this.betG);
                        this.betList.push(this.betH);
                        this.betList.push(this.betI);

                        // 判斷各注項金額是否為空白(未填) 或是超過5000
                        let blankAndZero = false;
                        for (let i = 0; i < this.betList.length; i++) {
                            if (this.betList[i] == null || this.betList[i] > 5000) {
                                blankAndZero = true;
                            }
                        }
                        console.log(typeof this.betList[0]);
                        if (blankAndZero == true) {
                            alert('單一注項金額最高為5000，且任一注不能留空');
                            console.log(typeof this.betList[0]);
                            this.betList = [];
                        } else {
                            // 計算投注總金額
                            const reducer = (accumulator, currentValue) => parseInt(accumulator) + parseInt(currentValue);
                            this.totalBetMoney = this.betList.reduce(reducer);
                            if (this.wallet - this.totalBetMoney < 0) {
                                alert("投注總金額超過目前餘額，請重新下注");
                                this.clearAll();
                            } else {
                                this.editDisabled = true;
                                this.goBtn = false;
                                this.wallet = this.wallet - this.totalBetMoney;
                            }
                            
                        }
                    }
                },
                getMoney() {
                    // 取得會員目前餘額
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'getMoney');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/game', formData)
                        .then(function (response) {
                            _this.wallet = response.data;
                        }).catch(function (error){
                            alert(error);
                        });
                },
                checkStatus() {
                    // 檢查玩家狀態 被停權則無法進行遊戲
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'checkStatus');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/game', formData)
                        .then(function (response) {
                            if (response.data == 'lock') {
                                alert('此帳號已被停權，暫時無法下注');
                                _this.editDisabled = true;
                                _this.goBtn = true;
                                _this.resetDisabled = true;
                                _this.isLogin = true;
                                _this.isAdmin = false;
                                _this.accountStatus = "帳號狀態：停權(無法下注)";
                            } else if (response.data == 'on') {
                                _this.editDisabled = false;
                                _this.goBtn = true;
                                _this.resetDisabled = false;
                                _this.isLogin = true;
                                _this.isAdmin = false;
                                _this.accountStatus = "帳號狀態：啟用(可以下注)";
                            } else if (response.data == '未登入') {
                                _this.editDisabled = true;
                                _this.goBtn = true;
                                _this.resetDisabled = true;
                                _this.isLogin = false;
                                _this.isAdmin = false;
                                _this.accountStatus = "帳號狀態：未登入(無法下注)";
                            }
                        }).catch(function (error){
                            alert(error);
                        });
                },
                // 帳號登出
                logout() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'logout');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/game', formData)
                        .then(function (response) {
                            // _this.result = response.data;
                            alert('登出成功');
                            _this.isLogin = false;
                            _this.isAdmin = false;
                            window.location.reload(true);
                        }).catch(function (error) {
                            alert(error);
                        });
                }
            },
        });
    </script>
</body>
</html>