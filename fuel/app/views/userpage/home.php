<?php

// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/apis/user/home">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/reg">Registration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/game">Slot Game</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/memberinfo">Member Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/apis/user/memberlist">Member List</a>
                </li>
            </ul>
        </div>
    </nav>






    <br>
    <h1>Home</h1>
    <hr>
    <h3><?php echo $loadTime; ?></h3>

    <!-- <a href="/apis/user/home">Home</a>
    <br><br><br>
    <a href="/apis/user/login">Login</a>
    <br><br><br>
    <a href="/apis/user/reg">Registration</a>
    <br><br><br>
    <a href="/apis/user/game">Slot Game</a>
    <br><br><br>
    <a href="/apis/user/memberinfo">Member Page</a>
    <br><br><br>
    <a href="/apis/user/memberlist">Member List</a>
    <hr> -->
</body>
</html>