<?php
if (!isset($_SESSION)) {
    session_start();
}
// session_start();
// 未登入狀態下，無法查看此頁面，並跳轉至登入頁面
if (!isset($_SESSION['account'])) {
    header('Location: /apis/user/login');
} else {
    $member = $_SESSION['account'];
}


// $memeber = $_SESSION['account'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>member page</title>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>
<body>
    <div id="msg">
        <h1>Member Page</h1>
        <hr>
        Welcome 
        <?php 
            if (!isset($_SESSION['account'])) { 
                header('Location: /apis/user/login');
                // echo $member; 
            } else {
                $member = $_SESSION['account'];
                echo $member;
                // header('Location: /apis/user/login');
            }
        ?>
        <br>
        <button v-on:click="logout">Logout</button>
    </div>
    

    <script>
        let msg = new Vue({
            el: "#msg",
            data: {

            },
            methods: {
                logout() {
                    <?php 
                        session_destroy();
                        if (!isset($_SESSION['account'])) {
                            header('Location: /apis/user/login');
                        }
                        // header('Location: /apis/user/login');
                    ?>
                }
            },
        })
    </script>
</body>
</html>