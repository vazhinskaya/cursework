<?php
require_once('include/core.php');
require_once('include/lib_general.php');
RegisterGlobalNULL('bcancel','kn', 'nom', 'main', 'id', 'act', 'uch', 'op', 'meskv', 'datekv', 'askue');

	if ($uch=="") {$uch="1";}
	if ($bcancel=="Отменить") {
		$act="view";
		foreach($op as $key=>$value) {
			if ($key=="knkv") {$kn = $value;}
			if ($key=="nomkv") {$nom = $value;}
			if ($key=="uchkv") {$uch = $value;}
		}
	}

	//*********************************************************//
	// Delete
	//*********************************************************//
	if ($act=="delete") {
		// Архивирование оплат
		$tbn = GetUserTbnCookie();
        $edittime = date("Y.m.d H:i:s");
	    $sql = "update ".CURRENT_RES."_kvit set dlt=1, tbn=".$tbn.", edittime='".$edittime."', reason='Удаление' where id=".$id;
		$res = $dblink->query($sql);
		$act="view";
	}

	//*********************************************************//
	// Move
	//*********************************************************//
	if ($act=="moov") { // Перенос в неясные
		$tbn= GetUserTbnCookie();
		$sql = "select a.* from ".CURRENT_RES."_kvit a where a.id='".$id."'";
		$res = $dblink->query($sql);
		$rows = $res->fetchAll();
		$data = $rows[0];
		$npachka = $data["npachkv"];
		foreach($rows[0] as $key=>$value) {
			if (($key!='edittime') and ($key!='tbn') and ($key!='reason') and ($key!='id')) {
				$sql_params[] = $key;
				if (trim($value)=="") {$value=null;}
				if (($key=='imeskv') or ($key=='datekv'))
					{$sql_values[] = "'".dodate($value)."'";}
				else
					{$sql_values[] = "'".$value."'";}
			}
		}
		$edittime=date("Y.m.d H:i:s");
		$reason = 'Перенос из ясных';
		$sql_params = implode(" , ", $sql_params).", edittime,    tbn,    reason";
		$sql_values = implode(" , ", $sql_values).",'$edittime', '$tbn', '$reason'";
		$sql = "insert into ".CURRENT_RES."_xkvit(".$sql_params.") VALUES(".$sql_values.")";
		$res = $dblink->query($sql);

		$reason = 'Перенос в неясные';
	    $sql = "update ".CURRENT_RES."_kvit set dlt=1, tbn=".$tbn.", edittime='".$edittime."', reason='".$reason."' where id=".$id;
		$res = $dblink->query($sql);

		$act="view";
	}

	//*********************************************************//
	// Edit
	//*********************************************************//
	if (($act=="edit") or ($act=="edit_nach")) {
	    $sql = "select a.* from ".CURRENT_RES."_kvit a where a.id='".$id."' ";
		$res = $dblink->query($sql);
		$rows = $res->fetchAll();
		$rows[0]["datekv"] = dodate(mb_substr($rows[0]["datekv"],0,10));
        $rows[0]["imeskv"] = date("m.Y", strtotime($rows[0]["imeskv"])); // Для редактирования начала расчета
		$smarty->assign("kvitedit", $rows[0]);
		$today = getdate(strtotime($rows[0]["datekv"]));
		$smarty->assign("yearedit",$today['year']);
		$smarty->assign("monthedit",$today['mon']);
		$smarty->assign("dayedit",$today['mday']);
		$smarty->assign("yearnow",date("Y"));
	}

	//*********************************************************//
	// Save
	//*********************************************************//
	if (($act=="edit_save") or ($act=="add_save") or ($act=="add_nach_save") or ($act=="edit_nach_save")) {// Сохранение после редактирования
      dbBeginTransaction();
		$tbn= GetUserTbnCookie();
		$edittime=date("Y.m.d H:i:s");

		if (($act=="edit_save") or ($act=="edit_nach_save")) { // Архивирование тарифа
		    $sql = "update ".CURRENT_RES."_kvit set dlt=1, tbn='$tbn', edittime='$edittime', reason='Редактирование' where id='".$id."'";
			$res = $dblink->query($sql);
		}
		foreach($op as $key=>$value) {
			$sql_params[] = $key;
			if (trim($value) == "") $value=null;
			$sql_values[] = "'".$value."'";
			if ($key == "knkv")  $kn  = $value;
			if ($key == "nomkv") $nom = $value;
			if ($key == "uchkv") $uch = $value;
		}
		$imeskv = preobrDate("01.".$meskv);
		$datekv = preobrDate($datekv);
		$sql_params = implode(" , ", $sql_params).", meskv, datekv, dlt, edittime, tbn, imeskv, reason, details, is_card ";
  		$sql_values = implode(" , ", $sql_values).", '$meskv', '$datekv', 0, '$edittime', '$tbn', '$imeskv', '', '', 0";
  		$sql = "insert into ".CURRENT_RES."_kvit (".$sql_params.") VALUES (".$sql_values.")";
    	$res = $dblink->query($sql);
		$act = "view";
      dbEndTransaction();
	}

	//*********************************************************//
	// View
	//*********************************************************//
	if ($act=="view") {
		// Этот элемент для возврата после отмены
		if ($op)
		foreach($op as $key=>$value) {
			if ($key=="knkv")  {$kn = $value;}
			if ($key=="nomkv") {$nom = $value;}
			if ($key=="uchkv") {$uch = $value;}
		}

		$oplata = GetKvit($kn, $nom, $uch);
		for ($k=0; $k<count($oplata); $k++) {
	    	$oplata[$k]["edittime"] = date("d.m.Y H:i:s", strtotime($oplata[$k]["edittime"]));
            if ($oplata[$k]["oshkv"] < 8) {
                if ($oplata[$k]["npachkv"] <> NULL) {
			        $sql = "select dat_form, kassa, ppor, tbn from ".CURRENT_RES."_pachka where npachka=".$oplata[$k]['npachkv'];
					$res = $dblink->query($sql);
					$rows = $res->fetchAll();
	    	        if ($res->rowCount()>0) {
	            	    $rows[0]["dat_form"] = dodate($rows[0]["dat_form"]);
				        $oplata[$k]["pachka"] = $rows[0];
                    }
                }
			    for ($i=0; $i<count($oplata[$k]["stroka"]); $i++) {
			    	if ($oplata[$k]["stroka"]!="") {
				    	$a = explode("|",$oplata[$k]["stroka"][$i]);
					    $oplata[$k]["expstroka"][$i] = $a;
				    }
			    }
            }
		}
		$smarty->assign("kvit_spisok_edit", $oplata);

		$sql = "select b.* from ".CURRENT_RES."_main b, ".CURRENT_RES."_dom c
				where b.kn = '".$kn."' and b.nom = '".$nom."' and b.id_dom=c.id and c.askue=1 and b.dlt=0 order by b.edittime desc limit 1";
		$res1 = $dblink->query($sql);
		if ($res1->rowCount()>0) { $askue = "1"; }

        if ($askue == '1') {
            $kvit_array = GetKvit($kn,$nom,$uch,true);
            if (count($kvit_array)>0) {
	            $last_kvit = $kvit_array[0];
	            $dolg_rub = 0;
	            $askuekvit = '';
	            $date_obh = date("Y.m.01");
	            $DAYS_PENYA=0;
	            $date_obh_pred = dateYMD('-1 month',strtotime($date_obh));

	            $temp2 = strtotime('-1 day',strtotime('01.'.date('m.Y')));
	            $pokaz = round($last_kvit['pokaz']);
	            $len = mb_strlen($last_kvit['pokaz']);
	            $tarif = floatval($last_kvit['tarif']);
	            $tarif = sprintf("%.4f", $tarif);
	            $fulltar = floatval($last_kvit['fulltar']);
	            $fulltar =  sprintf("%.4f", $fulltar);
	            if ($fulltar == $tarif) $fulltar = "0";

	            $pok_obh = dbOne("Select ob_pok from ".CURRENT_RES."_obhod where knobh='".$kn."' and nomobh='".$nom."' and uchobh='".$uch."' and dateobh='$date_obh' and ob_pok>'' ".DLTHIDE." order by dateobh desc, id desc limit 1");
	            if ($pok_obh > 0) {
					if ((intval($pok_obh) - intval($pokaz)) < -8000){
						$dolg = intval('1'.$pok_obh) - intval(($pokaz));   // Переход через ноль
                    }
                    else {
			   			$dolg = intval($pok_obh) - intval(($pokaz));
					}
            		if ($dolg < 8000){
	                    // текущий счет
						$temp5 = GetCalculator($kn.$nom, $dolg, $uch, $date_obh_pred);
						$dolg_rub = round($temp5[0]["sum"], 2);
	                }
	            }
		        if ($dolg_rub>0) {
		            $askuekvit = "Согласно снятых показаний по системе <b>АСКУЭ</b> на <u>$date_obh</u> г. выставлен счет за <u>".date('m.Y', $temp2)."</u> г. с показания <u><b>".ForZn($pokaz, $len)." по ".ForZn($pok_obh, $len)."</b></u> за <b>".intval($dolg)." кВтч</b> на сумму <u><b>".floatval($dolg_rub)."</b></u> руб.";
		        }
	            if ($dolg_rub<0) {
	                $askuekvit = "По системе <b>АСКУЭ</b> на <u>$date_obh</u> г. снято показание <u><b>".ForZn($pok_obh, $len)."</b></u>. Оплачено показание <u><b>".ForZn($pokaz, $len)."</b></u>. Переплата за <b>".intval(-$dolg)." кВтч</b> на сумму <u><b>".floatval(-$dolg_rub)."</b></u> руб.";
	            }
	            $smarty->assign("dolg_rub", $dolg_rub);
			    $smarty->assign("askuekvit", $askuekvit);
		    }
        }

		$kvitedit = array();
		$kvitedit["date_obh"] = date("01.m.Y");
		$kvitedit["meskv"] = date("m.Y");
		$kvitedit["pokkv"] = "0";
		$kvitedit["sumkv"] = "0";
		$kvitedit["penkv"] = "0";
		$smarty->assign("kvitedit", $kvitedit);
	}
	$smarty->assign("kn",$kn);
	$smarty->assign("nom",$nom);
	$smarty->assign("act",$act);
	$smarty->assign("uch", $uch);
	$smarty->display("oplata.html");
?>