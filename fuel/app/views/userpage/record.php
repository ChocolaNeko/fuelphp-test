<?php
use Fuel\Core\Session;

$memberRecord = Session::get('record');
$member = Session::get('member');
$admin = Session::get('admin');

if (is_null($admin) && !is_null($member)) {
    // 一般會員 => 跳至會員頁面
    header('Location: /apis/user/memberinfo');
} elseif (!is_null($admin) && is_null($memberRecord)) {
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
    <title><?php echo "會員 " . $memberRecord . " 交易紀錄"; ?></title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div id="record">
        <br>
        <h5>會員 <?php echo $memberRecord; ?> 交易紀錄</h5>
        <br>
        <button v-on:click="backList">返回會員清單</button>
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

    <script>
        let record = new Vue({
            el: "#record",
            data: {
                record: [],
            },
            mounted: function () {
                let _this = this;
                let formData = new FormData();
                formData.append('flag', 'getRecord');
                formData.append('value', 'none');
                axios.post('/apis/ajax/record', formData)
                    .then(function (response) {
                        _this.record = response.data;
                        console.log(_this.record);
                        // window.location.replace(response.data);
                    }).catch(function (error) {
                        alert(error);
                    });
            },
            methods: {
                backList() {
                    let _this = this;
                    let formData = new FormData();
                    formData.append('flag', 'backList');
                    formData.append('value', 'none');
                    axios.post('/apis/ajax/record', formData)
                        .then(function (response) {
                            window.location.replace(response.data);
                        }).catch(function (error) {
                            alert(error);
                        });
                }
            },
        });
    </script>
</body>
</html>