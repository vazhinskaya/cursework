<?php
  $time_start = getmicrotime();

  $res_eng = array();
  $res_rus = array();
  $res = dbQuery("SELECT type_res, knaim FROM _res
                  WHERE id_fes=(select id from _fes where fes='".$_COOKIE['userfes']."')
                  GROUP BY knaim, type_res");
  while ($res_base = dbFetchAssoc($res)) {
      $res_eng[]=$res_base['type_res'];
      $res_rus[]=$res_base['knaim'];
  }
  $res_rus[]="И Т О Г О";

  $res0 = array();
  $res1 = array();
  $resr = get_input('nres','int');
  $idres2 = 0;
  if ($resr == 0) {
    //  Весь РЭС
     $res2 = "1=1";
     for($k=0; $k < count($res_eng); $k++)
       {
           $res0[] = $res_eng[$k];
           $res1[] = $res_rus[$k];
       }

     $res1[] = $res_rus[count($res_rus)-1];
  }
  else {
    //  Только выбранный под-РЭС (Городской,Сельский)
     $res2 = "dom.id_res=".$resr;
     $res = dbQuery("select res, type_res, knaim FROM _res where id = ".$resr);
     $res_base = dbFetchAssoc($res);
     $res0[] = $res_base['type_res'];
     $res1[] = $res_base['knaim'];
     $nres2=$res_base['res'];
     $res = dbQuery("select * FROM _res where type_res = '".$res0[0]."' order by id");
     $res_base = dbFetchAssoc($res);
     $idres2=$res_base['id'];
  }
  $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
  $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."Common_report.xlsx");

  $date_beg = dateYMD(get_input('common_report_dateb'));
  $date_end = dateYMD(get_input('common_report_datee'));
  $kvt_recount = get_input('kvt_recount','int');
pr($kvt_recount);
  if ($date_beg > $date_end) {
    $errors[] = 'Даты начала и конца расчетного периода не указаны или указаны неверно';
  }
  if (count($errors)) {
    $smarty->assign('label_error',1);
    $smarty->assign('errors',$errors);
    $smarty->display('general_errors.html');
    exit();
  }
  else {
    $row1 = 2;
    $row2 = 2;
    $row_res = 'G';
    foreach($res0 as $cur_res) {
      // Вставка для пересчета KVTKV
      if ($kvt_recount == 1) {
        pr('gg');
        $sql = "select ID, KNKV, NOMKV, UCHKV, IMESKV, SUMKV, PENKV from ".$cur_res."_kvit where oshkv<>'8' and oshkv<>'9' and (kvtkv is Null or kvtkv=0) and NPACHKV in (select NPACHKA from ".$cur_res."_pachka where dat_form >= '2020.01.01')";
   /*     $sql = "select ID, KNKV, NOMKV, UCHKV, IMESKV, SUMKV, PENKV from ".$cur_res."_kvit where oshkv<>'8' and oshkv<>'9' and NPACHKV in (select npachka from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)";*/
        $res = dbQuery($sql);
        $rows = dbFetchArray($res);
        for($k=0; $k<$res->RowCount(); $k++) {
          $kvt = 0;
          $kvt = GetKvitOne($rows[$k]["KNKV"], $rows[$k]["NOMKV"], $rows[$k]["UCHKV"], $rows[$k]["IMESKV"], $rows[$k]["SUMKV"]-$rows[$k]["PENKV"]);
          $sql = "update ".$cur_res."_kvit set KVTKV=".$kvt." where id=".$rows[$k]["ID"];
          $res__ = dbQuery($sql);
        }
      }

      $output_array = array();
      $output_array = dbFetchArray(dbQuery("
        SELECT
        dom.id_res as id_res, res.knaim as res, main.archive as archive,
        CASE
          WHEN (tip_np.tip_np='Хутор' or tip_np.tip_np='Деревня' or tip_np.tip_np='Агрогородок' or tip_np.tip_np='Поселок' or tip_np.tip_np='Станция') THEN 'Село'
          ELSE tip_np.tip_np
        END as tipnp,
        tip_sc.label as faza,
        CASE
          WHEN mainsc.pokusc3<>'' and (mainsc.pokssc3='' or mainsc.pokssc3 is NULL) THEN 3
          WHEN mainsc.pokusc2<>'' and (mainsc.pokssc2='' or mainsc.pokssc2 is NULL) THEN 2
          ELSE 1
        END as chislo_tar,
        tarhist.idt as idt,
        CASE
          WHEN dom.askue>0 THEN 1
          ELSE 0
        END as is_askue,
        vidtar.vidtar as vidtar,
        COUNT(main.id) as kolvo,
        CASE
          WHEN main.datadog>'2006-01-01' THEN 1
          ELSE 0
        END as datadogovora,
        SUBSTR(tip_postr.postr,1,1) as postroika
        FROM ".$cur_res."_main as main
        LEFT JOIN ".$cur_res."_dom as dom on dom.id=main.id_dom
        LEFT JOIN _res as res on dom.id_res=res.id
        LEFT JOIN _tip_postr as tip_postr on tip_postr.id=dom.id_postr
        LEFT JOIN ".$cur_res."_street as street on street.id=dom.id_ul
        LEFT JOIN ".$cur_res."_np as np on np.id=street.id_np
        LEFT JOIN _tip_np as tip_np on tip_np.id=np.id_tip
        LEFT JOIN ".$cur_res."_mainsc as mainsc on mainsc.kn=main.kn and mainsc.nom=main.nom
        LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on tarhist.kn=main.kn and tarhist.nom=main.nom
        LEFT JOIN _vidtar as vidtar on tarhist.idt=vidtar.id
        LEFT JOIN _tip_sc as tip_sc on tip_sc.id=mainsc.ts
        WHERE ".$res2." and main.dlt=0 and mainsc.dlt=0 and tarhist.dlt=0
         and
          tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.kn = main.kn and a.nom=main.nom and a.dlt=0 ORDER BY a.ddate desc limit 1)
         and mainsc.dateust <= '$date_end' and (mainsc.datesn >= '$date_end' or mainsc.datesn is NULL)
        GROUP BY id_res, archive, tipnp, faza, chislo_tar, idt, is_askue, datadogovora, postroika
        ORDER BY id_res, archive, tipnp, faza, chislo_tar, idt, is_askue, datadogovora, postroika
      "));

      //записываем название РЭСа в файл:
      $objPHPExcel->setActiveSheetIndex(2);
      $objPHPExcel->getActiveSheet()->setCellValue($row_res.'8', $output_array[1]['res']);
      $row_res = $row_res++;
      $row_res = $row_res++;
      //записываем первую строку в файл:
      $objPHPExcel->setActiveSheetIndex(0);
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ИД РЭС');
      $objPHPExcel->getActiveSheet()->setCellValue('B1', 'РЭС');
      $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Архивный');
      $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Тип НП');
      $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Фаза сч.');
      $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Число тар.');
      $objPHPExcel->getActiveSheet()->setCellValue('G1', 'ИД тар.');
      $objPHPExcel->getActiveSheet()->setCellValue('H1', 'ИД льг.');
      $objPHPExcel->getActiveSheet()->setCellValue('I1', 'АСКУЭ');
      $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Вид тар.');
      $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Вид льг.');
      $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Кол-во абон.');
      $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Дата дог.');
      $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Тип постройки');
      //записываем данные в файл:
      foreach ($output_array as $col) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row1, $col['id_res']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row1, $col['res']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row1, $col['archive']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row1, $col['tipnp']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$row1, $col['faza']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$row1, $col['chislo_tar']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$row1, $col['idt']);
//        $objPHPExcel->getActiveSheet()->setCellValue('H'.$row1, $col['idl']);
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$row1, $col['is_askue']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$row1, $col['vidtar']);
//        $objPHPExcel->getActiveSheet()->setCellValue('K'.$row1, $col['vidlg']);
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$row1, $col['kolvo']);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$row1, $col['datadogovora']);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$row1, $col['postroika']);
        $row1++;
      }



      //  Добавляем сведения из базы квитанций за месяц
      $output_array = array();
      $output_array = dbQuery("
        SELECT
        dom.id_res as id_res, res.knaim as res, main.archive as archive,
        CASE
          WHEN (tip_np.tip_np='Хутор' or tip_np.tip_np='Деревня' or tip_np.tip_np='Агрогородок' or tip_np.tip_np='Поселок' or tip_np.tip_np='Станция') THEN 'Село'
          ELSE tip_np.tip_np
        END as tipnp,
        tip_sc.label as faza,
        CASE
          WHEN mainsc.pokusc3<>'' and (mainsc.pokssc3='' or mainsc.pokssc3 is NULL) THEN 3
          WHEN mainsc.pokusc2<>'' and (mainsc.pokssc2='' or mainsc.pokssc2 is NULL) THEN 2
          ELSE 1
        END as chislo_tar,
        kvit.uchkv as uchkv,
        tarhist.idt as idt,
        CASE
          WHEN dom.askue>0 THEN 1
          ELSE 0
        END as is_askue,
        vidtar.vidtar as vidtar,
        round(SUM(kvit.sumkv),2) as sumkv, round(SUM(kvit.sumper),2) as sumper,
        round(SUM(kvit.penkv),2) as penkv, round(SUM(kvit.kvtkv),2) as kvtkv,
        COUNT(main.id) as kolvo
        FROM ".$cur_res."_main as main
        LEFT JOIN ".$cur_res."_kvit as kvit on main.kn=kvit.knkv and main.nom=kvit.nomkv
        LEFT JOIN ".$cur_res."_dom as dom on dom.id=main.id_dom
        LEFT JOIN _res as res on dom.id_res=res.id
        LEFT JOIN ".$cur_res."_street as street on street.id=dom.id_ul
        LEFT JOIN ".$cur_res."_np as np on np.id=street.id_np
        LEFT JOIN _tip_np as tip_np on tip_np.id=np.id_tip
        LEFT JOIN ".$cur_res."_mainsc as mainsc on mainsc.kn=main.kn and mainsc.nom=main.nom
        LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on tarhist.kn=kvit.knkv and tarhist.nom=kvit.nomkv and tarhist.uch=kvit.uchkv
        LEFT JOIN _vidtar as vidtar on tarhist.idt=vidtar.id
        LEFT JOIN _tip_sc as tip_sc on tip_sc.id=mainsc.ts
        WHERE ".$res2." and main.dlt=0 and mainsc.dlt=0 and tarhist.dlt=0 and tarhist.uch=kvit.uchkv and
            tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.kn = main.kn and a.nom=main.nom and a.uch=tarhist.uch and a.dlt=0 ORDER BY a.ddate desc limit 1)
          and mainsc.dateust <= '$date_end' and (mainsc.datesn >= '$date_end' or mainsc.datesn is NULL)
          and kvit.dlt=0
          and kvit.npachkv in (select npachka from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)
        GROUP BY id_res, archive, tipnp, faza, chislo_tar, uchkv, idt, is_askue
        ORDER BY id_res, archive, tipnp, faza, chislo_tar, uchkv, idt, is_askue
      ");

      //записываем данные в файл:
      $objPHPExcel->setActiveSheetIndex(1);
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ИД РЭС');
      $objPHPExcel->getActiveSheet()->setCellValue('B1', 'РЭС');
      $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Архивный');
      $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Тип НП');
      $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Фаза сч.');
      $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Число тар.');
      $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Номер учета');
      $objPHPExcel->getActiveSheet()->setCellValue('H1', 'ИД тар.');
      $objPHPExcel->getActiveSheet()->setCellValue('I1', 'ИД льг.');
      $objPHPExcel->getActiveSheet()->setCellValue('J1', 'АСКУЭ');
      $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Вид тар.');
      $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Вид льг.');
      $objPHPExcel->getActiveSheet()->setCellValue('M1', 'Сумма квит.');
      $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Сумма к переч.');
      $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Сумма пени');
      $objPHPExcel->getActiveSheet()->setCellValue('P1', 'Кол-во кВт');
      $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Кол-во квитанций');
      $objPHPExcel->getActiveSheet()->setCellValue('R1', 'Сумма квит. минус сумма пени');
      foreach ($output_array as $col) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row2, $col['id_res']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row2, $col['res']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row2, $col['archive']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row2, $col['tipnp']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$row2, $col['faza']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$row2, $col['chislo_tar']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$row2, $col['uchkv']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$row2, $col['idt']);
//        $objPHPExcel->getActiveSheet()->setCellValue('I'.$row2, $col['idl']);
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$row2, $col['is_askue']);
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$row2, $col['vidtar']);
//        $objPHPExcel->getActiveSheet()->setCellValue('L'.$row2, $col['vidlg']);
        $objPHPExcel->getActiveSheet()->setCellValue('M'.$row2, $col['sumkv']);
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$row2, $col['sumper']);
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$row2, $col['penkv']);
        $objPHPExcel->getActiveSheet()->setCellValue('P'.$row2, $col['kvtkv']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row2, $col['kolvo']);
        $objPHPExcel->getActiveSheet()->setCellValue('R'.$row2, $col['sumkv']-$col['penkv']);
        $row2++;
      }



      //  Добавляем сведения из базы квитанций за месяц по полному возмещению
      $output_array = array();
      $output_array = dbQuery("
        SELECT
        CASE
          WHEN position('36' in main.nomr)>0 THEN 1
          WHEN position('53' in main.nomr)>0 THEN 3
          WHEN (dom.id_postr=66 or main.postr=66) THEN 4
          WHEN position('52' in main.nomr)>0 THEN 5
          WHEN position('59' in main.nomr)>0 THEN 6
        END as priznak,
        vidtar.vidtar as vidtar,
        round(SUM(kvit.sumkv),2) as sumkv, round(SUM(kvit.sumper),2) as sumper,
        round(SUM(kvit.penkv),2) as penkv, round(SUM(kvit.kvtkv),2) as kvtkv,
        COUNT(main.id) as kolvo
        FROM ".$cur_res."_main as main
        LEFT JOIN ".$cur_res."_kvit as kvit on main.kn=kvit.knkv and main.nom=kvit.nomkv
        LEFT JOIN ".$cur_res."_dom as dom on dom.id=main.id_dom
        LEFT JOIN _res as res on dom.id_res=res.id
        LEFT JOIN ".$cur_res."_street as street on street.id=dom.id_ul
        LEFT JOIN ".$cur_res."_np as np on np.id=street.id_np
        LEFT JOIN _tip_np as tip_np on tip_np.id=np.id_tip
        LEFT JOIN ".$cur_res."_mainsc as mainsc on mainsc.kn=main.kn and mainsc.nom=main.nom
        LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on tarhist.kn=kvit.knkv and tarhist.nom=kvit.nomkv and tarhist.uch=kvit.uchkv
        LEFT JOIN _vidtar as vidtar on tarhist.idt=vidtar.id
        LEFT JOIN _tip_sc as tip_sc on tip_sc.id=mainsc.ts
        WHERE ".$res2." and main.dlt=0 and mainsc.dlt=0 and tarhist.dlt=0 and tarhist.uch=kvit.uchkv
          and (tarhist.idt=31 or tarhist.idt=32 or tarhist.idt=33)
          and
            tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.kn = main.kn and a.nom=main.nom and a.uch=tarhist.uch and a.dlt=0 ORDER BY a.ddate desc limit 1)
          and mainsc.dateust <= '$date_end' and (mainsc.datesn >= '$date_end' or mainsc.datesn is NULL)
          and kvit.dlt=0
          and kvit.npachkv in (select npachka from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)
        GROUP BY priznak
        ORDER BY priznak
      ");
      // выбираем акты и услуги
      $output_array2 = dbQuery("
        SELECT
        round(SUM(kvitxvp.sumkv),2) as sumkv, round(SUM(kvitxvp.sumper),2) as sumper,
        round(SUM(kvitxvp.penkv),2) as penkv, round(SUM(kvitxvp.kvtkv),2) as kvtkv,
        COUNT(main.id) as kolvo
        FROM ".$cur_res."_main as main
        LEFT JOIN ".$cur_res."_kvitxvp as kvitxvp on main.kn=kvitxvp.knkv and main.nom=kvitxvp.nomkv
        LEFT JOIN ".$cur_res."_dom as dom on dom.id=main.id_dom
        LEFT JOIN _res as res on dom.id_res=res.id
        LEFT JOIN ".$cur_res."_street as street on street.id=dom.id_ul
        LEFT JOIN ".$cur_res."_np as np on np.id=street.id_np
        LEFT JOIN _tip_np as tip_np on tip_np.id=np.id_tip
        LEFT JOIN ".$cur_res."_mainsc as mainsc on mainsc.kn=main.kn and mainsc.nom=main.nom
        LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on tarhist.kn=kvitxvp.knkv and tarhist.nom=kvitxvp.nomkv and tarhist.uch=kvitxvp.uchkv
        LEFT JOIN _vidtar as vidtar on tarhist.idt=vidtar.id
        LEFT JOIN _tip_sc as tip_sc on tip_sc.id=mainsc.ts
        WHERE ".$res2." and main.dlt=0 and mainsc.dlt=0 and tarhist.dlt=0 and tarhist.uch=kvitxvp.uchkv
          and (tarhist.idt=31 or tarhist.idt=32 or tarhist.idt=33)
          and
            tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.kn = main.kn and a.nom=main.nom and a.uch=tarhist.uch and a.dlt=0 ORDER BY a.ddate desc limit 1)
          and mainsc.dateust <= '$date_end' and (mainsc.datesn >= '$date_end' or mainsc.datesn is NULL)
          and kvitxvp.npachkv in (select npachka from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)
          and kvitxvp.typekv>1
      ");

      //записываем данные в файл:
      $objPHPExcel->setActiveSheetIndex(10);
      foreach ($output_array as $col) {
        $row3 = 46+$col['priznak'];
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row3, $col['kolvo']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row3, $col['kvtkv']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$row3, $col['sumkv']-$col['penkv']);
      }
      foreach ($output_array2 as $col) {
        $row3 = 48;
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row3, $col['kolvo']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row3, $col['kvtkv']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$row3, $col['sumkv']-$col['penkv']);
      }



    // здесь еще акты

    }
  }

  $objPHPExcel->setActiveSheetIndex(2);
  Write_report("Общий отчет", $objPHPExcel, preobrDate($date_beg), preobrDate($date_end),
   '', $key2, 0, true, 'Xlsx');
  echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";


/*
      //  Находим Общий и Непромышленный тариф на дату отчета
      $aTarif = dbOne("Select cena from _centar where ddate <= adddate('$date_beg', -1) and kod=1 order by ddate desc limit 1");
      $aNepromTarif = dbOne("Select cena from _centar where ddate <= adddate('$date_beg', -1) and kod=3 order by ddate desc limit 1");

      list($output_array_str['aXKV_M'],$output_array_str['pXKV_M']) = dbFetchRow(dbQuery("Select SUM(SUMKV)-SUM(PENKV), SUM(PENKV) from ".$cur_res."_xkvit  as k where k.dlt=0 and k.NPACHKV in (select NPACHKA from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)"));

      list($output_array_str['aPR_M'],$output_array_str['pPR_M'])  = dbFetchRow(dbQuery("Select SUM(SUMKV)-SUM(PENKV), SUM(PENKV) from ".$cur_res."_kvitvp as k where k.typekv = 8 and k.dlt=0 and k.NPACHKV in (select NPACHKA from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)"));

      list($output_array_str['aRES_M'],$output_array_str['kRES_M'],$output_array_str['pRES_M']) = dbFetchRow(dbQuery("Select SUM(SUMKV)-SUM(PENKV), SUM(KVTKV), SUM(PENKV) from ".$cur_res."_kvit as k where SUBSTRING(k.DETAILS,3,1)='2' and k.dlt=0 and k.NPACHKV in (select NPACHKA from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0)"));

      if ($resr == $idres2) {
        $output_array_str['aGOR_1']  += ($output_array_str['aXKV_M']);
        $output_array_str['aGOR_3']  += ($output_array_str['aPR_M']);
        $output_array_str['kGOR_1']  += ($output_array_str['aXKV_M']/$aTarif);
        $output_array_str['kGOR_3']  += ($output_array_str['aPR_M']/$aNepromTarif);
        $output_array_str['pGOR_1']  += ($output_array_str['pXKV_M']);
        $output_array_str['pGOR_3']  += ($output_array_str['pPR_M']);
      }

      //  Добавляем кВт по актам за месяц
      $query = "Select * from ".$cur_res."_kvitvp as k where (k.typekv=5 and k.dlt=0 and k.npachkv in (Select npachka from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and p.dlt = 0))";
      $res = dbQuery($query);
      while($row = dbFetchAssoc($res)) {
        if (get_abonent_res($row['knkv'], $row['nomkv']) == $resr) {
          if (get_is_from_city($row['knkv'], $row['nomkv'])) {
            $output_array_str['aGOR_3'] += floatval($row['sumkv']) - floatval($row['penkv']);
            $output_array_str['kGOR_3'] += intval($row['kvtkv']);
            $output_array_str['pGOR_3'] += floatval($row['penkv']);
          }
          else {
            $output_array_str['aSEL_3'] += floatval($row['sumkv']) - floatval($row['penkv']);
            $output_array_str['kSEL_3'] += intval($row['kvtkv']);
            $output_array_str['pSEL_3'] += floatval($row['penkv']);
            $output_array_str['aRES_M'] += floatval($row['sumkv']) - floatval($row['penkv']);
            $output_array_str['kRES_M'] += intval($row['kvtkv']);
            $output_array_str['pRES_M'] += floatval($row['penkv']);
          }
        }
      }




  if ($resr > 0) {
    $objPHPExcel->getActiveSheet()->setCellValue("A2",$nres2);
        if ($key == 0  and $resr == 0) {
          $key2 = 16;
          $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$str['kRES_M']);
          $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$str['aRES_M']);
          $objPHPExcel->getActiveSheet()->setCellValue("R$key2",$str['pRES_M']);
          $key_line = 13;

*/
?>
