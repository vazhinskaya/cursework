<?php
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

    $date_beg = get_input('f_08_dateb');
    $date_end = get_input('f_08_datee');
    $date_eed = date('d.m.Y',strtotime('-61 day',strtotime($date_end)));

    $date_2 = date('Y-m-d',strtotime('-2 month',strtotime($date_end)));
    $date_4 = date('Y-m-d',strtotime('-4 month',strtotime($date_end)));
    $date_7 = date('Y-m-d',strtotime('-7 month',strtotime($date_end)));
    $date_10 = date('Y-m-d',strtotime('-10 month',strtotime($date_end)));
    $date_g = date('Y-m-d',strtotime('-12 month',strtotime($date_end)));

    $kol_2 = 0;
    $kol_4 = 0;
    $kol_7 = 0;
    $kol_10 = 0;
    $kol_g = 0;

    $kot_2 = 0;
    $kot_4 = 0;
    $kot_7 = 0;
    $kot_10 = 0;
    $kot_g = 0;

    $kpr_2 = 0;
    $kpr_4 = 0;
    $kpr_7 = 0;
    $kpr_10 = 0;
    $kpr_g = 0;

    $temp_array = explode('.',$date_beg);
    if ( isset($temp_array[0]) && isset($temp_array[1]) && isset($temp_array[2])){
        if (!checkdate($temp_array[1],$temp_array[0],$temp_array[2]))
            $errors[] = 'Дата начала расчета не установлена либо некорректна';
    }
    else {
        $errors[] = 'Дата начала расчета не установлена либо некорректна';
    }
    $temp_array = explode('.',$date_end);
    if ( isset($temp_array[0]) && isset($temp_array[1]) && isset($temp_array[2])){
        if (!checkdate($temp_array[1],$temp_array[0],$temp_array[2]))
            $errors[] = 'Дата начала расчета не установлена либо некорректна';
    }
    else {
        $errors[] = 'Дата начала расчета не установлена либо некорректна';
    }
    if (
    date(DATE_FORMAT_YMD,strtotime(get_input('f_08_dateb')))
    <
    date(DATE_FORMAT_YMD,strtotime(get_input('f_08_datee')))
    ){
        $errors[] = 'Даты указаны неверно';
    }

    if (count($errors)){
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
        exit();
    }

    $knnomuch_array = array();
    $output_array = array();
    $query_main = "Select main.kn, main.nom, kvit.uchkv, kvit.datekv, kvit.pokkv, kvit.oshkv, main.fam, main.im, main.ot, main.fam+' '+main.im+' '+main.ot AS fio, main.archive, main.postr, main.nomr
                from ".CURRENT_RES."_main as main
        LEFT JOIN ".CURRENT_RES."_dom as dom on dom.id = main.id_dom, ".CURRENT_RES."_kvit as kvit
        WHERE (main.dlt is NULL or main.dlt = 0 ) and main.archive = 0 ".$res2." and main.kn=kvit.knkv and main.nom=kvit.nomkv and kvit.datekv<'".$date_eed."' and kvit.id = (select id from ".CURRENT_RES."_kvit as k where main.kn=k.knkv and main.nom=k.nomkv and (k.dlt is NULL or k.dlt = 0) order by datekv desc,oshkv desc limit 1)";

    $res_main = dbQuery($query_main);
    while($row_main = dbFetchAssoc($res_main)) {
        $kn = $row_main['kn'];
        $nom = $row_main['nom'];
        $uch=1;
        $knnomuch =$kn.'-'.$nom.'-'.$uch;

        $last_kvit_date = $row_main['datekv'];
        $last_kvit_pok = $row_main['pokkv'];
        $last_kvit_oshkv = $row_main['oshkv'];
        $nomr = $row_main['nomr'];

        $last_tarif = '';
        $is_dolg = 0;

        $query_obh = "Select knobh, nomobh, uchobh, date_format(dateobh, '%d.%m.%Y') as dateobh_shot, ob_pok  from ".CURRENT_RES."_obhod where knobh='".$kn."' and nomobh='".$nom."' and uchobh='".$uch."' and ob_pok>'' ".DLTHIDE." order by knobh, nomobh, dateobh desc, id desc";
        $res_obh = dbQuery($query_obh);
        $row_obh = dbFetchAssoc($res_obh);
        $pok_obh = $row_obh['ob_pok'];
        $date_obh = $row_obh['dateobh_shot'];

        $tip_p = dbOne("Select postr from _tip_postr where id=".$row_main['postr']);


         $query_otkl = "SELECT date_format(dateot, '%d.%m.%Y') as dateot_short, id_pr FROM ".CURRENT_RES."_otkl as otkl WHERE (otkl.dlt = 0 or otkl.dlt is NULL) and otkl.kn='".$kn."' and otkl.nom='".$nom."' order by dateot desc limit 1";
         $res_otkl = dbQuery($query_otkl);
         $dateot = " ";

         if ($row_otkl = dbFetchAssoc($res_otkl)) {
          if ($row_otkl['id_pr']<16 or $row_otkl['id_pr']=24)
          {
            $dateot = $row_otkl['dateot_short'];
          }
         }
            $fio = trim( $row_main['fio'] );
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
            'oshkv'   => $last_kvit_oshkv,
            'last_obhod_date'    => $date_obh,
            'last_obhod_pok'    => $pok_obh*1,
            'postr'   => $tip_p,
            'dateot'   =>  $dateot,
            );
           $nomr = explode(' ',trim(str_replace(',',' ',$nomr)));
           $is_dom=0;
           for($k=0; $k < count($nomr); $k++) {
               if ($nomr[$k]==7) {$is_dom=1;}
           }
           if (date('Y-m-d',strtotime($last_kvit_date)) <= date('Y-m-d',strtotime($date_2)) and date('Y-m-d',strtotime($last_kvit_date)) > date('Y-m-d',strtotime($date_4)))
            {
               $kol_2 += 1;
               if ($dateot!=" ")
                 { $kot_2 +=1; }
               elseif ($row_main['postr']>=61 and $row_main['postr']!=67 or $is_dom==1)
                  { $kpr_2 +=1; }
            }
           elseif (date('Y-m-d',strtotime($last_kvit_date)) <= date('Y-m-d',strtotime($date_4)) and date('Y-m-d',strtotime($last_kvit_date)) > date('Y-m-d',strtotime($date_7)))
            {
               $kol_4 += 1;
               if ($dateot!=" ")
                 { $kot_4 +=1; }
               elseif ($row_main['postr']>=61 and $row_main['postr']!=67 or $is_dom==1)
                  { $kpr_4 +=1; }

            }
           elseif (date('Y-m-d',strtotime($last_kvit_date)) <= date('Y-m-d',strtotime($date_7)) and date('Y-m-d',strtotime($last_kvit_date)) > date('Y-m-d',strtotime($date_10)))
            {
               $kol_7 += 1;
               if ($dateot!=" ")
                 { $kot_7 +=1; }
               elseif ($row_main['postr']>=61 and $row_main['postr']!=67 or $is_dom==1)
                  { $kpr_7 +=1; }

            }
           elseif (date('Y-m-d',strtotime($last_kvit_date)) <= date('Y-m-d',strtotime($date_10)) and date('Y-m-d',strtotime($last_kvit_date)) > date('Y-m-d',strtotime($date_g)))
            {
               $kol_10 += 1;
               if ($dateot!=" ")
                 { $kot_10 +=1; }
               elseif ($row_main['postr']>=61 and $row_main['postr']!=67 or $is_dom==1)
                  { $kpr_10 +=1; }
            }
           elseif (date('Y-m-d',strtotime($last_kvit_date)) <= date('Y-m-d',strtotime($date_g)))
            {
               $kol_g += 1;
               if ($dateot!=" ")

                 { $kot_g +=1; }
               elseif ($row_main['postr']>=61 and $row_main['postr']!=67 or $is_dom==1)
                  { $kpr_g +=1; }
            }
    }

    if (count($output_array)) {
        echo "<br>НЕПЛАТЕЛЬЩИКОВ - ".count($output_array)." чел.<br>";
        uasort($output_array, function ($a,$b) {return strnatcasecmp($a['knnomuch'], $b['knnomuch']);});
    }
    else {
        echo "<br>НЕТ НЕПЛАТЕЛЬЩИКОВ<br>";
        exit();
    }
    /*************************************************************************************/
    /*                  ПИШЕМ В EXCEL, Читаем стиль шапки                                */
    /*************************************************************************************/
    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

    $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."FORMA_8_template.xls");
    $objPHPExcel->setActiveSheetIndex(0);


    /***************************************************************************************************************************/
    /*                                                Write data                                                               */
    /***************************************************************************************************************************/
        $key2 = 11;
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$kol_2+$kol_4+$kol_7+$kol_10+$kol_g);
        $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$kol_2);
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$kol_4);
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$kol_7);
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$kol_10);
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$kol_g);

        $key2 = 13;
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$kot_2+$kot_4+$kot_7+$kot_10+$kot_g);
        $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$kot_2);
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$kot_4);
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$kot_7);
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$kot_10);
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$kot_g);

        $key2 = 15;
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",($kol_2+$kol_4+$kol_7+$kol_10+$kol_g)-($kot_2+$kot_4+$kot_7+$kot_10+$kot_g)-($kpr_2+$kpr_4+$kpr_7+$kpr_10+$kpr_g));
        $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$kol_2-$kot_2-$kpr_2);
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$kol_4-$kot_4-$kpr_4);
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$kol_7-$kot_7-$kpr_7);
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$kol_10-$kot_10-$kpr_10);
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$kol_g-$kot_g-$kpr_g);

    $key2_2=10;
    $key2_4=10;
    $key2_7=10;
    $key2_10=10;
    $key2_g=10;

    foreach ($output_array as $key => $str){

           if (date('Y-m-d',strtotime($str['last_kvit_date'])) <= date('Y-m-d',strtotime($date_2)) and date('Y-m-d',strtotime($str['last_kvit_date'])) > date('Y-m-d',strtotime($date_4)))
            {
               $key2_2 += 1;
               $key2 = $key2_2;
               $objPHPExcel->setActiveSheetIndex(1);
            }
           elseif (date('Y-m-d',strtotime($str['last_kvit_date'])) <= date('Y-m-d',strtotime($date_4)) and date('Y-m-d',strtotime($str['last_kvit_date'])) > date('Y-m-d',strtotime($date_7)))
            {
               $key2_4 += 1;
               $key2 = $key2_4;
               $objPHPExcel->setActiveSheetIndex(2);
            }
           elseif (date('Y-m-d',strtotime($str['last_kvit_date'])) <= date('Y-m-d',strtotime($date_7)) and date('Y-m-d',strtotime($str['last_kvit_date'])) > date('Y-m-d',strtotime($date_10)))
            {
               $key2_7 += 1;
               $key2 = $key2_7;
               $objPHPExcel->setActiveSheetIndex(3);
            }
           elseif (date('Y-m-d',strtotime($str['last_kvit_date'])) <= date('Y-m-d',strtotime($date_10)) and date('Y-m-d',strtotime($str['last_kvit_date'])) > date('Y-m-d',strtotime($date_g)))
            {
               $key2_10 += 1;
               $key2 = $key2_10;
               $objPHPExcel->setActiveSheetIndex(4);
            }
           elseif (date('Y-m-d',strtotime($str['last_kvit_date'])) <= date('Y-m-d',strtotime($date_g)))
            {
               $key2_g += 1;
               $key2 = $key2_g;
               $objPHPExcel->setActiveSheetIndex(5);
            }


        $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$str['kn'].'-'.$str['nom']);
        $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$str['fio']);

        if (mb_strlen(trim($str['address']))>37) $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow("C$key2")->getFont()->setName('Arial Narrow');
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['address']);

        if ($str['oshkv']>='8')  $objPHPExcel->getActiveSheet()->setCellValue("D$key2",date('d.m.Y',strtotime($str['last_kvit_date']))."*");
        else $objPHPExcel->getActiveSheet()->setCellValue("D$key2",date('d.m.Y',strtotime($str['last_kvit_date'])));
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$str['last_kvit_pok']);
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['last_obhod_date']);
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['last_obhod_pok']);
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['postr']);
        $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['dateot']);
    }

    $key2 = 20;
    $objPHPExcel->setActiveSheetIndex(0);
    Write_report("Форма_8", $objPHPExcel, $date_beg, $date_end, '', $key2, 0, false);
    $objPHPExcel->setActiveSheetIndex(1);
    Write_report("Форма_8", $objPHPExcel, $date_beg, $date_end, '', $key2_2+3, 0, false);
    $objPHPExcel->setActiveSheetIndex(2);
    Write_report("Форма_8", $objPHPExcel, $date_beg, $date_end, '', $key2_4+3, 0, false);
    $objPHPExcel->setActiveSheetIndex(3);
    Write_report("Форма_8", $objPHPExcel, $date_beg, $date_end, '', $key2_7+3, 0, false);
    $objPHPExcel->setActiveSheetIndex(4);
    Write_report("Форма_8", $objPHPExcel, $date_beg, $date_end, '', $key2_10+3, 0, false);
    $objPHPExcel->setActiveSheetIndex(5);
    Write_report("Форма_8", $objPHPExcel, $date_beg, $date_end, '', $key2_g+3, 0);
    echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
?>