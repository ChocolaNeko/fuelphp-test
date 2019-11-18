<?php

use Fuel\Core\Validation;

class Controller_Apis_User extends Controller
{
	public function action_home()
	{
        // The data parameter only accepts objects and arrays.
        $userInfo = array();
        $userInfo['name'] = 'Paul';
        $userInfo['loadTime'] = date('l jS \of F Y h:i:s A');
        var_dump($userInfo);
		// return View::forge('userpage/home', $userInfo);
    }
    
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

	public function action_profile()
	{
        // pass database data to view 'profile'
        $user = Model_Userdata::find_all();
        $data = array();
        $data['members'] = $user;
        return View::forge('userpage/profile', $data);
    }
    
    public function action_reg()
    {
        $name = Input::post('name','');
        $account = Input::post('account','');
        $password = Input::post('password','');
        $email = Input::post('email','');
        $tel = Input::post('tel','');

        // regex validation
        // $val = Validation::forge();

        if ($name != '' && $account != '' && $password != '' && $email != '' && $tel != '' ) {
            // account exist!?
            $checkExist = Model_Userdata::find_one_by('account', $account);
            if ($checkExist === null) {
                // account not exist => save new user
                echo $account . ": OK<br>";
                $newUser = Model_Userdata::forge()->set(array(
                    'name' => $name,
                    'account' => $account,
                    'password' => $password,
                    'email' => $email,
                    'tel' => $tel
                ));
                if ($newUser->save()) {
                    echo "success!";
                } else {
                    echo "reg failed!";
                }
            } else {
                // account exist => error
                echo $checkExist->account . ": XX";
            }
        } else {
            echo "empty";
        }
        return View::forge('userpage/reg');

    }

	public function action_setting()
	{
		return Response::forge(View::forge('userpage/setting'));
	}
}
