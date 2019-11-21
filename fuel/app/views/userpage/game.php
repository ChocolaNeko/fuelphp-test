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
    <h1>Game</h1>
    <hr>
    <div id="bar">
        <button v-on:click="go" v-bind:disabled="goBtn">GO!</button>
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
        <label for="">BINS對應數量 - 拉霸盤面 - 中獎注項</label>
        <br>
        <label for="">{{ result }}</label>
        <br>
        <button v-on:click="clearAll">清空所有投注組合</button>
        <br><br>
        <button v-on:click="saveAll" v-bind:disabled="editDisabled">確認下注</button>
        <br><br>
        <table class="bet table-bordered">
            <tr>
                <th>注項</th>
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
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betA" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betB" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betC" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betD" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betE" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betF" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betG" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betH" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
                <th class="text-center"><input type="number" min="0" max="5000" step="1" v-model="betI" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-bind:disabled="editDisabled"></th>
            </tr>
        </table>
        <hr>
        <label for="">目前投注：</label>
        {{ betList }}
    </div>
    

    <script>
        let bar = new Vue({
            el: "#bar",
            data: {
                betList: [],
                barA: "B",
                barB: "B",
                barC: "I",
                barD: "N",
                betA: "",
                betB: "",
                betC: "",
                betD: "",
                betE: "",
                betF: "",
                betG: "",
                betH: "",
                betI: "",
                result: [],
                msg: "",
                editDisabled : false,
                goBtn: true,
            },
            watch: {
                
            },
            methods: {
                go() {
                    const isBelowThreshold = (currentValue) => currentValue != "0";
                    let notZero = this.betList.every(isBelowThreshold);
                    // console.log(notZero);
                    if (notZero == false) {
                        alert('至少要有一注金額大於0');
                    } else {
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'go');
                        formData.append('value', this.betList);
                        axios.post('/apis/ajax/game', formData)
                            .then(function (response) {
                                _this.result = response.data;

                                // // 結果 => temp[0]
                                // let temp = _this.result.split('-');
                                // _this.msg = temp[0];

                                // // 拉霸盤面 => temp[1]
                                // let showBar = temp[1].replace(/[[]/gm,"");
                                // showBar = showBar.replace(/["]]/gm,"");
                                // showBar = showBar.replace(/[",]/gm,"");
                                // showBar = showBar.split('');
                                // // console.log(showBar);
                                // _this.barA = showBar[0];
                                // _this.barB = showBar[1];
                                // _this.barC = showBar[2];
                                // _this.barD = showBar[3];

                            }).catch(function (error){
                                alert(error);
                            });
                    }
                },
                clearAll() {
                    this.betList = [];
                    this.betA = "";
                    this.betB = "";
                    this.betC = "";
                    this.betD = "";
                    this.betE = "";
                    this.betF = "";
                    this.betG = "";
                    this.betH = "";
                    this.betI = "";
                    this.editDisabled = false;
                    this.goBtn = true;
                },
                saveAll() {
                    if (this.betA == "" && this.betB == "" && this.betC == "" && this.betD == "" && this.betE == "" && this.betF == "" && this.betG == "" && this.betH == "" && this.betI == "") {
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
                        this.editDisabled = true;
                        this.goBtn = false;
                    }
                    
                    // console.log(this.betList.length);
                },
            },
        });
    </script>
</body>
</html>