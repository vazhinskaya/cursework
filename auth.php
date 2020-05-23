<?php
	require_once ("include/setup.php");
	require_once ("include/lib_db.php");
	require_once ("include/lib_user.php");
//	ini_set ("display_errors",         DEBUG);/
//	ini_set ("display_startup_errors", DEBUG);
//	ini_set ("error_reporting",        1);
//	ini_set ("error_reporting",        E_ALL);

	$dblink = dbConnect();
	$action = get_input ('act');
	$errors = array();

	/*******************************************************************************************/
	/*                    AJAX-обработчики общего назначения                                   */
	/*******************************************************************************************/
	if ($action == 'gen_func') {
		$step = get_input('step');
		/****************************************************************************************/
		/*             подгрузка РЭСов для выбраннонго ФЭСА в форме аутентификации          	*/
		/****************************************************************************************/
		if ($step == 'getResforFes') {
			$fes = get_input('fes');
        	$spisok = GetResForLogin($fes);
			$res = $spisok[0]['type_res'];
			echo "<div id='ress_select_form'>";
				echo "<select name='ress' id='ress' class='login' onchange='getUsersforRes();'>";
					foreach ($spisok as $row)
					echo "<option value='{$row['type_res']}'>{$row['knaim']}</option>";
				echo "</select>";
			echo "</div>";
		}

		/****************************************************************************************/
		/*             подгрузка пользователей для выбраннонго РЭСА в форме аутентификации   	*/
		/****************************************************************************************/
		if ($step == 'getUsersforRes') {
	        $res = get_input('res');
	        if ($res == 'first') {
	        	$spisok = GetResForLogin(get_input('fes'));
				$res = $spisok[0]['type_res'];
	        }
	        else {
				if (dbOne("SELECT COUNT(*) FROM _res where type_res = '$res'",'int') == 0) {
              		$res = dbOne("SELECT type_res FROM _res LIMIT 1");
            	}
            }
			$spisok = GetUsers($res);
			echo "<div id='users_select_form'>";
				echo "<select name='userid' class='login'>" ;
					foreach ($spisok as $row)
					echo "<option value='{$row['tbn']}'>{$row['fio']}</option>";
				echo "</select>";
			echo "</div>";
		}
	}

    if ($action == "login") {
        $fess  = get_input('fess');
        $fessid= dbOne("select id from _fes where fes='".$fess."'");
        $ress  = get_input('ress');
        $ressid= dbOne("select id from _res where res='".$ress."'");
        $userid= get_input('userid');
        $pass  = get_input('pass');
        $sql = $dblink->query("select * from ".$ress."_personal where tbn='".$userid."' and pass='".$pass."'");
        if ($sql->RowCount() == 0) {$userid = -1;}
        else {
            SetUserCookie($fess, $fessid, $ress, $ressid, $userid);
        }
        header("Location: /index.php");
    }
?>