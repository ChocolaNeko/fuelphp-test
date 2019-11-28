<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\DB;
use Fuel\Core\Session;

// session_start();

class Controller_Apis_Ajax extends Controller_Rest
{
    /* localhost/apis/user/home 所處理的動作 => home (首頁) */
    public function post_home()
    {
        // 取得POST傳過來的動作(flag) 與 值(value)
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        // 取得Session
        $admin = Session::get('admin');
        $member = Session::get('member');

        switch ($flag) {
            // 檢查目前登入狀態
            case 'checkLogin':
                if (isset($admin)) {
                    echo $admin;
                } elseif (isset($member)) {
                    echo $member;
                } else {
                    echo "no";
                }
                break;
            // 帳號登出 清除Session
            case 'logout':
                Session::destroy();
                echo "logout";
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    /* localhost/apis/user/reg 所處理的動作 => reg (註冊) */
    public function post_regs()
    {
        // 取得POST的帳號密碼
        $userAcc = $_POST['account'];
        $userPwd = $_POST['password'];

        if ($userAcc != '' && $userPwd != '') {
            // regex檢查
            $checkAcc = preg_match("/^[A-Za-z0-9]{5,}$/", $userAcc);
            $checkPwd = preg_match("/^[A-Za-z0-9]{5,}$/", $userPwd);

            if ($checkAcc && $checkPwd) {
                // 通過regex => 檢查是否有此帳號!?
                $checkExist = Model_Userdata::find_one_by('account', $userAcc);
                if ($checkExist === null) {
                    // 帳號不存在 => 新增此筆會員資料
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

    /* localhost/apis/user/login 所處理的動作 => login (登入) */
    public function post_login()
    {
        // 取得POST的帳號密碼
        $logAcc = $_POST['account'];
        $logPwd = $_POST['password'];

        // 檢查帳號 是否存在
        $sql = DB::select('*')->from('members')->where('account', '=', $logAcc)->execute();
        $accIsExist = count($sql);

        if ($accIsExist == 1) {
            // 帳號存在 檢查密碼
            $sql = DB::select('password')->from('members')->where('account', '=', $logAcc)->execute();
            $result_array = $sql->as_array();
            if (password_verify($logPwd, $result_array[0]['password'])) {
                // 認證通過 檢查身份 (一般會員 or 管理員)
                if ($logAcc == 'admin') {
                    Session::set('admin', $logAcc); // 管理員身份 設定Session 名稱為 admin
                    echo "memberlist";
                } else {
                    // 一般會員：判斷帳號是否被凍結 凍結則無法登入
                    $sql = DB::select('status')->from('members')->where('account', '=', $logAcc)->execute();
                    $result_array = $sql->as_array();
                    if ($result_array[0]['status'] == 'ban') {
                        echo "ban";
                    } else {
                        Session::set('member', $logAcc); // 一般會員 設定Session 名稱為 member
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

    /* localhost/apis/user/memberinfo 所處理的動作 => memberinfo (會員資料) */
    public function post_memberinfo()
    {
        // 取得POST傳過來的動作(flag) 與 值(value)
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            // 登出 清除Session
            case 'logout':
                Session::destroy();
                echo "/apis/user/login";
                break;
            // 取得目前登入帳號 以及帳號基本資料
            case 'nowSession':
                $nowSession = Session::get('member'); // 透過登入時設定的Session 取得目前登入帳號
                $sql = DB::select('account', 'money', 'status')->from('members')->where('account', '=', $nowSession)->execute();
                $result_array = $sql->as_array();
                echo json_encode($result_array);
                break;
            // 修改密碼
            case 'changePwd':
                // check oldPwd (old: $pwd[0], new: $pwd[1])
                $pwd = explode("|", $value);
                $nowAcc = Session::get('member'); // 透過登入時設定的Session 取得目前登入帳號
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
            // 取得交易紀錄
            case 'getRecord':
                $nowAcc = Session::get('member'); // 透過登入時設定的Session 取得目前登入帳號
                $record = DB::select('*')->from('money_record')->where('account', '=', $nowAcc)->execute()->as_array();
                echo json_encode($record);
                break;
            // 取得下注紀錄
            case 'getBetRecord':
                $nowAcc = Session::get('member'); // 透過登入時設定的Session 取得目前登入帳號
                $betRecord = DB::select('*')->from('bet_record')->where('account', '=', $nowAcc)->execute()->as_array();

                // 透過 下注時間 與 自動遞增的PK 組成注單編號
                foreach ($betRecord as $key => $value) {
                    $ymd = explode(" ", $betRecord[$key]['bet_time']); // 取得年月日
                    $ymd[0] = str_replace("-", "", $ymd[0]);

                    $num = $betRecord[$key]['bet_serial_num']; // 取得編號
                    $num = str_pad($num, 12, "0", STR_PAD_LEFT); // 將編號補足12碼

                    // 產生注單編號：年4碼 + 月2碼 + 日2碼 + 不重複唯一碼(12) 共 20 碼, 併入陣列中回傳
                    $serialNum = $ymd[0] . $num;
                    $betRecord[$key]['serialNum'] = $serialNum;
                }
                echo json_encode($betRecord);
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    /* localhost/apis/user/memberlist 所處理的動作 => memberlist (管理員後台 - 會員管理) */
    public function post_memberlist()
    {
        // 取得POST傳過來的動作(flag) 與 值(value)
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            // 登出 清除Session
            case 'logout':
                Session::destroy();
                echo "/apis/user/login";
                break;
            // 取得所有會員資料 (排除管理員)
            case 'showMembers':
                $sql = DB::select('id', 'account', 'money', 'status')->from('members')->where('account', '!=', 'admin')->execute();
                $result_array = $sql->as_array();
                echo json_encode($result_array);
                break;
            // 帳號凍結 鎖定 恢復啟用
            case 'accBan':
                $sql = DB::update('members')->value('status', 'ban')->where('account', '=', $value)->execute();
                break;
            case 'accLock':
                $sql = DB::update('members')->value('status', 'lock')->where('account', '=', $value)->execute();
                break;
            case 'accOn':
                $sql = DB::update('members')->value('status', 'on')->where('account', '=', $value)->execute();
                break;
            // 加錢
            case 'addMoney':
                $val = explode("|", $value); // value 以 | 隔開 前面為金額 後面為帳號名稱
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
            // 扣錢
            case 'subMoney':
                $val = explode("|", $value); // value 以 | 隔開 前面為金額 後面為帳號名稱
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
                // 新增一個session 存放會員帳號 呈現指定會員交易紀錄 並導到交易紀錄頁面 
                Session::set('record', $value);
                echo "/apis/user/record";
                break;
            case 'betRecord':
                // 新增一個session 存放會員帳號 呈現指定會員下注紀錄 並導到下注紀錄頁面 
                Session::set('betRecord', $value);
                echo "/apis/user/betrecord";
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    /* localhost/apis/user/record 所處理的動作 => record (管理員查看會員交易紀錄) */
    public function post_record()
    {
        // 取得POST傳過來的動作(flag) 與 值(value)
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            // 返回會員管理頁面 將session清除 導回會員清單頁面
            case 'backList':
                Session::delete('record');
                echo "/apis/user/memberlist";
                break;
            // 取得指定會員交易紀錄
            case 'getRecord':
                $memberRecord = Session::get('record');
                $sql = DB::select('*')->from('money_record')->where('account', '=', $memberRecord)->order_by('update_time', 'desc')->execute()->as_array();
                echo json_encode($sql);
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    /* localhost/apis/user/betrecord 所處理的動作 => betrecord (管理員查看會員下注紀錄) */
    public function post_betrecord()
    {
        // 取得POST傳過來的動作(flag) 與 值(value)
        $flag = $_POST['flag'];
        $value = $_POST['value'];

        switch ($flag) {
            // 返回會員管理頁面 將session清除 導回會員清單頁面
            case 'backList':
                Session::delete('memberBetRecord');
                echo "/apis/user/memberlist";
                break;
            // 取得指定會員交易紀錄    
            case 'showBetRecord':
                $memberBetRecord = Session::get('betRecord');
                $sql = DB::select('*')->from('bet_record')->where('account', '=', $memberBetRecord)->order_by('bet_time', 'desc')->execute()->as_array();

                // 透過 下注時間 與 自動遞增的PK 組成注單編號
                foreach ($sql as $key => $value) {
                    $ymd = explode(" ", $sql[$key]['bet_time']); // 取得年月日
                    $ymd[0] = str_replace("-", "", $ymd[0]);

                    $num = $sql[$key]['bet_serial_num']; // 取得編號
                    $num = str_pad($num, 12, "0", STR_PAD_LEFT); // 將編號補足12碼

                    // 產生注單編號：年4碼 + 月2碼 + 日2碼 + 不重複唯一碼(12) 共 20 碼, 併入陣列中回傳
                    $serialNum = $ymd[0] . $num;
                    $sql[$key]['serialNum'] = $serialNum;
                }
                echo json_encode($sql);
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }

    /* localhost/apis/user/game 所處理的動作 => game (拉霸機) */
    public function post_game()
    {
        // 取得POST傳過來的動作(flag) 與 值(value)
        $flag = $_POST['flag'];
        $value = $_POST['value'];
        // 拉霸各盤面會出現的值
        $barList = ["B","B","I","N","*","*"];

        switch ($flag) {
            // 拉霸開獎
            case 'go':
                /* --- RNG產生4個值 組成拉霸盤面(一組陣列) 存到$result內 --- */
                $result = [];
                array_push($result, $barList[rand(0, 5)]);
                array_push($result, $barList[rand(0, 5)]);
                array_push($result, $barList[rand(0, 5)]);
                array_push($result, $barList[rand(0, 5)]);

                /* ----- 計算BINS 個別值 ----- */
                $countB = 0;
                $countI = 0;
                $countN = 0;
                $countStar = 0;
                $countBarList = array_count_values($result);
                // 計算B數 (不考慮*出現位置)
                if (isset($countBarList['B'])) {
                    $countB = $countBarList['B'];
                } else {
                    $countB = 0;
                }
                // 計算I數 (不考慮*出現位置)
                if (isset($countBarList['I'])) {
                    $countI = $countBarList['I'];
                } else {
                    $countI = 0;
                }
                // 計算N數 (不考慮*出現位置)
                if (isset($countBarList['N'])) {
                    $countN = $countBarList['N'];
                } else {
                    $countN = 0;
                }
                // 計算*數 (不考慮*出現位置)
                if (isset($countBarList['*'])) {
                    $countStar = $countBarList['*'];
                } else {
                    $countStar = 0;
                }
                // 計算實際B數量 (當P1 P2 出現 * 時, B數+1)
                if ($result[0] == '*' && $result[1] == '*') {
                    $countB = $countB + 2; // P1 P2 = ** => 等同於2個B
                } elseif (($result[0] == '*' && $result[1] != '*') || ($result[0] != '*' && $result[1] == '*')) {
                    $countB = $countB + 1; // P1 P2 = *X or X* => 等同於1個B
                } elseif (($result[0] == '*' && $result[1] == 'B') || ($result[0] == 'B' && $result[1] == '*')) {
                    $countB = $countB + 1; // P1 P2 = *B or B* => 等同於2個B 但上面已算過B出現次數 因此只需要加上 *等效於B 也就是 +1
                }
                // 計算實際I數量 (當P3 出現 * 時, I數+1)
                if ($result[2] == '*') {
                    $countI = $countI + 1;
                }
                // 計算實際N數量 (當P4 出現 * 時, N數+1)
                if ($result[3] == '*') {
                    $countN = $countN + 1;
                }
                // BINS 四位數組成
                $bins = $countB . $countI . $countN . $countStar;

                /* ----- 計算中獎注項 ----- */
                $winList = [];
                switch ($countB) {
                    case 0:
                        array_push($winList, '0B');
                        break;
                    case 1:
                        array_push($winList, '1B');
                        break;
                    case 2:
                        array_push($winList, '2B');
                        break;
                    case 3:
                        array_push($winList, '3B');
                        break;
                    case 4:
                        array_push($winList, '4B');
                        array_push($winList, 'BBBB');
                        break;
                    default:
                        // none
                        break;
                }
                if ($countI == 4) {
                    array_push($winList, 'IIII');
                }
                if ($countN == 4) {
                    array_push($winList, 'NNNN');
                }
                if ($countStar == 4) {
                    if (array_search('2B', $winList) === false) {
                        array_push($winList, '2B');
                    }
                    array_push($winList, '****');
                }

                /* ----- 判斷是否出現BONUS ----- */
                $bonus = 'NO';
                if ($result[0] == 'B' && $result[1] == 'B' && $result[2] == 'I' && $result[3] == 'N') {
                    $bonus = 'YES';
                } elseif ($result[0] == '*' && $result[1] == '*' && $result[2] == '*' && $result[3] == '*') {
                    $bonus = 'YES';
                }

                /* ----- 回傳此次下注中獎注項 ----- */
                $betMoney = explode(",", $value); // 取得下注獎金
                $totalBetMoney = array_sum($betMoney); // 先計算總投注金額
                for ($i = 0; $i < count($betMoney); $i++) { // 輸入為空白 => 代表沒下此注(等同0元)
                    if ($betMoney[$i] == '') {
                        $betMoney[$i] = '0';
                    }
                }
                // 0個B 1個B    2個B	3個B	4個B	BBBB	IIII	NNNN	****
                //  20	 12	    4	    12	   20	   30	   50	   50	   100
                // 若此注項下注0元 => 代表沒有下此注項 欄位清空 => 更新 $betPosition
                $betPosition = ['0B', '1B', '2B', '3B', '4B', 'BBBB', 'IIII', 'NNNN', '****'];
                for ($i = 0; $i < count($betMoney); $i++) {
                    if ($betMoney[$i] == '0') {
                        $betPosition[$i] = '';
                    }
                }
                $moneyBack = ['', '', '', '', '', '', '', '', '']; // 此次下注中獎注項
                foreach ($winList as $k => $v) {
                    if (array_search($v, $betPosition) !== false) {
                        $moneyBack[array_search($v, $betPosition)] = $v;
                    }
                }

                /* ----- 判斷是否中獎  ----- */
                $betResult = "未中獎";
                for ($i = 0; $i < count($betPosition); $i++) {
                    if (($betPosition[$i] === $moneyBack[$i]) && $betPosition[$i] != "" && $moneyBack[$i] != "") {
                        $betResult = "中獎";
                    }
                }

                /* ----- 回傳此次下注中獎獎金 ----- */
                $rate = [20, 12, 4, 12, 20, 30, 50, 50, 100]; // 賠率
                for ($i = 0; $i < count($betMoney); $i++) {
                    if ($moneyBack[$i] != '') {
                        $betMoney[$i] = $betMoney[$i] * $rate[$i];
                        if ($bonus == 'YES') {
                            $betMoney[$i] = $betMoney[$i] * 2;
                        }
                        $betMoney[$i] = (string)$betMoney[$i];
                    } else {
                        if ($bonus != 'YES') {
                            $betMoney[$i] = "0";
                        }
                    }
                }
                $totalReward = array_sum($betMoney); // 計算總中獎金額

                
                // 寫檔 (測試用)
                $file = 'win_record.txt';
                $current = file_get_contents($file);
                $current .= $bins . " - " . json_encode($result) . " - " . json_encode($winList) . " - " . json_encode($moneyBack) . " - " . json_encode($betMoney) . " - " . $bonus . "\n";
                file_put_contents($file, $current);

                DB::start_transaction(); // 啟動MYSQL交易機制
                $msg = "OK"; // 設定回傳訊息

                /* ----- 先扣錢 並寫一筆交易紀錄 註明為 下注扣款 ----- */
                // 取得原本金額($getPrevMoney) 再減去下注總金額($totalBetMoney)
                $nowMember = Session::get('member');
                $getPrevMoney = DB::select('money')->from('members')->where('account', '=', $nowMember)->execute()->as_array();
                if ($totalBetMoney > $getPrevMoney[0]['money']) {
                    $msg = "交易失敗，ERRCODE:餘額不足";
                    DB::rollback_transaction(); // 交易失敗 rollback
                } else {
                    $updateMoney = DB::update('members')->value('money', $getPrevMoney[0]['money'] - $totalBetMoney)->where('account', '=', $nowMember);
                    if ($updateMoney->execute()) {
                        // 執行成功 => 寫一筆交易紀錄 註明為 下注扣款
                        $moneyRecord = DB::insert('money_record')->columns(array(
                            'account', 'update_money', 'current_money', 'desc', 'status'
                        ))->values(array(
                            $nowMember, $totalBetMoney, $getPrevMoney[0]['money'] - $totalBetMoney, '下注扣款', 'bet'
                        ))->execute();
                    } else {
                        $msg = "交易失敗，ERRCODE:下注扣款SQL執行錯誤";
                        DB::rollback_transaction(); // 交易失敗 rollback
                    }
                }

                sleep(1); // 下注扣款 與 寫入開獎結果+中獎交易紀錄 之間 間隔幾秒 (方便紀錄)

                /* ----- 開獎後 寫一筆下注紀錄 包含此次下注注項 下注結果(中獎/未中獎) ｜ 若有中獎 寫一筆交易紀錄 並加錢 ----- */
                $winOrLose = $totalReward - $totalBetMoney; // 盈虧金額
                $betRecord = DB::insert('bet_record')->columns(array(
                    // 帳號 - 開獎結果 - 下注組合 - 下注總金額 - 中獎(派彩)金額 - 盈虧金額 - 下注結果
                    'account', 'win_list', 'bet_list', 'total_bet_money', 'total_reward', 'total_win_money', 'bet_result'
                ))->values(array(
                    $nowMember, json_encode($winList), json_encode($betPosition), $totalBetMoney, $totalReward, $winOrLose, $betResult
                ));
                if ($betRecord->execute()) {
                    // 下注紀錄儲存成功 => 判斷是否中獎
                    if ($betResult == "中獎") {
                        // 中獎 => 加錢 並寫一筆交易紀錄
                        $getPrevMoney = DB::select('money')->from('members')->where('account', '=', $nowMember)->execute()->as_array();
                        $updateMoney = DB::update('members')->value('money', $getPrevMoney[0]['money'] + $totalReward)->where('account', '=', $nowMember);
                        if ($updateMoney->execute()) {
                            // 執行成功 => 寫一筆交易紀錄 註明為 下注獲利
                            $moneyRecord = DB::insert('money_record')->columns(array(
                                'account', 'update_money', 'current_money', 'desc', 'status'
                            ))->values(array(
                                $nowMember, $totalReward, $getPrevMoney[0]['money'] + $totalReward, '下注獲利', 'win'
                            ))->execute();
                        } else {
                            $msg = "交易失敗，ERRCODE:獲利寫入SQL執行錯誤";
                            DB::rollback_transaction(); // 交易失敗 rollback
                        }
                    }
                    // 下注 扣錢(更新會員金額 寫入交易紀錄) => 開獎(寫入下注紀錄 中獎時 更新會員金額與寫入交易紀錄)
                    // 上述動作完成後 做最後commit 完成MYSQL交易機制
                    DB::commit_transaction();
                } else {
                    $msg = "交易失敗，ERRCODE:下注紀錄寫入SQL執行錯誤";
                    DB::rollback_transaction(); // 交易失敗 rollback
                }
                // 回傳 是否中獎 - 拉霸盤面(一組陣列) - 中獎注項(一組陣列) - 此次下注中獎注項 - 此次下注中獎金額 - 是否出現BONUS - SQL儲存狀況
                echo $betResult . " - " . json_encode($result) . " - " . json_encode($winList) . " - " . json_encode($moneyBack) . " - " . json_encode($betMoney) . " - " . $bonus . " - " . $msg;
                break;
            // 取得會員目前餘額    
            case 'getMoney':
                $nowMember = Session::get('member');
                // 檢查session是否存在(是否為登入狀態)
                if (is_null($nowMember)) {
                    echo "登入後查詢";
                } else {
                    $sql = DB::select('money')->from('members')->where('account', '=', $nowMember)->execute()->as_array();
                    echo $sql[0]['money'];
                }
                break;
            // 檢查玩家狀態 被停權無法進行遊戲
            case 'checkStatus':
                $nowMember = Session::get('member');
                if (is_null($nowMember)) {
                    echo "未登入";
                } else {
                    $sql = DB::select('status')->from('members')->where('account', '=', $nowMember)->execute()->as_array();
                    echo $sql[0]['status'];
                }
                break;
            // 登出 清除Session
            case 'logout':
                Session::destroy();
                break;
            default:
                echo "POST ERROR";
                break;
        }
    }
}
