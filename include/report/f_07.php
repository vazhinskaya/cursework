<?php
    $time_start = getmicrotime();

    $output_array_pes = array();
    $output_array = array(
    'ob_gor' => 0,
    'ob_pgt' => 0,
    'ob_selo' => 0,
    'ob_itogo' => 0,
    'ob_gor_1f' => 0,
    'ob_pgt_1f' => 0,
    'ob_selo_1f' => 0,
    'ob_itogo_1f' => 0,
    'ob_gor_3f' => 0,
    'ob_pgt_3f' => 0,
    'ob_selo_3f' => 0,
    'ob_itogo_3f' => 0,
    'elpl_gor' => 0,
    'elpl_pgt' => 0,
    'elpl_selo' => 0,
    'elpl_itogo' => 0,
    'elpl_gor_1f' => 0,
    'elpl_pgt_1f' => 0,
    'elpl_selo_1f' => 0,
    'elpl_itogo_1f' => 0,
    'elpl_gor_3f' => 0,
    'elpl_pgt_3f' => 0,
    'elpl_selo_3f' => 0,
    'elpl_itogo_3f' => 0,
    'neprom_gor' => 0,
    'neprom_pgt' => 0,
    'neprom_selo' => 0,
    'neprom_itogo' => 0,
    'neprom_gor_1f' => 0,
    'neprom_pgt_1f' => 0,
    'neprom_selo_1f' => 0,
    'neprom_itogo_1f' => 0,
    'neprom_gor_3f' => 0,
    'neprom_pgt_3f' => 0,
    'neprom_selo_3f' => 0,
    'neprom_itogo_3f' => 0,
    'nagr_gor' => 0,
    'nagr_pgt' => 0,
    'nagr_selo' => 0,
    'nagr_itogo' => 0,
    'nagr_gor_1f' => 0,
    'nagr_pgt_1f' => 0,
    'nagr_selo_1f' => 0,
    'nagr_itogo_1f' => 0,
    'nagr_gor_3f' => 0,
    'nagr_pgt_3f' => 0,
    'nagr_selo_3f' => 0,
    'nagr_itogo_3f' => 0,
    'bezopl_gor' => 0,
    'bezopl_pgt' => 0,
    'bezopl_selo' => 0,
    'bezopl_itogo' => 0,
    'bezopl_gor_1f' => 0,
    'bezopl_pgt_1f' => 0,
    'bezopl_selo_1f' => 0,
    'bezopl_itogo_1f' => 0,
    'bezopl_gor_3f' => 0,
    'bezopl_pgt_3f' => 0,
    'bezopl_selo_3f' => 0,
    'bezopl_itogo_3f' => 0,
    'itogo_gor' => 0,
    'itogo_pgt' => 0,
    'itogo_selo' => 0,
    'itogo_itogo' => 0,
    'itogo_gor_1f' => 0,
    'itogo_pgt_1f' => 0,
    'itogo_selo_1f' => 0,
    'itogo_itogo_1f' => 0,
    'itogo_gor_3f' => 0,
    'itogo_pgt_3f' => 0,
    'itogo_selo_3f' => 0,
    'itogo_itogo_3f' => 0,

    'kol_gor' => 0,
    'kol_pgt' => 0,
    'kol_selo' => 0,
    'kol_itogo' => 0,
    'kol_bez_uch_gor' => 0,
    'kol_bez_uch_pgt' => 0,
    'kol_bez_uch_selo' => 0,
    'kol_bez_uch_itogo' => 0,
    'kol_energ_gor' => 0,
    'kol_energ_pgt' => 0,
    'kol_energ_selo' => 0,
    'kol_energ_itogo' => 0,
    'kol_otkl_avt_gor' => 0,
    'kol_otkl_avt_pgt' => 0,
    'kol_otkl_avt_selo' => 0,
    'kol_otkl_avt_itogo' => 0,
    'kol_otkl_v_sc_gor' => 0,
    'kol_otkl_v_sc_pgt' => 0,
    'kol_otkl_v_sc_selo' => 0,
    'kol_otkl_v_sc_itogo' => 0,
    'kol_otkl_jes_gor' => 0,
    'kol_otkl_jes_pgt' => 0,
    'kol_otkl_jes_selo' => 0,
    'kol_otkl_jes_itogo' => 0,
    'kol_otkl_front_gor' => 0,
    'kol_otkl_front_pgt' => 0,
    'kol_otkl_front_selo' => 0,
    'kol_otkl_front_itogo' => 0,
    'kol_otkl_vnutr_gor' => 0,
    'kol_otkl_vnutr_pgt' => 0,
    'kol_otkl_vnutr_selo' => 0,
    'kol_otkl_vnutr_itogo' => 0,
    'kol_otkl_izol_gor' => 0,
    'kol_otkl_izol_pgt' => 0,
    'kol_otkl_izol_selo' => 0,
    'kol_otkl_izol_itogo' => 0,
    'kol_otkl_opora_gor' => 0,
    'kol_otkl_opora_pgt' => 0,
    'kol_otkl_opora_selo' => 0,
    'kol_otkl_opora_itogo' => 0,
    'kol_otkl_provod_gor' => 0,
    'kol_otkl_provod_pgt' => 0,
    'kol_otkl_provod_selo' => 0,
    'kol_otkl_provod_itogo' => 0,
    'kol_ust_gor' => 0,
    'kol_ust_pgt' => 0,
    'kol_ust_selo' => 0,
    'kol_ust_gor_1f' => 0,
    'kol_ust_pgt_1f' => 0,
    'kol_ust_sel_1f' => 0,
    'kol_ust_gor_3f' => 0,
    'kol_ust_pgt_3f' => 0,
    'kol_ust_sel_3f' => 0,
    'kol_ust_itogo' => 0,
    'kol_ust_itogo_1f' => 0,
    'kol_ust_itogo_3f' => 0,
    'kol_zamen_gor' => 0,
    'kol_zamen_pgt' => 0,
    'kol_zamen_selo' => 0,
    'kol_zamen_gor_1f' => 0,
    'kol_zamen_pgt_1f' => 0,
    'kol_zamen_sel_1f' => 0,
    'kol_zamen_gor_3f' => 0,
    'kol_zamen_pgt_3f' => 0,
    'kol_zamen_sel_3f' => 0,
    'kol_zamen_itogo' => 0,
    'kol_zamen_itogo_1f' => 0,
    'kol_zamen_itogo_3f' => 0,
    'kol_zamen_pover_gor' => 0,
    'kol_zamen_pover_pgt' => 0,
    'kol_zamen_pover_selo' => 0,
    'kol_zamen_pover_gor_1f' => 0,
    'kol_zamen_pover_pgt_1f' => 0,
    'kol_zamen_pover_sel_1f' => 0,
    'kol_zamen_pover_gor_3f' => 0,
    'kol_zamen_pover_pgt_3f' => 0,
    'kol_zamen_pover_sel_3f' => 0,
    'kol_zamen_pover_itogo' => 0,
    'kol_zamen_pover_itogo_1f' => 0,
    'kol_zamen_pover_itogo_3f' => 0,
    );
    $output_itogo = $output_array;

    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."FORMA_7_template.xls");

    /***************************************************************************************************************/
    /*                                   Report exec                                                               */
    /***************************************************************************************************************/
    $date_beg = dateYMD(get_input('f_07_dateb'));
    $date_end = dateYMD(get_input('f_07_datee'));

    if ( $date_beg > $date_end) {
        $errors[] = 'Даты начала и конца расчетного периода не указаны или указаны неверно';
    }
    if (count($errors)){
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
        exit();
    }
    else{
  $time_start = getmicrotime();
  //      $res0 = array("BRE","JAB","MAL","KOB","KAM");
  //      $res1 = array("Брест","Жабинка","Малорита","Кобрин","Каменец","И Т О Г О");
  //
  //       $res1 = array($res1[array_search(CURRENT_RES,$res0)]);
  //       $res0 = array(CURRENT_RES);
  $res_eng = array();
  $res_rus = array();
  $res = dbQuery("select type_res,knaim FROM _res GROUP BY knaim,type_res");
  While ($res_base = dbFetchAssoc($res))
       {
           $res_eng[]=$res_base['type_res'];
           $res_rus[]=$res_base['knaim'];
       }
  $res_rus[]="П Э С";

  $res0 = array();
  $res1 = array();
  $resr = get_input('nres','int');
  $idres2=0;
  if ($resr==0) {
     $res2=" and 1=1 ";
     for($k=0; $k < count($res_eng); $k++)
       {
           $res0[] = $res_eng[$k];
           $res1[] = $res_rus[$k];
       }

     $res1[] = $res_rus[count($res_rus)-1];
   }
  else {
     $res2=" and (dom.id_res=".$resr.") ";
     $res = dbQuery("select res,type_res,knaim FROM _res where id=".$resr);
     $res_base = dbFetchAssoc($res);
     $res0[] = $res_base['type_res'];
     $res1[] = $res_base['knaim'];
     $nres2=$res_base['res'];
     $res = dbQuery("select * FROM _res where type_res='".$res0[0]."' order by id");
     $res_base = dbFetchAssoc($res);
     $idres2=$res_base['id'];
   }


       foreach($res0 as $key => $cur_res) {
            $output_cur = $output_array;



/***************************************************************************************************************/
/*        БЕРЕМ ВСЕ НЕОБХОДИМЫЕ ДАННЫЕ ОБ УЧЕТАХ,                                                              */
/*        ПРОБЕГАЕМ ПО ВСЕМ УЧЕТАМ и ПОДСЧИТЫВАЕМ СТАТИСТИКУ                                                   */
/***************************************************************************************************************/
          //$time_start = getmicrotime();
            $query = "SELECT
                            main.kn, main.nom,
                            tip_np.tip_np,
                            mainsc.ts,
                            vidtar.vidtar
                        FROM ".$cur_res."_main as main
                        LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                        LEFT JOIN ".$cur_res."_street as street on street.id = dom.id_ul
                        LEFT JOIN ".$cur_res."_np as np on np.id = street.id_np
                        LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
                        RIGHT JOIN ".$cur_res."_mainsc as mainsc on main.kn = mainsc.kn and main.nom = mainsc.nom
                        LEFT JOIN _tip_sc as tip_sc on mainsc.ts = tip_sc.id
                        LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on main.kn = tarhist.kn and main.nom = tarhist.nom
                        LEFT JOIN _vidtar as vidtar on tarhist.idt = vidtar.id
                        WHERE 1=1
                        and     (main.dlt is NULL or  main.dlt = 0 )
                        and     (main.archive = 0)
                        ".$res2."
                        and     (mainsc.dlt is NULL or mainsc.dlt = 0)
                        and     (mainsc.datesn is null)
                        and     (tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.kn = main.kn and a.nom = main.nom and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc limit 1))";

            $res = dbQuery($query);
            while($row = dbFetchAssoc($res)){
                if (mb_strpos($row['vidtar'], "бщий") !== false ){
                    if ($row['tip_np'] == "Город"){
                        if ( intval($row['ts']) < 2000) $output_cur['ob_gor_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['ob_gor_3f'] += 1;
                        $output_cur['ob_gor'] += 1;
                    }
                    elseif($row['tip_np'] == "ГП"){
                        if ( intval($row['ts']) < 2000) $output_cur['ob_pgt_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['ob_pgt_3f'] += 1;
                        $output_cur['ob_pgt'] += 1;
                    }
                    else{
                        if ( intval($row['ts']) < 2000) $output_cur['ob_selo_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['ob_selo_3f'] += 1;
                        $output_cur['ob_selo'] += 1;
                   }
                }
                elseif (mb_strpos($row['vidtar'], "плит") !== false ){
                    if ($row['tip_np'] == "Город"){
                        if ( intval($row['ts']) < 2000) $output_cur['elpl_gor_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['elpl_gor_3f'] += 1;
                        $output_cur['elpl_gor'] += 1;
                    }
                    elseif($row['tip_np'] == "ГП"){
                        if ( intval($row['ts']) < 2000) $output_cur['elpl_pgt_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['elpl_pgt_3f'] += 1;
                        $output_cur['elpl_pgt'] += 1;
                    }
                    else{
                        if ( intval($row['ts']) < 2000) $output_cur['elpl_selo_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['elpl_selo_3f'] += 1;
                        $output_cur['elpl_selo'] += 1;
                   }
                }
                elseif (mb_strpos($row['vidtar'], "возм") !== false ){
                    if ($row['tip_np'] == "Город"){
                        if ( intval($row['ts']) < 2000) $output_cur['neprom_gor_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['neprom_gor_3f'] += 1;
                        $output_cur['neprom_gor'] += 1;
                    }
                    elseif($row['tip_np'] == "ГП"){
                        if ( intval($row['ts']) < 2000) $output_cur['neprom_pgt_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['neprom_pgt_3f'] += 1;
                        $output_cur['neprom_pgt'] += 1;
                    }
                    else{
                        if ( intval($row['ts']) < 2000) $output_cur['neprom_selo_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['neprom_selo_3f'] += 1;
                        $output_cur['neprom_selo'] += 1;
                   }
                }
                elseif (mb_strpos($row['vidtar'], "Нагрев") !== false ){
                    if ($row['tip_np'] == "Город"){
                        if ( intval($row['ts']) < 2000) $output_cur['nagr_gor_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['nagr_gor_3f'] += 1;
                        $output_cur['nagr_gor'] += 1;
                    }
                    elseif($row['tip_np'] == "ГП"){
                        if ( intval($row['ts']) < 2000) $output_cur['nagr_pgt_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['nagr_pgt_3f'] += 1;
                        $output_cur['nagr_pgt'] += 1;
                    }
                    else{
                        if ( intval($row['ts']) < 2000) $output_cur['nagr_selo_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['nagr_selo_3f'] += 1;
                        $output_cur['nagr_selo'] += 1;
                   }
                }
                elseif (mb_strpos($row['vidtar'], "Без оплаты") !== false ){
                    if ($row['tip_np'] == "Город"){
                        if ( intval($row['ts']) < 2000) $output_cur['bezopl_gor_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['bezopl_gor_3f'] += 1;
                        $output_cur['bezopl_gor'] += 1;
                    }
                    elseif($row['tip_np'] == "ГП"){
                        if ( intval($row['ts']) < 2000) $output_cur['bezopl_pgt_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['bezopl_pgt_3f'] += 1;
                        $output_cur['bezopl_pgt'] += 1;
                    }
                    else{
                        if ( intval($row['ts']) < 2000) $output_cur['bezopl_selo_1f'] += 1;
                        elseif (intval($row['ts']>2999)) $output_cur['bezopl_selo_3f'] += 1;
                        $output_cur['bezopl_selo'] += 1;
                   }
                }
                else{
                    echo "<br>".$row['kn'].$row['nom']."<br>";
                }
            }
            // ИТОГО ПО 3 ЯЧЕЙКАМ: Город+ГП+Село
            $output_cur['ob_itogo_1f'] =  $output_cur['ob_gor_1f'] + $output_cur['ob_pgt_1f'] + $output_cur['ob_selo_1f'];
            $output_cur['ob_itogo_3f'] =  $output_cur['ob_gor_3f'] + $output_cur['ob_pgt_3f'] + $output_cur['ob_selo_3f'];
            $output_cur['elpl_itogo_1f'] =  $output_cur['elpl_gor_1f'] + $output_cur['elpl_pgt_1f'] + $output_cur['elpl_selo_1f'];
            $output_cur['elpl_itogo_3f'] =  $output_cur['elpl_gor_3f'] + $output_cur['elpl_pgt_3f'] + $output_cur['elpl_selo_3f'];
            $output_cur['neprom_itogo_1f'] =  $output_cur['neprom_gor_1f'] + $output_cur['neprom_pgt_1f'] + $output_cur['neprom_selo_1f'];
            $output_cur['neprom_itogo_3f'] =  $output_cur['neprom_gor_3f'] + $output_cur['neprom_pgt_3f'] + $output_cur['neprom_selo_3f'];
            $output_cur['nagr_itogo_1f'] =  $output_cur['nagr_gor_1f'] + $output_cur['nagr_pgt_1f'] + $output_cur['nagr_selo_1f'];
            $output_cur['nagr_itogo_3f'] =  $output_cur['nagr_gor_3f'] + $output_cur['nagr_pgt_3f'] + $output_cur['nagr_selo_3f'];
            $output_cur['bezopl_itogo_1f'] =  $output_cur['bezopl_gor_1f'] + $output_cur['bezopl_pgt_1f'] + $output_cur['bezopl_selo_1f'];
            $output_cur['bezopl_itogo_3f'] =  $output_cur['bezopl_gor_3f'] + $output_cur['bezopl_pgt_3f'] + $output_cur['bezopl_selo_3f'];
            // ИТОГО ПО ТАРИФАМ И ФАЗАМ: Общий Эл.плиты  Полн.возм.  Нагрев  Без оплаты
            $output_cur['ob_itogo'] = $output_cur['ob_itogo_1f'] + $output_cur['ob_itogo_3f'];
            $output_cur['elpl_itogo'] = $output_cur['elpl_itogo_1f'] + $output_cur['elpl_itogo_3f'];
            $output_cur['neprom_itogo'] = $output_cur['neprom_itogo_1f'] + $output_cur['neprom_itogo_3f'];
            $output_cur['nagr_itogo'] = $output_cur['nagr_itogo_1f'] + $output_cur['nagr_itogo_3f'];
            $output_cur['bezopl_itogo'] = $output_cur['bezopl_itogo_1f'] + $output_cur['bezopl_itogo_3f'];
            // ИТОГО ИТОГО:
            $output_cur['itogo_gor'] = $output_cur['ob_gor']+$output_cur['elpl_gor']+$output_cur['neprom_gor']+$output_cur['nagr_gor']+$output_cur['bezopl_gor'];
            $output_cur['itogo_pgt'] = $output_cur['ob_pgt']+$output_cur['elpl_pgt']+$output_cur['neprom_pgt']+$output_cur['nagr_pgt']+$output_cur['bezopl_pgt'];
            $output_cur['itogo_selo'] = $output_cur['ob_selo']+$output_cur['elpl_selo']+$output_cur['neprom_selo']+$output_cur['nagr_selo']+$output_cur['bezopl_selo'];
            $output_cur['itogo_itogo'] = $output_cur['itogo_gor'] + $output_cur['itogo_pgt'] + $output_cur['itogo_selo'];

            $output_cur['itogo_gor_1f'] = $output_cur['ob_gor_1f']+$output_cur['elpl_gor_1f']+$output_cur['neprom_gor_1f']+$output_cur['nagr_gor_1f']+$output_cur['bezopl_gor_1f'];
            $output_cur['itogo_pgt_1f'] = $output_cur['ob_pgt_1f']+$output_cur['elpl_pgt_1f']+$output_cur['neprom_pgt_1f']+$output_cur['nagr_pgt_1f']+$output_cur['bezopl_pgt_1f'];
            $output_cur['itogo_selo_1f'] = $output_cur['ob_selo_1f']+$output_cur['elpl_selo_1f']+$output_cur['neprom_selo_1f']+$output_cur['nagr_selo_1f']+$output_cur['bezopl_selo_1f'];
            $output_cur['itogo_itogo_1f'] = $output_cur['itogo_gor_1f'] + $output_cur['itogo_pgt_1f'] + $output_cur['itogo_selo_1f'];

            $output_cur['itogo_gor_3f'] = $output_cur['ob_gor_3f']+$output_cur['elpl_gor_3f']+$output_cur['neprom_gor_3f']+$output_cur['nagr_gor_3f']+$output_cur['bezopl_gor_3f'];
            $output_cur['itogo_pgt_3f'] = $output_cur['ob_pgt_3f']+$output_cur['elpl_pgt_3f']+$output_cur['neprom_pgt_3f']+$output_cur['nagr_pgt_3f']+$output_cur['bezopl_pgt_3f'];
            $output_cur['itogo_selo_3f'] = $output_cur['ob_selo_3f']+$output_cur['elpl_selo_3f']+$output_cur['neprom_selo_3f']+$output_cur['nagr_selo_3f']+$output_cur['bezopl_selo_3f'];
            $output_cur['itogo_itogo_3f'] = $output_cur['itogo_gor_3f'] + $output_cur['itogo_pgt_3f'] + $output_cur['itogo_selo_3f'];

            /***************************************************************************************************************/
            /*            СТАТИСТИЧЕСКИЕ ДАННЫЕ ПО ОТКЛЮЧЕННЫМ УЧЕТАМ                                                      */
            /***************************************************************************************************************/
            //Количество абонентов
            $output_cur['kol_itogo'] = dbOne("
                SELECT COUNT(*) FROM ".$cur_res."_main as main
                LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                LEFT JOIN ".$cur_res."_street as street on street.id = dom.id_ul
                LEFT JOIN ".$cur_res."_np as np on np.id = street.id_np
                LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
                WHERE
                    (main.dlt is NULL or  main.dlt = 0)
                    and     (main.archive = 0)
                    ".$res2.""
                );
            //$output_cur['kol_gor'] = dbOne("SELECT COUNT(*)FROM ".$cur_res."_main as main LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom LEFT JOIN ".$cur_res."_street as street on street.id = dom.id_ul LEFT JOIN ".$cur_res."_np as np on np.id = street.id_np LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
            //    WHERE (main.dlt is NULL or  main.dlt = 0) and (tip_np.tip_np LIKE 'город')");
            //$output_cur['kol_pgt'] = dbOne("SELECT COUNT(*)FROM ".$cur_res."_main as main LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom LEFT JOIN ".$cur_res."_street as street on street.id = dom.id_ul LEFT JOIN ".$cur_res."_np as np on np.id = street.id_np LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
            //    WHERE (main.dlt is NULL or  main.dlt = 0) and (tip_np.tip_np LIKE 'гП')");
            //$output_cur['kol_selo'] =  $output_cur['kol_itogo'] - $output_cur['kol_gor'] - $output_cur['kol_pgt'];

            // в т.ч. без учетов
            $output_cur['kol_bez_uch_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_main as main , ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and (SELECT count(*) from ".$cur_res."_mainsc as mainsc WHERE main.kn=mainsc.kn and main.nom=mainsc.nom and mainsc.datesn is null)=0");
            //  в т.ч. энергетиков
            $temp = dbOne("SELECT id FROM _spr_nomr WHERE nomr LIKE '%энергосистем%'");
            $output_cur['kol_energ_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_main as main , ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2."  AND (nomr LIKE '$temp,%' or nomr LIKE '%,$temp,%' or nomr LIKE '%,$temp%') ");
            $vkl_id = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%вкл%'");;

            //  в т.ч. отключен автоматом
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%автомат%'");
            if ($temp)
                $output_cur['kol_otkl_avt_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) )  and (SELECT COUNT(*) FROM ".$cur_res."_main as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0 ");
            //  в т.ч. отключен в счетчике
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%в счетчик%'");
            if ($temp)
                $output_cur['kol_otkl_v_sc_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main  as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            // в т.ч. отключен ЖЭСом за неопл.кв.
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%ЖЭС%'");
            if ($temp)
                $output_cur['kol_otkl_jes_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main as  main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            //в т.ч. отключен на фронтоне
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%фронтон%'");
            if ($temp)
                $output_cur['kol_otkl_front_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main  as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            //  в т.ч. отключен от внутридомовой сети
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%внутридом%'");
            if ($temp)
                $output_cur['kol_otkl_vnutr_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main  as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            // в т.ч. отключен от изоляторов
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%изолятор%'");
            if ($temp)
                $output_cur['kol_otkl_izol_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main  as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            //  в т.ч. отключен от опоры
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%опор%'");
            if ($temp)
                $output_cur['kol_otkl_opora_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            //  в т.ч. отключен проводами
            $temp = dbOne("SELECT id FROM _spr_otkl WHERE otkl LIKE '%провода%'");
            if ($temp)
                $output_cur['kol_otkl_provod_itogo'] = dbOne("SELECT COUNT(*) FROM ".$cur_res."_otkl as otkl WHERE id_pr = {$temp} and (otkl.dlt = 0 or otkl.dlt is NULL) and 0 = (SELECT COUNT(*) from ".$cur_res."_otkl as a where a.kn = otkl.kn and a.nom = otkl.nom and a.id_pr = {$vkl_id} and a.dateot > otkl.dateot and (a.dlt = 0 or a.dlt is NULL) ) and (SELECT COUNT(*) FROM ".$cur_res."_main as main, ".$cur_res."_dom as dom  where (main.dlt is NULL or  main.dlt = 0)
                    and  (main.archive = 0) and main.id_dom = dom.id ".$res2." and main.kn = otkl.kn and main.nom = otkl.nom) > 0");
            /************************************************************************************************************/
            /*            УСТАНОВКА/ЗАМЕНА/С УЧЕТОМ ГОС ПОВЕРКИ                                                         */
            /*            Выбираем всех у кого были установлены счетчики в указанный период,                            */
            /*            по каждому: проверка стоял ли раньше счетчик, если НЕТ то "УСТАНОВЛЕНО" ,                     */
            /*            если ДА "ЗАМЕНЕНО", и тут же проверка на "ИСТЕКШИЙ СРОК ПОВЕРКИ"                              */
            /************************************************************************************************************/
            $query = "
                SELECT
                    mainsc.kn, mainsc.nom, mainsc.ts, mainsc.dateust,
                    tip_np.tip_np
                FROM ".$cur_res."_main as main
                LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                LEFT JOIN ".$cur_res."_street as street on street.id = dom.id_ul
                LEFT JOIN ".$cur_res."_np as np on np.id = street.id_np
                LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
                RIGHT JOIN ".$cur_res."_mainsc as mainsc on main.kn = mainsc.kn and main.nom = mainsc.nom
                WHERE 1=1
                and (main.dlt is NULL or  main.dlt = 0 )
                and (main.archive = 0)
                ".$res2."
                and (mainsc.dlt is NULL or mainsc.dlt = 0)
                and (mainsc.datesn is null)
                and (mainsc.dateust BETWEEN '{$date_beg}' AND '{$date_end}')
                order by kn, nom
            ";
            $res = dbQuery($query);
            while($row = dbFetchAssoc($res)){
                $query_temp = "SELECT mainsc.yearprov, mainsc.datesn, tip_sc.spov
                                FROM ".$cur_res."_mainsc as mainsc
                                LEFT JOIN _tip_sc as tip_sc on tip_sc.id = mainsc.ts
                                WHERE
                                    mainsc.dateust < '{$row['dateust']}'
                                    and mainsc.kn = '{$row['kn']}' and mainsc.nom = '{$row['nom']}'
                                    and (mainsc.dlt = 0 or mainsc.dlt is NULL)
                                ORDER BY mainsc.dateust DESC LIMIT 1";


                $res_temp = dbQuery($query_temp);
                if (dbRowsCount($res_temp)==0){
                    //ЗАПИСЕЙ НЕТ - НОВАЯ УСТАНОВКА СЧЕТЧИКА // ПРОВЕРКА ГОРОДА-ГП-СЕЛА
                    if ($row['tip_np'] == "Город") {
			    $output_cur['kol_ust_gor'] += 1;
	                    if ( intval($row['ts']) < 2000)     $output_cur['kol_ust_gor_1f'] += 1;
        	               elseif (intval($row['ts']>2999)) $output_cur['kol_ust_gor_3f'] += 1;
			}
                        elseif($row['tip_np'] == "ГП") {
			    $output_cur['kol_ust_pgt'] += 1;
	                    if ( intval($row['ts']) < 2000)     $output_cur['kol_ust_pgt_1f'] += 1;
        	               elseif (intval($row['ts']>2999)) $output_cur['kol_ust_pgt_3f'] += 1;
			}
                        else {
			    $output_cur['kol_ust_selo'] += 1;
	                    if ( intval($row['ts']) < 2000)     $output_cur['kol_ust_sel_1f'] += 1;
        	               elseif (intval($row['ts']>2999)) $output_cur['kol_ust_sel_3f'] += 1;
			}
                    if ( intval($row['ts']) < 2000)     $output_cur['kol_ust_itogo_1f'] += 1;
                       elseif (intval($row['ts']>2999)) $output_cur['kol_ust_itogo_3f'] += 1;
                    $output_cur['kol_ust_itogo'] += 1;

                }
                else {
                    //ЗАПИСИ ЕСТЬ - ЗАМЕНА СЧЕТЧИКА
                    if ($row['tip_np'] == "Город") {
			    $output_cur['kol_zamen_gor'] += 1;
	                    if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_gor_1f'] += 1;
        	               elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_gor_3f'] += 1;
			}
                        elseif($row['tip_np'] == "ГП") {
			    $output_cur['kol_zamen_pgt'] += 1;
	                    if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_pgt_1f'] += 1;
        	               elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_pgt_3f'] += 1;
			}
                        else {
                            $output_cur['kol_zamen_selo'] += 1;
	                    if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_sel_1f'] += 1;
        	               elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_sel_3f'] += 1;
			}
                    if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_itogo_1f'] += 1;
                       elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_itogo_3f'] += 1;
                    $output_cur['kol_zamen_itogo'] += 1;

                    $row_temp = dbFetchAssoc($res_temp);
                    // ИСТЕК СРОК ПОВЕРКИ НА МОМЕНТ СНЯТИЯ СЧЕТЧИКА
                    if ( date("Y",strtotime($row_temp['datesn'])) >= $row_temp['yearprov']+$row_temp['spov']){
                        if ($row['tip_np'] == "Город") {
				$output_cur['kol_zamen_pover_gor'] += 1;
                        	if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_pover_gor_1f'] += 1;
	                           elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_pover_gor_3f'] += 1;
			}
                        elseif($row['tip_np'] == "ГП") {
				$output_cur['kol_zamen_pover_pgt'] += 1;
                        	if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_pover_pgt_1f'] += 1;
	                           elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_pover_pgt_3f'] += 1;
			}
                        else {
	                        $output_cur['kol_zamen_pover_selo'] += 1;
                        	if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_pover_sel_1f'] += 1;
	                           elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_pover_sel_3f'] += 1;
			}
                        if ( intval($row['ts']) < 2000)     $output_cur['kol_zamen_pover_itogo_1f'] += 1;
                           elseif (intval($row['ts']>2999)) $output_cur['kol_zamen_pover_itogo_3f'] += 1;
                        $output_cur['kol_zamen_pover_itogo'] += 1;
                    }
                }
            }
          /*  Добавляем запись в общий массив   */
          $output_array_pes[] = $output_cur;
          }

          if ($resr==0) {
           /*  Добавляем в общий массив ИТОГО   */
            foreach($output_array_pes as $temp){
            $output_itogo['ob_gor'] += $temp['ob_gor'];
            $output_itogo['elpl_gor'] += $temp['elpl_gor'];
            $output_itogo['neprom_gor'] += $temp['neprom_gor'];
            $output_itogo['nagr_gor'] += $temp['nagr_gor'];
            $output_itogo['bezopl_gor'] += $temp['bezopl_gor'];
            $output_itogo['itogo_gor'] += $temp['itogo_gor'];

            $output_itogo['ob_pgt'] += $temp['ob_pgt'];
            $output_itogo['elpl_pgt'] += $temp['elpl_pgt'];
            $output_itogo['neprom_pgt'] += $temp['neprom_pgt'];
            $output_itogo['nagr_pgt'] += $temp['nagr_pgt'];
            $output_itogo['bezopl_pgt'] += $temp['bezopl_pgt'];
            $output_itogo['itogo_pgt'] += $temp['itogo_pgt'];

            $output_itogo['ob_selo'] += $temp['ob_selo'];
            $output_itogo['elpl_selo'] += $temp['elpl_selo'];
            $output_itogo['neprom_selo'] += $temp['neprom_selo'];
            $output_itogo['nagr_selo'] += $temp['nagr_selo'];
            $output_itogo['bezopl_selo'] += $temp['bezopl_selo'];
            $output_itogo['itogo_selo'] += $temp['itogo_selo'];

            $output_itogo['ob_itogo'] += $temp['ob_itogo'];
            $output_itogo['elpl_itogo'] += $temp['elpl_itogo'];
            $output_itogo['neprom_itogo'] += $temp['neprom_itogo'];
            $output_itogo['nagr_itogo'] += $temp['nagr_itogo'];
            $output_itogo['bezopl_itogo'] += $temp['bezopl_itogo'];
            $output_itogo['itogo_itogo'] += $temp['itogo_itogo'];

            $output_itogo['ob_gor_1f'] += $temp['ob_gor_1f'];
            $output_itogo['elpl_gor_1f'] += $temp['elpl_gor_1f'];
            $output_itogo['neprom_gor_1f'] += $temp['neprom_gor_1f'];
            $output_itogo['nagr_gor_1f'] += $temp['nagr_gor_1f'];
            $output_itogo['bezopl_gor_1f'] += $temp['bezopl_gor_1f'];
            $output_itogo['itogo_gor_1f'] += $temp['itogo_gor_1f'];

            $output_itogo['ob_pgt_1f'] += $temp['ob_pgt_1f'];
            $output_itogo['elpl_pgt_1f'] += $temp['elpl_pgt_1f'];
            $output_itogo['neprom_pgt_1f'] += $temp['neprom_pgt_1f'];
            $output_itogo['nagr_pgt_1f'] += $temp['nagr_pgt_1f'];
            $output_itogo['bezopl_pgt_1f'] += $temp['bezopl_pgt_1f'];
            $output_itogo['itogo_pgt_1f'] += $temp['itogo_pgt_1f'];

            $output_itogo['ob_selo_1f'] += $temp['ob_selo_1f'];
            $output_itogo['elpl_selo_1f'] += $temp['elpl_selo_1f'];
            $output_itogo['neprom_selo_1f'] += $temp['neprom_selo_1f'];
            $output_itogo['nagr_selo_1f'] += $temp['nagr_selo_1f'];
            $output_itogo['bezopl_selo_1f'] += $temp['bezopl_selo_1f'];
            $output_itogo['itogo_selo_1f'] += $temp['itogo_selo_1f'];

            $output_itogo['ob_itogo_1f'] += $temp['ob_itogo_1f'];
            $output_itogo['elpl_itogo_1f'] += $temp['elpl_itogo_1f'];
            $output_itogo['neprom_itogo_1f'] += $temp['neprom_itogo_1f'];
            $output_itogo['nagr_itogo_1f'] += $temp['nagr_itogo_1f'];
            $output_itogo['bezopl_itogo_1f'] += $temp['bezopl_itogo_1f'];
            $output_itogo['itogo_itogo_1f'] += $temp['itogo_itogo_1f'];

            $output_itogo['ob_gor_3f'] += $temp['ob_gor_3f'];
            $output_itogo['elpl_gor_3f'] += $temp['elpl_gor_3f'];
            $output_itogo['neprom_gor_3f'] += $temp['neprom_gor_3f'];
            $output_itogo['nagr_gor_3f'] += $temp['nagr_gor_3f'];
            $output_itogo['bezopl_gor_3f'] += $temp['bezopl_gor_3f'];
            $output_itogo['itogo_gor_3f'] += $temp['itogo_gor_3f'];

            $output_itogo['ob_pgt_3f'] += $temp['ob_pgt_3f'];
            $output_itogo['elpl_pgt_3f'] += $temp['elpl_pgt_3f'];
            $output_itogo['neprom_pgt_3f'] += $temp['neprom_pgt_3f'];
            $output_itogo['nagr_pgt_3f'] += $temp['nagr_pgt_3f'];
            $output_itogo['bezopl_pgt_3f'] += $temp['bezopl_pgt_3f'];
            $output_itogo['itogo_pgt_3f'] += $temp['itogo_pgt_3f'];

            $output_itogo['ob_selo_3f'] += $temp['ob_selo_3f'];
            $output_itogo['elpl_selo_3f'] += $temp['elpl_selo_3f'];
            $output_itogo['neprom_selo_3f'] += $temp['neprom_selo_3f'];
            $output_itogo['nagr_selo_3f'] += $temp['nagr_selo_3f'];
            $output_itogo['bezopl_selo_3f'] += $temp['bezopl_selo_3f'];
            $output_itogo['itogo_selo_3f'] += $temp['itogo_selo_3f'];

            $output_itogo['ob_itogo_3f'] += $temp['ob_itogo_3f'];
            $output_itogo['elpl_itogo_3f'] += $temp['elpl_itogo_3f'];
            $output_itogo['neprom_itogo_3f'] += $temp['neprom_itogo_3f'];
            $output_itogo['nagr_itogo_3f'] += $temp['nagr_itogo_3f'];
            $output_itogo['bezopl_itogo_3f'] += $temp['bezopl_itogo_3f'];
            $output_itogo['itogo_itogo_3f'] += $temp['itogo_itogo_3f'];

            $output_itogo['kol_itogo'] += $temp['kol_itogo'] ;
            $output_itogo['kol_bez_uch_itogo'] += $temp['kol_bez_uch_itogo'] ;
            $output_itogo['kol_energ_itogo'] += $temp['kol_energ_itogo'] ;
            $output_itogo['kol_otkl_avt_itogo'] += $temp['kol_otkl_avt_itogo'] ;
            $output_itogo['kol_otkl_v_sc_itogo'] += $temp['kol_otkl_v_sc_itogo'] ;
            $output_itogo['kol_otkl_jes_itogo'] += $temp['kol_otkl_jes_itogo'] ;
            $output_itogo['kol_otkl_front_itogo'] += $temp['kol_otkl_front_itogo'] ;
            $output_itogo['kol_otkl_vnutr_itogo'] += $temp['kol_otkl_vnutr_itogo'] ;
            $output_itogo['kol_otkl_izol_itogo'] += $temp['kol_otkl_izol_itogo'] ;
            $output_itogo['kol_otkl_opora_itogo'] += $temp['kol_otkl_opora_itogo'] ;
            $output_itogo['kol_otkl_provod_itogo'] += $temp['kol_otkl_provod_itogo'] ;

            $output_itogo['kol_ust_gor'] += $temp['kol_ust_gor'] ;
            $output_itogo['kol_ust_pgt'] += $temp['kol_ust_pgt'] ;
            $output_itogo['kol_ust_selo'] += $temp['kol_ust_selo'] ;
            $output_itogo['kol_ust_itogo'] += $temp['kol_ust_itogo'] ;
            $output_itogo['kol_ust_gor_1f'] += $temp['kol_ust_gor_1f'] ;
            $output_itogo['kol_ust_pgt_1f'] += $temp['kol_ust_pgt_1f'] ;
            $output_itogo['kol_ust_sel_1f'] += $temp['kol_ust_sel_1f'] ;
            $output_itogo['kol_ust_itogo_1f'] += $temp['kol_ust_itogo_1f'] ;
            $output_itogo['kol_ust_gor_3f'] += $temp['kol_ust_gor_3f'] ;
            $output_itogo['kol_ust_pgt_3f'] += $temp['kol_ust_pgt_3f'] ;
            $output_itogo['kol_ust_sel_3f'] += $temp['kol_ust_sel_3f'] ;
            $output_itogo['kol_ust_itogo_3f'] += $temp['kol_ust_itogo_3f'] ;

            $output_itogo['kol_zamen_gor'] += $temp['kol_zamen_gor'] ;
            $output_itogo['kol_zamen_pgt'] += $temp['kol_zamen_pgt'] ;
            $output_itogo['kol_zamen_selo'] += $temp['kol_zamen_selo'] ;
            $output_itogo['kol_zamen_itogo'] += $temp['kol_zamen_itogo'] ;
            $output_itogo['kol_zamen_gor_1f'] += $temp['kol_zamen_gor_1f'] ;
            $output_itogo['kol_zamen_pgt_1f'] += $temp['kol_zamen_pgt_1f'] ;
            $output_itogo['kol_zamen_sel_1f'] += $temp['kol_zamen_sel_1f'] ;
            $output_itogo['kol_zamen_itogo_1f'] += $temp['kol_zamen_itogo_1f'] ;
            $output_itogo['kol_zamen_gor_3f'] += $temp['kol_zamen_gor_3f'] ;
            $output_itogo['kol_zamen_pgt_3f'] += $temp['kol_zamen_pgt_3f'] ;
            $output_itogo['kol_zamen_sel_3f'] += $temp['kol_zamen_sel_3f'] ;
            $output_itogo['kol_zamen_itogo_3f'] += $temp['kol_zamen_itogo_3f'] ;

            $output_itogo['kol_zamen_pover_gor'] += $temp['kol_zamen_pover_gor'] ;
            $output_itogo['kol_zamen_pover_pgt'] += $temp['kol_zamen_pover_pgt'] ;
            $output_itogo['kol_zamen_pover_selo'] += $temp['kol_zamen_pover_selo'];
            $output_itogo['kol_zamen_pover_itogo'] += $temp['kol_zamen_pover_itogo'];
            $output_itogo['kol_zamen_pover_gor_1f'] += $temp['kol_zamen_pover_gor_1f'] ;
            $output_itogo['kol_zamen_pover_pgt_1f'] += $temp['kol_zamen_pover_pgt_1f'] ;
            $output_itogo['kol_zamen_pover_sel_1f'] += $temp['kol_zamen_pover_sel_1f'] ;
            $output_itogo['kol_zamen_pover_itogo_1f'] += $temp['kol_zamen_pover_itogo_1f'];
            $output_itogo['kol_zamen_pover_gor_3f'] += $temp['kol_zamen_pover_gor_3f'] ;
            $output_itogo['kol_zamen_pover_pgt_3f'] += $temp['kol_zamen_pover_pgt_3f'] ;
            $output_itogo['kol_zamen_pover_sel_3f'] += $temp['kol_zamen_pover_sel_3f'] ;
            $output_itogo['kol_zamen_pover_itogo_3f'] += $temp['kol_zamen_pover_itogo_3f'];


                }
          $output_array_pes[] = $output_itogo;
          }

            /***************************************************************************************************************/
            /*                            ПИШЕМ ДАННЫЕ В ФАЙЛ ОТЧЕТА                                                         */
            /***************************************************************************************************************/

           if ($resr>0) {
             $objPHPExcel->getActiveSheet()->setCellValue("A2",$nres2);
           }

            foreach ($output_array_pes as $key => $str){

            $objPHPExcel->setActiveSheetIndex($key);
            $objPHPExcel->getActiveSheet()->setTitle($res1[$key]);

            $objPHPExcel->getActiveSheet()->setCellValue("A9",$res1[$key]);
            $objPHPExcel->getActiveSheet()->setCellValue("C9",$str['ob_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("C10",$str['elpl_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("C11",$str['neprom_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("C12",$str['nagr_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("C13",$str['bezopl_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("C14",$str['itogo_gor']);

            $objPHPExcel->getActiveSheet()->setCellValue("D9",$str['ob_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("D10",$str['elpl_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("D11",$str['neprom_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("D12",$str['nagr_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("D13",$str['bezopl_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("D14",$str['itogo_pgt']);

            $objPHPExcel->getActiveSheet()->setCellValue("E9",$str['ob_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("E10",$str['elpl_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("E11",$str['neprom_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("E12",$str['nagr_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("E13",$str['bezopl_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("E14",$str['itogo_selo']);

            $objPHPExcel->getActiveSheet()->setCellValue("F9",$str['ob_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F10",$str['elpl_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F11",$str['neprom_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F12",$str['nagr_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F13",$str['bezopl_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F14",$str['itogo_itogo']);

            $objPHPExcel->getActiveSheet()->setCellValue("G9",$str['ob_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("G10",$str['elpl_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("G11",$str['neprom_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("G12",$str['nagr_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("G13",$str['bezopl_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("G14",$str['itogo_gor_1f']);

            $objPHPExcel->getActiveSheet()->setCellValue("H9",$str['ob_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H10",$str['elpl_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H11",$str['neprom_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H12",$str['nagr_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H13",$str['bezopl_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H14",$str['itogo_pgt_1f']);

            $objPHPExcel->getActiveSheet()->setCellValue("I9",$str['ob_selo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I10",$str['elpl_selo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I11",$str['neprom_selo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I12",$str['nagr_selo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I13",$str['bezopl_selo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I14",$str['itogo_selo_1f']);

            $objPHPExcel->getActiveSheet()->setCellValue("J9",$str['ob_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J10",$str['elpl_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J11",$str['neprom_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J12",$str['nagr_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J13",$str['bezopl_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J14",$str['itogo_itogo_1f']);

            $objPHPExcel->getActiveSheet()->setCellValue("K9",$str['ob_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K10",$str['elpl_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K11",$str['neprom_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K12",$str['nagr_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K13",$str['bezopl_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K14",$str['itogo_gor_3f']);

            $objPHPExcel->getActiveSheet()->setCellValue("L9",$str['ob_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L10",$str['elpl_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L11",$str['neprom_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L12",$str['nagr_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L13",$str['bezopl_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L14",$str['itogo_pgt_3f']);

            $objPHPExcel->getActiveSheet()->setCellValue("M9",$str['ob_selo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M10",$str['elpl_selo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M11",$str['neprom_selo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M12",$str['nagr_selo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M13",$str['bezopl_selo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M14",$str['itogo_selo_3f']);

            $objPHPExcel->getActiveSheet()->setCellValue("N9",$str['ob_itogo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N10",$str['elpl_itogo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N11",$str['neprom_itogo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N12",$str['nagr_itogo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N13",$str['bezopl_itogo_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N14",$str['itogo_itogo_3f']);

            $objPHPExcel->getActiveSheet()->setCellValue("F15",$str['kol_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F16",$str['kol_bez_uch_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F17",$str['kol_energ_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F18",$str['kol_otkl_avt_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F19",$str['kol_otkl_v_sc_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F20",$str['kol_otkl_jes_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F21",$str['kol_otkl_front_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F22",$str['kol_otkl_vnutr_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F23",$str['kol_otkl_izol_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F24",$str['kol_otkl_opora_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F25",$str['kol_otkl_provod_itogo']);

            $objPHPExcel->getActiveSheet()->setCellValue("C26",$str['kol_ust_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("D26",$str['kol_ust_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("E26",$str['kol_ust_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F26",$str['kol_ust_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("G26",$str['kol_ust_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H26",$str['kol_ust_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I26",$str['kol_ust_sel_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J26",$str['kol_ust_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K26",$str['kol_ust_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L26",$str['kol_ust_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M26",$str['kol_ust_sel_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N26",$str['kol_ust_itogo_3f']);

            $objPHPExcel->getActiveSheet()->setCellValue("C27",$str['kol_zamen_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("D27",$str['kol_zamen_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("E27",$str['kol_zamen_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F27",$str['kol_zamen_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("G27",$str['kol_zamen_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H27",$str['kol_zamen_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I27",$str['kol_zamen_sel_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J27",$str['kol_zamen_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K27",$str['kol_zamen_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L27",$str['kol_zamen_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M27",$str['kol_zamen_sel_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N27",$str['kol_zamen_itogo_3f']);

            $objPHPExcel->getActiveSheet()->setCellValue("C28",$str['kol_zamen_pover_gor']);
            $objPHPExcel->getActiveSheet()->setCellValue("D28",$str['kol_zamen_pover_pgt']);
            $objPHPExcel->getActiveSheet()->setCellValue("E28",$str['kol_zamen_pover_selo']);
            $objPHPExcel->getActiveSheet()->setCellValue("F28",$str['kol_zamen_pover_itogo']);
            $objPHPExcel->getActiveSheet()->setCellValue("G28",$str['kol_zamen_pover_gor_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("H28",$str['kol_zamen_pover_pgt_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("I28",$str['kol_zamen_pover_sel_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("J28",$str['kol_zamen_pover_itogo_1f']);
            $objPHPExcel->getActiveSheet()->setCellValue("K28",$str['kol_zamen_pover_gor_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("L28",$str['kol_zamen_pover_pgt_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("M28",$str['kol_zamen_pover_sel_3f']);
            $objPHPExcel->getActiveSheet()->setCellValue("N28",$str['kol_zamen_pover_itogo_3f']);

            $key2 = 30;

            if ($resr==0 and count($res0)>$key)
            {
            $objSheet = clone $objPHPExcel->getActiveSheet();
            $objSheet->setTitle($res1[$key+1]);
            $objPHPExcel->addSheet($objSheet);
            }

            Write_report("Форма_7", $objPHPExcel, preobrDate($date_beg), preobrDate($date_end), '', $key2, 0, false);
        }
        Write_report("Форма_7", $objPHPExcel, preobrDate($date_beg), preobrDate($date_end), '', $key2, 0);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
    }
?>