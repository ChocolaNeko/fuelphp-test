<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\DB;
use Fuel\Core\Session;

// session_start();

class Controller_Apis_Ajax extends Controller_Rest
{
    public function post_regs() 
    {
        $userAcc = $_POST['account'];
        $userPwd = $_POST['password'];

        if ($userAcc != '' && $userPwd != '') {
            // check regex
            $checkAcc = preg_match("/^[A-Za-z0-9]{5,}$/", $userAcc);
            $checkPwd = preg_match("/^[A-Za-z0-9]{5,}$/", $userPwd);

            if ($checkAcc && $checkPwd) {
                // pass regex => account exist!?
                $checkExist = Model_Userdata::find_one_by('account', $userAcc);
                if ($checkExist === null) {
                    // account not exist => save new user
                    $passwordHash = password_hash($userPwd, PASSWORD_DEFAULT);
                    $newUser = Model_Userdata::forge()->set(array(
                        'account' => $userAcc,
                        'password' => $passwordHash,
                    ));
                    if ($newUser->save()) {
                        // 註冊成功 寫一筆紀錄到交易紀錄內 （增加0元 目前0元 敘述：新會員初始值0元）
                        $firstRecord = DB::insert('money_record')->columns(array(
                            'account', 'update_money', 'current_money', 'desc', 'status'
                        ))->values(array(
                            $userAcc, 0, 0, '新會員初始值0元', 'new'
                        ))->execute();
                        echo "註冊成功";
                    } else {
                        echo "註冊失敗";
                    }
                } else {
                    echo "此帳號已有人使用，換一個吧";
                }
            } else {
                echo "輸入資料不符合規定，請再試一次";
            }
        } else {
            echo "各欄位不能為空";
        }
    }

    public function post_login()
    {
        // echo "OK";    
        $logAcc = $_POST['account'];
        $logPwd = $_POST['password'];

        $sql = DB::select('*')->from('members')->where('account', '=', $logAcc)->execute();
        $accIsExist = count($sql);

        if ($accIsExist == 1) {
            // acc OK, check password
            $sql = DB::select('password')->from('members')->where('account', '=', $logAcc)->execute();
            $result_array = $sql->as_array();
            if(password_verify($logPwd, $result_array[0]['password'])) {
                if ($logAcc == 'admin') {
                    Session::set('admin', $logAcc);
                    echo "memberlist";
                } else {
                    // 判斷帳號是否被凍結 -> 凍結則無法登入
                    $sql = DB::select('status')->from('members')->where('account', '=', $logAcc)->execute();
                    $result_array = $sql->as_array();
                    if ($result_array[0]['status'] == 'ban') {
                        echo "ban";
                    } else {
                        Session::set('member', $logAcc);
                        echo "memberinfo";
                    }
                }
            } else {
                echo "登入失敗，請確認帳號或密碼是否有誤";
            }
        } else {
            echo "登入失敗，請確認帳號或密碼是否有誤";
        }
    }

    public function post_memberinfo()
    {
        // 根據post過來的動作 決定要做 登出/會員資料修改/密碼修改...等動作
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            case 'logout':
                Session::destroy();
                echo "http://localhost/apis/user/login";
                break;
            case 'nowSession':
                $nowSession = Session::get('member');
                $sql = DB::select('account', 'money', 'status')->from('members')->where('account', '=', $nowSession)->execute();
                $result_array = $sql->as_array();
                echo json_encode($result_array);
                break;
            case 'changePwd':
                // check oldPwd (old: $pwd[0], new: $pwd[1])
                $pwd = explode("|", $value);
                $nowAcc = Session::get('member');
                $sql = DB::select('password')->from('members')->where('account', '=', $nowAcc)->execute();
                $result_array = $sql->as_array();
                if (password_verify($pwd[0], $result_array[0]['password'])) {
                    // echo "OK";
                    $checkPwd = preg_match("/^[A-Za-z0-9]{5,}$/", $pwd[1]);
                    if ($checkPwd) {
                        $passwordHash = password_hash($pwd[1], PASSWORD_DEFAULT);
                        $sql = DB::update('members')->value('password', $passwordHash)->where('account', '=', $nowAcc)->execute();
                        echo "密碼修改成功";
                    } else {
                        echo "新密碼不符規定，密碼修改失敗";
                    }
                } else {
                    echo "舊密碼錯誤，密碼修改失敗";
                }
                break;
            default:
                echo "登出失敗";
                break;
        }
    }

    public function post_memberlist() 
    {
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            case 'logout':
                Session::destroy();
                echo "http://localhost/apis/user/login";
                break;
            case 'showMembers':
                $sql = DB::select('id', 'account', 'money', 'status')->from('members')->where('account', '!=', 'admin')->execute();
                $result_array = $sql->as_array();
                echo json_encode($result_array);
                break;
            case 'accBan':
                $sql = DB::update('members')->value('status', 'ban')->where('account', '=', $value)->execute();
                break;
            case 'accLock':
                $sql = DB::update('members')->value('status', 'lock')->where('account', '=', $value)->execute();
                break;
            case 'accOn':
                $sql = DB::update('members')->value('status', 'on')->where('account', '=', $value)->execute();
                break;
            case 'addMoney':
                $val = explode("|", $value);
                // 取出原有金額 再 更新金額(原有+增加值)
                $nowMoney = DB::select('money')->from('members')->where('account', '=', $val[1])->execute()->as_array();
                $updateMoney = DB::update('members')->value('money', $nowMoney[0]['money'] + $val[0])->where('account', '=', $val[1]);
                if ($updateMoney->execute()) {
                    $sql = DB::select('money')->from('members')->where('account', '=', $val[1])->execute();
                    $result_array = $sql->as_array();
                    $addRecord = DB::insert('money_record')->columns(array(
                        'account', 'update_money', 'current_money', 'desc', 'status'
                    ))->values(array(
                        $val[1], $val[0], $result_array[0]['money'], '管理員手動增加金額', 'add'
                    ))->execute();
                    echo "增加" . $val[0] . "元 成功";
                } else {
                    echo "增加失敗";
                }
                break;
            case 'subMoney':
                $val = explode("|", $value);
                // 取出原有金額 先與 扣除金額比較
                $nowMoney = DB::select('money')->from('members')->where('account', '=', $val[1])->execute()->as_array();
                if ($nowMoney[0]['money'] >= $val[0]) {
                    // 原有>欲扣 => 可扣
                    $updateMoney = DB::update('members')->value('money', $nowMoney[0]['money'] - $val[0])->where('account', '=', $val[1]);
                    if ($updateMoney->execute()) {
                        $sql = DB::select('money')->from('members')->where('account', '=', $val[1])->execute()->as_array();
                        $addRecord = DB::insert('money_record')->columns(array(
                            'account', 'update_money', 'current_money', 'desc', 'status'
                        ))->values(array(
                            $val[1], $val[0], $sql[0]['money'], '管理員手動扣除金額', 'sub'
                        ))->execute();
                        echo "扣款" . $val[0] . "元 成功";
                    } else {
                        echo "扣款失敗";
                    }

                } else {
                    // 原有<欲扣 => 不可扣
                    echo "扣款失敗(扣除金額不得小於原有金額)";
                }
                break;
            case 'record':
                // 新增一個session 用來導到交易紀錄頁面 呈現指定會員交易紀錄
                Session::set('record', $value);
                echo "/apis/user/record";
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    public function post_record()
    {
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            case 'backList':
                // 將session清除 導回會員清單頁面
                Session::delete('record');
                echo "/apis/user/memberlist";
                break;
            case 'getRecord':
                // 取得指定會員交易紀錄
                $memberRecord = Session::get('record');
                // SELECT * FROM `money_record`
                $sql = DB::select('*')->from('money_record')->where('account', '=', $memberRecord)->order_by('update_time','desc')->execute()->as_array();
                echo json_encode($sql);
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    public function post_game()
    {
        $flag = $_POST['flag'];
        $value = $_POST['value'];
        $barList = ["B","B","I","N","*","*"];

        switch ($flag) {
            case 'go':
                // 紀錄本次遊戲投注注項
                $betList = explode(",", $value);
                // RNG產生4個值
                $result = [];
                array_push($result, $barList[rand(0, 5)]);
                array_push($result, $barList[rand(0, 5)]);
                array_push($result, $barList[rand(0, 5)]);
                array_push($result, $barList[rand(0, 5)]);
                
                // 輸出此次結果 及與此符合的注項
                $countBarList = array_count_values($result);
                if (isset($countBarList['B'])){
                    echo $countBarList['B'] . "個B - " . json_encode($result);
                } else {
                    echo "0個B - " . json_encode($result);
                }
                break;
            
            default:
                echo "POST ERROR";
                break;
        }
    }
}