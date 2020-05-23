<?php
	$db_type  = 'mysql';        // DSN type
	$db_name  = 'UNIBYT';       // Database name
	$db_user  = 'root';         // Database user name
	$db_pass  = 'root';         // User password
	$db_host  = '127.0.0.1';    // Database host
	//$db_port  = '1434';       // Database server port

	$db_type_suee  = 'mysql';         // DSN type
	$db_name_suee  = 'suee';          // Database name
	$db_user_suee  = 'vazhinsky-s';   // Database user name
	$db_pass_suee  = 'S1eEVa2h';      // User password
	$db_host_suee  = '10.181.12.137'; // Database host
	//$db_port  = '1434';             // Database server port

	//define ('SERVER_ROOT_DIR', '/home/bes/byt.bes.brest.energo.net');
	define ('SERVER_ROOT',     "http://".$_SERVER["SERVER_NAME"]);
	define ('SERVER_ROOT_DIR', "d:/OSPanel/domains/unibyt");

	define ('SERVER2019_ROOT',     SERVER_ROOT."/unibyt2019");
	define ('SERVER2019_ROOT_DIR', SERVER_ROOT_DIR."/unibyt2019");

?>