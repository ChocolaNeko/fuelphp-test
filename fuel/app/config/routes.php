<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

	// 'newpage' => 'welcome/newpage',

	// testController => main.php
	// testView => pageA.php / pageB.php / pageC.php
	// 'testView/pageA' => 'testc/main/pagea',
	// 'pageB' => 'testView/pageB',
	// 'pageC' => 'testView/pageC',

	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);