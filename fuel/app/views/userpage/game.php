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
    <title>Game</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <h1>Slot Game</h1>
    <hr>
    <div id="bar">
        <?php echo "歡迎 " . $member; ?>
        <br><br>
        <button class="btn btn-primary" v-on:click="go" v-bind:disabled="goBtn">GO!</button>
        <br><br>
        <table class="table-bordered">
            <tr>
                <th class="text-center"> {{ barA }} </th>
                <th class="text-center"> {{ barB }} </th>
                <th class="text-center"> {{ barC }} </th>
                <th class="text-center"> {{ barD }} </th>
            </tr>
        </table>
        <br>
        <label for="">{{ msg }}</label>
        <br>
        <label for="">BINS - 拉霸盤面 - 中獎注項 - 此次下注中獎注項 - 此次下注中獎金額 - 是否出現BONUS</label>
        <br>
        <label for="">{{ result }}</label>
        <br>
        <!-- <input type="text" id="cannotCP" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> -->
        <!-- <br><br> -->
        <button class="btn btn-primary" v-on:click="clearAll">清空所有投注組合</button>
        <br><br>
        <button class="btn btn-primary" v-on:click="saveAll" v-bind:disabled="editDisabled">確認下注</button>
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
                <th class="text-center"><input type="text" class="cannotCP" v-model="betA" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betB" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betC" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betD" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betE" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betF" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betG" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betH" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="text" class="cannotCP" v-model="betI" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
            </tr>
        </table>
        <hr>
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
        <hr>
        <button class="btn btn-primary" v-on:click="betrecord">查看下注紀錄</button>
    </div>
    

    <script>
        // 禁用貼上功能 (WIP)
        // window.onload = function () {
        //     const cannotCP = document.getElementsByClassName('cannotCP');
        //     cannotCP.onpaste = function (e) {
        //         console.log("paste");
        //         e.preventDefault();
        //     }
        // };
        let bar = new Vue({
            el: "#bar",
            data: {
                betList: [],
                barA: "B",
                barB: "B",
                barC: "I",
                barD: "N",
                betA: "0",
                betB: "0",
                betC: "0",
                betD: "0",
                betE: "0",
                betF: "0",
                betG: "0",
                betH: "0",
                betI: "0",
                result: [],
                msg: "",
                editDisabled : false,
                goBtn: true,
                winMoney: [],
                totalBetMoney: "",
                totalWinMoney: "",
            },
            methods: {
                go() {
                    // const isBelowThreshold = (currentValue) => currentValue >= 0;
                    // let notZero = this.betList.every(isBelowThreshold);
                    // console.log(notZero);
                    // if (notZero == false) {
                    //     alert('至少要有一注金額大於0');
                    // } else {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'go');
                    formData.append('value', this.betList);
                    axios.post('/apis/ajax/game', formData)
                        .then(function (response) {
                            _this.result = response.data;

                            let temp = _this.result.split('-');
                            // 顯示派彩結果與派彩總金額
                            _this.winMoney = temp[4];
                            _this.winMoney = _this.winMoney.replace(/[[]/gm,"");
                            _this.winMoney = _this.winMoney.replace(/["]]/gm,"");
                            _this.winMoney = _this.winMoney.replace(/["]/gm,"");
                            _this.winMoney = _this.winMoney.split(',');
                            const reducer = (accumulator, currentValue) => parseInt(accumulator) + parseInt(currentValue);
                            _this.totalWinMoney = _this.winMoney.reduce(reducer);

                            // 顯示拉霸盤面
                            let barView = temp[1];
                            barView = barView.replace(/[[]/gm,"");
                            barView = barView.replace(/["]]/gm,"");
                            barView = barView.replace(/["]/gm,"");
                            barView = barView.split(',');
                            _this.barA = barView[0];
                            _this.barB = barView[1];
                            _this.barC = barView[2];
                            _this.barD = barView[3];
                        }).catch(function (error){
                            alert(error);
                        });
                    
                },
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
                },
                saveAll() {
                    // if (this.betA == "" && this.betB == "" && this.betC == "" && this.betD == "" && this.betE == "" && this.betF == "" && this.betG == "" && this.betH == "" && this.betI == "") {
                    if (this.betA + this.betB + this.betC + this.betD + this.betE + this.betF + this.betG + this.betH + this.betI <= 0) {
                        alert('至少下一注');
                    } else {
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
                            if (this.betList[i] == '' || this.betList[i] > 5000) {
                                blankAndZero = true;
                            }
                        }
                        if (blankAndZero == true) {
                            alert('單一注項金額最高為5000，且任一注不能留空');
                            this.betList = [];
                        } else {
                            // 計算投注總金額
                            const reducer = (accumulator, currentValue) => parseInt(accumulator) + parseInt(currentValue);
                            this.totalBetMoney = this.betList.reduce(reducer);
                            this.editDisabled = true;
                            this.goBtn = false;
                        }
                    }
                },
                betrecord() {
                    window.location.replace('/apis/user/betrecord');
                }
            },
        });
    </script>
</body>
</html>