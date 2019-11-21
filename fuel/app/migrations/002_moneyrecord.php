<?php
namespace Fuel\Migrations;
class Moneyrecord
{

    function up()
    {
        \DBUtil::create_table('money_record', array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'account' => array('type' => 'varchar', 'constraint' => 20, 'comment' => '帳號'),
            'update_money' => array('type' => 'int', 'constraint' => 11, 'comment' => '此次交易金額'),
            'current_money' => array('type' => 'int', 'constraint' => 11, 'comment' => '此次交易後總金額'),
            'desc' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '交易描述'),
            'update_time' => array('type' => 'timestamp', 'comment' => '交易時間'),
            'status' => array('type' => 'varchar', 'constraint' => 20, 'default' => 'none', 'comment' => '交易狀態'),
        ), array('id'), false, 'InnoDB', 'utf8mb4_unicode_ci');
    }

    function down()
    {
       \DBUtil::drop_table('money_record');
    }
}