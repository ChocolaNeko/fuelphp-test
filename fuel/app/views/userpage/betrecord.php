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
    <title>Bet record</title>
</head>
<body>
    <?php echo "會員 " . $member . " 下注紀錄"; ?>
    <hr>
</body>
</html>