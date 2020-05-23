<?php
    function f_04_sort_by_knnomuch($a,$b){
        return strnatcasecmp($a['knnomuch'], $b['knnomuch']);
    }

    $time_start = getmicrotime();
    $key2  = 0;

    $resr = get_input('nres','int');
    if ($resr==0) {
        $res2=" and 1=1 ";
        }
        else {
        $res2=" and (dom.id_res=".$resr.") ";
        $nres2 = dbOne("select res FROM _res where id=".$resr);
        }

    $date_beg = date(DATE_FORMAT_YMD, strtotime(get_input('f_04_dateb')));
    $date_end = date(DATE_FORMAT_YMD, strtotime(get_input('f_04_datee')));
    $raznica = get_input('f_04_raznica');
    $with_askue = get_input('with_askue','int');
    $dolg_kvt_city = 0;
    $dolg_rub_city = 0;
    $dolg_kvt_selo = 0;
    $dolg_rub_selo = 0;
    $dolg_kvt_selres = 0;
    $dolg_rub_selres = 0;
    $temp_array = explode('.',$date_beg);
    if ( isset($temp_array[0]) && isset($temp_array[1]) && isset($temp_array[2])){
        if (!checkdate($temp_array[1],$temp_array[2],$temp_array[0]))
            $errors[] = 'Дата начала расчета не установлена либо некорректна';
    }
    else {
        $errors[] = 'Дата начала расчета не установлена либо некорректна';
    }
    $temp_array = explode('.',$date_end);
    if ( isset($temp_array[0]) && isset($temp_array[1]) && isset($temp_array[2])){
        if (!checkdate($temp_array[1],$temp_array[2],$temp_array[0]))
            $errors[] = 'Дата окончания расчета не установлена либо некорректна';
    }
    else {
        $errors[] = 'Дата окончания расчета не установлена либо некорректна';
    }
    if (
    date(DATE_FORMAT_YMD, strtotime(get_input('f_04_dateb'))) > date(DATE_FORMAT_YMD, strtotime(get_input('f_04_datee')))) {
        $errors[] = 'Даты начала расчета больше даты окончания расчета';
    }

    if (count($errors)){
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
        exit();
    }

    $knnomuch_array = array();
    $output_array = array();

    $query_obh = "Select knobh, nomobh, uchobh, date_format(dateobh, '%d.%m.%Y') as dateobh_shot, ob_pok  from ".CURRENT_RES."_obhod where dateobh >= '$date_beg' and dateobh <= '$date_end' and ob_pok>'' ".DLTHIDE." order by knobh, nomobh, dateobh desc, id desc";
print($query_obh);
    $res_obh = $dblink->query($query_obh);

    while($row_obh = $res_obh->fetch()) {
        $kn = $row_obh['knobh'];
        $nom = $row_obh['nomobh'];
        // пропускаем абонентов с АСКУЭ
        //pr($kn.$nom, false);
        if (($with_askue == 0) && (dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_main WHERE kn='{$kn}' and nom='{$nom}' ".DLTHIDE." and id_dom in (SELECT id FROM ".CURRENT_RES."_dom WHERE ASKUE=1)")>0 )) continue;
        //pr('1 - '.$kn.$nom, false);
        // оставляем только абонентов с АСКУЭ (если with_askue=1)
        if (($with_askue == 1) && (dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_main WHERE kn='{$kn}' and nom='{$nom}' ".DLTHIDE." and id_dom in (SELECT id FROM ".CURRENT_RES."_dom WHERE ASKUE=1)")==0)) continue;
        //pr('2 - '.$kn.$nom, false);
        //if ($resr>0) {
        // пропускаем абонентов чужого РЭС
        if (dbOne("Select count(*) from ".CURRENT_RES."_main as main,".CURRENT_RES."_dom as dom WHERE  main.kn='{$kn}' and main.nom='{$nom}' and (main.dlt = 0 or main.dlt is NULL) and main.archive = 0 and dom.id=main.id_dom ".$res2)>0) {
         } else
         { continue;
         }
         //pr('3 - '.$kn.$nom, false);
        //}
        // пропускаем архивных и удаленных абонентов
        //if (dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_main WHERE kn='{$kn}' and nom='{$nom}' and (archive=1 or dlt=2)")>0) continue;
        if (dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_main WHERE kn='{$kn}' and nom='{$nom}' and ((archive=1 and dlt=0) or (dlt=2))")>0) continue; // SPP
        //pr('4 - '.$kn.$nom, false);
        $uch = $row_obh['uchobh'];
        if (check_switch_off($kn,$nom)) continue;
        $pok_obh = $row_obh['ob_pok'];
        $date_obh = $row_obh['dateobh_shot'];
        //pr('5 - '.$kn.$nom, false);

        // пропускаем дубликаты - берем только самые последние обходы
        $knnomuch =$row_obh['knobh'].'-'.$row_obh['nomobh'].'-'.$row_obh['uchobh'];
        if (in_array($knnomuch,$knnomuch_array)) continue;
        $knnomuch_array[] = $knnomuch;

        //pr('6 - '.$kn.$nom, false);

        $imes = date('m.Y'.strtotime($date_obh));
        $kvit_array = GetKvit($kn,$nom,$uch,false);

        $last_kvit_date = '';
        $last_kvit_pok = '';
        $last_kvit_oshkv = 1;
	$mt = GetTarif($kn, $nom, 1);
	$kvits = GetKvit($kn, $nom, 1, true);
        $is_dolg = 0;

        foreach ($kvit_array as $kvit) {
            if (date(DATE_FORMAT_YMD, strtotime('01.'.$kvit['imeskv'])) <= date(DATE_FORMAT_YMD, strtotime($date_obh))) {
                  $pokkv=$kvit['pokaz']*1;
                  $pokob=$pok_obh*1;
		          $pokf1=pow(10,mb_strlen($pok_obh));
		          if ($pokob<5000) {
			         if ($pokkv>($pokf1-2000) and $pokob<2000) {
				        $pokob=$pokob+$pokf1;
			         }
	               }
		        elseif ($pokkv<5000) {
			     if ($pokob>($pokf1-2000) and $pokkv<2000) {
				    $pokkv=$pokkv+$pokf1;
			     }
		        }
                $dolg = $pokob - ($pokkv  + $raznica);
                $last_kvit_date = $kvit['datekv'];
                $last_kvit_pok = intval($kvit['pokaz']);
                $last_kvit_oshkv = intval($kvit['oshkv']);
                break;
            }//if
        }//foreach

        //if ( ($dolg > 0) && ($dolg < 8000) && (date(DATE_FORMAT_YMD,strtotime($last_kvit_date)) <= date(DATE_FORMAT_YMD,strtotime($date_end)))){
            if ( ($dolg > 0) && (date(DATE_FORMAT_YMD,strtotime($last_kvit_date)) <= date(DATE_FORMAT_YMD,strtotime($date_end)))){
            // ПРОВЕРКА НА СМЕНУ СЕТЧИКА В ПЕРИОД С ПОСЛЕДНЕЙ ОПЛАТЫ ДО КОНЦА ПОCЛЕДНЕЙ ОПЛАТЫ
            // Если была смена счетчика - продолжаем, если НЕТ - НЕ ДОЛЖНИК
            if (dbOne("Select COUNT(*) from ".CURRENT_RES."_mainsc where kn='$kn' and nom = '$nom' and dateust >= '$last_kvit_date' ", 'int')) {
                if($last_kvit_oshkv == 9 || $last_kvit_oshkv == 8)
                    $is_dolg = 1;
                else
                    $is_dolg = 0;
            }
            else
                $is_dolg = 1;
        }
        else $is_dolg = 0;

        if ($is_dolg){
            $nomr = dbOne("SELECT nomr FROM ".CURRENT_RES."_main WHERE kn = '$kn' and nom = '$nom'");
            if (mb_strlen($nomr)>1){
                $nomr = implode(',',explode(' ',trim(str_replace(',',' ',$nomr))));
                $temp = dbFetchArray(dbQuery("SELECT simv FROM _spr_nomr WHERE id in (-1,$nomr)"));
                $nomr = '';
                foreach($temp as $value)
                    $nomr .= $value['simv'].', ';
            }
            else $nomr = '';

            $fio = get_abonent_name($kn,$nom);
            $fio = implode(' ',$fio);
            $output_array[] = array(
            'kn'     => $kn,
            'nom'    => $nom,
            'uch'    => $uch,
            'nomr'    => $nomr,
            'knnomuch'    => $knnomuch,
            'fio'    => $fio,
            'address'    => get_address($kn,$nom),
            'last_kvit_date'     => $last_kvit_date,
            'last_kvit_pok'        => $last_kvit_pok*1,
            'last_obhod_date'    => $date_obh,
            'last_obhod_pok'    => $pok_obh*1,
            'dolg_kvt'    => $dolg + $raznica,
            'dolg_rub'    => kvt_from_summ( ($dolg + $raznica),$kvits[0]['fulltar'],$kvits[0]['tarif'],$kvits[0]['norma'])

            );
            if (get_is_from_city($kn,$nom)) {
                $dolg_kvt_city += $dolg + $raznica;
                $dolg_rub_city += kvt_from_summ( ($dolg + $raznica),$kvits[0]['fulltar'],$kvits[0]['tarif'],$kvits[0]['norma']);

            }
            else {
                $dolg_kvt_selo += $dolg + $raznica;
                $dolg_rub_selo += kvt_from_summ( ($dolg + $raznica),$kvits[0]['fulltar'],$kvits[0]['tarif'],$kvits[0]['norma']);
            }
            if (get_abonent_res($kn,$nom)==2) {
                $dolg_kvt_selres += $dolg + $raznica;
                $dolg_rub_selres += kvt_from_summ( ($dolg + $raznica),$kvits[0]['fulltar'],$kvits[0]['tarif'],$kvits[0]['norma']);
            }
        }
    }

    if (count($output_array)){
        echo "<br>ДОЛЖНИКОВ - ".count($output_array)." чел.<br>";
        uasort($output_array, 'f_04_sort_by_knnomuch');
    }
    else{
        echo "<br>НЕТ ДОЛЖНИКОВ<br>";
        exit();
    }
    /*************************************************************************************/
    /*                  ПИШЕМ В EXCEL, Читаем стиль шапки                                */
    /*************************************************************************************/
    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

    $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."FORMA_4_template.xls");
    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->getActiveSheet()->setTitle(CURRENT_RES);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);

    $key2 = 11;

    /***************************************************************************************************************************/
    /*                                                Write data                                                               */
    /***************************************************************************************************************************/
    $key2 -= 1;
    foreach ($output_array as $key => $str){
        $key2 += 1;
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$str['kn'].'-'.$str['nom']);
        $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$str['nomr']);
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['fio']);

        if (mb_strlen(trim($str['address']))>37) $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow("D$key2")->getFont()->setName('Arial Narrow');
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$str['address']);
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$str['last_kvit_date']);
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['last_kvit_pok']);
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['last_obhod_date']);
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['last_obhod_pok']);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['dolg_kvt']);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['dolg_rub']);
    }
    $objPHPExcel->getActiveSheet()->getStyle("A11:D$key2")->applyFromArray($style_a_9_l_b);
    $objPHPExcel->getActiveSheet()->getStyle("E11:J$key2")->applyFromArray($style_a_9_r_b);
    $key3 = $key2;

    /***********************************************************************/
    /*                     Пишем ИТОГО                                     */
    /***********************************************************************/
    $key2 += 1;
    $objPHPExcel->getActiveSheet()->mergeCells("H$key2:I$key2");
    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",'кВтч');
    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",'Руб.');

    $key2 += 1;
    $objPHPExcel->getActiveSheet()->mergeCells("A$key2:F$key2");
    $objPHPExcel->getActiveSheet()->getStyle("A$key2:F$key2")->applyFromArray($style_a_9_c_b);
    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'Всего на дату обхода:');

    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'ИТОГО:');
    $objPHPExcel->getActiveSheet()->mergeCells("H$key2:I$key2");
    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$dolg_kvt_city+$dolg_kvt_selo);
    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$dolg_rub_city+$dolg_rub_selo);

    $key2 += 1;
    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'Город:');
    $objPHPExcel->getActiveSheet()->mergeCells("H$key2:I$key2");
    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$dolg_kvt_city);
    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$dolg_rub_city);

    $key2 += 1;
    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'Село:');
    $objPHPExcel->getActiveSheet()->mergeCells("H$key2:I$key2");
    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$dolg_kvt_selo);
    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$dolg_rub_selo);

    if (CURRENT_RES == "BRE") {
        $key2 += 1;
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'Гор.РЭС:');
        $objPHPExcel->getActiveSheet()->mergeCells("H$key2:I$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$dolg_kvt_city+$dolg_kvt_selo-$dolg_kvt_selres);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$dolg_rub_city+$dolg_rub_selo-$dolg_rub_selres);
        $key2 += 1;
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'Сел.РЭС:');
        $objPHPExcel->getActiveSheet()->mergeCells("H$key2:I$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$dolg_kvt_selres);
        $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$dolg_rub_selres);
    }

    $objPHPExcel->getActiveSheet()->getStyle("G$key3:J$key2")->applyFromArray($style_a_9_r_b);

    if ($key2) $key2 = $key2 + 2;
    else $key2 = 12;




// Add conditional formatting
//echo date('H:i:s') , " Add conditional formatting" , PHP_EOL;
$objConditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
$objConditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
$objConditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_BETWEEN);
$objConditional1->addCondition(200);
$objConditional1->addCondition(400);
$objConditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
$objConditional1->getStyle()->getFont()->setBold(true);

$objConditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
$objConditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
$objConditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_BETWEEN);
$objConditional2->addCondition('400');
$objConditional2->addCondition('800');
$objConditional2->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
$objConditional2->getStyle()->getFont()->setBold(true);

$objConditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
$objConditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
$objConditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
$objConditional3->addCondition('200');
$objConditional3->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
$objConditional3->getStyle()->getFont()->setBold(true);

$objConditional4 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
$objConditional4->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
$objConditional4->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
$objConditional4->addCondition('800');
$objConditional4->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
$objConditional4->getStyle()->getFont()->setBold(true);

$conditionalStyles = $objPHPExcel->getActiveSheet()->getStyle('J11')->getConditionalStyles();
array_push($conditionalStyles, $objConditional1);
array_push($conditionalStyles, $objConditional2);
array_push($conditionalStyles, $objConditional3);
array_push($conditionalStyles, $objConditional4);
$objPHPExcel->getActiveSheet()->getStyle('J11')->setConditionalStyles($conditionalStyles);

$objPHPExcel->getActiveSheet()->duplicateConditionalStyle($objPHPExcel->getActiveSheet()->getStyle('I11')->getConditionalStyles(),"I12:I$key2");

    Write_report("Форма_4C", $objPHPExcel, $date_beg, $date_end, '', $key2, 0);
    echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , PHP_EOL;
?>
