<?php
use Fuel\Core\Session;

$memberBetRecord = Session::get('betRecord');
$member = Session::get('member');
$admin = Session::get('admin');

if (is_null($admin) && !is_null($member)) {
    // 一般會員 => 跳至會員頁面
    header('Location: /apis/user/memberinfo');
} elseif (!is_null($admin) && is_null($memberBetRecord)) {
    // 管理員 但 沒有帶指定會員session => 跳至會員清單頁面
    header('Location: /apis/user/memberlist');
} elseif (is_null($admin) && is_null($member)) {
    // 不是管理員 也不是一般會員 => 請登入
    header('Location: /apis/user/login');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo "管理員後台 - 會員 " . $memberBetRecord . " 下注紀錄"; ?></title>
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
    <div id="betRecord">
        <!-- 頁面上方 navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <label class="navbar-brand">管理員後台 - 會員 <?php echo $memberBetRecord; ?> 下注紀錄</label>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" v-on:click="backList">返回會員清單</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- 主要內容 -->
        <br>
        下注時間：
        <el-date-picker v-model="startDate" type="datetime" value-format="yyyy-MM-dd HH:mm:ss" placeholder="start date time"></el-date-picker>
         至 
        <el-date-picker v-model="endDate" type="datetime" value-format="yyyy-MM-dd HH:mm:ss" placeholder="end date time"></el-date-picker>
        <br><br>
        每頁顯示：
        <el-input-number size="small" v-model="listLength" :min="20" :max="100" :step="20" step-strictly></el-input-number>
         筆資料
        <br><br>
        <button v-on:click="showBetRecord">查詢</button>
        <hr>
        <button v-on:click="prev">Prev.</button>
        | 第 {{ count }} 頁, 共 {{ totalPage }} 頁 | 共 {{ total }} 筆資料 |
        <button v-on:click="next">Next.</button>
        <br><br>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
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
                <td>{{ index + 1}}</td>
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
    
    <script>
        ELEMENT.locale(ELEMENT.lang.zhTW); // ELEMENT 套件語言設定
        let betRecord = new Vue({
            el: "#betRecord",
            data: {
                betRecord: [], // 存放取得的所有下注紀錄
                count: 1,  // 目前頁數
                totalPage: 1,  // 總頁數
                total: 0,  // 總資料數
                listLength: 20,  // 每頁顯示資料數
                startDate: "", // 開始日期
                endDate: "",  // 結束日期
            },
            methods: {
                // 返回會員管理頁面
                backList() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'backList');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/betrecord', formData)
                        .then(function (response) {
                            window.location.replace(response.data);
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                showBetRecord() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'showBetRecord');
                    formData.append('value', `${this.startDate}|${this.endDate}|${this.listLength}`);
                    axios.post('/apis/ajax/betrecord', formData)
                        .then(function (response) {
                            _this.betRecord = response.data;

                            // 查到資料 與 查不到資料 的顯示內容
                            if (_this.betRecord.length != 0) {
                                _this.total = _this.betRecord[0].totalColumn; // 取得此次查詢 得到的總資料數
                                _this.totalPage = Math.ceil(_this.total / _this.listLength); // 取得此次查詢 得到的總頁數
                                _this.count = 1;
                            } else {
                                _this.total = 0;
                                _this.count = 1;
                                _this.totalPage = 1;
                            }
                        }).catch(function (error) {
                            alert(error);
                        });
                },
                // 換頁(往前)
                prev() {
                    if (this.count > 1) {
                        this.count--;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spage');
                        // 傳送 起始時間 結束時間 欲切換頁碼 一頁幾筆資料 總資料量
                        formData.append('value', `${this.startDate}|${this.endDate}|${this.count}|${this.listLength}|${this.total}`);
                        axios.post('/apis/ajax/betrecord', formData)
                            .then(function (response){
                                _this.betRecord = response.data;
                                // _this.totalPage = Math.ceil(_this.total / _this.listLength);
                                // console.log(response.data);
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                },
                // 換頁(往後)
                next() {
                    if (this.count < this.totalPage) {
                        this.count++;
                        let _this = this;
                        let formData = new FormData();
                        formData.append('flag', 'spage');
                        // 傳送 起始時間 結束時間 欲切換頁碼 一頁幾筆資料 總資料量
                        formData.append('value', `${this.startDate}|${this.endDate}|${this.count}|${this.listLength}|${this.total}`);
                        axios.post('/apis/ajax/betrecord', formData)
                            .then(function (response){
                                _this.betRecord = response.data;
                                // _this.totalPage = Math.ceil(_this.total / _this.listLength);
                                // console.log(response.data);
                            }).catch(function (error) {
                                alert(error);
                            });
                    }
                },
                // getRange() {
                //     console.log(this.startDate);
                //     console.log(this.endDate);
                // },
            },
        })
    </script>
</body>
</html>