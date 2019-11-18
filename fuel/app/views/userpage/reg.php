<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <?php echo Form::open(array('action' => 'apis/user/reg', 'method' => 'POST')); ?>
    <br><br>
    Name: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Form::input('name'); ?>
    <br><br>
    Account:&nbsp;&nbsp;&nbsp;<?php echo Form::input('account'); ?>
    <br><br>
    Password: <?php echo Form::password('password'); ?>
    <br><br>
    Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Form::input('email'); ?>
    <br><br>
    Tel:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Form::input('tel'); ?>
    <hr>
    <?php echo Form::submit('submit'); ?>
    <?php echo Form::close(); ?>
</body>
</html>