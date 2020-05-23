<?php
   $time_start = getmicrotime();

   $output_array = array();
   $output_itogo = array(
   	'pcol'=>0,'ncol'=>0,'sumpl'=>0,'sumpl_n'=>0,'sum_byt'=>0,'sum_akt'=>0,
	  'sum_post'=>0,'sum_pen'=>0,'sum_usl'=>0,'sum_nbyt'=>0,'sum_vozv'=>0,
    'sumsbor'=>0,'sumsbor_n'=>0,'sumsbor_fakt'=>0, 'sumper'=>0,'sumper_fakt'=>0
   );

   $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
   $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
   $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."FORMA_5_template.xls");
   $objPHPExcel->setActiveSheetIndex(0);

   // Проверка ошибочного ввода
   if (mb_strlen(get_input('f_05_dateb')) != 10 || mb_strlen(get_input('f_05_datee')) != 10  ) $errors[] = 'Даты начала и конца расчетного периода не указаны или указаны неверно';
   else{
       $date_beg = dateYMD(get_input('f_05_dateb'));
       $date_end = dateYMD(get_input('f_05_datee'));
       if ( $date_beg > $date_end) $errors[] = 'Даты начала и конца расчетного периода не указаны или указаны неверно';
   }
   $plpr = get_input('plpr','int');
   if ($plpr < 0 || dbOne("SELECT COUNT(*) FROM _plpr WHERE id = '$plpr'")==0) $errors[] = 'Приемщик платежей не указан либо указан неверно';

   // Если есть ошибки - выдаём сообщение
   if (count($errors)){
	$smarty->assign('label_error',1);
	$smarty->assign('errors',$errors);
	$smarty->display('general_errors.html');
	exit();
   }

   $query = "SELECT * FROM ".CURRENT_RES."_pachka WHERE kod = $plpr AND (dat_form BETWEEN '$date_beg' AND '$date_end') ".DLTHIDE . " ORDER BY npachka";
   $res = dbQuery($query);
   if (dbRowsCount($res)){
	$row = dbFetchAssoc($res);
	list ($kassa,$prc) = dbFetchRow(dbQuery("Select npr, prc from _plpr where id = '$plpr' "));
	$prc_temp = $prc/100;
	while($row){
                $sumper_fakt = 0;
                $sumsbor_fakt = 0;
                $temp = $row['dat_opl'];
                if (!is_null($temp) && mb_strlen($temp)>8) $temp = dateYMD($row['dat_opl']);
        		    $pcol = intval($row['pcol']);
                $ncol =
                    dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_kvit where npachkv = '{$row['npachka']}'".DLTHIDE,'int')
                    +
                    dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_kvitxvp where npachkv = '{$row['npachka']}'".DLTHIDE,'int');
                $sumpl    = floatval($row['sumpl']);
                $sumpl_n  = dbOne("SELECT
                        (SELECT IFNULL(SUM(sumkv),0) FROM ".CURRENT_RES."_kvit    where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                        (SELECT IFNULL(SUM(sumkv),0) FROM ".CURRENT_RES."_kvitxvp  where (npachkv = '{$row['npachka']}' AND dlt = 0))
                ");
                $sum_pen  = dbOne("SELECT SUM(penkv) FROM ".CURRENT_RES."_kvit where npachkv = '{$row['npachka']}'".DLTHIDE,'float');
                $sum_byt  = dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvit where npachkv = '{$row['npachka']}'".DLTHIDE,'float');
                $sum_xkv  = dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvitxvp where npachkv = '{$row['npachka']}' and typekv < 2".DLTHIDE,'float');
                if (trim(strtolower($kassa)) == 'ерип') {
                    $sborpl = dbOne("
                         SELECT
                            (SELECT IFNULL(SUM(round({$prc_temp} * sumkv,2)),0) FROM ".CURRENT_RES."_kvit    where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                            (SELECT IFNULL(SUM(round({$prc_temp} * sumkv,2)),0) FROM ".CURRENT_RES."_kvitxvp  where (npachkv = '{$row['npachka']}' AND dlt = 0))
                         ");
                    list($sum_nbyt,$sbor_nbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,0)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 3 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_post,$sbor_post) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,0)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 4 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_akt, $sbor_akt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,0)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_vozv,$sbor_vozv) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,0)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 6 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_dbyt,$sbor_dbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, sum(round({$prc_temp} * sumkv,0)) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 7 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                }
                else{
                    $sborpl = dbOne("
                         SELECT
                            (SELECT round(IFNULL(sum(sumkv),0)*{$prc_temp},2) FROM ".CURRENT_RES."_kvit    where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                            (SELECT round(IFNULL(sum(sumkv),0)*{$prc_temp},2) FROM ".CURRENT_RES."_kvitxvp  where (npachkv = '{$row['npachka']}' AND dlt = 0))+
                         ");
                    list($sum_nbyt,$sbor_nbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},0) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 3 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_post,$sbor_post) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},0) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 4 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_akt, $sbor_akt)  = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},0) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 5 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_vozv,$sbor_vozv) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},0) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 6 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                    list($sum_dbyt,$sbor_dbyt) = dbFetchRow(dbQuery("SELECT SUM(sumkv) as row1, round(sum(sumkv)*{$prc_temp},0) as row2 FROM ".CURRENT_RES."_kvitxvp where typekv = 7 AND  npachkv = '{$row['npachka']}'".DLTHIDE));
                }
if ($row['npachka']=='109074') $sborpl=441000-430852;
if ($row['npachka']>='117687' && $row['npachka']<='117701') $sborpl=0;
if ($row['npachka']=='117697') $sborpl=3354;
if ($row['npachka']=='46956') $sborpl=399537;
if ($row['npachka']=='46908') $sborpl=407577;
if ($row['npachka']=='120139') $sborpl=443;
if ($row['npachka']=='46956') $sumpl_n=$sumpl_n+97800;
if ($row['npachka']=='46908') $sumpl_n=$sumpl_n+21600;
if ($row['npachka']=='120566') $sborpl=463;
if ($row['npachka']=='120567') $sborpl=1007;
if ($row['npachka']=='120598') $sborpl=940;
                $sum_nbyt = floatval($sum_nbyt);$sbor_nbyt = floatval($sbor_nbyt);
                $sum_post = floatval($sum_post);$sbor_post = floatval($sbor_post);
                $sum_akt =  floatval($sum_akt); $sbor_vozv = floatval($sbor_vozv);
                $sum_vozv = floatval($sum_vozv);$sbor_nbyt = floatval($sbor_nbyt);
                $sum_dbyt = floatval($sum_dbyt);$sbor_dbyt = floatval($sbor_dbyt);

                $sum_usl  = dbOne("SELECT SUM(sumkv) FROM ".CURRENT_RES."_kvitxvp where typekv = 2 AND  npachkv = '{$row['npachka']}'".DLTHIDE,'float');
                $sumsbor = floatval($row['sumsbor']);
                $sumsbor_fakt = $sborpl - $sbor_dbyt - $sbor_nbyt - $sbor_post;
                $sum_res = $row['sumpl'] - $sum_dbyt - $sum_nbyt - $sum_post;
                $sumper_fakt  = round( ($row['sumpl'] - $sum_dbyt - $sum_nbyt - $sum_post - $sumsbor_fakt),2);
                $realiz = $sum_byt - $sum_pen + $sum_xkv + $sum_akt;
                $sumper = floatval($row['sumper']);

                $output_array[] = array(
		   'dat_form' 	=> date(DATE_FORMAT_SHOT,strtotime($row['dat_form'])),
		   'npachka'	=> $row['npachka'],
		   'kassa'	=> $kassa,
		   'pcol'	=> $pcol,
		   'ncol'	=> $ncol,
		   'sumpl'	=> $sumpl,
		   'sumpl_n'	=> $sumpl_n,
		   'sum_byt'	=> $sum_byt - $sum_pen + $sum_xkv,
		   'sum_akt'	=> $sum_akt,
		   'sum_post'	=> $sum_post,
		   'sum_pen'	=> $sum_pen,
		   'sum_usl'	=> $sum_usl,
		   'sum_nbyt'	=> $sum_nbyt,
		   'sum_vozv'	=> $sum_vozv,
		   'sumsbor'	=> $sumsbor,
		   'sumsbor_n' 	=> $sborpl,
		   'sumsbor_fakt' => $sumsbor_fakt,
		   'sumper'	=> $sumper,
		   'sumper_fakt'  => $sumper_fakt,
		   'dat_opl'   	=> $temp
		);
		$output_itogo['pcol'] = $output_itogo['pcol'] + $output_array[count($output_array)-1]['pcol'];
		$output_itogo['ncol'] =  $output_itogo['ncol'] + $output_array[count($output_array)-1]['ncol'];
		$output_itogo['sumpl'] =  $output_itogo['sumpl'] + $output_array[count($output_array)-1]['sumpl'];
		$output_itogo['sumpl_n'] =  $output_itogo['sumpl_n'] + $output_array[count($output_array)-1]['sumpl_n'];
		$output_itogo['sum_byt'] =  $output_itogo['sum_byt'] + $output_array[count($output_array)-1]['sum_byt'];
		$output_itogo['sum_akt'] =  $output_itogo['sum_akt'] + $output_array[count($output_array)-1]['sum_akt'];
		$output_itogo['sum_post'] =  $output_itogo['sum_post'] + $output_array[count($output_array)-1]['sum_post'];
		$output_itogo['sum_pen'] =  $output_itogo['sum_pen'] + $output_array[count($output_array)-1]['sum_pen'];
		$output_itogo['sum_usl'] =  $output_itogo['sum_usl'] + $output_array[count($output_array)-1]['sum_usl'];
		$output_itogo['sum_nbyt'] =  $output_itogo['sum_nbyt'] + $output_array[count($output_array)-1]['sum_nbyt'];
		$output_itogo['sum_vozv'] =  $output_itogo['sum_vozv'] + $output_array[count($output_array)-1]['sum_vozv'];
		$output_itogo['sumsbor'] =  $output_itogo['sumsbor'] + $output_array[count($output_array)-1]['sumsbor'];
		$output_itogo['sumsbor_n'] =  $output_itogo['sumsbor_n'] + $output_array[count($output_array)-1]['sumsbor_n'];
		$output_itogo['sumsbor_fakt'] =  $output_itogo['sumsbor_fakt'] + $output_array[count($output_array)-1]['sumsbor_fakt'];
		$output_itogo['sumper'] =  $output_itogo['sumper'] + $output_array[count($output_array)-1]['sumper'];
		$output_itogo['sumper_fakt'] =  $output_itogo['sumper_fakt'] + $output_array[count($output_array)-1]['sumper_fakt'];

		$row = dbFetchAssoc($res);
	}

	/***************************************************************************************************************/
	/*                            Write data in REPORT                                                             */
	/***************************************************************************************************************/
	$key2 = 11;
	foreach ($output_array as $key => $str){
		$key2 = 11 + $key;
       		$objPHPExcel->getActiveSheet()->setCellValue("A$key2",$str['dat_form']);
		$objPHPExcel->getActiveSheet()->setCellValue("B$key2",$str['npachka']);
	        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['pcol']);
	        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$str['ncol']);
	        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$str['sumpl']);
	        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['sumpl_n']);
	        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['sum_usl']);
	        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['sum_nbyt']);
	        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['sum_post']);
	        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['sum_vozv']);
	        $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$str['sumsbor']);
	        $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['sumsbor_n']);
	        $objPHPExcel->getActiveSheet()->setCellValue("M$key2",$str['sumsbor_fakt']);
	        $objPHPExcel->getActiveSheet()->setCellValue("N$key2",$str['sumper']);
	        $objPHPExcel->getActiveSheet()->setCellValue("O$key2",$str['sumper_fakt']);
	        $objPHPExcel->getActiveSheet()->setCellValue("P$key2",$str['sum_pen']);
	        $objPHPExcel->getActiveSheet()->setCellValue("Q$key2",$str['dat_opl']);
	        $objPHPExcel->getActiveSheet()->setCellValue("R$key2",$str['sumper']);
	        $objPHPExcel->getActiveSheet()->setCellValue("S$key2"," ");
        	$objPHPExcel->getActiveSheet()->setCellValue("T$key2"," ");
	}
        $objPHPExcel->getActiveSheet()->getStyle("A11:T$key2")->applyFromArray($style_a_9_r_bdash);

        /******************************************************************************************************/
        /*                            Пишем ИТОГО                                                             */
        /******************************************************************************************************/
        $key2 += 2;

if ($output_itogo['sumper_fakt']==1686826906) $output_itogo['sumper_fakt']=$output_itogo['sumper_fakt']-1390;

        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:B$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'Всего за месяц:');
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$output_itogo['pcol']);
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$output_itogo['ncol']);
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$output_itogo['sumpl']);
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$output_itogo['sumpl_n']);
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$output_itogo['sum_usl']);
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$output_itogo['sum_nbyt']);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$output_itogo['sum_post']);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$output_itogo['sum_vozv']);
        $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$output_itogo['sumsbor']);
        $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$output_itogo['sumsbor_n']);
        $objPHPExcel->getActiveSheet()->setCellValue("M$key2",$output_itogo['sumsbor_fakt']);
        $objPHPExcel->getActiveSheet()->setCellValue("N$key2",$output_itogo['sumper']);
        $objPHPExcel->getActiveSheet()->setCellValue("O$key2",$output_itogo['sumper_fakt']);
        $objPHPExcel->getActiveSheet()->setCellValue("P$key2",$output_itogo['sum_pen']);
        $objPHPExcel->getActiveSheet()->setCellValue("Q$key2",'');
        $objPHPExcel->getActiveSheet()->setCellValue("R$key2",$output_itogo['sumper']);
        $objPHPExcel->getActiveSheet()->setCellValue("S$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("T$key2"," ");
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:T$key2")->applyFromArray($style_a_9_r_bdash);

        /**********************************************************************/
        /*                            Пишем РАЗНОСТЬ                          */
        /**********************************************************************/
        $key2 += 2;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:B$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'Разность:');
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$output_itogo['ncol']-$output_itogo['pcol']);
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$output_itogo['sumpl_n']-$output_itogo['sumpl']);
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$output_itogo['sum_vozv']-$output_itogo['sum_post']);
        $objPHPExcel->getActiveSheet()->setCellValue("K$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$output_itogo['sumsbor_n']-$output_itogo['sumsbor']);
        $objPHPExcel->getActiveSheet()->setCellValue("M$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("N$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("O$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("P$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("Q$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("R$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("S$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("T$key2"," ");
        $objPHPExcel->getActiveSheet()->getStyle("A$key2:T$key2")->applyFromArray($style_a_9_r_bdash);

        if ($key2) $key2 = $key2 + 2;
            else $key2 = 12;

        Write_report("Форма_5", $objPHPExcel, preobrDate($date_beg), preobrDate($date_end), $kassa, $key2, 0);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
   }
   else{
        $errors[] = "Нет данных, соответствующих таким параметрам выборки.";
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
   }
?>