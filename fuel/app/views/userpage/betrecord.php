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
    <title><?php echo "會員 " . $memberBetRecord . " 下注紀錄"; ?></title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>
<body>
    <div id="betRecord">
        <br>
        <h5>會員 <?php echo $memberBetRecord; ?> 下注紀錄</h5>
        <br>
        <button v-on:click="backList">返回會員清單</button>
        <hr>
        <!-- <pre>{{ betRecord }}</pre> -->
        
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
    
    <script>
        let betRecord = new Vue({
            el: "#betRecord",
            data: {
                betRecord: [],
            },
            mounted: function () {
                this.showBetRecord();
            },
            methods: {
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
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/betrecord', formData)
                        .then(function (response) {
                            _this.betRecord = response.data;
                            console.log(_this.betRecord);
                        }).catch(function (error) {
                            alert(error);
                        });
                }
            },
        })
    </script>
</body>
</html>