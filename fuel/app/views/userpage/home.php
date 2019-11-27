<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
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
    <div id="home">
        <!-- 頁面上方navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/apis/user/home">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- 以一般會員登入 才顯示拉霸機連結 -->
                    <li class="nav-item" v-if="!isAdmin">
                        <a class="nav-link" href="/apis/user/game">拉霸機</a>
                    </li>
                    <!-- 以一般會員登入 才顯示會員資料連結 -->
                    <li class="nav-item" v-if="isMember">
                        <a class="nav-link" href="/apis/user/memberinfo">會員資料</a>
                    </li>
                    <!-- 以管理員登入 才顯示後台管理 -->
                    <li class="nav-item" v-if="isAdmin">
                        <a class="nav-link" href="/apis/user/memberlist">會員管理(管理員後台)</a>
                    </li>
                </ul>
                <!-- 未登入顯示 登入/註冊帳號 -->
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
        <!-- 頁面主要內容 -->
        <br>
        <h1>Home</h1>
        <hr>
        <h4><?php echo $loadTime; ?></h4>
        <br>
        <h4>歡迎 {{ msg }}</h4>
        <hr>
        拉霸動畫效果測試
        <button id="goBtn" v-on:click="go">GO</button>
        <table class="table table-bordered">
            <tr>
                <th class="text-center" v-bind:style="{ animation: aniKeyframe, 'background-color': test }">{{ barA }}</th>
                <th class="text-center" v-bind:style="{ animation: aniKeyframe, 'background-color': test }">{{ barB }}</th>
                <th class="text-center" v-bind:style="{ animation: aniKeyframe, 'background-color': test }">{{ barC }}</th>
                <th class="text-center" v-bind:style="{ animation: aniKeyframe, 'background-color': test }">{{ barD }}</th>
            </tr>
        </table>
    </div>
    
    <script>
        let home = new Vue({
            el: "#home",
            data: {
                isLogin: false,
                isMember: false,
                isAdmin: false,
                barA: "B",
                barB: "B",
                barC: "I",
                barD: "N",
                msg: "",
                aniKeyframe: "",
                test: "gray",
                randABC: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
            },
            mounted() {
                this.checkLogin();
            },
            methods: {
                go() {
                    this.aniKeyframe = "flip-horizontal-bottom 0.5s cubic-bezier(0.455, 0.030, 0.515, 0.955) 10 both";
                    let _this = this;
                    let timer = 0;
                    let interval = setInterval(function () {
                        if (timer === 9) {
                            clearInterval(interval);
                        }
                        _this.barA = _this.randABC[Math.floor(Math.random() * 10)];
                        _this.barB = _this.randABC[Math.floor(Math.random() * 10)];
                        _this.barC = _this.randABC[Math.floor(Math.random() * 10)];
                        _this.barD = _this.randABC[Math.floor(Math.random() * 10)];
                    }, 500);
                    setTimeout(function () {
                        _this.barA = Math.floor(Math.random() * 10);
                        _this.barB = Math.floor(Math.random() * 10);
                        _this.barC = Math.floor(Math.random() * 10);
                        _this.barD = Math.floor(Math.random() * 10);
                        _this.aniKeyframe = "";
                    }, 5000);
                    
                },
                // 檢查目前登入狀態
                checkLogin() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'checkLogin');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/home' , formData)
                        .then(function (response) {
                            if (response.data == 'admin') {
                                _this.isLogin = true;
                                _this.isAdmin = true;
                                _this.isMember = false;
                                _this.msg = response.data;
                            } else if (response.data == 'no') {
                                _this.isLogin = false;
                                _this.isAdmin = false;
                                _this.isMember = false;
                                _this.msg = "訪客";
                            } else {
                                _this.isLogin = true;
                                _this.isAdmin = false;
                                _this.isMember = true;
                                _this.msg = response.data;
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
                    axios.post('/apis/ajax/home' , formData)
                        .then(function (response) {
                            if (response.data == 'logout') {
                                alert("登出成功");
                                _this.isLogin = false;
                                _this.isMember = false;
                                _this.isAdmin = false;
                                window.location.reload(true);
                            }
                        }).catch(function (error) {
                                alert(error);
                        });
                }
            },
        });
    </script>
</body>
</html>