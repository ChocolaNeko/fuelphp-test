<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\DB;
session_start();

class Controller_Apis_Ajax extends Controller_Rest
{
    public function post_regs() 
    {
        $userName = $_POST['name'];
        $userAcc = $_POST['account'];
        $userPwd = $_POST['password'];
        $userEmail = $_POST['email'];
        $userPhone = $_POST['phone'];

        if ($userName != '' && $userAcc != '' && $userPwd != '' && $userEmail != '' && $userPhone != '' ) {
            // check regex
            $checkName = preg_match("/^[^.,\/#!$%\^&\*;:{}=\-_`~()@<>\s]{1,}$/", $userName);
            $checkAcc = preg_match("/^[A-Za-z0-9]{5,}$/", $userAcc);
            $checkPwd = preg_match("/^[A-Za-z0-9]{5,}$/", $userPwd);
            $checkEmail = preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/", $userEmail);
            $checkPhone = preg_match("/^\d{10}$/", $userPhone);

            if ($checkName && $checkAcc && $checkPwd && $checkEmail && $checkPhone) {
                // pass regex => account exist!?
                $checkExist = Model_Userdata::find_one_by('account', $userAcc);
                if ($checkExist === null) {
                    // account not exist => save new user
                    $passwordHash = password_hash($userPwd, PASSWORD_DEFAULT);
                    $newUser = Model_Userdata::forge()->set(array(
                        'name' => $userName,
                        'account' => $userAcc,
                        'password' => $passwordHash,
                        'email' => $userEmail,
                        'tel' => $userPhone
                    ));
                    if ($newUser->save()) {
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
                $_SESSION['account'] = $logAcc;
                echo "登入成功";
            } else {
                echo "登入失敗，請確認帳號或密碼是否有誤";
            }
        } else {
            echo "登入失敗，請確認帳號或密碼是否有誤";
        }
    }
}