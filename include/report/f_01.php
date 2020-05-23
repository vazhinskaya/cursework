<?php
    $time_start = getmicrotime();

    $output_array = array();
    $output_itogo = array(
	'pcol'=>0, 'ncol'=>0, 'sumpl'=>0, 'sumpl_n'=>0, 'sum_byt'=>0, 'sum_akt'=>0,
	'sum_post'=>0, 'sum_pen'=>0, 'sum_usl'=>0, 'sum_nbyt'=>0, 'sum_vozv'=>0,
    'sumsbor'=>0, 'sumsbor_n'=>0, 'sumper'=>0, 'sumper_n'=>0, 'sum_dbyt'=>0,
    'sum_xkv'=>0, 'sum_card'=>0, 'kol_card'=>0, 'sumsbor_fakt'=>0, 'sumsbor_razn'=>0,
    'sumper_fakt'=>0, 'realiz'=>0, 'sum_res'=>0, 'stemp'=>0, 'sum_akt_shtraf'=>0
    );

    $out_akt_selo = array();
    $out_akt_selo_kvt = 0;
    $out_akt_selo_summa = 0;
    $out_akt_gorod = array();
    $out_akt_gorod_kvt = 0;
    $out_akt_gorod_summa = 0;
    $out_akt_shtraf = 0;
    $out_akt_gorod_shtraf = 0;
    $out_akt_gorod_uscherb = 0;
    $out_akt_selo_shtraf = 0;
    $out_akt_selo_uscherb = 0;

	$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
	$objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
	$objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."FORMA_1_template.xls");
	$objPHPExcel->setActiveSheetIndex(0);

	/********************************************/
	/*              Report exec                 */
	/********************************************/
	$date_beg = date(DATE_FORMAT_SHOT, strtotime(get_input('f_01_dateb')));
	$date_end = date(DATE_FORMAT_SHOT, strtotime(get_input('f_01_datee')));

	$date_beg_ymd = date(DATE_FORMAT_YMD, strtotime($date_beg));
	$date_end_ymd = date(DATE_FORMAT_YMD, strtotime($date_end));

	if ( $date_beg_ymd > $date_end_ymd){
		$errors[] = 'Даты начала и конца расчетного периода не указаны или указаны неверно';
	}
	if (count($errors)){
		$smarty->assign('label_error',1);
		$smarty->assign('errors',$errors);
		$smarty->display('general_errors.html');
		exit();
	}
	else{
		$query = "Select * from ".CURRENT_RES."_pachka where dat_form >= '$date_beg_ymd' and dat_form <= '$date_end_ymd' and not (kassa LIKE 'АС') ".DLTHIDE . " order by npachka";
		$res_main = $dblink->query($query);

        while ($row = $res_main->fetch(PDO::FETCH_ASSOC)) {
			$res = $dblink->prepare("Select npr, prc from _plpr where id = ? limit 1");
            $res->execute([$row['kod']]);
            $kassa = $res->fetchColumn(0);
            $prc = $res->fetchColumn(1);
			$prc_temp = $prc/100;
            //фактическая оплата бытовиков //фактическая комиссия
            $sumper_fakt  = 0;
            $sumsbor_fakt = 0;

            $res  = $dblink->prepare("SELECT
                (SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvit    where (npachkv = ? AND dlt = 0))+
                (SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvitxvp where (npachkv = ? AND dlt = 0))");
            $res->execute([$row['npachka'], $row['npachka'], $row['npachka']]);
            $sumpl = $res->fetch(PDO::FETCH_NUM)[0];
            $res = $dblink->prepare("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvitxvp where typekv = 2 AND npachkv = ?".DLTHIDE);
            $res->execute([$row['npachka']]);
            $sum_usl = $res->fetch(PDO::FETCH_NUM)[0];
            if (trim(strtolower($kassa)) == 'ерип') {
				/*

				OLD VARIANT: 20.11.2019

                $sborpl = $dblink->query("
                     SELECT
                        (SELECT ISNULL(SUM(round({$prc_temp} * sumkv,2)),0) FROM ".CURRENT_RES."_kvit   where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT ISNULL(SUM(round({$prc_temp} * sumkv,2)),0) FROM ".CURRENT_RES."_kvitvp where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT ISNULL(SUM(round({$prc_temp} * sumkv,2)),0) FROM ".CURRENT_RES."_xkvit  where (npachkv = '{$row['npachka']}' AND dlt = 0))
                     ");
                list($sum_nbyt,$sbor_nbyt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,2)) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 3 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_post,$sbor_post)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,2)) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 4 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_akt, $sbor_akt)   = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,2)) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_vozv,$sbor_vozv)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,2)) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 6 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_dbyt,$sbor_dbyt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,2)) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 7 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_pr,  $sbor_pr)    = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,2)) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 8 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
				//27.11.2019
				list($sum_byt_max,  $sum_byt_min)    = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(sumper) as row2 FROM ".CURRENT_RES."_kvit where npachkv = '{$row['npachka']}'".DLTHIDE));
				*/

				$sborpl = dbOne("SELECT
                        (SELECT SUM(round(CASE WHEN sumper = 0 OR sumper IS NULL THEN {$prc_temp} * sumkv WHEN sumkv != 0 THEN sumkv - sumper END,2)) FROM ".CURRENT_RES."_kvit   where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT SUM(round(CASE WHEN sumper = 0 OR sumper IS NULL THEN {$prc_temp} * sumkv WHEN sumkv != 0 THEN sumkv - sumper END,2)) FROM ".CURRENT_RES."_kvitxvp where (npachkv = '{$row['npachka']}' AND dlt = 0))
                     ");
					 //SUM(round(CASE WHEN sumper = 0 THEN round(sum(sumkv)*{$prc_temp},2) WHEN sumper > 0 THEN sumkv-sumper END),2) as row2
                list($sum_nbyt,$sbor_nbyt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN {$prc_temp} * sumkv WHEN sumper > 0 THEN sumkv - sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 3 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_post,$sbor_post)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN {$prc_temp} * sumkv WHEN sumper > 0 THEN sumkv - sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 4 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_akt, $sbor_akt)   = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN {$prc_temp} * sumkv WHEN sumper > 0 THEN sumkv - sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_vozv,$sbor_vozv)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN {$prc_temp} * sumkv WHEN sumper > 0 THEN sumkv - sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 6 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_dbyt,$sbor_dbyt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN {$prc_temp} * sumkv WHEN sumper > 0 THEN sumkv - sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 7 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
			}
            else{
				/*

				OLD VARIANT: 20.11.2019


                $sborpl = $dblink->query("
                     SELECT

                        (SELECT round(isnull(sum(sumkv),0)*{$prc_temp},2) FROM ".CURRENT_RES."_kvit   where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT round(isnull(sum(sumkv),0)*{$prc_temp},2) FROM ".CURRENT_RES."_kvitvp where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT round(isnull(sum(sumkv),0)*{$prc_temp},2) FROM ".CURRENT_RES."_xkvit  where (npachkv = '{$row['npachka']}' AND dlt = 0))
                     ");
                list($sum_nbyt,$sbor_nbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},2) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 3 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_post,$sbor_post) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},2) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 4 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_akt, $sbor_akt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},2) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_vozv,$sbor_vozv) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},2) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 6 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_dbyt,$sbor_dbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},2) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 7 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_pr,  $sbor_pr)   = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},2) as row2 FROM ".CURRENT_RES."_kvitvp where typekv = 8 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
				//27.11.2019
				//list($sum_byt_max,  $sum_byt_min)    = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(sumper) as row2 FROM ".CURRENT_RES."_kvit where npachkv = '{$row['npachka']}'".DLTHIDE));
				$sum_byt_max = 0;
				$sum_byt_min = 0;
				*/
				//SELECT SUM(sumkv) as row1, SUM(CASE WHEN sumper = 0 THEN round(0.02 * sumkv,2) WHEN sumper > 0 THEN round(sumkv - sumper,2) END) as row2

				$sborpl = dbOne("SELECT
                        (SELECT SUM(round(CASE WHEN sumper = 0 OR sumper IS NULL THEN {$prc_temp} * sumkv WHEN sumper != 0 AND sumper IS NOT NULL THEN sumkv - sumper END,2)) FROM ".CURRENT_RES."_kvit   where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT SUM(round(CASE WHEN sumper = 0 OR sumper IS NULL THEN {$prc_temp} * sumkv WHEN sumper != 0 AND sumper IS NOT NULL THEN sumkv - sumper END,2)) FROM ".CURRENT_RES."_kvitxvp where (npachkv = '{$row['npachka']}' AND dlt = 0))", 'float');
                list($sum_nbyt,$sbor_nbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN sumkv*{$prc_temp} WHEN sumper > 0 THEN sumkv-sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 3 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_post,$sbor_post) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN sumkv*{$prc_temp} WHEN sumper > 0 THEN sumkv-sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 4 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_akt, $sbor_akt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN sumkv*{$prc_temp} WHEN sumper > 0 THEN sumkv-sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_vozv,$sbor_vozv) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN sumkv*{$prc_temp} WHEN sumper > 0 THEN sumkv-sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 6 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                list($sum_dbyt,$sbor_dbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, SUM(round(CASE WHEN sumper = 0 THEN sumkv*{$prc_temp} WHEN sumper > 0 THEN sumkv-sumper END,2)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 7 AND  npachkv = '{$row['npachka']}'".DLTHIDE));

			}
			//$sum_byt_max = floatval($sum_byt_max);
			//$sum_byt_min = floatval($sum_byt_min);

            $sum_akt_shtraf = dbOne("SELECT SUM(penkv) as row1 FROM ".CURRENT_RES."_kvitxvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE, 'float');
            $sum_pen  = dbOne("SELECT SUM(penkv) FROM ".CURRENT_RES."_kvit   where npachkv = '{$row['npachka']}'".DLTHIDE,'float');
            $sum_byt  = dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvit   where npachkv = '{$row['npachka']}'".DLTHIDE,'float');
            $sum_xkv  = dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvitxvp  where npachkv = '{$row['npachka']}' and typekv < 2".DLTHIDE,'float');
            $ncol     = dbOne("SELECT COUNT(*)  FROM ".CURRENT_RES."_kvit   where npachkv = '{$row['npachka']}'".DLTHIDE,'float')
                      + dbOne("SELECT COUNT(*)  FROM ".CURRENT_RES."_kvitxvp where npachkv = '{$row['npachka']}'".DLTHIDE,'float');
            $sum_card = dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvit   where npachkv = '{$row['npachka']}' and is_card = 1".DLTHIDE,'float')
                      + dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvitxvp where npachkv = '{$row['npachka']}' and is_card = 1".DLTHIDE,'float');
            $kol_card = dbOne("SELECT COUNT(*)  FROM ".CURRENT_RES."_kvit   where npachkv = '{$row['npachka']}' and is_card = 1".DLTHIDE,'float')
                      + dbOne("SELECT COUNT(*)  FROM ".CURRENT_RES."_kvitxvp where npachkv = '{$row['npachka']}' and is_card = 1".DLTHIDE,'float');
            $sum_res = $row['sumpl'] - $sum_dbyt - $sum_nbyt - $sum_post;

			//29.11.2019
			//$sbor_dbyt
			//!!!!!!!!!!!!!

            $sumsbor_fakt = $sborpl - $sbor_dbyt - $sbor_nbyt - $sbor_post;
            $sumper_fakt  = round( ($row['sumpl'] - $sum_dbyt - $sum_nbyt - $sum_post - $sumsbor_fakt), 2);
			if ($row['npachka']=='155676') {echo "r_sumpl->".$row['sumpl'] . ": sum_dbyt->$sum_dbyt" . ": sum_nbyt->$sum_nbyt" . ": sbor_post->$sum_post" . ": sborpl->".$sborpl; }
            $realiz = $sum_byt - $sum_pen + $sum_xkv + $sum_akt - $sum_akt_shtraf;

            $output_array[] = array(
                'dat_form' 	  => dateDMY($row['dat_form']),
                'npachka'	  => $row['npachka'],
                'kassa'		  => $kassa,
                'pcol'		  => intval($row['pcol']),                // квитанций по ярлыку
                'ncol'		  => $ncol,  	                       	// квитанций по пачке
                'sumpl'       => floatval($row['sumpl']),               //Сумма платежей по яклыку
                'sumpl_n'	  => floatval($row['sumpl'] - $row['sumpl_n']), // Разница сумм //sumpl_n - Сумма пачки по квитанциям
                'sum_byt'     => $sum_byt - $sum_pen + $sum_xkv ,     //Сумма быта по квитанциям за электроэнергию
                'sum_card'	  => $sum_card,                           // Сумма по пластиковым картам
                'kol_card' 	  => $kol_card,                           // Кол-во квитанций по пластиковым картам
                'sum_akt'     => ($sum_akt - $sum_akt_shtraf),       	// 5 тип - ущерб
                'sum_akt_shtraf'=> $sum_akt_shtraf,             	// 5 тип - штраф
                'sum_post'	  => $sum_post,                         //intval($row['sum_post']),    // 4 тип - посторонние платежи
                'sum_pen'	  => $sum_pen,                            // Пеня
                'sum_usl'     => $sum_usl,                            //intval($row['sum_usl']),     // 2 тип - услуги
                'sum_dbyt'	  => $sum_dbyt,                           // 7 тип - Платежи других РЭС
                'sum_nbyt'    => $sum_nbyt,                           // 3 Тип - Расчетная группа
                'sum_xkv'     => $sum_xkv,                            // Сумма неясных квитанций
                'sum_vozv'	  => $sum_vozv,                          	// 6 Тип - Возврат посторонних платежей
                'sumsbor'	  => round($row['sumsbor'],2),              // Комиссия - Сумма сбора по ярлыку
		//  27.11.2019:
                'sumsbor_fakt' => round($sumsbor_fakt,2),            	// Комиссия фактическая по квитанциям
                //'sumsbor_fakt'  =>  $sum_byt_max - $sum_byt_min/*round($row['sumsbor'],2)*/,            	// Комиссия фактическая по квитанциям

                'sumsbor_razn' => round($row['sumsbor'],2) - $sumsbor_fakt, // Комиссия - разница
				//list($sum_byt_max,  $sum_byt_min, $sum_sbor_byt)
                //'sumsbor_razn'  => $sum_byt_max - $sum_byt_min, // Комиссия - разница
		//!-------------
                'sumper'	   => round($row['sumper'],2),           	// Сумма перечисления по ярлыку
		//  27.11.2019:
                'sumper_fakt'  => round($sumper_fakt,2),             	// Сумма к перечислению только по БЫТу фактическое
            //    'sumper_fakt'   => $sum_byt_min,/*round($row['sumper'],2),*/             	// Сумма к перечислению только по БЫТу фактическое
		//!---------------
                'realiz'	=> round($realiz,2),                  	// Реализация
                'sum_res'   => round($sum_res,2),
			);
		$output_itogo['pcol']       =  $output_itogo['pcol'] + $output_array[count($output_array)-1]['pcol'];
		$output_itogo['ncol']       =  $output_itogo['ncol'] + $output_array[count($output_array)-1]['ncol'];
        $output_itogo['sumpl']      =  $output_itogo['sumpl'] + $output_array[count($output_array)-1]['sumpl'];
		$output_itogo['sumpl_n']    =  $output_itogo['sumpl_n'] + $output_array[count($output_array)-1]['sumpl_n'];
	    $output_itogo['sum_byt']    =  $output_itogo['sum_byt'] + $output_array[count($output_array)-1]['sum_byt'];
		$output_itogo['sum_card']   =  $output_itogo['sum_card'] + $output_array[count($output_array)-1]['sum_card'];
        $output_itogo['kol_card']   =  $output_itogo['kol_card'] + $output_array[count($output_array)-1]['kol_card'];
	    $output_itogo['sum_akt']    =  $output_itogo['sum_akt'] + $output_array[count($output_array)-1]['sum_akt'];
		$output_itogo['sum_akt_shtraf']=$output_itogo['sum_akt_shtraf'] + $output_array[count($output_array)-1]['sum_akt_shtraf'];
		$output_itogo['sum_post']   =  $output_itogo['sum_post'] + $output_array[count($output_array)-1]['sum_post'];
		$output_itogo['sum_pen']    =  $output_itogo['sum_pen'] + $output_array[count($output_array)-1]['sum_pen'];
        $output_itogo['sum_usl']    =  $output_itogo['sum_usl'] + $output_array[count($output_array)-1]['sum_usl'];
		$output_itogo['sum_dbyt']   =  $output_itogo['sum_dbyt'] + $output_array[count($output_array)-1]['sum_dbyt'];
	    $output_itogo['sum_nbyt']   =  $output_itogo['sum_nbyt'] + $output_array[count($output_array)-1]['sum_nbyt'];
	    $output_itogo['sum_xkv']    =  $output_itogo['sum_xkv'] + $output_array[count($output_array)-1]['sum_xkv'];
		$output_itogo['sum_vozv']   =  $output_itogo['sum_vozv'] + $output_array[count($output_array)-1]['sum_vozv'];
        $output_itogo['sumsbor']    =  $output_itogo['sumsbor'] + $output_array[count($output_array)-1]['sumsbor'];
		$output_itogo['sumsbor_fakt']= $output_itogo['sumsbor_fakt'] + $output_array[count($output_array)-1]['sumsbor_fakt'];
		$output_itogo['sumsbor_razn']= $output_itogo['sumsbor_razn'] + $output_array[count($output_array)-1]['sumsbor_razn'];
		$output_itogo['sumper']     =  $output_itogo['sumper'] + $output_array[count($output_array)-1]['sumper'];
        $output_itogo['sumper_fakt']=  $output_itogo['sumper_fakt'] + $output_array[count($output_array)-1]['sumper_fakt'];
	    $output_itogo['realiz']     =  $output_itogo['realiz'] + $output_array[count($output_array)-1]['realiz'];
		$output_itogo['sum_res']    =  $output_itogo['sum_res'] + $output_array[count($output_array)-1]['sum_res'];
		}
    }
	/*****************************************************************************************/
	/*                     Write data in REPORT                                              */
	/*****************************************************************************************/
	$key2 = 11;
	foreach ($output_array as $key => $str){
	    $key2 = 11 + $key;
            $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$str['dat_form']);
            $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$str['npachka']);
            $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['kassa']);
            $objPHPExcel->getActiveSheet()->setCellValue("D$key2",(integer)($str['pcol']));
            $objPHPExcel->getActiveSheet()->setCellValue("E$key2",(integer)($str['ncol']));
            $objPHPExcel->getActiveSheet()->setCellValue("F$key2",($str['sumpl']));
            $objPHPExcel->getActiveSheet()->setCellValue("G$key2",($str['sumsbor']));
            $objPHPExcel->getActiveSheet()->setCellValue("H$key2",($str['sumper']));
            $objPHPExcel->getActiveSheet()->setCellValue("I$key2",($str['sum_res']));
            $objPHPExcel->getActiveSheet()->setCellValue("J$key2",($str['sumsbor_fakt']));
            $objPHPExcel->getActiveSheet()->setCellValue("K$key2",($str['sumper_fakt']));
            $objPHPExcel->getActiveSheet()->setCellValue("L$key2",($str['sum_byt']));
            $objPHPExcel->getActiveSheet()->setCellValue("M$key2",($str['sum_akt']));
            $objPHPExcel->getActiveSheet()->setCellValue("O$key2",($str['sum_akt_shtraf']));
            $objPHPExcel->getActiveSheet()->setCellValue("P$key2",($str['sum_pen']));
            $objPHPExcel->getActiveSheet()->setCellValue("Q$key2",($str['sum_dbyt']));
            $objPHPExcel->getActiveSheet()->setCellValue("R$key2",($str['sum_nbyt']));
            $objPHPExcel->getActiveSheet()->setCellValue("S$key2",($str['sum_post']));
            $objPHPExcel->getActiveSheet()->setCellValue("T$key2",($str['sum_usl']));
            $objPHPExcel->getActiveSheet()->setCellValue("U$key2",($str['sum_vozv']));
            $objPHPExcel->getActiveSheet()->setCellValue("V$key2",($str['realiz']));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A11:V$key2")->applyFromArray($style_a_9_r_bdash);

        /***************************************/
        /*           Пишем ИТОГО               */
        /***************************************/
        $key2 += 2;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:C$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'Всего за месяц:');
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",(integer)($output_itogo['pcol']));
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",(integer)($output_itogo['ncol']));
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$output_itogo['sumpl']);
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$output_itogo['sumsbor']);
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$output_itogo['sumper']);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$output_itogo['sum_res']);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$output_itogo['sumsbor_fakt']);
        $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$output_itogo['sumper_fakt']);
        $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$output_itogo['sum_byt']);
        $objPHPExcel->getActiveSheet()->setCellValue("M$key2",$output_itogo['sum_akt']);
        $objPHPExcel->getActiveSheet()->setCellValue("O$key2",$output_itogo['sum_akt_shtraf']);
        $objPHPExcel->getActiveSheet()->setCellValue("P$key2",$output_itogo['sum_pen']);
        $objPHPExcel->getActiveSheet()->setCellValue("Q$key2",$output_itogo['sum_dbyt']);
        $objPHPExcel->getActiveSheet()->setCellValue("R$key2",$output_itogo['sum_nbyt']);
        $objPHPExcel->getActiveSheet()->setCellValue("S$key2",$output_itogo['sum_post']);
        $objPHPExcel->getActiveSheet()->setCellValue("T$key2",$output_itogo['sum_usl']);
        $objPHPExcel->getActiveSheet()->setCellValue("U$key2",$output_itogo['sum_vozv']);
        $objPHPExcel->getActiveSheet()->setCellValue("V$key2",$output_itogo['realiz']);

        $objPHPExcel->getActiveSheet()->getStyle("A$key2:V$key2")->applyFromArray($style_a_9_r_bdash);

        /*****************************************/
        /*       Дополнительная информация       */
        /*****************************************/

        if ($key2) $key2 = $key2 + 2;
            else $key2 = 12;
        $objPHPExcel->getActiveSheet()->mergeCells("C$key2:D$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",'Небаланс:');
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",(integer)($output_itogo['pcol']-$output_itogo['ncol']));
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",($output_itogo['sumpl_n']));
        $objPHPExcel->getActiveSheet()->getStyle("C$key2:F$key2")->applyFromArray($style_a_9_r_bdash);

        $key2 += 2;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $temp0 = dbOne("SELECT cena FROM _centar WHERE kod = 1 and ddate < '{$date_beg_ymd}' ORDER BY ddate DESC LIMIT 1",'float');
        $temp = 'Поступило неясных квитанций на сумму   '.($output_itogo['sum_xkv']).' руб. / '.$temp0.' руб./кВтч =   '.(round($output_itogo['sum_xkv']/$temp0,2)).' кВтч';
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$temp);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'Оплачено абонентами других РЭС квитанций на сумму -       '.($output_itogo['sum_dbyt']).' руб.');
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $temp0 = dbOne("Select SUM(sumpl) from ".CURRENT_RES."_pachka where dat_form >= '$date_beg_ymd' and dat_form <= '$date_end_ymd' and (kassa LIKE 'АС' or kassa LIKE 'AC') ".DLTHIDE,'float' );
        $temp = 'Поступило квитанций из других РЭС на сумму -       '.($temp0).' руб.';
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$temp);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $temp = 'Поступило '.($output_itogo['kol_card']).' квитанций по пластиковым картам на сумму - '.($output_itogo['sum_card']).' руб.';
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$temp);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        // ПРОСЧИТЫВАЕМ ИНФОРМАЦИЮ ПО АКТАМ
        $query = "
            SELECT * from ".CURRENT_RES."_kvitxvp as kvitxvp
                LEFT JOIN
                    ".CURRENT_RES."_pachka as pachka on pachka.npachka = kvitxvp.npachkv
                WHERE 1=1
                    and pachka.dat_form >= '{$date_beg_ymd}' and pachka.dat_form <= '{$date_end_ymd}'
                    and kvitxvp.typekv = 5
                    and (kvitxvp.dlt = 0 or kvitxvp.dlt is NULL)
                ORDER BY kvitxvp.datekv
        ";
        $res = $dblink->query($query);
        while($row = $res->fetch()){
            $out_akt_str = array('str'=>'','sumkv'=>0,'kvtkv'=>0);
            $out_akt_str['str']     = date('d.m.Y',strtotime($row['datekv'])).'г.   опл.по акту   '.$row['knkv'].'-'.$row['nomkv'].'-'.$row['uchkv'].' ';
            $out_akt_str['sumkv']   = $row['sumkv'];
            $out_akt_str['kvtkv']   = $row['kvtkv'];
            $out_akt_str['penkv']   = $row['penkv'];
            $out_akt_shtraf += $row['penkv'];
            if (get_is_from_city($row['knkv'],$row['nomkv'])){
                $out_akt_gorod[] = $out_akt_str;
                $out_akt_gorod_kvt += $row['kvtkv'];
                $out_akt_gorod_summa += $row['sumkv'];
                $out_akt_gorod_shtraf += $row['penkv'];
                $out_akt_gorod_uscherb += ($row['sumkv'] - $row['penkv']);
            }
            else{
                 $out_akt_selo[] = $out_akt_str;
                 $out_akt_selo_kvt += $row['kvtkv'];
                 $out_akt_selo_summa += $row['sumkv'];
                 $out_akt_selo_shtraf += $row['penkv'];
                 $out_akt_selo_uscherb += ($row['sumkv'] - $row['penkv']);
           }
        }

        $key2 += 2;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $temp = 'Оплачено по актам -  '.strval($out_akt_gorod_summa+$out_akt_selo_summa).' руб.';
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$temp);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $temp = 'в т.ч. ущерб -   '.strval($out_akt_selo_uscherb + $out_akt_gorod_uscherb).' руб.';
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$temp);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:J$key2");
        $temp = 'в т.ч. штраф -  '.($out_akt_gorod_shtraf + $out_akt_selo_shtraf).' руб.';
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$temp);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_l_b);

        //ВЫВОД АКТОВ // ГОРОД
        $key2 += 2;
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'ГОРОД:');
        $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",'руб.');
        $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'кВтч');
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",'штраф');
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",'ущерб');
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);

        foreach ($out_akt_gorod as $key => $str){
            $key2 += 1;
            $objPHPExcel->getActiveSheet()->mergeCells("A$key2:D$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$str['str']);
            $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("E$key2",($str['sumkv']));
            $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("G$key2",(integer)($str['kvtkv']));
            $objPHPExcel->getActiveSheet()->setCellValue("I$key2",($str['penkv']));
            $objPHPExcel->getActiveSheet()->setCellValue("J$key2",($str['sumkv']-$str['penkv']));
	    $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);
        }

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:D$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'ИТОГО по ГОРОД:');
        $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$out_akt_gorod_summa);
        $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$out_akt_gorod_kvt);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$out_akt_gorod_shtraf);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$out_akt_gorod_uscherb);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);

        //ВЫВОД АКТОВ // СЕЛО
        $key2 += 2;
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'СЕЛО:');
        $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",'руб.');
        $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'кВтч');
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",'штраф');
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",'ущерб');
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);

        foreach ($out_akt_selo as $key => $str){
            $key2 += 1;
            $objPHPExcel->getActiveSheet()->mergeCells("A$key2:D$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$str['str']);
            $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("E$key2",($str['sumkv']));
            $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("G$key2",(integer)($str['kvtkv']));
            $objPHPExcel->getActiveSheet()->setCellValue("I$key2",($str['penkv']));
            $objPHPExcel->getActiveSheet()->setCellValue("J$key2",($str['sumkv']-$str['penkv']));
	    $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);
        }

        $key2 += 1;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:D$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'ИТОГО по СЕЛО:');
        $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$out_akt_selo_summa);
        $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$out_akt_selo_kvt);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$out_akt_selo_shtraf);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$out_akt_selo_uscherb);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);

        //ИТОГО ПО АКТАМ
        $key2 += 2;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:D$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'ИТОГО ПО АКТАМ:');
        $objPHPExcel->getActiveSheet()->mergeCells("E$key2:F$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$out_akt_gorod_summa + $out_akt_selo_summa);
        $objPHPExcel->getActiveSheet()->mergeCells("G$key2:H$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$out_akt_gorod_kvt + $out_akt_selo_kvt);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$out_akt_gorod_shtraf + $out_akt_selo_shtraf);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$out_akt_gorod_uscherb + $out_akt_selo_uscherb);
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:J$key2")->applyFromArray($style_a_9_r_bdash);

    $key2 += 2;
    Write_report("Форма_1", $objPHPExcel, $date_beg, $date_end, '', $key2, 0);
    echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
?>