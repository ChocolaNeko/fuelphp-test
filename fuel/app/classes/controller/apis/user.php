<?php

use Fuel\Core\Validation;

class Controller_Apis_User extends Controller
{
    public function action_newhome()
    {
        // create view
        $view = View::forge('userpage/newhome');

        // pass var to view 'newhome'
        $view->userName = 'Paul';
        $view->title = 'New home';

        // pass var to view 'newhome' (ver2)
        // $view->set('userName', 'Paul--');
        // $view->set('title', 'New home--');

        // show view
        return $view;
    }

    public function action_testfilter()
    {
        $view = View::forge('userpage/testfilter');

        $view->title = '<strong>not bold because filtered</strong>';
        $view->set('title2', '<strong> bold because unfiltered</strong>', false);
        $view->set_safe('title3', '<strong> bold because unfiltered</strong>');

        return $view;
    }

    /* localhost/apis/user/home 所回傳的View => home (首頁) */
    public function action_home()
    {
        $userInfo = array();
        $userInfo['loadTime'] = date('l jS \of F Y h:i:s A');
        return View::forge('userpage/home', $userInfo);
    }

    /* localhost/apis/user/memberlist 所回傳的View => memberlist (管理員後台 - 會員管理) */
    public function action_memberlist()
    {
        return View::forge('userpage/memberlist');
    }
    
    /* localhost/apis/user/reg 所回傳的View => reg (註冊帳號) */
    public function action_reg()
    {
        return View::forge('userpage/reg');
    }

    /* localhost/apis/user/login 所回傳的View => login (登入) */
    public function action_login()
    {
        return View::forge('userpage/login');
    }

    /* localhost/apis/user/memberinfo 所回傳的View => memberinfo (會員資料) */
    public function action_memberinfo()
    {
        return View::forge('userpage/memberinfo');
    }

    /* localhost/apis/user/game 所回傳的View => game (拉霸機) */
    public function action_game()
    {
        return View::forge('userpage/game');
    }

    public function action_setting()
    {
        // example: google search
        // echo Form::open(array('action' => 'http://google.com/search?', 'method' => 'get'));
        // echo Form::input('q', 'value', array('style' => 'border: 3px green dotted;'));
        // echo Form::csrf();
        // echo Form::submit();
        // echo Form::close();
        // ===============================================================================
        return Response::forge(View::forge('userpage/setting'));
    }
    
    /* localhost/apis/user/record 所回傳的View => record (管理員查看會員交易紀錄) */
    public function action_record()
    {
        return View::forge('userpage/record');
    }

    /* localhost/apis/user/betrecord 所回傳的View => betrecord (管理員查看會員下注紀錄) */
    public function action_betrecord()
    {
        return View::forge('userpage/betrecord');
    }
}
