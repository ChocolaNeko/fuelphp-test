

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>下注紀錄 分頁-搜尋測試</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>
<body>
    <div id="test">
        <br>
        <button v-on:click="prev">Prev.</button>
        | 第 {{ count }} 頁, 共 {{ totalPage }} 頁 | 共 {{ total }} 筆資料 |
        <button v-on:click="next">Next.</button>
        |
        每頁顯示
        <input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' v-model="listLength">
        <hr>
        <table class="table table-bordered">
            <tr>
                <!-- <th>ID</th> -->
                <th>下注時間</th>
                <th>注單編號</th>
                <th>開獎結果</th>
                <th>下注組合</th>
                <th>下注總金額</th>
                <th>中獎(派彩)金額</th>
                <th>盈虧</th>
                <th>下注結果</th>
            </tr>
            <tr v-for="(item, index) in result" :key="index">
                <!-- <td>{{ index + 1 }}</td> -->
                <td>{{ item.bet_time }}</td>              
                <td>{{ item.bet_serial_num }}</td>                 
                <td>{{ item.win_list }}</td>           
                <td>{{ item.bet_list }}</td>
                <td>{{ item.total_bet_money }}</td>
                <td>{{ item.total_reward }}</td>
                <td>{{ item.total_win_money }}</td>
                <td>{{ item.bet_result }}</td>
            </tr>
        </table>
        <hr>
    </div>

    <script>
        let test = new Vue({
            el: "#test",
            data: {
                result: [],
                count: 1, // 目前頁數
                listLength: 10,  // 每頁顯示資料筆數
                totalPage: 0, // 總資料頁數
                total: 0,  // 總資料筆數
            },
            mounted() {
                this.showBetRecord();
                this.getTotal();
            },
            methods: {
                // 取得總資料筆數 總資料頁數
                getTotal() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'getTotal');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/setting', formData)
                        .then(function (response) {
                            _this.total = response.data;
                            _this.totalPage = Math.ceil(_this.total / _this.listLength);
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                // 載入時預設顯示頭10筆資料
                showBetRecord() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'showBetRecord');
                    formData.append('value', this.listLength);
                    axios.post('/apis/ajax/setting', formData)
                        .then(function (response) {
                            _this.result = response.data;
                            console.log(response.data);
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                // 目前頁數 > 1時 按上一頁 顯示前10筆資料
                prev() {
                    if (this.count > 1) {
                        this.count--;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spage');
                        formData.append('value', `${this.count}|${this.listLength}`);
                        axios.post('/apis/ajax/setting', formData)
                            .then(function (response){
                                _this.result = response.data;
                                console.log(response.data);
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                },
                // 目前頁數 < 總頁數時 按下一頁 顯示後10筆資料
                next() {
                    if (this.count < this.totalPage) {
                        this.count++;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spage');
                        formData.append('value', `${this.count}|${this.listLength}`);
                        axios.post('/apis/ajax/setting', formData)
                            .then(function (response){
                                _this.result = response.data;
                                console.log(response.data);
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                }
            },
        });
    </script>
</body>
</html>

