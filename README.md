# 程式架構

## Model
-
## View
- app/views/userpage
    - home.php  首頁  (對應頁面: yourhost/apis/user/home)
    - game.php  拉霸機  (對應頁面: yourhost/apis/user/game)
    - login.php 登入  (對應頁面: yourhost/apis/user/login)
    - reg.php   註冊帳號  (對應頁面: yourhost/apis/user/reg)

    - 會員頁面
        - memberinfo.php  會員資料頁 (基本資料 / 密碼修改 / 交易紀錄 / 下注紀錄)  
        (對應頁面: yourhost/apis/user/memberinfo)

    - 管理員後台
        - memberlist.php  管理員後台 - 會員管理  
        (對應頁面: yourhost/apis/user/memberlist)
        - record.php      管理員後台 - 會員交易紀錄  
        (對應頁面: yourhost/apis/user/record)
        - betrecord.php   管理員後台 - 會員下注紀錄  
        (對應頁面: yourhost/apis/user/betrecord)

## Controller
 - app/classes/controller/apis
     - user.php  用來呈現各頁面(View)
     - ajax.php  各頁面使用的api

## 資料庫
 - 會員(members)
    - id (int(11), auto_increment, PK)  
    - account (varchar(20)) => 帳號
    - password (varchar(300)) => 密碼
    - money (int(11), default = 0) => 金額
    - status (varchar(10), default = 'on') =>帳號狀態

 - 交易紀錄(money_record)
    - id (int(11), auto_increment, PK)
    - account (varchar(20)) => 帳號
    - update_money (int(11)) => 此次交易金額
    - current_money (int(11)) => 此次交易後總金額
    - desc (varchar(100)) => 交易描述
    - update_time (timestamp) => 交易時間
    - status (varchar(20)) => 交易狀態

 - 下注紀錄(bet_record)
    - account (varchar(20)) => 下注帳號
    - bet_time (timestamp) => 下注時間
    - bet_serial_num (int(20), auto_increment, PK) => 注單編號(後12碼)
    - win_list (varchar(100)) => 開獎結果
    - bet_list (varchar(100)) => 下注組合
    - total_bet_money (varchar(100)) => 下注總金額
    - total_reward (varchar(100)) => 中獎(派彩)金額
    - total_win_money (varchar(100)) => 盈虧金額
    - bet_result (varchar(100)) => 下注結果