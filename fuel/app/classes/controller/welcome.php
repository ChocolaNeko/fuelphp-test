<?php

class Controller_Welcome extends Controller
{

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// test ouptut
		$data = array();
		$data['username'] = 'Paul';
		$data['title'] = 'Index';
		// ------------------------------------------------
		// localhost/welcome/index
		// localhost/index
		// localhost/
		return Response::forge(View::forge('welcome/index', $data));
	}

	public function action_newpage()
	{
		// localhost/welcome/newpage
		return Response::forge(View::forge('welcome/newpage'));
		// return Response::forge(View::forge('testView/pageA')); // link to testView/pageA
	}

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		// localhost/welcome/hello
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
}
