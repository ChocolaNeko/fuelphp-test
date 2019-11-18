<?php

echo "===== profile =====";
echo "<hr>";

// echo $userId;
// echo "<hr>";
// echo $userEmail;
// echo "<hr>";
// echo $userTel;
// echo "<hr>";

// echo "<pre>", var_dump($members), "</pre>";
// echo $members;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Member List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Account</th>
            <th>Email</th>
            <th>Tel</th>
            <th>Money</th>
        </tr>
        <?php 
            foreach ($members as $i)
            {
                echo "<tr>";
                echo "<td>";
                echo $i->id;
                echo "</td>";
                echo "<td>";
                echo $i->name;
                echo "</td>";
                echo "<td>";
                echo $i->account;
                echo "</td>";
                echo "<td>";
                echo $i->email;
                echo "</td>";
                echo "<td>";
                echo $i->tel;
                echo "</td>";
                echo "<td>";
                echo $i->money;
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </table>
</body>
</html>
