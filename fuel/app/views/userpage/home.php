<?php

// echo "===== home =====";
// echo "<hr>";
// echo "Welcome " . $name . "<br>";
// echo "Load time: " . $loadTime;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
    <h1>Home</h1>
    <hr>
    <h3>載入時間 <?php echo $loadTime; ?></h3>

    <a href="/apis/user/home">Home</a>
    <br><br><br>
    <a href="/apis/user/login">Login</a>
    <br><br><br>
    <a href="/apis/user/reg">Registration</a>
    <br><br><br>
    <a href="/apis/user/memberinfo">Member Page</a>
    <br><br><br>
    <a href="/apis/user/memberlist">Member List</a>
    <hr>
</body>
</html>