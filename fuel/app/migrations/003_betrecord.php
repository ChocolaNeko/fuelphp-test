<?php
namespace Fuel\Migrations;
class Betrecord
{

    function up()
    {
        \DBUtil::create_table('bet_record', array(
            'account' => array('type' => 'varchar', 'constraint' => 20, 'comment' => '下注帳號'),
            'bet_time' => array('type' => 'timestamp', 'comment' => '下注時間'),
            'bet_serial_num' => array('type' => 'int', 'constraint' => 20, 'auto_increment' => true, 'comment' => '注單編號(後12碼)'),
            'win_list' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '開獎結果'),
            'bet_list' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '下注組合'),
            'total_bet_money' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '下注總金額'),
            'total_reward' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '中獎(派彩)金額'),
            'total_win_money' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '盈虧金額'),
            'bet_result' => array('type' => 'varchar', 'constraint' => 100, 'comment' => '下注結果'),
        ), array('bet_serial_num'), false, 'InnoDB', 'utf8mb4_unicode_ci');
    }

    function down()
    {
       \DBUtil::drop_table('bet_record');
    }
}