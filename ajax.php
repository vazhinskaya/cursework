<?php
	// Модуль для обработки Ajax запросов !!!
	require_once ('include/core.php');
	RegisterGlobalNULL('kn', 'nom', 'main', 'id', 'act', 'uch', 'zn', 'tip_np', 'np', 'nu', 'knnom', 'kvtch', 'do', 'knnomuch');
	global $dblink;
	if ($uch=="") {$uch = 1;}
	$smarty->assign("act", $act);
	$smarty->assign("kn", $kn);
	$smarty->assign("uch", $uch);
	$smarty->assign("nom", $nom);
    $error_message="";
    header('Content-Type: text/html; charset=utf-8', true);
	switch ($act){
        //**********************************************************************************
        // Поиск счетчиков в базе suee
        case "findsc_suee":
            $dblink_suee = dbConnect_suee();
            $res = $dblink_suee->query("select id, su_code, r.name from su
                    left join register r on r.code=su.marka
                    where res_id = 11 and o_id is null and
                          location_id in (1, 2) and
                          status_id in (4,5,7);");
            $rows = $res->fetchAll();
//            for($k=0; $k < $res->rowCount(); $k++) {            }

            if ($res->rowCount() > 0) { $error_message=print_r($rows); }
            if ($error_message != "") {
                echo '<span style="color:#FF0000">'.$error_message.'</span> <input type="text" name="findsc22" id="findsc22" value="Сохранить" onclick="">';
            } else {
                echo '<span style="color:#009900">Все ок!</span>&nbsp;&nbsp;&nbsp; <input type="button" name="ustch2" id="ustch2" value="Сохранить" onclick="">';
            }
        break;

        //**********************************************************************************
        // Проверка на "ворованный" счетчик
        case "check_stolen":
            $stolen = CheckStolenSc($zn);
            if ($stolen!="") { $error_message="Этот счетчик был украден у абонента ".$stolen; }
            if ($error_message!="") {
                echo '<span style="color:#FF0000">'.$error_message.'</span> <input type="button" name="ustch2" id="ustch2" value="Сохранить" onclick="ustsc2()">';
            } else {
                echo '<span style="color:#009900">Все ок!</span>&nbsp;&nbsp;&nbsp; <input type="button" name="ustch2" id="ustch2" value="Сохранить" onclick="ustsc2()">';
            }
        break;

    	//**********************************************************************************
        // Загрузка данных при изменении дома во время редактирования карточки абонента
		case "after_dom_change":
            $sql = "SELECT tip_ps, k.n_ps, k.tip_lep, k.n_lep, d.opora,
                        ".CURRENT_RES."_region.region, ".CURRENT_RES."_street.street,
                         _tip_postr.postr, _res.res, ".CURRENT_RES."_tip_prinadl.tipps
                    FROM ".CURRENT_RES."_region, ".CURRENT_RES."_street, _tip_postr, _res,
                         ".CURRENT_RES."_tip_prinadl,
                         ".CURRENT_RES."_dom d LEFT JOIN ".CURRENT_RES."_ktp k ON d.id_vlkl = k.id
                    WHERE d.id_region= ".CURRENT_RES."_region.id and
                         d.id_ul     = ".CURRENT_RES."_street.id and
                         d.id_postr  = _tip_postr.id and
                         d.id_res    = _res.id and
                         d.id_prinadl= ".CURRENT_RES."_tip_prinadl.id and
                         d.id = ".$id;
            $res = $dblink->query($sql);
            $rows = $res->fetchAll();
			echo '<fieldset style="border:1px solid #c0d4db">
            <legend style="color:#006600;font-weight:bold;background:#FFFFFF">Данные дома</legend>
              <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="pstable">
                <tr>
                    <td width="50%">
                    <div style="float:left">РЭС:</div>
                        <div style="float:right">
                            <a title="Редактировать данные дома" onclick="editdom()" href="#"><img src="'.SERVER_ROOT.'/images/edit.png"></a>
                        </div>
                    </td>
                    <td>
                        <input type="text" size="20" disabled value="'.$rows[0]["res"].'">
                    </td>
                </tr>
                <tr>
                    <td>Регион</td>
                    <td>
                        <input type="text" size="20" disabled value="'.$rows[0]["region"].'">
                    </td>
                </tr>
                <tr>
                    <td>ПС:</td>
                    <td>
                        <input type="text" class="mtext_sh" disabled value="'.$rows[0]["tip_ps"]."-".$rows[0]["n_ps"].'">
                    </td>
                </tr>
                <tr>
                    <td>ВЛ(КЛ):</td>
                    <td><input type="text" class="mtext_sh" disabled value="'.$rows[0]["tip_lep"]."-".$rows[0]["n_lep"].'"></td>
                </tr>
                <tr>
                    <td>Опора:</td>
                    <td><input type="text" class="mtext_sh" disabled value="'.$rows[0]["opora"].'"></td>
                </tr>
                <tr>
                    <td>Принадлежность</td>
                    <td>
                        <input type="text" size="20" disabled value="'.$rows[0]["tipps"].'">
                    </td>
                </tr>
                <tr>
                    <td>Тип постройки</td>
                    <td>
                        <input type="text" size="20" disabled value="'.$rows[0]["postr"].'">
                    </td>
                </tr>
              </table>
            </fieldset>';
    	break;

        //**********************************************************************************
        // Загрузка Населенных Пунктов
		case "np_reload":
			$nasp = GetNasp($tip_np);
			echo '<select name="adr_np" id="adr_np" onchange="reloader(\'street_reload\', \'np\', this.value)" class="psi1">';
			echo '<option value="" SELECTED></option>';
			foreach ($nasp as $key=>$value) {
				echo '<option value="'.$value["id"].'">'.$value["np"].'</option>';
			}
			echo '</select>';
    	break;

		//**********************************************************************************
        // Загрузка Улиц
		case "street_reload":
			$nasp=GetStreet($np);
			echo '<select name="adr_nu" id="adr_nu" onchange="reloader(\'dom_reload\', \'nu\', this.value)" class="psi1">';
			echo '<option value=""></option>';
			foreach ($nasp as $key=>$value) {
				echo '<option value="'.$value["id"].'">'.$value["tip_street"].' '.$value["street"].'</option>';
			}
			echo '</select>';
		break;

		//**********************************************************************************
        // Загрузка Улиц
		case "dom_reload":
			$nasp=GetDom($nu);
			echo '<select id="adr_dom" name="adr_dom" class="psi1">
				<option value=""></option>';
			foreach ($nasp as $key=>$value) {
				if (trim($value["domadd"])=="") {echo '<option value="'.$value["id"].'">'.$value["dom"].'</option>';}
				else{ echo '<option value="'.$value["id"].'">'.$value["dom"].'/'.$value["domadd"].'</option>';}
			}
			echo '</select>';
		break;

		//**********************************************************************************
        // Калькулятор
		case "kalkulator":
			$itogo = GetCalculator($knnom, $kvtch, $uch);
			$summ = 0;
			$a='';
			for ($k=0;$k<count($itogo);$k++) {
				$a = $a.'('.$itogo[$k]['kvt'].' * '.$itogo[$k]['tar'].') ';
				$summ = $summ + $itogo[$k]['summ'];
			}
			echo '<b>'.$summ.' руб.</b> '.$a;
		break;

        //**********************************************************************************
        case "update_obhod":
            $ls = $knnomuch;
            $kn = mb_substr($ls,0,4);
            $nom = mb_substr($ls,4,3);

            if ($do=="update") {
                $sql = 'select * from _obhod_mobile where id='.$id;
                $res = $dblink->query($sql);
                $rows = $res->fetchAll();

                $sql = "select id from ".CURRENT_RES."_main where kn='".$kn."' and nom='".$nom."' and dlt<1";
                $res1 = $dblink->query($sql);
                $rows1 = $res1->fetchAll();
                $last_id = $rows1[0]["id"];

                $sql = $rows[0]["main_sql"];
                $res1 = $dblink->query($sql);
                if ($res1->RowCount() > 0) {
                    $sql = "update ".CURRENT_RES."_main set dlt=1 where id='".$last_id."'";
                    $res1 = $dblink->query($sql);
                }
                else {
                    echo 'Ошибка!';
                }
                $sql = $rows[0]["obhod_sql"];
                $res1 = $dblink->query($sql);

                $sql = 'update _obhod_mobile set needupd=0 where id='.$id;
                $res = $dblink->query($sql);

                echo 'Применено '.$knnomuch;
            }

            if ($do=="delete") {
                $sql = 'update _obhod_mobile set needupd=-1 where id='.$id;
                $res = $dblink->query($sql);
                echo 'Удалено '.$knnomuch;
            }
        break;
    }
?>