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
        <button v-on:click="go">GO!</button>
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
        <button v-on:click="clearAll">清空所有投注組合</button>
        <br><br>
        <table class="bet table-bordered">
            <tr>
                <th>注項</th>
                <th><button v-on:click="bet('0個B')">0個B</button></th>
                <th><button v-on:click="bet('1個B')">1個B</button></th>
                <th><button v-on:click="bet('2個B')">2個B</button></th>
                <th><button v-on:click="bet('3個B')">3個B</button></th>
                <th><button v-on:click="bet('4個B')">4個B</button></th>
                <th><button v-on:click="bet('BBBB')">BBBB</button></th>
                <th><button v-on:click="bet('IIII')">IIII</button></th>
                <th><button v-on:click="bet('NNNN')">NNNN</button></th>
                <th><button v-on:click="bet('****')">****</button></th>
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
                result: [],
                msg: "",
            },
            methods: {
                go() {
                    if (this.betList.length == 0) {
                        // console.log('請先下注');
                        alert('請先下注');
                    } else {
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'go');
                        formData.append('value', this.betList);
                        axios.post('/apis/ajax/game', formData)
                            .then(function (response) {
                                _this.result = response.data;
                                // 結果
                                let temp = _this.result.split(' - ');
                                temp[1] = temp[1].replace(/[",]/gm, "");
                                temp[1] = temp[1].replace("[", "");
                                temp[1] = temp[1].replace("]", "");
                                _this.msg = temp[0];
                                // 拉霸盤面
                                let temp1 = temp[1].split('');
                                _this.barA = temp1[0];
                                _this.barB = temp1[1];
                                _this.barC = temp1[2];
                                _this.barD = temp1[3];
                            }).catch(function (error){
                                alert(error);
                            });
                    }
                },
                bet(val) {
                    if (this.betList.includes(val)) {
                        alert('任一種組合只能選一次');
                    } else {
                        this.betList.push(val);
                    }
                    // console.log(this.betList.length);
                },
                clearAll() {
                    this.betList = [];
                }
            },
        });
    </script>
</body>
</html>