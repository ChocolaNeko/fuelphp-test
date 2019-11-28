<?php
    // Asset::js(array('slotmachine.js'), array(), 'slotmachine', false);
    // Asset::add_path('assets/js/', array('js'));
    echo Asset::js('slotmachine.js', array(), null, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <!-- <script src="slotmachine.js"></script> -->
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
        <!-- 拉霸動畫效果測試 -->
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
        <hr>
        <!-- 拉霸測試2 -->
        <div id="randomize">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <div>
                            <div id="machine1" class="randomizeMachine">
                                <div>B</div>
                                <div>B</div>
                                <div>I</div>
                                <div>N</div>
                                <div>*</div>
                                <div>*</div>
                            </div>
                        </div>
                    </div>        
                    <div class="col-sm-3">
                        <div>
                            <div id="machine2" class="randomizeMachine">
                                <div>B</div>
                                <div>B</div>
                                <div>I</div>
                                <div>N</div>
                                <div>*</div>
                                <div>*</div>
                            </div>
                        </div>
                    </div>        
                    <div class="col-sm-3">
                        <div>
                            <div id="machine3" class="randomizeMachine">
                                <div>B</div>
                                <div>B</div>
                                <div>I</div>
                                <div>N</div>
                                <div>*</div>
                                <div>*</div>
                            </div>
                        </div>
                    </div>        
                    <div class="col-sm-3">
                        <div>
                            <div id="machine4" class="randomizeMachine">
                                <div>B</div>
                                <div>B</div>
                                <div>I</div>
                                <div>N</div>
                                <div>*</div>
                                <div>*</div>
                            </div>
                        </div>
                    </div>        
                </div>
            </div>
        </div>
        <hr>
        <button id="randomizeButton" type="button" class="btn btn-danger btn-lg" v-on:click="next">Shuffle</button>
        <div id="machine1Result" class="col-xs-3 machineResult">Index: 0</div>
        <div id="machine2Result" class="col-xs-3 machineResult">Index: 0</div>
        <div id="machine3Result" class="col-xs-3 machineResult">Index: 0</div>
        <div id="machine4Result" class="col-xs-3 machineResult">Index: 0</div>
    </div>

    <script>
        const btn = document.querySelector('#randomizeButton');
        const results = {
            machine1: document.querySelector('#machine1Result'),
            machine2: document.querySelector('#machine2Result'),
            machine3: document.querySelector('#machine3Result'),
            machine4: document.querySelector('#machine4Result')
        };
        const el1 = document.querySelector('#machine1');
        const el2 = document.querySelector('#machine2');
        const el3 = document.querySelector('#machine3');
        const el4 = document.querySelector('#machine4');

        const machine1 = new SlotMachine(el1, { active: 0 });
        const machine2 = new SlotMachine(el2, { active: 0 });
        const machine3 = new SlotMachine(el3, { active: 0 });
        const machine4 = new SlotMachine(el4, { active: 0 });

        function onComplete(active) {
            results[this.element.id].innerText = `Index: ${this.active}`;
        }
        
        // btn.addEventListener('click', () => {
        //     console.log("OK");
        //     // setTimeout(() => machine2.shuffle(5, onComplete), 500);
        //     // setTimeout(() => machine3.shuffle(5, onComplete), 1000);
        //     // setTimeout(() => machine4.shuffle(5, onComplete), 1500);
        // });
    </script>

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
                randABC: ['B', 'B', 'I', 'N', '*', '*'],
            },
            mounted() {
                this.checkLogin();
            },
            methods: {
                next() {
                    console.log(machine1);
                    machine1.shuffle(5);
                    setTimeout(() => machine2.shuffle(5, onComplete), 500);
                    setTimeout(() => machine3.shuffle(5, onComplete), 1000);
                    setTimeout(() => machine4.shuffle(5, onComplete), 1500);
                },
                go() {
                    this.aniKeyframe = "flip-horizontal-bottom 0.5s cubic-bezier(0.455, 0.030, 0.515, 0.955) 10 both";
                    let _this = this;
                    let timer = 0;
                    let interval = setInterval(function () {
                        if (timer === 9) {
                            clearInterval(interval);
                        }
                        _this.barA = _this.randABC[Math.floor(Math.random() * 6)];
                        _this.barB = _this.randABC[Math.floor(Math.random() * 6)];
                        _this.barC = _this.randABC[Math.floor(Math.random() * 6)];
                        _this.barD = _this.randABC[Math.floor(Math.random() * 6)];
                        timer++;
                    }, 500);
                    setTimeout(function () {
                        _this.barA = "*";
                        _this.barB = "*";
                        _this.barC = "*";
                        _this.barD = "*";
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