<?php

// Рубилка для SERVER 11
//if (getenv ("SERVER_ADDR")=="10.181.160.111") {
//	$ip = getenv ("REMOTE_ADDR");
//	$ip3 = explode(".", $ip);
//	if ($ip3[2]!="160") { echo '<h1>Вы работаете в тестовой ВЕРСИИ !!!</h1>'; }
//}

require_once('include/core.php');
require_once("include/lib_general.php");

$ip = getenv("REMOTE_ADDR");
$smarty->assign("ip", $ip);
RegisterGlobalNULL('res_spisok', 'nomr_spisok', 'nomr_spisok_edit', 'abid', 'kvit_spisok_edit', 'username', 'user_access', 'users', 'userid', 'userres', 'fess', 'fessid', 'ress', 'pass', 'mt', 'ab', 'sel_all_ab');
RegisterGlobalNULL('filtrok', 'id', 'title', 'countries', 'main', 'act', 'street_spisok', 'nasp_spisok', 'street_spisok_edit', 'nasp_spisok_edit', 'region_spisok_edit', 'postr_spisok_edit', 'prinadl_spisok_edit', 'obhod_spisok_edit', 'main');
RegisterGlobalNULL('kn', 'nom', 'fam', 'im', 'ot', 'tip_np', 'np', 'street', 'dom', 'domadd', 'kb', 'rpage', 'page_num', 'page_count', 'order_num', 'ekn', 'enom', 's_text', 'mob_kod', 'plomba');
RegisterGlobalNULL('nday', 'nmonth', 'nyear', 'eres', 'reason', 'enomr', 'schet');

$title = 'Бытовые абоненты';
$smarty->assign("title", $title);
$smarty->assign("tipnasp", GetTipNasp());
$smarty->assign("deleted", false);
$error_message = "";
if (empty($page_num)) {$page_num = 1;}
if (empty($order_num)) {$order_num = 0;}
if ($main == "") {$main="main";}
if ($act == "") {$act="index";}

// *****************************
// Поиск на Ajax
// *****************************
if (!isset($_POST['rpage'])) {$_POST['rpage']="";}
if ($_POST['rpage']!="") {
	switch ($_POST['rpage']) {
			case "np":
				echo '<select name="np" class="psi1" id="np" onchange="spis2()">
					<option value="" selected>Не важно</option>';
				$nasp = GetNasp($_POST['tip_np']);
				foreach ($nasp as $key=>$value)
				{
					echo '<option value="'.$value["id"].'">'.$value["np"].'</option>';
				}
				echo '</select>';
			break;

			case "street":
				echo '<select name="street" class="psi1" id="street">
					<option value=""  SELECTED>Не важно</option>';
				if (isset($_POST['np'])) {
					$nasp=GetStreet($_POST['np']);
					foreach ($nasp as $key=>$value)
					{
						echo '<option value="'.$value["id"].'">'.$value["tip_street"].' '.$value["street"].'</option>';
					}
				}
				echo '</select>';
			break;

			case "enp":
				echo '<select name="ab[np1][]" id="ab[np1][]" class="psi1" onchange="espis2()">
				<option value=""  SELECTED></option>';
				$nasp=GetNasp($_POST['etip']);
				foreach ($nasp as $key=>$value)
				{
					echo '<option value="'.$value["id"].'">'.$value["np"].'</option>';
				}
				echo '</select>';
			break;

			case "estreet":
				$z = $_POST['ab'];
				$nasp=GetStreet($z["np1"][0]);
				echo '<select name="ab[nu1][]" id="ab[nu1][]" class="psi1" onchange="espis3()">
					<option value="" SELECTED></option>';
				foreach ($nasp as $key=>$value)
				{
					if ($value["street"]=="") {echo '<option value="'.$value["id"].'">Без улицы</option>';}
					else {
						echo '<option value="'.$value["id"].'">'.$value["tip_street"].' '.$value["street"].'</option>';
					}
				}
				echo '</select>';
			break;

			case "edom":
				$z = $_POST['ab'];
				echo '<select id="ab[id_dom][]" name="ab[id_dom][]" class="psi1" onchange="show_save();return false;">
					<option value="" SELECTED></option>';
				if (isset($z["nu1"][0])) {
					$nasp=GetDom($z["nu1"][0]);
					foreach ($nasp as $key=>$value)
					{
						if (trim($value["domadd"])=="") {echo '<option value="'.$value["id"].'">'.$value["dom"].'</option>';}
						else{ echo '<option value="'.$value["id"].'">'.$value["dom"].'/'.$value["domadd"].'</option>';}
					}
				}
				echo '</select>';
			break;
	}
}
else {
/*====================================*/
/* ОСНОВНОЕ ==========================*/
/*====================================*/

	// ************************************
	// EXIT
	// ************************************
	if ($act == "exit") { // Если пользователь завершает свой сеанс
		ClearUserCookie();
		$userid = -1;
		header("Location: /index.php");
		$act = "login";
	}

  	// ************************************
  	// LOGIN
  	// ************************************
  	if ($act == "login") { // Если входит
		$ress = get_input('ress');
		$sql = "select * from {$ress}_personal where tbn='".$userid."' and pass='".$pass."'";
    	$res = $dblink->query($sql);
		if ($res->rowCount() == 0) {$userid=-1;}
		else {
			SetUserCookie($userid, $ress);
		}
		header("Location: /index.php");
  	}
	else {
		$userfes = GetUserFesCookie();
		$userfesid=GetUserFesIdCookie();
		$userres = GetUserResCookie();
		$userresid=GetUserResIdCookie();
		$userid  = GetUserTbnCookie();
  	}

  	if ($userid >= 0) { // Проверка на "вошел ли пользователь"
		$user_access = GetUserAccess($userid);
		$username = GetUserName($userid);
		$smarty->assign("username", $username);       // USER NAME   - табельный номер
		$smarty->assign("user_access", $user_access); // USER ACCESS - уровень доступа
		$layout='layout.html';
		$smarty->assign("main_area", "main.html");
		$smarty->assign("s_text",$s_text);
        $smarty->assign("sel_all_ab",$sel_all_ab);
		$smarty->assign('kn',$kn);
		$smarty->assign('nom',$nom);
		$smarty->assign("fam",$fam);
		$smarty->assign("im",$im);
		$smarty->assign("ot",$ot);
		$smarty->assign("tip_np",$np);
		$smarty->assign("np",$np);
		$smarty->assign("street",$street);
		$smarty->assign("dom",$dom);
        $smarty->assign("domadd",$domadd);
		$smarty->assign("kb",$kb);
		$smarty->assign("schet",$schet);
		$smarty->assign("plomba",$plomba);

		// ************************************
		// INDEX
		// ************************************
		if ($act == "index") {
			$smarty->assign("tipnasp", GetTipNasp());
		}

		// ************************************
		// MAIN
		// ************************************
		if ($act == "main") {
			$main="main";
		}

		// ************************************
		// Добавление абонента
		// ************************************
		if ($act == "addab") {
			$smarty->assign("nomr_spisok", GetNomr());
			$smarty->assign("res_spisok", GetRes());
			$smarty->assign("tip_np", $tip_np);
			$smarty->assign("nasp_spisok", GetNasp($tip_np));
			$smarty->assign("street_spisok", GetStreet($np));
			$smarty->assign("nomr_spisok", GetNomr());
			$smarty->assign("res_spisok", GetRes());
			$tip_nasp = GetTipNasp();
			$smarty->assign("tipnasp_spisok_edit", $tip_nasp);
			$smarty->assign("nasp_spisok_edit", GetNasp($tip_nasp[0]["id"]));
			$main="addab";
		}

		// ************************************
		// Добавление ДУБЛИКАТА абонента
		// ************************************
		if ($act == "dublab") {
			$smarty->assign("nomr_spisok", GetNomr());
			$smarty->assign("res_spisok", GetRes());
			$smarty->assign("tip_np", $tip_np);
			$smarty->assign("nasp_spisok", GetNasp($tip_np));
			$smarty->assign("street_spisok", GetStreet($np));
			$smarty->assign("nomr_spisok", GetNomr());
			$smarty->assign("res_spisok", GetRes());
			$tip_nasp = GetTipNasp();
			$smarty->assign("tipnasp_spisok_edit", $tip_nasp);
			$smarty->assign("nasp_spisok_edit", GetNasp($tip_nasp[0]["id"]));

            // Получаем АБОНЕНТА
			$sql_sel = "select a.*, d.vl, d.opora, date_format(a.datadog, '%d.%m.%Y') as edatadog,
                		CASE WHEN a.paspdata is not null THEN date_format(a.paspdata, '%d.%m.%Y') ELSE null END paspdata,
                		b.np as nas_punkt, c.street, b.id_tip as tip_np, b.id as nasp_id, c.id as street_id, d.id as dom_id, d.id_region, e.region, d.id_postr, d.id_prinadl, d.id_res, d.dom, d.domadd, d.askue, p.fio
                        from ".CURRENT_RES."_main a LEFT JOIN ".CURRENT_RES."_personal p ON a.tbn=p.tbn,
                        ".CURRENT_RES."_np b, ".CURRENT_RES."_street c,
                        ".CURRENT_RES."_dom d,
                        ".CURRENT_RES."_region e
                        where a.id_dom=d.id and d.id_ul=c.id and c.id_np=b.id and a.kn='".$ekn."' and e.id=d.id_region and a.nom='".$enom."' and a.dlt=0";
	        $res = $dblink->query($sql_sel);
	        $rows = $res->fetchAll();
            // Разбиваем телефон на код и телефон
            $mob_tel = explode("-",$rows[0]['mobtel']);
            if (count($mob_tel)>1) {
            	$mob_kod = $mob_tel[0];
            	$rows[0]['mobtel'] = $mob_tel[1];
            } else {
            	$mob_kod = "";
            	$rows[0]['mobtel'] = $mob_tel[0];
            }
            $smarty->assign("mob_kod", $mob_kod);

			$schets = GetSchet($rows[0]['kn'], $rows[0]['nom']);
			if (count($schets[0])>0) {
				$rows[0]['sc_dubl'] = $schets[0][0]['scid'];
			}

            $rows[0]['nom'] = '';
            $rows[0]['fam'] = '';
            $rows[0]['im'] = '';
            $rows[0]['ot'] = '';
            $rows[0]['tel'] = '';
            $rows[0]['mobtel'] = '';
            $rows[0]['kb'] = '';
            $abonent = $rows[0]; // Сохранили абонента в переменную

			$smarty->assign('object', $rows[0]);
            $snomr = explode(',',$rows[0]['nomr']);
			$smarty->assign('nomr_spisok_edit', $snomr);
			$smarty->assign('tipnasp_spisok_edit', GetTipNasp());
			$smarty->assign('nasp_spisok_edit', GetNasp($rows[0]['tip_np']));
			$smarty->assign('street_spisok_edit', GetStreet($rows[0]['nasp_id']));
			$smarty->assign('region_spisok_edit', GetRegion($rows[0]['nasp_id']));
			$smarty->assign('postr_spisok_edit', GetPostr());
			$smarty->assign('dom_spisok_edit', GetDom($rows[0]['street_id']));

			$main='dublab';
		}

		// ************************************
		// DELETE
		// ************************************
		if ($act == 'delab') {
			$tbn= GetUserTbnCookie();
			// Архивирование
			$edittime=date('Y.m.d H:i:s');
			$reason= $reason.' (Удаление абонента)';
			$sql = "update ".CURRENT_RES."_main set dlt=2, tbn='$tbn', reason='".$reason."', edittime='$edittime' where kn='".$kn."' and nom='".$nom."' and dlt=0";
	        $res = $dblink->query($sql);
		}

		// ************************************
		// SEARCH  Простой поиск абонента
		// ************************************
		if ($act == "search_simple") {
            $s_text = trim($s_text);
			// после * - поиск по адресу
			if (mb_strpos($s_text, '*')!==false) {
				$s_textaddr = mb_substr($s_text, mb_strpos($s_text, '*')+1, mb_strlen($s_text)-mb_strpos($s_text, '*'));
				$s_textfio = trim(mb_substr($s_text, 0, mb_strpos($s_text, '*')));
			}
			else {
				$s_textaddr = '';
				$s_textfio = $s_text;
			}
            $z = explode(" ",$s_textfio);
            $res = array();
			if (!empty($s_textfio)) {
				if (count($z)==2) {
					$res = GetSearchSpis($page_count, $z[0], $z[1], $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num, "", "", 50, 0, $s_textaddr);
				}
				else {
					$z = explode("-",$s_textfio);
					if (count($z) == 2) {
						$res = GetSearchSpis($page_count, $z[0], $z[1], $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num,  "", "", 50, 0, $s_textaddr);
					}
					else {
						if (is_numeric($s_textfio)) {
							$res = GetSearchSpis($page_count, mb_substr($s_textfio,0,4), mb_substr($s_textfio,4,3), $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num, "", "", 50, 0, $s_textaddr);
						}
						else {
							$res = GetSearchSpis($page_count, $kn, $nom, $s_textfio, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num, "", "", 50, 0, $s_textaddr);
						}
					}
				}
			}
			else {
				$res = GetSearchSpis($page_count, $kn, $nom, $s_textfio, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num, "", "", 50, 0, $s_textaddr);
			}
            $rows = $res->fetchAll(PDO::FETCH_BOTH);
			$smarty->assign('tip_np', $tip_np);
			$smarty->assign('nasp_spisok', GetNasp($tip_np));
			$smarty->assign('street_spisok', GetStreet($np));
			$smarty->assign('object', $rows);
			$smarty->assign('page_count', $page_count);
			$smarty->assign('page_num', $page_num);
			$smarty->assign('order_num', $order_num);
			$smarty->assign('vsego', $res->rowCount());
			$main = 'search';
            if ($res->rowCount() == 1) {
            	$act='editab';
            	$ekn=$rows[0]['kn'];
            	$enom=$rows[0]['nom'];
            }
		}

		// ************************************
		// SEARCH  Поиск
		// ************************************
		if ($act == 'search') {
			$res= array();
			$smarty->assign('tip_np', $tip_np);
			$smarty->assign('nasp_spisok', GetNasp($tip_np));
			$smarty->assign('street_spisok', GetStreet($np));
            if ($sel_all_ab == '1') {      // Если пустой список, выводим всех абонентов
                $res = GetSearchSpis($page_count, 'Все абоненты', '', $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num);
            } else {
            	$res = GetSearchSpis($page_count, $kn, $nom, $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num, $schet, $plomba);
            }
            $rows = $res->fetchAll(PDO::FETCH_BOTH);
			$smarty->assign('object', $rows);
			$smarty->assign('page_count', $page_count);
			$smarty->assign('page_num', $page_num);
			$smarty->assign('order_num', $order_num);
			$smarty->assign('vsego', $res->rowCount());
			$main="search";
		}

		// ************************************
		// Сохранение абонента
		// ************************************
        if ($act == 'saveab') {
            dbBeginTransaction(); // Начало транзакции
            $etap = 0;
            $etap = 1;
            $tbn= GetUserTbnCookie();
            $edittime=date('Y.m.d H:i:s');
            // Проверка на существование записи
            $sql = "select * from ".CURRENT_RES."_main where kn='".$ab['kn']."' and nom='".$ab['nom']."' and dlt=0";
	        $res = $dblink->query($sql);
            // Если такой записи еще нет или редактирование
            if ((($res->rowCount()!=0) and ($id!="") and ($kn==$ab['kn']) and ($nom==$ab['nom'])) or ($res->rowCount()==0)) {
                $etap = 2;
                // Проверка диапазонов
                $sql = "select * from _diapazon_knnom where res='".CURRENT_RES."' and d2>='".$ab['kn']."' and d1<='".$ab['kn']."'";
		        $res = $dblink->query($sql);
                if ($res->rowCount()>0) {
                    $etap = 3;
                    // Архивирование
                    if (($kn!=$ab['kn']) or ($nom!=$ab['nom'])) {$reason= $reason." (Изменение номера абонента: был-".$kn.$nom.", стал-".$ab['kn'].$ab['nom'].")";} else { $reason = "Редактирование. ".$reason;}
                    $sql = "update ".CURRENT_RES."_main set dlt=1, tbn='$tbn', edittime='$edittime', reason='$reason' where kn='".$kn."' and nom='".$nom."' and dlt=0";
	                $res = $dblink->query($sql);
                    // Сохранение NOMR
                    $nomr = "-1";
                    if ($enomr!="") {
                        foreach($enomr as $key=>$value) {
                            $nomr = $nomr.",".$key;
                        }
                    }
                    if ($ab['mobtel']!="") { $ab['mobtel'] = $mob_kod."-".$ab['mobtel']; } // Сохраняем с кодом
                    $ab['id_dom'] = implode(",", $ab['id_dom']);

                    $ab["paspkem"] = trim($ab['paspkem1']." ".$ab['paspkem2']);
                    unset($ab['paspkem1']);
                    unset($ab['paspkem2']);

                    unset($ab['nu1']);
                    unset($ab['np1']);

                    if ($ab['postr']=='') $ab['postr']=0;
                    if ($ab['power']=='') $ab['power']=0;

                    $sql_params = array();
                    $sql_values = array();
                    foreach($ab as $key=>$value) {
                        $sql_params[] = $key;
                        if ($key=='datadog' or $key=='paspdata') {
                            if ($value=='') $sql_values[] = 'null';
                            else $sql_values[] = "'".dateYMD($value)."'";
                        }
                        else $sql_values[] = "'".$value."'";
                    }

                    $idate = $nday.".".$nmonth.".".$nyear; // Дата ввода
                    $sql_params = implode(" , ", $sql_params).", reason, dlt, edittime, tbn, nomr";
                    $sql_values = implode(" , ", $sql_values).", '$reason', 0, '$edittime', '$tbn', '".$nomr."'";

                    $sql = "insert into ".CURRENT_RES."_main(".$sql_params.") VALUES(".$sql_values.")";
		            $res = $dblink->query($sql);

                    // ************************************
                    // Процедура ПРИ ИЗМЕНЕНИИ НОМЕРА АБОНЕНТА
                    // ************************************
                    if (($kn!=$ab['kn']) or ($nom!=$ab['nom'])) {

	                    $sql = "update ".CURRENT_RES."_mainsc set kn='".$ab['kn']."', nom='".$ab['nom']."'  where kn='$kn' and nom='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_kvit set knkv='".$ab['kn']."', nomkv='".$ab['nom']."'  where knkv='$kn' and nomkv='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_kvitxvp set knkv='".$ab['kn']."', nomkv='".$ab['nom']."'  where knkv='$kn' and nomkv='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_obhod set knobh='".$ab['kn']."', nomobh='".$ab['nom']."'  where knobh='$kn' and nomobh='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_otkl set kn='".$ab['kn']."', nom='".$ab['nom']."'  where kn='$kn' and nom='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_otkl set kn='".$ab['kn']."', nom='".$ab['nom']."'  where kn='$kn' and nom='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_tarhist_sem set kn='".$ab['kn']."', nom='".$ab['nom']."'  where kn='$kn' and nom='$nom'  ";
				        $res = $dblink->query($sql);

	                    $sql = "update ".CURRENT_RES."_sem_lg set kn='".$ab['kn']."', nom='".$ab['nom']."'  where kn='$kn' and nom='$nom'  ";
				        $res = $dblink->query($sql);
                    }
                } else { $error_message = "В этом диапазоне KN ввод запрещен!"; }
            } else { $error_message = "Абонент с таким номером уже есть в базе!"; }
            $ekn = $ab['kn'];
            $enom = $ab['nom'];
            $act = 'editab';
            if ($error_message!='') {
                $smarty->assign('error_message', $error_message);
                $act = 'editab_error';
            }
            dbEndTransaction();
        }

		// ************************************
		// Редактирование абонента
		// ************************************
		if ($act == 'editab') {
			$smarty->assign('page_num', $page_num);
			$smarty->assign('tip_np', $tip_np);
			$smarty->assign('nasp_spisok', GetNasp($tip_np));
			$smarty->assign('street_spisok', GetStreet($np));
			$smarty->assign('nomr_spisok', GetNomr());
			$smarty->assign('res_spisok', GetRes());
			$smarty->assign('rovd_spisok', GetGenSpr(2));
			// Получаем АБОНЕНТА
			$sql_sel = "select a.*, d.id_vlkl, d.opora, date_format(a.datadog, '%d.%m.%Y') as edatadog,
			CASE WHEN a.paspdata is not null THEN date_format(a.paspdata, '%d.%m.%Y') ELSE null END paspdata,
			b.np as nas_punkt, c.street, b.id_tip as tip_np, b.id as nasp_id, c.id as street_id, d.id as dom_id, d.id_region, e.region, d.id_postr, d.id_prinadl, d.id_res, d.dom, d.domadd, d.askue, p.fio
			from ".CURRENT_RES."_main a
			LEFT JOIN ".CURRENT_RES."_personal p ON a.tbn=p.tbn,
			".CURRENT_RES."_np b, ".CURRENT_RES."_street c,
          	".CURRENT_RES."_dom d,
			".CURRENT_RES."_region e
			where a.id_dom=d.id and d.id_ul=c.id and c.id_np=b.id and a.kn='".$ekn."' and e.id=d.id_region and a.nom='".$enom."' and a.dlt=0";
	        $res = $dblink->query($sql_sel);
   		    $rows = $res->fetchAll();

			// Разбиваем телефон на код и телефон
			$mob_tel = explode("-",$rows[0]['mobtel']);
			if (count($mob_tel)>1) {
           		$mob_kod = $mob_tel[0];
	           	$rows[0]['mobtel'] = $mob_tel[1];
        	}
        	else {
            	$mob_kod = "";
	           	$rows[0]['mobtel'] = $mob_tel[0];
        	}
			$smarty->assign('mob_kod', $mob_kod);

			// Для дубликата
			if ($rows[0]['sc_dubl']!="") {
		  		$sql = "select a.id as scid, a.*, b.*, a.maxn as maxn , p.fio, r1.res_value, r2.res_value as res_value2, t.tipps
		        from ".CURRENT_RES."_mainsc a
				LEFT JOIN ".CURRENT_RES."_personal p ON a.tbn=p.tbn
				LEFT JOIN _gen_sprav r1 ON a.reas1=r1.id
				LEFT JOIN _gen_sprav r2 ON a.reas2=r2.id
				LEFT JOIN _tip_sc b ON a.ts=b.id
				LEFT JOIN ".CURRENT_RES."_tip_prinadl t ON a.ps=t.id
				where a.id = ".$rows[0]['sc_dubl']."
				order by a.dateust desc, a.id desc";
		  		$res0 = $dblink->query($sql);
		  		$rows0 = $res0->fetchAll();
		  		$smarty->assign('sc_dublicat', $rows0[0]);
    		}
			// -----------------------------------------

			$abonent = $rows[0]; // Сохранили абонента в переменную
			$smarty->assign('object', $rows[0]);
			$snomr = explode(',',$rows[0]['nomr']);
			$smarty->assign('nomr_spisok_edit', $snomr);
			$smarty->assign('tipnasp_spisok_edit', GetTipNasp());
			$smarty->assign('nasp_spisok_edit', GetNasp($rows[0]['tip_np']));
			$smarty->assign('street_spisok_edit', GetStreet($rows[0]['nasp_id']));
			$smarty->assign('region_spisok_edit', GetRegion($rows[0]['nasp_id']));
			$smarty->assign('postr_spisok_edit', GetPostr());
			$smarty->assign('dom_spisok_edit', GetDom($rows[0]['street_id']));

			// Для вывода подстанций и вл(кл)
			if ($rows[0]['id_vlkl'] != '') {
	            $sql = "SELECT DISTINCT * from ".CURRENT_RES."_ktp where id=".$rows[0]["id_vlkl"];
				$res0 = $dblink->query($sql);
				$rows0 = $res0->fetchAll();
	            if ($res0->rowCount()>0) {
	               	$smarty->assign('ktp', $rows0[0]);
		        }
   		    }

			$prinadl = GetPrinadl();
			for ($k=0;$k<count($prinadl)-1;$k++) {
				$prinadl[$k]['tipps'] = htmlspecialchars($prinadl[$k]['tipps']);
			}
			$smarty->assign('prinadl_spisok_edit', $prinadl);

			$schets = GetSchet($rows[0]['kn'], $rows[0]['nom']);
    	    $kol_uch = isset($schets[0][0]) ? count($schets) : 1;
			$prinadlezh='';
			if (isset($schets[0][0]['tipps'])) $prinadlezh = $schets[0][0]['tipps'];
			$smarty->assign('prinadlezh', $prinadlezh);
			$smarty->assign('schet_spisok_edit', $schets);
			$smarty->assign('schet_spisok_count', count($schets));

			// Многотарифность
			$kol_uch_show = 1;
			if ($schets[0][0]['pokusc2'] && !$schets[0][0]['pokssc2'] ) $kol_uch_show++;
			if ($schets[0][0]['pokusc3'] && !$schets[0][0]['pokssc3'] ) $kol_uch_show++;
   		    $smarty->assign("kol_uch_show", $kol_uch_show);
			for ($uch=1; $uch<=$kol_uch_show; $uch++) {
        	    $mt = GetTarif($rows[0]["kn"], $rows[0]["nom"], $uch);
           		if ($mt) {
                	$smarty->assign("mt", $mt[0]["mt"]);
            	}
            	// Обходы
	            $obhod = GetObhod($rows[0]["kn"], $rows[0]["nom"], $uch);
	            $smarty->assign("obhod_spisok_edit", $obhod);
   		        // Квитанции
    	        $kvits = GetKvit($rows[0]["kn"], $rows[0]["nom"], $uch, true);
       	     	$smarty->assign("kvit_spisok_edit", $kvits);

            	// Строка для быстрого отображения тарифа
	            $fast_tarif = '';
   	        	// Строка для быстрого отображения сост.семьи
   	        	$fast_semya = '';
            	$fututar = false;
            	$vid_tarif = "";
            	$tkey = 0;

	            if ($mt) {
    	            if (count($kvits) > 1 and count($mt)>1)
        	            if (strtotime($mt[0]["ddate"]) > strtotime($kvits[0]["datekv"])) $tkey = 1;
            	        else  $tkey = 0;
                	else $tkey = 0;

                	$temp=(isset($mt[$tkey]["semya"]) and $mt[$tkey]["semya"]>0)?$mt[$tkey]["semya"]:'0';
	                $fast_semya = 'Семья: <b>'.$temp.' </b>';
   	            	if (isset($mt[$tkey]["semlg"]) and $mt[$tkey]["semlg"]!="") {
   	            		$fast_semya = $fast_semya.'<b>('.$mt[$tkey]["semlg"].')</b> ';
                	}
                	$fast_semya = $fast_semya.' чел. ';
                	$vid_tarif = $mt[$tkey]["vidtar"];
                	//. ' (с ' . date('d.m.Y', strtotime($mt[$tkey]["ddate"])) . ')';
					if ($tkey>0) {
						if (!($mt[0]["idt"]==$mt[$tkey]["idt"] and $mt[0]["idl"]==$mt[$tkey]["idl"])) {
                        	$fututar = true;
                        	$vid_tarif = ' ( до ' . date('d.m.Y', strtotime($mt[0]["ddate"])) . ') '. $mt[$tkey]["vidtar"] ;
                    	}
					}
                 	if ($mt[$tkey]["semlg"] != "") {
                        $vid_tarif = $vid_tarif."-льг.";
                	}
            	}
            	if (count($kvits)>0) {
                	// $vid_tarif = GetVidtar($kvits[$tkey]["kodtar"]); // текущий?
                	$fast_tarif = $fast_tarif . 'Тариф: <b>' . $vid_tarif;
         		    if ($mt) {
                	    if ($mt[$tkey]['mt'] == 1) $fast_tarif .= '-макс';
                	    if ($mt[$tkey]['mt'] == 2) $fast_tarif .= '-мин';
                	    if ($mt[$tkey]['mt'] == 3) $fast_tarif .= '-ост';
                	}
                	if (isset($mt[$tkey]["semlg"]) and $mt[$tkey]["semlg"] > 0) {
                    	if ($kvits[$tkey]["kodtar"] == 9) {
	                        $fast_tarif = $fast_tarif . '-льг.: ' . $kvits[$tkey]["tarif"] . '(' . $kvits[$tkey]["fulltar"] . ')';
    	                }
    	                else {
                        	$fast_tarif = $fast_tarif . '-льг.: ' . round($kvits[$tkey]["fulltar"] * ($mt[$tkey]["semya"] - $mt[$tkey]["semlg"] * 0.5) / $mt[$tkey]["semya"], 6);
                        	$fast_tarif = $fast_tarif . ' (' . $kvits[$tkey]["fulltar"] . ')';
                    	}
                	}
                	else {
                    	$fast_tarif .= ': ' . $kvits[$tkey]["tarif"] . '</b>';
                    	if ($kvits[$tkey]["tarif"] != $kvits[$tkey]["fulltar"]) {
                        	$fast_tarif = $fast_tarif . ' (' . $kvits[$tkey]["fulltar"] . ')';
                    	}
                	}
            	}
            	else {
                	$fast_tarif = "";
            	}
            	$fast_semya = $fast_semya;
            	$smarty->assign("fast_semya", $fast_semya);
            	$smarty->assign("fast_tarif".$uch, $fast_tarif);
            	// Если есть льготник, то подготовить список для кнопки "Печать Справки"
            	if (count($kvits) > 0) {
               		if ($kvits[0]["islg"] == 1) {
                    	$smarty->assign("print_spravka", 1);
                	}
            	}
            	// Вычисление для среднемесячного потребления за год
            	$s = 0;
            	$k = 0;
            	$tdate = strtotime("now") - 60 * 60 * 24 * 366;
            	for ($k = 0; $k < count($kvits); $k++) {
                	if ($tdate < strtotime($kvits[$k]["datekv"] . " 00:00:00")) {
                    	$s = $s + $kvits[$k]["kvhour"];
                	}
            	}
            	$s = round($s / 12);
            	$smarty->assign("sredn_year_potrebl".$uch, $s);

            	// Проверка на должника
            	$o = 0;
            	$temp = '';
            	$znak = count($obhod)>0 ? mb_strlen(trim($obhod[$o]["ob_pok"])):0;
            	for ($k = 0; $k < count($obhod); $k++) {
                	if ($obhod[$k]["ob_pok"] != "") {
                    	$o = $k;
                    	break;
                	}
            	}
            	if ((count($kvits) > 0) and (count($obhod) > 0)) {
                	$znstc = $schets[0][0]["maxn"] * 1;
                	$pokkv = str_pad($kvits[0]["pokaz"], $znstc, '9', STR_PAD_LEFT) * 1;
                	if ($obhod[$o]["ob_pok"] > 0 ) $pokob = $obhod[$o]["ob_pok"] * 1;
                	else $pokob = 0;
                	if ($pokob < 5000) {
                    	if     ($pokkv > 8000    and $pokob < 3000 and $znak < 5) $pokob = $pokob + 10000;
                    	elseif ($pokkv > 90000   and $pokob < 5000 and $znak < 6) $pokob = $pokob + 100000;
                    	elseif ($pokkv > 990000  and $pokob < 5000 and $znak < 7) $pokob = $pokob + 1000000;
                    	elseif ($pokkv > 9990000 and $pokob < 5000 and $znak < 8) $pokob = $pokob + 10000000;
                    	elseif ($pokkv > 99990000 and $pokob < 5000) $pokob = $pokob + 100000000;
                	} elseif ($pokkv < 5000) {
                    	if     ($pokob > 8000    and $pokkv < 3000 and $znak < 5) $pokkv = $pokkv + 10000;
                    	elseif ($pokob > 90000   and $pokkv < 5000 and $znak < 6) $pokkv = $pokkv + 100000;
                    	elseif ($pokob > 990000  and $pokkv < 5000 and $znak < 7) $pokkv = $pokkv + 1000000;
                    	elseif ($pokob > 9990000 and $pokkv < 5000 and $znak < 8) $pokkv = $pokkv + 10000000;
                    	elseif ($pokob > 99990000 and $pokkv < 5000) $pokkv = $pokkv + 100000000;
                	}
                	if ($pokkv < $pokob) {
                    	$a = $pokob - $pokkv;
                    	if ($a > 0) {
                        	$temp .= "($a кВт*ч) ";
                    	}
                	}
                	if (($kvits[0]["sumdolg"] * 1) > 0) {
                    	$a = round($kvits[0]["sumdolg"] * 1, 2);
                    	$temp .= "( $a руб.) ";
                	}
                	if (mb_strlen($temp) > 1) {
                    	$smarty->assign("dolznik".$uch, "Должник " . $temp);
                	}
            	}
        	}

			// Вычисления для кнопок предыдущий/следующий
			$res = array();
			$res = GetSearchSpis($page_count, $kn, $nom, $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num, $page_num);
			$kn_prev = 0;
			$nom_prev= 0;
			$kn_next = 0;
			$nom_next= 0;
			if ($res) {
				$rows = $res->fetchAll();
				for($k=0; $k<$res->rowCount(); $k++) {
		    		if (($rows[$k]['kn']==$ekn) and ($rows[$k]['nom']==$enom)) {
						if ($k > 0) {
							$kn_prev=$rows[$k-1]['kn'];
							$nom_prev=$rows[$k-1]['nom'];
						}
						if ($k < $res->rowCount()-1) {
							$kn_next=$rows[$k+1]['kn'];
							$nom_next=$rows[$k+1]['nom'];
						}
		    		}
		    	}
			}
			$smarty->assign("kn_prev", $kn_prev);
			$smarty->assign("nom_prev", $nom_prev);
			$smarty->assign("kn_next", $kn_next);
			$smarty->assign("nom_next", $nom_next);

			// Поиск кол-ва учетов по фамилии
			$sql = "select * from ".CURRENT_RES."_main where fam='".$abonent["fam"]."' and im='".$abonent["im"]."' and ot='".$abonent["ot"]."' and dlt=0";
        	$res = $dblink->query($sql);
			$smarty->assign("kol_uch_fam", $res->rowCount());

			// Поиск кол-ва учетов по адресу
			$sql = "select * from ".CURRENT_RES."_main where id_dom='".$abonent['id_dom']."' and kb='".$abonent["kb"]."' and dlt=0";
        	$res = $dblink->query($sql);
			$smarty->assign("kol_uch_adr", $res->rowCount());

			// Поиск Актов и услуг
			$sql = "SELECT *, date_format(datekv, '%d.%m.%Y') as datekv_shot, date_format(imeskv, '%d.%m.%Y') as imeskv_shot from ".CURRENT_RES."_kvitxvp where knkv='$ekn' and nomkv='$enom' ".DLTHIDE." order by datekv desc";
			$res = $dblink->query($sql);
			$rows = $res->fetchAll();
			$output_array = array();
			foreach ($rows as $row){
				$row['typekv'] = switch_typekv($row['typekv']);
				$row['imeskv_shot'] = nulldate($row['imeskv_shot']);
				$output_array[] = $row;
			}

			if ($res->rowCount()>0)  $smarty->assign('acts', $output_array);
			else  $smarty->assign('acts', false);
			$main='editab';
		}

		// ************************************
		// EDITAB_ERROR
		// ************************************
    	if ($act == 'editab_error') {
        	$smarty->display('editab_error.html');
        	$main='editab_error';
        	exit;
    	}
		$smarty->assign('main', $main);
		$smarty->assign('act', $act);
		$smarty->display('layout.html');
	}
	$smarty->assign('s_text', $s_text);
}
$dblink = null;
?>