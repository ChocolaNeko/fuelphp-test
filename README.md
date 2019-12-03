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