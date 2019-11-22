<?php
namespace Fuel\Migrations;
class Betrecord
{

    function up()
    {
        \DBUtil::create_table('bet_record', array(
            'bet_serial_num' => array('type' => 'int', 'constraint' => 12, 'auto_increment' => true, 'comment' => '注單編號(後12碼)'),
            'bet_time' => array('type' => 'timestamp', 'comment' => '下注時間'),
            'account' => array('type' => 'varchar', 'constraint' => 20, 'comment' => '帳號'),
            'bet_list' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '此次下注注項'),
            'bet_result' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '下注結果'),
            'bet_desc' => array('type' => 'varchar', 'constraint' => 100, 'default' => 'none', 'comment' => '描述'),
        ), array('bet_serial_num'), false, 'InnoDB', 'utf8mb4_unicode_ci');
    }

    function down()
    {
       \DBUtil::drop_table('bet_record');
    }
}