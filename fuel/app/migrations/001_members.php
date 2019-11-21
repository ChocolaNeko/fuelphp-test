<?php
namespace Fuel\Migrations;
class Members
{

    function up()
    {
        \DBUtil::create_table('members', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'account' => array('type' => 'varchar', 'constraint' => 20, 'comment' => '帳號'),
            'password' => array('type' => 'varchar', 'constraint' => 300, 'comment' => '密碼'),
            'money' => array('type' => 'int', 'constraint' => 11, 'default' => 0, 'comment' => '金額'),
            'status' => array('type' => 'varchar', 'constraint' => 10, 'default' => 'on', 'comment' => '帳號狀態'),
        ), array('id'), false, 'InnoDB', 'utf8mb4_unicode_ci');
    }

    function down()
    {
       \DBUtil::drop_table('members');
    }
}