<?php
$FTarhist = array();
$FCentar = array();
//***************************************************************
// Получить среднесуточное										*
//***************************************************************
function GetWsr($kn, $nom, $uch, $pok, $ddate) {
	global $dblink;
    $sql = "select * from ".CURRENT_RES."_obhod
    		where knobh='$kn' and nomobh='$nom' and uchobh='$uch' and dlt=0
    		      and ob_pok<>'' and dateobh<'$ddate'
    		order by dateobh desc, id desc limit 1";
	$res = $dblink->query($sql);
	$rows = $res->fetchAll();
    $result = 0;
	if ($res->rowCount()>0) {
		$razn = $pok - $rows[0]["ob_pok"];
		// проверка на переход через 0
		if ($razn<0) {
			// получить значность счетчика
    		$sql = "select b.maxn
    				from ".CURRENT_RES."_mainsc a, _tip_sc b
    				where a.kn='".$kn."' and a.nom='".$nom."' and a.ts=b.id and a.dlt=0 limit 1";
			$res1 = $dblink->query($sql);
			$rows1 = $res1->fetchAll();
			$razn = pow(10,$rows1[0]["maxn"]) + $razn;
		}
		$date_elements  = explode(".",$ddate);
		$ldate_unix = mktime(0,0,0,$date_elements[1],$date_elements[0],$date_elements[2]);
		$date_elements1  = explode("-",mb_substr($rows[0]["dateobh"],0,10));
		$sdate_unix = mktime(0,0,0,$date_elements1[1],$date_elements1[2],$date_elements1[0]);
		if (($ldate_unix-$sdate_unix)!=0) $result = round($razn/(($ldate_unix-$sdate_unix)/60/60/24),2);
		else $result = 0;
	}
	if ($result > 1000) $result = 0;
	return $result;
}

//***********************************************************
// Получить тариф       									*
//***********************************************************
function GetTarif($kn, $nom, $uch) {
	global $dblink;
    $sql = "select a.*, b.vidtar, b.vidmt, p.fio
            from ".CURRENT_RES."_tarhist_sem a
            LEFT JOIN ".CURRENT_RES."_personal p ON a.tbn=p.tbn, _vidtar b
            where a.kn='".$kn."' and a.nom='".$nom."' and a.uch='".$uch."' and a.idt=b.id ".DLTHIDE."
            order by a.ddate desc, a.id desc";
	$res = $dblink->query($sql);
	return($res->fetchAll());
}

//*******************************************************************
function GetSchet($kn, $nom, $id="") {    // Получение массива счетчиков абонента
 	global $dblink;
	if ($id=="") $add_id = "";
	else         $add_id = "and a.id='$id'";
	$schet = array();

	$sql = "select a.*, b.*, a.id as scid, a.maxn as maxn, p.fio, r1.res_value, r2.res_value as res_value2, t.tipps
            from ".CURRENT_RES."_mainsc a
        	LEFT JOIN ".CURRENT_RES."_personal p ON a.tbn=p.tbn
        	LEFT JOIN _gen_sprav r1 ON a.reas1=r1.id
        	LEFT JOIN _gen_sprav r2 ON a.reas2=r2.id
        	LEFT JOIN _tip_sc b ON a.ts=b.id
			LEFT JOIN ".CURRENT_RES."_tip_prinadl t ON a.ps=t.id
            where a.kn='".$kn."' and a.nom='".$nom."' ".$add_id." and a.dlt<>1
    		order by a.dateust desc, a.id desc";
	$res = $dblink->query($sql);
	$rows = $res->fetchAll(PDO::FETCH_ASSOC);
	for ($n=0; $n<$res->rowCount(); $n++) {
		$rows[$n]["pokusc"] = $rows[$n]["pokusc1"];
		$rows[$n]["pokssc"] = $rows[$n]["pokssc1"];
		$schet[0][] = $rows[$n];
		if ($rows[$n]["pokusc2"]!="") {
			$rows[$n]["pokusc"] = $rows[$n]["pokusc2"];
			$rows[$n]["pokssc"] = $rows[$n]["pokssc2"];
			$schet[1][] = $rows[$n];
		}
		if ($rows[$n]["pokusc3"]!="") {
			$rows[$n]["pokusc"] = $rows[$n]["pokusc3"];
			$rows[$n]["pokssc"] = $rows[$n]["pokssc3"];
			$schet[2][] = $rows[$n];
		}
	}
	if (!$schet) {
         $schet[]="нет истории";  // На тот случай если новый абонент (без счетчика)
    }
	return($schet);
}

/******************************************************************/
/*    Получить список обходов                                     */
/******************************************************************/
function GetObhod($kn, $nom, $uchsc="1") {
	global $dblink;
    $sql = "select ob.*, pers.fio
            from ".CURRENT_RES."_obhod as ob
            LEFT JOIN ".CURRENT_RES."_personal as pers on ob.tbn = pers.tbn
            where ob.knobh='".$kn."' and ob.nomobh='".$nom."' and ob.uchobh='".$uchsc."'".DLTHIDE."
            order by ob.dateobh desc, ob.id desc";
	$res = $dblink->query($sql);
	return($res->fetchAll());
}

/********************************************************************/
/*    Получить список квитанций  									*/
/********************************************************************/
function GetKvit($kn, $nom, $uch="1", $last=false) {
	global $dblink;
	global $FCentar;
	global $FTarhist;

	$pokkv_old = 0;
	$pokaz_old = 0;
	$norma     = 0;
	$znak_2    = 0;
	$lstroka   = ""; // Строка, в которую будет сохраняться расшифровка расчетов льготника
	$islg = false;

	$FCentar = FAST_Centar();
	$FTarhist = FAST_Tarif($kn, $nom, $uch);

	if ($last) {
	    $sql = "select id, sumkv, pokkv, penkv, meskv, imeskv, npachkv, oshkv, datekv
            	from ".CURRENT_RES."_kvit
            	where knkv='".$kn."' and nomkv='".$nom."' and uchkv='".$uch."'".DLTHIDE."
            	order by imeskv desc, YEAR(datekv) desc, MONTH(datekv) desc, DAY(datekv) desc, id desc";
	}
	else {
	    $sql = "select kv.*, pers.fio
	    		from ".CURRENT_RES."_kvit as kv
	            LEFT JOIN ".CURRENT_RES."_personal as pers on kv.tbn=pers.tbn
	            where kv.knkv='".$kn."' and kv.nomkv='".$nom."' and kv.uchkv='".$uch."'".DLTHIDE."
	            order by kv.imeskv desc, YEAR(kv.datekv) desc, MONTH(kv.datekv) desc, DAY(kv.datekv) desc, kv.id desc";
	}
	$res = $dblink->query($sql);
	$rows = $res->fetchAll();
	if ($res->rowCount()>0) {
		$numbel = $res->rowCount();
		$rows[$numbel-1]["realsumkv"] = "";
		if ($last) {
			$arfirst = $rows[0];
			$numbel = array_unshift($rows, $arfirst);
        	$temp = strtotime('-1 day',strtotime('01.'.date('m.Y')));
		    $temp1_ = date('Y-m-d',$temp);
		    $rows[0]["meskv"] = date("m.Y",$temp);
			$rows[0]["imeskv"] = date('Y-m-d',$temp);
		    if (strtotime($temp1_) > strtotime($rows[0]["datekv"])) {
				$rows[0]["datekv"] = date('Y-m-d',$temp);
		    }
			$rows[0]["sumkv"] = "0";
			$rows[0]["penkv"] = "0";
			$rows[0]["oshkv"] = "1";
		}
		$scmas = GetSc($kn, $nom, $uch);
		$pokaz = 0;

        // ************************************************************************ //
		//     Расчет квитанций >>                                                  //
       	// ************************************************************************ //
		if ($numbel>0) {
			$pokaz = $rows[$numbel-1]["pokkv"];
		}
		// По квитанциям с конца
		$num_last_tarif = count($FTarhist);

		// Основной цикл - бегаем по квитанциям
	    for ($k=$numbel-1; $k>=0; $k--) {
	        $maxnorma = 0;
			$pred_day=1;
		 	// $rows - содержит квитанции
		  	// $k - счетчик квитанций с конца
		  	// $numbel - кол-во квитанций

			$skvhour = 0;
			$star    = 0;
			$spokaz  = 0;
	    	$kol_days_norma = 0;
		    $stroka = array();

			// Проверяем следующую квитанцию, может она = начало расчета - тогда ее рассчитать сразу без суммирования
		    if (($k==0) or ($k==$numbel-1) or ($clear_imes!=$rows[$k-1]["imeskv"]) or ($rows[$k]["oshkv"]=="8") or ($rows[$k]["oshkv"]=="9") or ($rows[$k-1]["oshkv"]=="8") or ($rows[$k-1]["oshkv"]=="9")) { // Это проверка на двойную проплату за месяц

				$oplata=0;
				$alltarif = Tarif($kn, $nom, $uch, $rows[$k]["imeskv"]);
				//print_r($alltarif);
				if ($alltarif["idl"]) {
					$islg = true;
				}
				else {
					$islg = false;
				}
				$kodtar = $alltarif["kod"]; // Код тарифа
				// Для учета значности счетчика по началу расчета
				if ($rows[$k]["oshkv"] >= "8") {
					$znak = mb_strlen($rows[$k]["pokkv"]);
					$znak_2 = $znak;
				}
				else {
					if ($znak_2 == 0) {
						$znak = GetZnak($scmas, $rows[$k]["datekv"]);
					}
					else {
						$znak = $znak_2;
						$znak_2 = 0;
					}
				}
				$sem = $alltarif["tarhist"];
	        	$fulltar = $alltarif["cena1"];
				if ($sem) {
					if (count($sem) <= 0) {
						$q=0; // На тот случай, если оплатили на дату раньше, чем завели карточку
					}
					$rows[$k]["mt"] = $sem["mt"];
	            	switch ($sem["mt"]) {
	                case "1":
	                    $fulltar = $alltarif["cena1"];
	                    break;
	                case "2":
	                    $fulltar = $alltarif["cena2"];
	                    break;
					case "3":
						$fulltar = $alltarif["cena3"];
						break;
					case "4":
						$fulltar = $alltarif["cena4"];
						break;
	                default:
	                    $fulltar = $alltarif["cena1"]; // Полная стоимость тарифа
	                    break;
	        	    }
					$semya = $sem["semya"];
					$semlg = $sem["semlg"];
					if ($semya==0) {
						$semya=1;
					}
				}
				else {
					$semya = 1;
					$semlg = 0;
				}
				$tar = round($fulltar * ($semya-SemLg($alltarif["idl"]))/$semya, 6);
				if (($rows[$k]["oshkv"]=="8") or (($rows[$k]["oshkv"]=="9"))) {
	                $pokaz=$rows[$k]["pokkv"];
					// Если была замена счетчика - начинаем отсчет с первых показаний
		    	    // вставка Пинск
			        $znak = mb_strlen($rows[$k]["pokkv"]);
			        $znak_2 = $znak;
			    }

                $lstroka = $dates[$n] . "|";
                $lstroka = $lstroka . $fulltar . "|" . $tar . "|";
                $lstroka = $lstroka . round($days_tar[$n] * $days_mes) . "|";
                // Если есть льготник
                if ($tar != $fulltar) {
                    if ($alltarif["norma"] > 0) { // Если есть норма
                        $kol_days_norma = $kol_days_norma + $days_tar[$n];
                        // Узнаем количество учетов для нормы
                        if (isset($sem["mt"])) {
                            if ($sem["mt"] > 0) {
                                $uch_count = $sem["mt"];
                            } else {
                                $uch_count = 1;
                            }
                        }
                        else {
                            $uch_count = 1;
                        }
                        $alltarif["norma"] = $alltarif["norma"] / $uch_count;
                        $lstroka = $lstroka . round($alltarif["norma"],2) . "|";
                        $normsum = $alltarif["norma"] * $semya * $tar;

                        // Оплата по норме
                        if ($normsum >= ($rows[$k]["sumkv"] - $rows[$k]["penkv"])) { // Если вписываемся в норму
                            if ($tar != 0) {
                                $kvhour = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"]) / $tar);
                             }
                            else {
                                $kvhour = 0;
                            }
                            $aa = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"]) * $days_tar[$n],6);
                            $lstroka = $lstroka . $aa . "|";
                            if ($tar != 0) {
                                $aa = round($aa / $tar,6);
                            }
                            $lstroka = $lstroka . $aa . "|";
                        }
                        else { // Если выше нормы
                            if ($tar != 0) {
                                $kvhour = round($normsum / $tar);
                            }
                            else {
                                $kvhour = 0;
                            }
                            $lstroka = $lstroka . round($normsum * $days_tar[$n],6) . "|";
                            if ($tar != 0) {
                                $lstroka = $lstroka . round($normsum * $days_tar[$n] / $tar) . "|";
                            }
                            else {
                                $lstroka = $lstroka . "|";
                            }
                            $kvhour = $kvhour + round(($rows[$k]["sumkv"] - $rows[$k]["penkv"] - $normsum) / $fulltar);
                            $aa = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"] - $normsum) * $days_tar[$n],2);
                            $lstroka = $lstroka . $aa . "|";
                            $aa = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"] - $normsum) * $days_tar[$n] / $fulltar,2);
                            $lstroka = $lstroka . $aa . "|";
                        }
                        $norma = $alltarif["norma"];
                        $maxnorma = $norma;
                    }
                   	else { // Если нормы нет
                       	$norma = 0;
                       	$normsum = 0;
                       	if ($tar != 0) {
                         	$kvhour = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"]) / $tar);
	                    }
    	                else {
        	                $kvhour = 0;
            	        }
                    }
	            }
                else { // Если нет льготника
                   	if ($fulltar != 0) { // На тот случай если тарифа еще нет или он 0
                       	$kvhour = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"]) / $fulltar);
                   	}
                   	else {
                       	$kvhour = 0;
                   	}
                   	$tar = $fulltar;
                   	$norma = 0;
                   	if ($rows[$k]["oshkv"] < 7) { // Если не начало расчета, то формируем строку
                       	$aa = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"]) * $days_tar[$n],2);
                       	$lstroka = $lstroka . "|||" . $aa . "|";
                       	$aa = round(($rows[$k]["sumkv"] - $rows[$k]["penkv"]) * $days_tar[$n] / $tar,2);
                       	$lstroka = $lstroka . $aa . "|";
                   	}
                }
                if ($k != $numbel - 1) { // Если первая квитанция - то считать ее как начало расчета
                   	$skvhour = $skvhour + $kvhour;
                   	$star = $star + $tar;
                }
                $stroka[] = $lstroka;
		   	   	// Эта строка для вывода реальной суммы квитанции, т.к. другая нужна для расчетов и она суммируется
    	  		if ($k != 0) {
       				$rows[$k-1]["realsumkv"]= $rows[$k-1]["sumkv"];
	       		}
    	   	}
			else {
				// Суммируем с предыдущей, если две проплаты за месяц
		    	$rows[$k-1]["realsumkv"] = $rows[$k-1]["sumkv"];
				$rows[$k-1]["sumkv"] = $rows[$k-1]["sumkv"]+$rows[$k]["sumkv"];
				$rows[$k-1]["penkv"] = $rows[$k-1]["penkv"]+$rows[$k]["penkv"];
			}
			$rows[$k]["islg"] = $islg; // Есть ли льгота в этой квитанции
			$skvhour = round($skvhour);
			$pokaz = $pokaz + $skvhour;
			if ($pokaz>=pow(10, $znak)) {
				$pokaz=$pokaz-pow(10, $znak);  // Переход через ноль
			}
			if (mb_strlen($pokaz)>$znak) {
				$pokaz = $pokaz % pow(10, $znak); // Отсекаем старшие разряды
			}
			$rows[$k]["imeskv"] = dodate($rows[$k]["imeskv"],1);
			$rows[$k]["datekv"] = dodate($rows[$k]["datekv"]);
			$rows[$k]["kvhour"] =$skvhour;
			$rows[$k]["tarif"] = round($star, 6);
			$rows[$k]["pokaz"]= ForZn(round($pokaz), $znak);
			$rows[$k]["norma"] = $maxnorma*$semya;
       		$rows[$k]["norma_lg"] = $norma*$semlg;
			$rows[$k]["fulltar"]=$fulltar;
			$rows[$k]["kodtar"] = $kodtar;
			$rows[$k]["semlg"] = $semlg;
			$rows[$k]["semya"] = $semya;
	        $rows[$k]["koef_norma"] = $kol_days_norma;
	        if (trim($rows[$k]["pokkv"]) == '') {
				$pokkv_razn = -$pokkv_old;
				$pokkv_old = 0;
   		    }
   	    	else {
				$pokkv_razn = $rows[$k]["pokkv"] - $pokkv_old;
				$pokkv_old = $rows[$k]["pokkv"];
        	}
			$pokaz_razn = $rows[$k]["pokaz"] - $pokaz_old;
			$pokaz_old = $rows[$k]["pokaz"];
			if ($pokkv_razn!=$pokaz_razn) {
				$rows[$k]["errkvit"] = 1;
			}
			else {
				$rows[$k]["errkvit"] = 0;
			}
			$rows[$k]["stroka"] = $stroka;
		}
		$rows[0]["norma"]=$maxnorma*$semya;
		$rows[0]["fulltar"]=$fulltar;
	}
	return($rows);
}

//*********************************************************************************
//*    Расчет для одной квитанции                       						  *
//*********************************************************************************
function GetKvitOne($kn, $nom, $uch="1", $ddate, $money)
{
	$norma=0;
	global $dblink;
	global $FCentar;
	global $FTarhist;

	$FCentar = FAST_Centar();
	$FTarhist = FAST_Tarif($kn, $nom, $uch);

	$pokaz=0;
	$days_tar = array(); // Массив из количества дней в месяце, что проработал тариф до изменения на другой
	$dates = array();    // Массив дат изменения тарифа за месяц
	$days_mes = date('t', strtotime($ddate));
	$pred_day=1;
	$dates[0] = $pred_day.".".date("m.Y", strtotime($ddate));
	$days_tar[count($days_tar)] = ($days_mes-$pred_day+1)/$days_mes;
	$skvhour = 0;
	$star = 0;
	$spokaz = 0;
	$n = 0;
	$oplata=0;
	$alltarif = Tarif($kn, $nom, $uch, $dates[0]);
	$fulltar = $alltarif["cena"]; // Полная стоимость тарифа
	$kodtar = $alltarif["kod"];   // Код тарифа
	$sql = "select id, idt, idl, mt, ddate, semya, semlg
            from ".CURRENT_RES."_tarhist_sem
            where kn='".$kn."' and nom='".$nom."' and uch='".$uch."' and ddate< '".$ddate."' and dlt=0
            order by ddate desc, id desc limit 1";
	$sem = $dblink->query($sql); // Семья
	$rowsem = $sem->fetchAll();
	if ($sem->rowCount()>0)
	{
        switch ($rowsem[0]["mt"])
        {
            case "0":
                $fulltar = $alltarif["cena"]; // Полная стоимость тарифа
            break;
            case "1":
                $fulltar = $alltarif["cenamax"];
            break;
            case "2":
                $fulltar = $alltarif["cenamin"];
            break;
        }

		$q = count($days_tar)-$n-1;
		if ($sem->rowCount()>=$q) {$q=0;} // На тот случай, если оплатили на дату раньше, чем завели карточку
		$semya = $rowsem[$q]["semya"];
		$semlg = $rowsem[$q]["semlg"];
		if ($semya==0) {$semya=1;}
	}
	else
	{
		 $semya = 1;
		 $semlg = 0;
	}

	// поиск предыдущей рассчитанной квитанции за этот месяц, чтобы учитывать нормы
	$sql = "select sum(sumkv) as sumkv, sum(penkv) as penkv, sum(kvtkv) as kvtkv
        	from ".CURRENT_RES."_kvit
	        where knkv='".$kn."' and nomkv='".$nom."' and uchkv='".$uch."' and kvtkv>0 and
		      year(imeskv)='".date("Y", strtotime($ddate))."' and month(imeskv)='".date("m", strtotime($ddate))."' ".DLTHIDE." ";
	$res0 = $dblink->query($sql);
	$rows0 = $res0->fetchAll();
	$agl = 0;
	if ($res0->rowCount()>0) {
		$agl = $rows0[0]["kvtkv"];
		$money = $money + $rows0[0]["sumkv"] - $rows0[0]["penkv"];
	}

        $tar = round($fulltar * ($semya-$semlg*0.5)/$semya, 6);
        $ukazatel_na_obeshaniya_chinovnikov = 0;
        if ((strtotime($dates[0])>=strtotime('01.02.2013')) and ($kodtar>4)) { $ukazatel_na_obeshaniya_chinovnikov = 1; } // С этого момента всё остальные записи пойдут по новым диф тар.

	if ($ukazatel_na_obeshaniya_chinovnikov == 1) {
								//------------------------------------------------------------------
								// Получаем список промежутков норм и их тарифов с учетом льготников
								$mass_tar = Get_mass_tar($alltarif, $semya, $semlg);
								// В цикле бегаем по массиву норм
								$kvhour = 0;
								$sum = $money;
								for ($i=0;$i<count($mass_tar);$i++) {
									$k1 = $sum/$mass_tar[$i]["tar"];
									if ($k1>($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"])) {
										$kvhour = $kvhour + $mass_tar[$i]["p2"]-$mass_tar[$i]["p1"];
										$sum = $sum - ($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"]) * $mass_tar[$i]["tar"];
									} else {
										$kvhour = $kvhour + $k1;
										break;
									}
								}


                        return(round($kvhour-$agl));


	}
        else
        {
        // Расчет по старому
	// Если есть льготник
	if  ($tar!=$fulltar)// (($semlg>0) and ($idlg>0))
	{
		if ($alltarif["norma"]>0)  // Если есть норма
		{
			$normsum = $alltarif["norma"] * $semya * $tar;
			// Оплата по норме
			if ($normsum>=($money)) // Если вписываемся в норму
			{
				if ($tar!=0) {$kvhour = round(($money / $tar));} else {$kvhour=0;}
			}
			else // Если выше нормы
			{
				if ($tar!=0) {$kvhour = round($normsum / $tar);} else {$kvhour=0;}
				$kvhour = $kvhour + round(($money - $normsum) / $fulltar);
			}
			$norma = $alltarif["norma"];
		}
		else // Если нормы нет
		{
			$norma = 0;
			$normsum = 0;
			if ($tar!=0) {$kvhour = round($money / $tar);} else {$kvhour=0;}
		}
	}
	else // Если нет льготника
	{
		if ($fulltar!=0) {
			$kvhour = round($money / $fulltar);
		} else {$kvhour = 0;}
	}
	return($kvhour-$agl);
   }
}

//*********************************************************************************
// Формируем массив тарифов с учетом льготников
//*********************************************************************************
function Get_mass_tar($alltarif, $semya, $semlg, $uch = 1) {
//	pr($alltarif, false);
	$T1 	= $alltarif["cena1"];
	$T1lg 	= $alltarif["cena1"]/2;
	$T2 	= $alltarif["cena2"];
	$T2lg 	= $alltarif["cena2"]/2;
	$T3 	= $alltarif["cena3"];
	$T3lg 	= $alltarif["cena3"]/2;
	$T4 	= $alltarif["cena4"];
	$T4lg 	= $alltarif["cena4"]/2;
	$NORMlg	= $alltarif["norma"];
	$Npr	= $semya;
	$Nlg    = $semlg;
	$mass_tar = array();
	if ($Nlg>0)	{ $PromLg = $Npr*$NORMlg; } else { $PromLg = 0; }
	$i = 0;
		if ($PromLg!=0) {
			$mass_tar[$i]["p1"] = 0;
			$mass_tar[$i]["p2"] = $PromLg;
			$mass_tar[$i]["tar"] = ($T1lg*$Nlg + ($Npr-$Nlg)*$T1)/$Npr;
            $mass_tar[$i]["tarfull"] = $T1;
         	$mass_tar[$i]["tarlg"] = $T1lg;
         	$mass_tar[$i]["lg"] = 1;
			$i++;
			$mass_tar[$i]["p1"] = $PromLg;
			$mass_tar[$i]["p2"] = $NORM1;
			$mass_tar[$i]["tar"] = $T1;
             		$mass_tar[$i]["tarfull"] = $T1;
         		$mass_tar[$i]["tarlg"] = $T1lg;
         		$mass_tar[$i]["lg"] = 0;

		} else {
			$mass_tar[$i]["p1"] = 0;
			$mass_tar[$i]["p2"] = $NORM1;
			$mass_tar[$i]["tar"] = $T1;
             		$mass_tar[$i]["tarfull"] = $T1;
         		$mass_tar[$i]["tarlg"] = $T1lg;
         		$mass_tar[$i]["lg"] = 0;
		}
	$i++;
				$mass_tar[$i]["p1"] = $NORM1;
				$mass_tar[$i]["p2"] = $NORM2;
				$mass_tar[$i]["tar"] = $T2;
                 		$mass_tar[$i]["tarfull"] = $T2;
                 		$mass_tar[$i]["tarlg"] = $T1lg;
                  		$mass_tar[$i]["lg"] = 0;
				$i++;
				$mass_tar[$i]["p1"] = $NORM2;
				$mass_tar[$i]["p2"] = 50000000; // и зноу па пяццот, шчасце
				$mass_tar[$i]["tar"] = $T3;
                 		$mass_tar[$i]["tarfull"] = $T3;
                		$mass_tar[$i]["tarlg"] = $T1lg;
                 		$mass_tar[$i]["lg"] = 0;
		$mass_tar[$i]["p1"] = $NORM1;
		$mass_tar[$i]["p2"] = 50000000; // и зноу па пяццот, шчасце
		$mass_tar[$i]["tar"] = $T2;
   		$mass_tar[$i]["tarfull"] = $T2;
      		$mass_tar[$i]["tarlg"] = $T1lg;
		$mass_tar[$i]["lg"] = 0;

	return $mass_tar;
}


//*********************************************************************************
// Формируем массив цен тарифов
//*********************************************************************************
function FAST_Centar() {
	global $dblink;
	ini_set('precision',12);
	$centar = array();
    $sql = "select a.* from _centar a order by a.kod, a.ddate desc";
	$res = $dblink->query($sql);
	$rows = $res->fetchAll();

	if ($res->rowCount()>0) {
		$kod = $rows[0]["kod"];
		for($k=0; $k<$res->rowCount(); $k++) {
			if ($kod != $rows[$k]["kod"]) {
				$kod = $rows[$k]["kod"];
			}
            $centar[$kod][] = $rows[$k];
		}
	}
	return $centar;
}

//*********************************************************************************
function FAST_Tarif($kn, $nom, $uch) {
	global $dblink;
   	$sql = "select id, idt, idl, mt, ddate, semya, semlg
            from ".CURRENT_RES."_tarhist_sem a
            where a.kn='".$kn."' and a.nom='".$nom."' and a.uch='".$uch."' and dlt=0
            order by ddate desc, id desc";
	$tarhist = $dblink->query($sql);
	return $tarhist->fetchAll();
}

//*********************************************************************************
function Get_Centar($kod, $imes){
	global $dblink;
	global $FCentar;
	$k = 0;
	while ((strtotime(($FCentar[$kod][$k]["ddate"]))>strtotime($imes)) and ($k<count($FCentar[$kod])-1)) {
		$k++;
	}
	return $FCentar[$kod][$k];
}

//*********************************************************************************
function Get_Tarif($imes) {
	global $dblink;
	global $FTarhist;
	$k = 0;
	if (count($FTarhist)>0) {
		while ((strtotime(($FTarhist[$k]["ddate"]))>strtotime($imes)) and ($k<count($FTarhist)-1)) {
			$k++;
		}
		return $FTarhist[$k];
	} else return false;
}

//*********************************************************************************
function Tarif($kn, $nom, $uch, $imes) {
	global $dblink;

	$tarhist = Get_Tarif($imes);
	if ($tarhist!="") {
		// Узнаем код тарифа
		$centar = Get_Centar($tarhist["idt"], $imes);
		// Проверяем есть ли льготники
		if ($tarhist["semlg"]>0) {
	    	$sql = "select a.idl, b.tip from ".CURRENT_RES."_sem_lg a, _vidlg b where a.idtarhist='".$tarhist["id"]."' and a.idl=b.id";
			$lg = $dblink->query($sql);
			$centar["idl"]=$lg->fetchAll(); // Сохраняем весь список льготников
		}
		else {
			$centar["idl"]="";
		}
		$centar["tarhist"] = $tarhist;
		return $centar;
	}
	else {
		$centar = array();
		$centar["tarhist"] = $tarhist;
		$centar["kod"] = 0;
		$centar["ddate"] = "01.01.1910";
		$centar["cena1"] = 0;
		$centar["cena2"] = 0;
		$centar["cena3"] = 0;
		$centar["cena4"] = 0;
		$centar["norma"] = 0;
		$centar["cena_base"] = 0;
		$centar["idl"] = "";
		return $centar;
	}
}

//*********************************************************************************
function SemLg($spis) {  // Возвращает сумму льгот для семьи
	$ssum=0;
	if ($spis!="") {
		for ($k=0;$k<count($spis);$k++) {
			if ($spis[$k]["tip"]==1) {$ssum = $ssum+0.5;}
			if ($spis[$k]["tip"]==2) {$ssum = $ssum+1;}
		}
		return $ssum;
	} else {
		return 0;
	}
}

//*********************************************************************************
function GetSc($kn, $nom) {
	global $dblink;
	$sql = "select a.maxn, a.dateust from ".CURRENT_RES."_mainsc a where a.kn='".$kn."' and a.nom='".$nom."' and dlt=0 order by dateust desc, a.id desc";
	$res = $dblink->query($sql);
	if ($res->rowCount()>0)
		return ($res->fetchAll());
	else
		return false;
}

//*********************************************************************************
function GetZnak(&$csmas, $imes) {
	$k=count($csmas)-1;
	$znak = $csmas[$k]["maxn"];
	while (($k>=0) and (strtotime($csmas[$k]["dateust"])<=strtotime($imes))) {
		$znak=$csmas[$k]["maxn"];
		$k--;
	}
	return $znak;
}

//*********************************************************************************
function GetCalculator($knnom, $kvtch, $uch=1, $ddate="") {

	global $FCentar;
	global $FTarhist;

	$kn = mb_substr($knnom,0,4);
	$nom = mb_substr($knnom,4,3);

	$FCentar = FAST_Centar();
	$FTarhist = FAST_Tarif($kn, $nom, $uch);

//	if ($ddate=="") { $ddate = date('d.m.Y'); }
	if ($ddate=="") { $ddate = date("d.m.Y", strtotime('-1 day',strtotime('01.'.date('m.Y')))); } //последний день предыдущего месяца

	$alltarif = Tarif($kn, $nom, $uch, $ddate);
	$itogo = array();
	$str='';
	$it = 0;
	//Если тарифы 3-х ставочные: (общий 2 и эл. плиты 2)
	/*if ($alltarif["tarhist"]["idt"] == 19 || $alltarif["tarhist"]["idt"] == 20)
	{
		$mass_tar = Get_mass_tar($alltarif, $alltarif["tarhist"]["semya"], $alltarif["tarhist"]["semlg"]);

		//print_r($mass_tar);
		//print_r($alltarif);

		$str='';
		$summ = 0;
		for ($k=0;$k<count($mass_tar);$k++) {
			if ($kvtch<($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])) {
				$summ = $summ + $kvtch*$mass_tar[$k]["tar"];
				$itogo[$k]["kvt"] = $kvtch;
				$itogo[$k]["tar"] = $mass_tar[$k]["tar"];
				$itogo[$k]["summ"] = $kvtch*$mass_tar[$k]["tar"];
				$it = $it + $itogo[$k]["summ"];
				$str= $str.' '.$itogo[$k]["kvt"].' кВтч по тарифу '.$itogo[$k]["tar"].' руб. на сумму '.round($itogo[$k]["summ"],2).' руб.';
				break;
			} else {
				$summ = $summ + ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])*$mass_tar[$k]["tar"];
				$kvtch = $kvtch - ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"]);
				$a = ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"]);

				$itogo[$k]["kvt"] = $mass_tar[$k]["p2"]-$mass_tar[$k]["p1"];
				$itogo[$k]["tar"] = $mass_tar[$k]["tar"];
				$itogo[$k]["summ"] = ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])*$mass_tar[$k]["tar"];
				$it = $it + $itogo[$k]["summ"];
				$str= $str.' '.$itogo[$k]["kvt"].' кВтч по тарифу '.$itogo[$k]["tar"].' руб. на сумму '.round($itogo[$k]["summ"],2).' руб.,~';
			}
		}
	}*/
	// Если новые тарифы, и тариф не "отопление и гвс"
	if ($alltarif["tarhist"]["idt"]>4 && $alltarif["tarhist"]["idt"] != 19 && $alltarif["tarhist"]["idt"] != 20 && $alltarif["tarhist"]["idt"] != 21) {
		$mass_tar = Get_mass_tar($alltarif, $alltarif["tarhist"]["semya"], $alltarif["tarhist"]["semlg"]);
		$str='';
		$summ = 0;
		for ($k=0;$k<count($mass_tar);$k++) {
			if ($kvtch<($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])) {
				$summ = $summ + $kvtch*$mass_tar[$k]["tar"];
				$itogo[$k]["kvt"] = $kvtch;
				$itogo[$k]["tar"] = $mass_tar[$k]["tar"];
				$itogo[$k]["summ"] = $kvtch*$mass_tar[$k]["tar"];
				$it = $it + $itogo[$k]["summ"];
				$str= $str.' '.$itogo[$k]["kvt"].' кВтч по тарифу '.$itogo[$k]["tar"].' руб. на сумму '.round($itogo[$k]["summ"],2).' руб.';
				break;
			} else {
				$summ = $summ + ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])*$mass_tar[$k]["tar"];
				$kvtch = $kvtch - ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"]);
				$a = ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"]);

				$itogo[$k]["kvt"] = $mass_tar[$k]["p2"]-$mass_tar[$k]["p1"];
				$itogo[$k]["tar"] = $mass_tar[$k]["tar"];
				$itogo[$k]["summ"] = ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])*$mass_tar[$k]["tar"];
				$it = $it + $itogo[$k]["summ"];
				$str= $str.' '.$itogo[$k]["kvt"].' кВтч по тарифу '.$itogo[$k]["tar"].' руб. на сумму '.round($itogo[$k]["summ"],2).' руб.,~';
			}
		}
	}
	else {
	// Если тарифы старые
		$sem = $alltarif["tarhist"];
		$fulltar = $alltarif["cena"];

		if ($sem) {
	        switch ($sem["mt"])
	        {
	            case "0":
	                $fulltar = $alltarif["cena"]; // Полная стоимость тарифа
	            break;
	            case "1":
	                $fulltar = $alltarif["cenamax"];
	            break;
	            case "2":
	                $fulltar = $alltarif["cenamin"];
	            break;
				case "3":
					$fulltar = $alltarif["cena2"];
				break;
	        }
			$semya = $sem["semya"];
			$semlg = $sem["semlg"];
			if ($semya==0) {$semya=1;}
		} else {
			 $semya = 1;
			 $semlg = 0;
		}

		if ($alltarif['norma'] == 0 /*|| $kvtch <= $alltarif["norma"]*/ )		//если норма в тарифе равна 0 или число киловатт меньше или равно норме:
		{
			$tar = round($fulltar * ($semya-SemLg($alltarif["idl"]))/$semya, 6);
			$itogo[0]["kvt"] = $kvtch;
			$itogo[0]["tar"] = $tar;
			$itogo[0]["summ"] = round($kvtch*$tar, 6);
			$it = $itogo[0]["summ"];
			$str= $str.' '.round($itogo[0]["kvt"],2).' кВтч по тарифу '.$itogo[0]["tar"].' руб. на сумму '.round($itogo[0]["summ"],2).' руб.';
		}
		else {
			$kvt_lost = $kvtch;

			//если льготник, то считаем по льготе:
			if ($sem["semlg"] > 0)
			{
				//считаем по льготе
					//сколько киловатт приходится на 1 человека в среднем:
					$kvt_za_1 = $kvtch/$semya;
					//Подбираем норму в зависимости от ставки тарифа:
					$norma_n = $alltarif["norma"];
					if ($alltarif["tarhist"]["idt"] == 19 || $alltarif["tarhist"]["idt"] == 20 || $alltarif["tarhist"]["idt"] == 21)
					{
						$norma_n = $alltarif["norma"] / 3;
						//print_r($alltarif);
					}
					//максимальное число киловатт по льготе для квартиры:
					$kvt_max_lg = min($norma_n*$sem["semlg"], $kvt_za_1 * $sem["semlg"]);


					//$tar = round($fulltar * ($semya-SemLg($alltarif["idl"]))/$semya, 6);		//оставил как комментарий, потому как может надо будет вернуться
					$tar = round($fulltar/2,6);			//сейчас скидка по льготе 50% (на 2019-02-26)
					$itogo[0]["kvt"] = $kvt_max_lg;
					$itogo[0]["tar"] = $tar;
					$itogo[0]["summ"] = round($kvt_max_lg/*$sem["semlg"]*/*$tar, 6);
					$it = $itogo[0]["summ"];
					$str= $str.' '.round($itogo[0]["kvt"],2).' кВтч по тарифу '.$itogo[0]["tar"].' руб. на сумму '.round($itogo[0]["summ"],2).' руб.';

				//считаем остаток по норме
					$tar = round($fulltar, 6);
					$itogo[1]["kvt"] = $kvtch - $itogo[0]["kvt"];
					$itogo[1]["tar"] = $fulltar;
					$itogo[1]["summ"] = round(($kvtch - $itogo[0]["kvt"])*$tar, 6);
					$it = $it + $itogo[1]["summ"];
					$str= $str.' '.round($itogo[1]["kvt"],2).' кВтч по тарифу '.$itogo[1]["tar"].' руб. на сумму '.round($itogo[1]["summ"],2).' руб.';

					//приведём в читаемый вид:
					$itogo[0]["kvt"] = round($itogo[0]["kvt"],0);
					$itogo[0]["tar"] = round($itogo[0]["tar"],5);
					$itogo[0]["summ"] = round($itogo[0]["summ"],5);

					$itogo[1]["kvt"] = round($itogo[1]["kvt"],0);
					$itogo[1]["tar"] = round($itogo[1]["tar"],5);
					$itogo[1]["summ"] = round($itogo[1]["summ"],5);
			}
			else
			{
				//считаем по норме
					$tar = round($fulltar, 6);
					$itogo[0]["kvt"] = $kvtch;
					$itogo[0]["tar"] = $fulltar;
					$itogo[0]["summ"] = round($kvtch*$tar, 6);
					$it = $it + $itogo[0]["summ"];
					$str= $str.' '.round($itogo[0]["kvt"],2).' кВтч по тарифу '.$itogo[0]["tar"].' руб. на сумму '.round($itogo[0]["summ"],2).' руб.';
			}
		}

	}
	$itogo[0]["sum"] = $it;
	$itogo[0]["str"] = $str;
	return $itogo;
}









?>
