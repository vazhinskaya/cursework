<?php
$time_start = getmicrotime();

$res = dbQuery("select type_res,knaim FROM _res GROUP BY knaim,type_res");
while ($res_base = dbFetchAssoc($res)){
       $res_eng[]=$res_base['type_res'];
       $res_rus[]=$res_base['knaim'];
}
$res_rus[]="И Т О Г О";

$res0 = array();
$res1 = array();

$period_beg = date(DATE_FORMAT_SHOT,strtotime(get_input('dateb')));
$period_end = date(DATE_FORMAT_SHOT,strtotime(get_input('datee')));
$period_beg_ymd = date(DATE_FORMAT_YMD,strtotime(get_input('dateb')));
$period_end_ymd = date(DATE_FORMAT_YMD,strtotime(get_input('datee')));

if (date(DATE_FORMAT_YMD,strtotime(get_input('dateb'))) > date(DATE_FORMAT_YMD,strtotime(get_input('datee'))) ||
    date(DATE_FORMAT_YMD,strtotime(get_input('dateb'))) < date(DATE_FORMAT_YMD,strtotime('01.01.2000')) ||
    date(DATE_FORMAT_YMD,strtotime(get_input('datee'))) < date(DATE_FORMAT_YMD,strtotime('01.01.2000'))
   ) {
       $errors[] = 'Даты начала и конца расчетного периода не указаны или указаны неверно';
     }
if (count($errors)) {
    $smarty->assign('label_error',1);
    $smarty->assign('errors',$errors);
    $smarty->display('general_errors.html');
    exit();
}
else {
	$resr = get_input('nres','int');
  	$idres2=0;
   	if ($resr==0) {
     		$res2=" and 1=1 ";
     		for($k=0; $k < count($res_eng); $k++){
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
		$idres2 = $res_base['id'];
	}


        /* ******************************************************************* */
        /*           Регистрация переменных для сбора данных отчета            */
        /* ******************************************************************* */
        $knnom = array();      // все абоненты, которых надо обсчитать

        $output_str = array(
                'c6_vsego'  =>0,'c6_g_vsego'=>0,'c6_g_plita'=>0,'c6_s_vsego'=>0,'c6_s_plita'=>0,
                'c7_vsego'  =>0,'c7_g_vsego'=>0,'c7_g_plita'=>0,'c7_s_vsego'=>0,'c7_s_plita'=>0,
                'c8_vsego'  =>0,'c8_g_vsego'=>0,'c8_g_plita'=>0,'c8_s_vsego'=>0,'c8_s_plita'=>0,
                'c9_vsego'  =>0,'c9_g_vsego'=>0,'c9_g_plita'=>0,'c9_s_vsego'=>0,'c9_s_plita'=>0,
                'c10_vsego'  =>0,'c10_g_vsego'=>0,'c10_g_plita'=>0,'c10_s_vsego'=>0,'c10_s_plita'=>0,
                'c11_vsego'  =>0,'c11_g_vsego'=>0,'c11_g_plita'=>0,'c11_s_vsego'=>0,'c11_s_plita'=>0,
                'c12_vsego'  =>0,'c12_g_vsego'=>0,'c12_g_plita'=>0,'c12_s_vsego'=>0,'c12_s_plita'=>0
        );
        $con_knnomuch = array();

        $kodtar_el_pl_ar = array();
        $res = dbQuery("Select id from _vidtar where id = 2 or id = 7");
        while ($row = dbFetchAssoc($res)) {
               $kodtar_el_pl_ar[] = $row['id'];
        }

	foreach($res0 as $cur_res) {
	    $output = array();		// весь выходной массив
	    $output[0] = $output_str;
            $knnom = array();		// все абоненты, которых надо обсчитать
            $con_knnomuch = array();

            /* ********************************************************************* */
            /*     ШАГ 1.Выборка льготников на КОНЕЦ расчетного периода              */
            /* ********************************************************************* */
            $query_beg = "SELECT
                    main.kn, main.nom, 1 as uch, tarhist.idl, tarhist.idt, tarhist.semlg
                    FROM ".$cur_res."_main as main
                    LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                    LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on main.kn = tarhist.kn and main.nom = tarhist.nom
                    LEFT JOIN _vidtar as vidtar on tarhist.idt = vidtar.id
                    LEFT JOIN _vidlg as vidlg on tarhist.idl = vidlg.id
                    WHERE main.archive=0
                    and (main.dlt is NULL or main.dlt = 0 )
                    ".$res2."
                    and tarhist.idl>1
                    and (tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.ddate <= '$period_end' and a.kn = main.kn and a.nom = main.nom and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc limit 1))
                    and (tarhist.idl IN (SELECT _vidlg.id FROM _vidlg WHERE datee IS NULL or datee >= '$period_end'))
                    ORDER BY kn, nom";
            $res_beg = dbQuery($query_beg);

            $knnom_temp = array();
            while ($row_beg = dbFetchAssoc($res_beg)) {
                $temp = $row_beg['kn'].$row_beg['nom'];
                if (!in_array($temp,$knnom_temp)){
                    $knnom[] = array(
                        'kn'    => $row_beg['kn'],
                        'nom'   => $row_beg['nom'],
                        'uch'   => $row_beg['uch'],
                        'idl'   => $row_beg['idl'],
                        'idt'   => $row_beg['idt'],
                        'semlg' => $row_beg['semlg']
                    );
                    $knnom_temp[] = $temp;
                }
            }

            $query_beg = "SELECT
                    main.kn, main.nom, 1 as uch, sem_lg.idl, tarhist.idt, tarhist.semlg
                    FROM ".$cur_res."_main as main
                    LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                    LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on main.kn = tarhist.kn and main.nom = tarhist.nom
                    LEFT JOIN _vidtar as vidtar on tarhist.idt = vidtar.id
                    LEFT JOIN ".$cur_res."_sem_lg as sem_lg on sem_lg.idtarhist=tarhist.id and main.kn = sem_lg.kn and main.nom = sem_lg.nom
                    LEFT JOIN _vidlg as vidlg on sem_lg.idl = vidlg.id
                    WHERE tarhist.idl<=1
                    and main.dlt = 0
                    and main.archive=0
                    ".$res2."
                    and (main.dlt is NULL or main.dlt = 0 )
                    and (tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.ddate <= '$period_end' and a.kn = main.kn and a.nom = main.nom and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc limit 1))
                    and (sem_lg.idl IN (SELECT _vidlg.id FROM _vidlg WHERE datee IS NULL or datee >= '$period_end'))
                    ORDER BY kn, nom";

            $res_beg = dbQuery($query_beg);
	    while ($row_beg = dbFetchAssoc($res_beg)){
                $temp = $row_beg['kn'].$row_beg['nom'];
                if (!in_array($temp,$knnom_temp)){
                    $knnom[] = array(
                        'kn'    => $row_beg['kn'],
                        'nom'   => $row_beg['nom'],
                        'uch'   => $row_beg['uch'],
                        'idl'   => $row_beg['idl'],
                        'idt'   => $row_beg['idt'],
                        'semlg' => $row_beg['semlg']
                    );
                    $knnom_temp[] = $temp;
                }
            }

            echo $cur_res.":  Количество абонентов со льготами на КОНЕЦ расчетного периода : ".$all = count($knnom)."<br>";
	    $c6 = $c7 = $c8 = $c9 = $c10 = $c11 = $c12 = 0;
            for ($x=0; $x <= $all-1; $x++){
                $kn	= $knnom[$x]['kn'];
                $nom	= $knnom[$x]['nom'];
                $uch	= $knnom[$x]['uch'];
                $idl	= $knnom[$x]['idl'];
                $idt	= $knnom[$x]['idt'];
                $semlg	= $knnom[$x]['semlg'];
                $is_from_gorod = get_is_from_cityF6($kn,$nom,$cur_res);

                /* ********************************************************* */
                /*   Выборка квитанций данного абонента за указанный период  */
                /* ********************************************************* */
                //$kvit_array = GetKvit($kn,$nom,$uch,false);
                $kvit_array = GetKvitFltrF6($kn,$nom,$uch,false,$period_beg,$period_end, $cur_res);
                for ($xx=0; $xx<=count($kvit_array)-1; $xx++){
                    /* ****************************************** */
                    /* Входит ли ПАЧКА квитанции в период расчета */
                    /* ****************************************** */
                    $sql = "select * from ".$cur_res."_pachka where npachka='".$kvit_array[$xx]['npachkv']."' and dat_form >= '$period_beg' and dat_form <= '$period_end' and not (kassa LIKE 'АС') and (dlt = 0 or dlt is NULL)";
                    $res = db_ExtQuery($dblink, $sql);

                    /* *************************************************************************************** */
                    /* Записываем в общий выходной массив                                                      */
                    /* *************************************************************************************** */
                    $id_lg = $idl;
                    if ($id_lg && ($res->Num_Rows>0) && $kvit_array[$xx]['kvhour']>0){
                       $is_plita = 0; if (in_array($kvit_array[$xx]['kodtar'],$kodtar_el_pl_ar)) $is_plita = 1;

                       $c6 = 1;
                       $c7 = $kvit_array[$xx]['semlg'];
                       $c8 = $kvit_array[$xx]['kvhour'];
                       if (strtotime("01.".$kvit_array[$xx]['imeskv'])>=strtotime('01.02.2013') && $kvit_array[$xx]['kodtar']>4) {
                          $c9    = round(c9($kvit_array[$xx]['kvhour'],$kvit_array[$xx]['norma'],$kvit_array[$xx]['semlg'],$kvit_array[$xx]['semya'],1),1);
                          $temp1 = GetCalculatorFullF6($kn.$nom,$kvit_array[$xx]['kvhour'],1, date('01.'.$kvit_array[$xx]['imeskv']), $cur_res);
                          $c10   = $temp1[0]["sum"];
                        //   echo " По новому ";
                       }
                       else {
                          $c9 =  round(c9($kvit_array[$xx]['kvhour'],$kvit_array[$xx]['norma'],$kvit_array[$xx]['semlg'],$kvit_array[$xx]['semya'],$kvit_array[$xx]['koef_norma']),1);
                          $c10 = $kvit_array[$xx]['kvhour']*$kvit_array[$xx]['fulltar'];
                       //   echo " По старому ";
                       }
                       $c11 = $kvit_array[$xx]['sumkv']-$kvit_array[$xx]['penkv'];
                       $c12 = $c10-$c11;
//                    echo  $c12/$c9."   ".$x." ".$kn.$nom." ".$kvit_array[$xx]['kvhour']." ".$kvit_array[$xx]['norma']." ".$kvit_array[$xx]['semlg']." ".$kvit_array[$xx]['semya']." ".$kvit_array[$xx]['imeskv']." ".($kvit_array[$xx]['sumkv']-$kvit_array[$xx]['penkv'])." c9-".$c9." c10-".$c10." c11-".$c11." c12-".$c12."<br>";
//                       if ($id_lg == 26) {
//                                echo $knnom[$x]['kn'].$knnom[$x]['nom']."  Кв.тар.".$kvit_array[$xx]['kodtar']." кВтч ".$kvit_array[$xx]['kvhour']." руб.".$kvit_array[$xx]['sumkv']." imes".$kvit_array[$xx]['imeskv']." date".$kvit_array[$xx]['datekv']." tarif".$kvit_array[$xx]['tarif']." norma".$kvit_array[$xx]['norma']." semlg".$kvit_array[$xx]['semlg']." semya".$kvit_array[$xx]['semya']." koef_norma".$kvit_array[$xx]['koef_norma']."<br>";
//					   }
			if ($c9==0) {
				$c8  = 0;
				$c10 = 0;
				$c11 = 0;
				$c12 = 0;
			}

                       if (!isset($output[$id_lg])) $output[$id_lg] = $output_str;

                       $output[$id_lg]['c8_vsego']  += $c8;
                       $output[$id_lg]['c9_vsego']  += $c9;
                       $output[$id_lg]['c10_vsego'] += $c10;
                       $output[$id_lg]['c11_vsego'] += $c11;
                       $output[$id_lg]['c12_vsego'] += $c12;

                       $output[0]['c8_vsego']  += $c8;
                       $output[0]['c9_vsego']  += $c9;
                       $output[0]['c10_vsego'] += $c10;
                       $output[0]['c11_vsego'] += $c11;
                       $output[0]['c12_vsego'] += $c12;
                       /* *************************************************************************************** */
                       /* записываем в общий выходной массив ГОРОДСКИХ                                            */
                       /* *************************************************************************************** */
                       if ($is_from_gorod){
                           $output[$id_lg]['c8_g_vsego'] += $c8;
                           $output[$id_lg]['c9_g_vsego'] += $c9;
                           $output[$id_lg]['c10_g_vsego'] += $c10;
                           $output[$id_lg]['c11_g_vsego'] += $c11;
                           $output[$id_lg]['c12_g_vsego'] += $c12;

                           $output[0]['c8_g_vsego'] += $c8;
                           $output[0]['c9_g_vsego'] += $c9;
                           $output[0]['c10_g_vsego'] += $c10;
                           $output[0]['c11_g_vsego'] += $c11;
                           $output[0]['c12_g_vsego'] += $c12;
                           /* *************************************************************************************** */
                           /* записываем в общий выходной массив ЭЛЕКТРОПЛИТОЧНИКОВ                                   */
                           /* *************************************************************************************** */
                           if ($is_plita){
                               $output[$id_lg]['c8_g_plita'] += $c8;
                               $output[$id_lg]['c9_g_plita'] += $c9;
                               $output[$id_lg]['c10_g_plita'] += $c10;
                               $output[$id_lg]['c11_g_plita'] += $c11;
                               $output[$id_lg]['c12_g_plita'] += $c12;

                               $output[0]['c8_g_plita'] += $c8;
                               $output[0]['c9_g_plita'] += $c9;
                               $output[0]['c10_g_plita'] += $c10;
                               $output[0]['c11_g_plita'] += $c11;
                               $output[0]['c12_g_plita'] += $c12;
                           }
                       }
                       /* *************************************************************************************** */
                       /* записываем в общий выходной массив СЕЛЬСКИХ                                             */
                       /* *************************************************************************************** */
                       else{
                           $output[$id_lg]['c8_s_vsego']  += $c8;
                           $output[$id_lg]['c9_s_vsego']  += $c9;
                           $output[$id_lg]['c10_s_vsego'] += $c10;
                           $output[$id_lg]['c11_s_vsego'] += $c11;
                           $output[$id_lg]['c12_s_vsego'] += $c12;

                           $output[0]['c8_s_vsego']  += $c8;
                           $output[0]['c9_s_vsego']  += $c9;
                           $output[0]['c10_s_vsego'] += $c10;
                           $output[0]['c11_s_vsego'] += $c11;
                           $output[0]['c12_s_vsego'] += $c12;
                           /* *************************************************************************************** */
                           /* записываем в общий выходной массив ЭЛЕКТРОПЛИТОЧНИКОВ                                   */
                           /* *************************************************************************************** */
                           if ($is_plita){
                               $output[$id_lg]['c8_s_plita']  += $c8;
                               $output[$id_lg]['c9_s_plita']  += $c9;
                               $output[$id_lg]['c10_s_plita'] += $c10;
                               $output[$id_lg]['c11_s_plita'] += $c11;
                               $output[$id_lg]['c12_s_plita'] += $c12;

                               $output[0]['c8_s_plita']  += $c8;
                               $output[0]['c9_s_plita']  += $c9;
                               $output[0]['c10_s_plita'] += $c10;
                               $output[0]['c11_s_plita'] += $c11;
                               $output[0]['c12_s_plita'] += $c12;
                           }
                       }
                    }
                }
            }
            /* ************************************************************************************************** */
            /*           Подсчет льготников на КОНЕЦ расчетного периода(они нужны только для статистики)          */
            /* ************************************************************************************************** */
            $query_end = "SELECT
                    main.kn, main.nom, 1 as uch, tarhist.idl, tarhist.idt, tarhist.semlg
                    FROM ".$cur_res."_main as main
                    LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                    LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on main.kn = tarhist.kn and main.nom = tarhist.nom
                    LEFT JOIN _vidtar as vidtar on tarhist.idt = vidtar.id
                    LEFT JOIN _vidlg as vidlg on tarhist.idl = vidlg.id
                    WHERE main.archive=0
                    and (main.dlt is NULL or main.dlt = 0 )
                    ".$res2."
                    and tarhist.idl>1
                    and (tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.ddate <= '$period_end' and a.kn = main.kn and a.nom = main.nom and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc limit 1))
                    and (tarhist.idl IN (SELECT _vidlg.id FROM _vidlg WHERE datee IS NULL or datee >= '$period_end'))
                    ORDER BY kn, nom";
            $res_end = dbQuery($query_end);

            $knnom_temp = array();
            while ($row_end = dbFetchAssoc($res_end)){
                $temp = $row_end['kn'].$row_end['nom'];
                if (in_array($temp,$knnom_temp)) continue;
                $knnom_temp[] = $temp;
                $kn    = $row_end['kn'];
                $nom   = $row_end['nom'];
                $uch   = $row_end['uch'];

                $id_lg = $row_end['idl'];
                $idt   = $row_end['idt'];
                $semlg = $row_end['semlg'];

                $is_from_gorod = get_is_from_cityF6($kn,$nom,$cur_res);
                $is_plita = 0; if (in_array($row_end['idt'],$kodtar_el_pl_ar)) $is_plita = 1;

                $c6 = 1;
                $c7 = $semlg;

                if (isset($output[$id_lg])){
                    $output[$id_lg]['c6_vsego'] +=  $c6;
                    $output[$id_lg]['c7_vsego'] += $c7;
                    $output[0]['c6_vsego'] +=  $c6;
                    $output[0]['c7_vsego'] += $c7;
                    if ($is_from_gorod){
                        $output[$id_lg]['c6_g_vsego'] +=  $c6;
                        $output[$id_lg]['c7_g_vsego'] += $c7;
                        $output[0]['c6_g_vsego'] +=  $c6;
                        $output[0]['c7_g_vsego'] += $c7;

                        if ($is_plita){
                            $output[$id_lg]['c6_g_plita'] +=  $c6;
                            $output[$id_lg]['c7_g_plita'] += $c7;
                            $output[0]['c6_g_plita'] +=  $c6;
                            $output[0]['c7_g_plita'] += $c7;
                        }
                    }
                    else {
                        $output[$id_lg]['c6_s_vsego'] +=  $c6;
                        $output[$id_lg]['c7_s_vsego'] += $c7;
                        $output[0]['c6_s_vsego'] +=  $c6;
                        $output[0]['c7_s_vsego'] += $c7;
                        if ($is_plita){
                            $output[$id_lg]['c6_s_plita'] +=  $c6;
                            $output[$id_lg]['c7_s_plita'] += $c7;
                            $output[0]['c6_s_plita'] +=  $c6;
                            $output[0]['c7_s_plita'] += $c7;
                        }
                    }
                }
            }

            $query_end = "SELECT
                    main.kn, main.nom, 1 as uch, sem_lg.idl, tarhist.idt, tarhist.semlg
                    FROM ".$cur_res."_main as main
                    LEFT JOIN ".$cur_res."_dom as dom on dom.id = main.id_dom
                    LEFT JOIN ".$cur_res."_tarhist_sem as tarhist on main.kn = tarhist.kn and main.nom = tarhist.nom
                    LEFT JOIN _vidtar as vidtar on tarhist.idt = vidtar.id
                    LEFT JOIN ".$cur_res."_sem_lg as sem_lg on sem_lg.idtarhist=tarhist.id and main.kn = sem_lg.kn and main.nom = sem_lg.nom
                    LEFT JOIN _vidlg as vidlg on sem_lg.idl = vidlg.id
                    WHERE tarhist.idl<=1
                    and main.dlt = 0
                    and main.archive=0
                    and (main.dlt is NULL or main.dlt = 0 )
                    ".$res2."
                    and (tarhist.id = (SELECT a.id FROM ".$cur_res."_tarhist_sem as a where a.ddate <= '$period_end' and a.kn = main.kn and a.nom = main.nom and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc limit 1))
                    and (sem_lg.idl IN (SELECT _vidlg.id FROM _vidlg WHERE datee IS NULL or datee >= '$period_end'))
                    ORDER BY kn, nom";
            $res_end = dbQuery($query_end);

            while ($row_end = dbFetchAssoc($res_end)){
                $temp = $row_end['kn'].$row_end['nom'];
                if (in_array($temp,$knnom_temp)) continue;
                $knnom_temp[] = $temp;
                $kn     = $row_end['kn'];
                $nom    = $row_end['nom'];
                $uch    = $row_end['uch'];

                $id_lg  = $row_end['idl'];
                $idt    = $row_end['idt'];
                $semlg  = $row_end['semlg'];

                $is_from_gorod = get_is_from_cityF6($kn,$nom,$cur_res);
                $is_plita = 0; if (in_array($row_end['idt'],$kodtar_el_pl_ar)) $is_plita = 1;

                $c6 = 1;
                $c7 = $semlg;

                if (isset($output[$id_lg])){
                    $output[$id_lg]['c6_vsego'] +=  $c6;
                    $output[$id_lg]['c7_vsego'] += $c7;
                    $output[0]['c6_vsego'] +=  $c6;
                    $output[0]['c7_vsego'] += $c7;
                    if ($is_from_gorod){
                        $output[$id_lg]['c6_g_vsego'] +=  $c6;
                        $output[$id_lg]['c7_g_vsego'] += $c7;
                        $output[0]['c6_g_vsego'] +=  $c6;
                        $output[0]['c7_g_vsego'] += $c7;

                        if ($is_plita){
                            $output[$id_lg]['c6_g_plita'] +=  $c6;
                            $output[$id_lg]['c7_g_plita'] += $c7;
                            $output[0]['c6_g_plita'] +=  $c6;
                            $output[0]['c7_g_plita'] += $c7;
                        }
                    }
                    else {
                        $output[$id_lg]['c6_s_vsego'] +=  $c6;
                        $output[$id_lg]['c7_s_vsego'] += $c7;
                        $output[0]['c6_s_vsego'] +=  $c6;
                        $output[0]['c7_s_vsego'] += $c7;
                        if ($is_plita){
                            $output[$id_lg]['c6_s_plita'] +=  $c6;
                            $output[$id_lg]['c7_s_plita'] += $c7;
                            $output[0]['c6_s_plita'] +=  $c6;
                            $output[0]['c7_s_plita'] += $c7;
                        }
                    }
                }
            }
       	/*  Добавляем запись в общий массив  */
       	$output_res[] = $output;
	}

        $output_itogo = array();
        foreach($output_res as $temp){
	        /*  Добавляем в общий массив ИТОГО   */
        	for($mm=0;$mm<30;$mm++){
			if (isset($temp[$mm])){
				if (!isset($output_itogo[$mm])){
					$output_itogo[$mm] = $output_str;
				}
				$output_itogo[$mm]['c6_vsego']   += $temp[$mm]['c6_vsego'];
			  	$output_itogo[$mm]['c6_g_vsego'] += $temp[$mm]['c6_g_vsego'];
			  	$output_itogo[$mm]['c6_g_plita'] += $temp[$mm]['c6_g_plita'];
			  	$output_itogo[$mm]['c6_s_vsego'] += $temp[$mm]['c6_s_vsego'];
			  	$output_itogo[$mm]['c6_s_plita'] += $temp[$mm]['c6_s_plita'];
			  	$output_itogo[$mm]['c7_vsego']   += $temp[$mm]['c7_vsego'];
			  	$output_itogo[$mm]['c7_g_vsego'] += $temp[$mm]['c7_g_vsego'];
			  	$output_itogo[$mm]['c7_g_plita'] += $temp[$mm]['c7_g_plita'];
			  	$output_itogo[$mm]['c7_s_vsego'] += $temp[$mm]['c7_s_vsego'];
			  	$output_itogo[$mm]['c7_s_plita'] += $temp[$mm]['c7_s_plita'];
			  	$output_itogo[$mm]['c8_vsego']   += $temp[$mm]['c8_vsego'];
			  	$output_itogo[$mm]['c8_g_vsego'] += $temp[$mm]['c8_g_vsego'];
			  	$output_itogo[$mm]['c8_g_plita'] += $temp[$mm]['c8_g_plita'];
			  	$output_itogo[$mm]['c8_s_vsego'] += $temp[$mm]['c8_s_vsego'];
			  	$output_itogo[$mm]['c8_s_plita'] += $temp[$mm]['c8_s_plita'];
			  	$output_itogo[$mm]['c9_vsego']   += $temp[$mm]['c9_vsego'];
			  	$output_itogo[$mm]['c9_g_vsego'] += $temp[$mm]['c9_g_vsego'];
			  	$output_itogo[$mm]['c9_g_plita'] += $temp[$mm]['c9_g_plita'];
			  	$output_itogo[$mm]['c9_s_vsego'] += $temp[$mm]['c9_s_vsego'];
			  	$output_itogo[$mm]['c9_s_plita'] += $temp[$mm]['c9_s_plita'];
			  	$output_itogo[$mm]['c10_vsego']  += $temp[$mm]['c10_vsego'];
			  	$output_itogo[$mm]['c10_g_vsego']+= $temp[$mm]['c10_g_vsego'];
			  	$output_itogo[$mm]['c10_g_plita']+= $temp[$mm]['c10_g_plita'];
			  	$output_itogo[$mm]['c10_s_vsego']+= $temp[$mm]['c10_s_vsego'];
			  	$output_itogo[$mm]['c10_s_plita']+= $temp[$mm]['c10_s_plita'];
			  	$output_itogo[$mm]['c11_vsego']  += $temp[$mm]['c11_vsego'];
			  	$output_itogo[$mm]['c11_g_vsego']+= $temp[$mm]['c11_g_vsego'];
			  	$output_itogo[$mm]['c11_g_plita']+= $temp[$mm]['c11_g_plita'];
			  	$output_itogo[$mm]['c11_s_vsego']+= $temp[$mm]['c11_s_vsego'];
			  	$output_itogo[$mm]['c11_s_plita']+= $temp[$mm]['c11_s_plita'];
			  	$output_itogo[$mm]['c12_vsego']  += $temp[$mm]['c12_vsego'];
			  	$output_itogo[$mm]['c12_g_vsego']+= $temp[$mm]['c12_g_vsego'];
			  	$output_itogo[$mm]['c12_g_plita']+= $temp[$mm]['c12_g_plita'];
			  	$output_itogo[$mm]['c12_s_vsego']+= $temp[$mm]['c12_s_vsego'];
			  	$output_itogo[$mm]['c12_s_plita']+= $temp[$mm]['c12_s_plita'];
			}
		}
        }
       	$output_res[] = $output_itogo;


	/****************************************************************************/
	/*               Пишем отчет в файл                                         */
	/****************************************************************************/
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue("A2",'филиал РУП "Брестэнерго" Брестские электрические сети');
        $objPHPExcel->getProperties()->setCreator('Служба АСУ');
        $objPHPExcel->getProperties()->setLastModifiedBy('Служба АСУ');
        $objPHPExcel->getProperties()->setTitle("Отчеты (формы, ведомости, реестры): Отчёт по предоставлению отдельным категориям граждан льгот.");
        $objPHPExcel->getProperties()->setSubject("Отчеты (формы, ведомости, реестры): Отчёт по предоставлению отдельным категориям граждан льгот");
        $objPHPExcel->getProperties()->setDescription("Отчеты (формы, ведомости, реестры): Отчёт по предоставлению отдельным категориям граждан льгот");

        /************************************************************************************/
        /*                    Structure                                                     */
        /************************************************************************************/
        $objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(7);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(100);
        $objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(100);

        /***************************************************************************************************************************/
        /*                                                Print Head                                                               */
        /***************************************************************************************************************************/
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_a_8_l);
        $objPHPExcel->getActiveSheet()->setCellValue('A2',$GLOBALS["Settings"]['org']);

        $objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($style_a_8_r);
        $objPHPExcel->getActiveSheet()->setCellValue('I2','Дата печати ');

        $objPHPExcel->getActiveSheet()->getStyle('J2')->applyFromArray($style_a_8_l);
        $objPHPExcel->getActiveSheet()->setCellValue('J2',date('d.m.Y').'г.');

        $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($style_a_8_c);
        $objPHPExcel->getActiveSheet()->setCellValue('F3','С В Е Д Е Н И Я');

        $objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($style_a_8_c);
        $temp = 'о расходах на предоставление отдельным категориям граждан льгот по оплате электрической энергии с %date_beg% г. по %date_end% г.';
        $temp = str_replace('%date_beg%',$period_beg,$temp);
        $temp = str_replace('%date_end%',$period_end,$temp);
        $objPHPExcel->getActiveSheet()->setCellValue('F4',$temp);

        $objPHPExcel->getActiveSheet()->mergeCells('A5:A6');
        $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A5','N п/п');

        $objPHPExcel->getActiveSheet()->mergeCells('B5:B6');
        $objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('B5','Категории граждан, имеющие право на скидку с оплаты за электрическую энергию, основание (№ пункта, подпункта статьи 16 Закона, иной нормативный правовой акт)');

        $objPHPExcel->getActiveSheet()->mergeCells('C5:C6');
        $objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('C5','Скидка с оплаты, %');

        $objPHPExcel->getActiveSheet()->mergeCells('D5:D6');
        $objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('D5','Место проживания');

        $objPHPExcel->getActiveSheet()->mergeCells('E5:E6');
        $objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('E6','Тарифная группа');

        $objPHPExcel->getActiveSheet()->mergeCells('F5:G5');
        $objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('F5','Количество граждан, имеющих право на скидку при оплате электрической энергии, чел.');

        $objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('F6')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('F6','абонентов');

        $objPHPExcel->getActiveSheet()->getStyle('G6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('G6')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('G6','граждан (абонентов и членов семей)');

        $objPHPExcel->getActiveSheet()->mergeCells('H5:I5');
        $objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('H5','Количество электрической энергии, потребленной и оплаченной льготными категориями граждан, тыс.кВтч');

        $objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('H6')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('H6','потребленное абонентом');

        $objPHPExcel->getActiveSheet()->getStyle('I6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('I6')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('I6','потребленное гражданами, имеющими право на скидку');

        $objPHPExcel->getActiveSheet()->mergeCells('J5:K5');
        $objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('J5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('J5','Стоимость электрической энергии, потребленной и оплаченной льготными категориями граждан, тыс.руб.');

        $objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('J6')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('J6','без учета льгот');

        $objPHPExcel->getActiveSheet()->getStyle('K6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('K6')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('K6','с учетом льгот');

        $objPHPExcel->getActiveSheet()->mergeCells('L5:L6');
        $objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('L6')->applyFromArray($style_a_8_c_c);
        $objPHPExcel->getActiveSheet()->getStyle('L5')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue('L5','Расходы на предоставление льгот, тыс.руб. (графа 10-графа 11)');

	$objSheet0 = clone $objPHPExcel->getActiveSheet();
//  $objPHPExcel->addSheet();

  foreach ($output_res as $key0 => $str0) {
  if ($key0==0){
        $objPHPExcel->setActiveSheetIndex($key0);
        $objPHPExcel->getActiveSheet()->setTitle($res1[$key0]);
        if ($res1[$key0]!="И Т О Г О")   $objPHPExcel->getActiveSheet()->setCellValue("A1",$res1[$key0]);
        else	       		    	 $objPHPExcel->getActiveSheet()->setCellValue("A1","   ");

	    if (isset($str0)){

                /**************************************************************************************************************/
                /*  Пишем вычисленные данные. Все полностью, сначала будут идти общие итоговые                                */
                /**************************************************************************************************************/
                $xx = 0;
                foreach ($str0 as $key => $str){
                    $key2 = 7 + ($xx*6);

                    $objPHPExcel->getActiveSheet()->getRowDimension($key2)->setRowHeight(13);
                    $objPHPExcel->getActiveSheet()->getStyle("A$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",'1');
                    $objPHPExcel->getActiveSheet()->getStyle("B$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("B$key2",'2');
                    $objPHPExcel->getActiveSheet()->getStyle("C$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("C$key2",'3');
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$key2",'4');
                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key2",'5');
                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",'6');
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",'7');
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",'8');
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$key2",'9');
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",'10');
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("K$key2",'11');
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->applyFromArray($style_a_10_bold_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("L$key2",'12');

                    // Строка с6-12_vsego
                    $key2++;
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2)->setRowHeight(20);
                    $objPHPExcel->getActiveSheet()->getStyle("A$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$xx+1);

                    $temp = "B$key2:B".($key2+4);
                    $objPHPExcel->getActiveSheet()->mergeCells("$temp");
                    $objPHPExcel->getActiveSheet()->getStyle("B".$key2)->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("B".($key2+1))->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("B".($key2+2))->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("B".($key2+3))->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("B".($key2+4))->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("B".$key2)->getAlignment()->setWrapText(true);

                    $temp = "C$key2:C".($key2+4);
                    $objPHPExcel->getActiveSheet()->mergeCells("$temp");
                    $objPHPExcel->getActiveSheet()->getStyle("C$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("C".$key2)->getAlignment()->setWrapText(true);
                    $temp = "C".($key2+1);    $objPHPExcel->getActiveSheet()->getStyle("$temp")->applyFromArray($style_a_8_c_c);
                    $temp = "C".($key2+2);    $objPHPExcel->getActiveSheet()->getStyle("$temp")->applyFromArray($style_a_8_c_c);
                    $temp = "C".($key2+3);    $objPHPExcel->getActiveSheet()->getStyle("$temp")->applyFromArray($style_a_8_c_c);
                    $temp = "C".($key2+4);    $objPHPExcel->getActiveSheet()->getStyle("$temp")->applyFromArray($style_a_8_c_c);

                    if ($key>0){
                        list($vidlg,$percent) = dbFetchRow(dbQuery("Select vidlg, tip from _vidlg where id = '$key'"));
                    }
                        else{
                            $vidlg = "Всего, по всем категориям граждан, имеющих право на скидку с оплаты электрической энергии";
                            $percent = "";
                    }

                    $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$vidlg);
                    if ($percent == 2) $percent = '100';
                        else $percent = $percent = '50';
                    $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$percent);

                    $temp = "D$key2:E$key2";
                    $objPHPExcel->getActiveSheet()->mergeCells("$temp");
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$key2","Всего");

                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['c6_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['c7_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['c8_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['c9_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['c10_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$str['c11_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['c12_vsego']);

                    // Строка с6-12_g_vsego
                    $key2++;
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2)->setRowHeight(20);
                    $objPHPExcel->getActiveSheet()->getStyle("A$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$xx+1 .".1 ");

                    $temp = "D$key2:D".($key2+1);
                    $objPHPExcel->getActiveSheet()->mergeCells("$temp");
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("D".($key2+1))->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$key2","в т.ч. город");

                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key2","всего");

                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['c6_g_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['c7_g_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['c8_g_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['c9_g_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['c10_g_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$str['c11_g_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['c12_g_vsego']);

                    // Строка с6-12_g_plita
                    $key2++;
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2)->setRowHeight(20);
                    $objPHPExcel->getActiveSheet()->getStyle("A$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$xx+1 .".2 ");

                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key2","в т.ч. с электроплитами");

                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['c6_g_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['c7_g_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['c8_g_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['c9_g_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['c10_g_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$str['c11_g_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['c12_g_plita']);

                    // Строка с6-12_s_vsego
                    $key2++;
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2)->setRowHeight(20);
                    $objPHPExcel->getActiveSheet()->getStyle("A$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$xx+1 .".3 ");

                    $temp = "D$key2:D".($key2+1);
                    $objPHPExcel->getActiveSheet()->mergeCells("$temp");
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("D".($key2+1))->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("D$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("D$key2","в т.ч. село");

                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key2","всего");

                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['c6_s_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['c7_s_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['c8_s_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['c9_s_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['c10_s_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$str['c11_s_vsego']);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['c12_s_vsego']);

                    // Строка с6-12_s_plita
                    $key2++;
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2)->setRowHeight(20);
                    $objPHPExcel->getActiveSheet()->getStyle("A$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->setCellValue("A$key2",$xx+1 .".4 ");

                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("E$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key2","в т.ч. с электроплитами");

                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("F$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$str['c6_s_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['c7_s_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("H$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['c8_s_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("I$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['c9_s_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("J$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['c10_s_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("K$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("K$key2",$str['c11_s_plita']);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->applyFromArray($style_a_8_c_c);
                    $objPHPExcel->getActiveSheet()->getStyle("L$key2")->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['c12_s_plita']);

                    $xx += 1;

		    if ($key2) $key2 = $key2 + 2;
        	    else $key2 = 11;

                }
		if ($resr == 0 and $key0 < (count($output_res)-1) ){
			$objSheet = clone $objSheet0;
			$objSheet->setTitle($res1[$key0]);
			$objPHPExcel->addSheet($objSheet);
		}
	    }
}        }
	Write_report("Форма_6", $objPHPExcel, $period_beg, $period_end, '', $key2, 0);
	echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
}



/********************************************************************/
/*    Используется только в ПИНСКЕ и в форме 6											*/
/********************************************************************/
function GetKvitFltrF6($kn, $nom, $uch="1", $last=false, $date_beg, $date_end, $cur_res) {

	$pokkv_old = 0;
	$pokaz_old = 0;
	$norma     = 0;
	$znak_2    = 0;
	$lstroka   = ""; // Строка, в которую будет сохраняться расшифровка расчетов льготника

	$ukazatel_na_obeshaniya_chinovnikov = 0;

	$islg = false;

	global $dblink;
	global $FCentar;
	global $FTarhist;

	$FCentar  = FAST_Centar();
	$FTarhist = FAST_TarifF6($kn, $nom, $uch, $cur_res);

    //if ($last) {
	//    $sql = "select id, sumkv, pokkv, penkv, meskv, imeskv, npachkv, oshkv, datekv
        //    from ".$cur_res."_kvit
        //    where knkv='".$kn."' and nomkv='".$nom."' and uchkv='".$uch."' and dlt=0 order by imeskv desc, YEAR(datekv) desc, MONTH(datekv) desc, DAY(datekv) desc, id desc";
    //}
    //else {
   // 	$sql = "select a.* , p.fio
   //         from ".$cur_res."_kvit a LEFT JOIN ".$cur_res."_personal p ON a.tbn=p.tbn
   //         where a.knkv='".$kn."' and a.nomkv='".$nom."' and a.uchkv='".$uch."' and dlt=0 order by a.imeskv desc, YEAR(a.datekv) desc, MONTH(a.datekv) desc, DAY(a.datekv) desc, a.id desc";
   // }
    $sql = "select * from ".$cur_res."_kvit  where knkv='".$kn."' and nomkv='".$nom."'  and uchkv='".$uch."' and dlt=0  and oshkv<>'8' and oshkv<>'9' and NPACHKV in (select NPACHKA from ".$cur_res."_pachka as p where p.dat_form >= '$date_beg' and p.dat_form <= '$date_end' and not (p.kassa LIKE 'АС') and (p.dlt = 0 or p.dlt is NULL)) order by imeskv desc, datekv desc, id desc";
	$res = db_ExtQuery($dblink, $sql);
	if ($res->Num_Rows>0) {
		$numbel = $res->Num_Rows;
		$res->Rows[$numbel-1]["realsumkv"] = ""; // Добавлено 16.02.2012
		if ($last) {
			$arfirst = $res->Rows[0];
			//pr($arfirst, false);
			$numbel = array_unshift($res->Rows, $arfirst);
            $temp = strtotime('-1 day',strtotime('01.'.date('m.Y')));
            $temp1_ = date('Y-m-d',$temp);
            $res->Rows[0]["meskv"] = date("m.Y",$temp);
			$res->Rows[0]["imeskv"] = date('Y-m-d',$temp);
            if (strtotime($temp1_) > strtotime($res->Rows[0]["datekv"]))
            {
				$res->Rows[0]["datekv"] = date('Y-m-d',$temp);
            }
			$res->Rows[0]["sumkv"] ="0";
			$res->Rows[0]["penkv"] ="0";
			$res->Rows[0]["oshkv"] ="1";
			//pr($res->Rows[0]);
		}

		$scmas=GetScF6($kn, $nom, $uch, $cur_res);
		$pokaz=0;
        // ************************************************************************ //
		//     Расчет квитанций >>                                                  //
        // ************************************************************************ //
		if ($numbel>0) {$pokaz=$res->Rows[$numbel-1]["pokkv"];}
		// По квитанциям с конца

        //pr($FTarhist);

        $num_last_tarif = count($FTarhist);

        //????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
		// Основной цикл - бегаем по квитанциям
		//&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;
		for ($k=$numbel-1; $k>=0;$k--) {
	          	$maxnorma = 0;
			$days_mes = date('t', strtotime($res->Rows[$k]["imeskv"]));
			$clear_imes=$res->Rows[$k]["imeskv"];
			$res->Rows[$k]["imeskv"] = mb_substr($res->Rows[$k]["imeskv"],0,8).$days_mes;
			$days_tar = array();  // Массив из количества дней в месяце, что проработал тариф до изменения на другой
			$dates = array();     // Массив дат изменения тарифа за месяц
			$kodes_tar = array(); // Массив кодов тарифов
			$pred_day=1;

			// Проверяем сколько записей в tarhist за этот месяц
			$a = 0;
			if ($num_last_tarif>0){
			    for ($n=$num_last_tarif-1;$n>=0;$n--) {
				    if ((strtotime($FTarhist[$n]["ddate"]) <= strtotime($res->Rows[$k]["imeskv"]))) {
					    $td = date("d", strtotime($FTarhist[$n]["ddate"]));
					    $tm = date("m", strtotime($FTarhist[$n]["ddate"]));
					    $ty = date("Y", strtotime($FTarhist[$n]["ddate"]));
					    $im = date("m", strtotime($res->Rows[$k]["imeskv"]));
					    $iy = date("Y", strtotime($res->Rows[$k]["imeskv"]));

                        if (($tm==$im) and ($ty==$iy)) {
						        $days_tar[$a] = ($td-$pred_day)/$days_mes;
						        $dates[$a] = $pred_day.".".date("m.Y", strtotime($res->Rows[$k]["imeskv"]));
						        //$kodes_tar[$a] = $FTarhist[$n]["idt"];
						        $pred_day = $td;
						        $a++;
                                $num_last_tarif = $num_last_tarif++;
                        }
                        $kodes_tar[0] = $FTarhist[$n]["idt"];
				    }
			    }
            }
			//pr($kodes_tar, false);
            if (!isset($kodes_tar[0])) { $kodes_tar[0] = $FTarhist[0]["idt"]; }
			$dates[$a] = $pred_day.".".date("m.Y", strtotime($res->Rows[$k]["imeskv"]));
			$days_tar[$a] = ($days_mes-$pred_day+1)/$days_mes;


	// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
	// Великое РАЗДЕЛЕНИЕ ТАРИФОВ НА ДИФ, КАК ОБЕЩАЛИ ЧИНОВНИКИ ВРЕМЕННОЕ ДО 15-го года $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
	// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
		//pr($dates, false);
			if (isset($dates[0])) {
				// Если дата установки больше 01.02.2013 и код тарифа больше 4-х - значит новые правила расчета
				//pr($kodes_tar, false);
				if ((strtotime($dates[0])>=strtotime('01.02.2013')) and ($kodes_tar[0]>4)) { $ukazatel_na_obeshaniya_chinovnikov = 1; } // С этого момента всё остальные записи пойдут по новым диф тар.
				//if (($dates[0]=='01.02.2013') and ($kodes_tar[0]>4)) { $ukazatel_na_obeshaniya_chinovnikov = 1; } // С этого момента всё остальные записи пойдут по новым диф тар.
			}
			if ($ukazatel_na_obeshaniya_chinovnikov == 1) {
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// Рассчеты по НОВЫМ тарифам !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

			// $res->Rows - содержит квитанции
			// $k - счетчик квитанций с конца
			// $numbel - кол-во квитанций

			//pr($res->Rows, false);
 						$dates[0] = $pred_day.".".date("m.Y", strtotime($res->Rows[$k]["imeskv"]));
						$days_tar[0] = ($days_mes-$pred_day+1)/$days_mes;

						$skvhour = 0;
						$star    = 0;
						$spokaz  = 0;
			            $kol_days_norma = 0;
			            $stroka = "";

						// Проверяем следующую квитанцию, может она = начало расчета - тогда ее рассчитать сразу без суммирования
			            if (($k==0) or ($k==$numbel-1) or ($clear_imes!=$res->Rows[$k-1]["imeskv"]) or ($res->Rows[$k]["oshkv"]=="8") or ($res->Rows[$k]["oshkv"]=="9") or ($res->Rows[$k-1]["oshkv"]=="8") or ($res->Rows[$k-1]["oshkv"]=="9")) // Это проверка на двойную проплату за месяц
						{
								$oplata=0;
								$alltarif= TarifF6($kn, $nom, $uch, $dates[0], $cur_res);
								//pr($alltarif);
								if ($alltarif["idl"]) {$islg = true;} else {$islg = false;}
								$kodtar = $alltarif["kod"]; // Код тарифа
								// Для учета значности счетчика по началу расчета
								if ($res->Rows[$k]["oshkv"]>="8") { $znak = mb_strlen($res->Rows[$k]["pokkv"]); $znak_2 = $znak;}
								else {
									if ($znak_2 == 0) {
										$znak = GetZnak($scmas, $res->Rows[$k]["datekv"]);
									} else {$znak = $znak_2; $znak_2 = 0;}
								}

								$sem = $alltarif["tarhist"];
			                    $fulltar = $alltarif["cena"];
								if ($sem) {
									if (count($sem)<=0) {$q=0;} // На тот случай, если оплатили на дату раньше, чем завели карточку
									$res->Rows[$k]["mt"] = $sem["mt"];
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
			                        }
									$semya = $sem["semya"];
									$semlg = $sem["semlg"];
									if ($semya==0) {$semya=1;}
								} else {
									 $semya = 1;
									 $semlg = 0;
								}

						//		$tar = round($fulltar * ($semya-SemLg($alltarif["idl"]))/$semya,2);
						                $tar = round($fulltar * ($semya-$semlg*0.5)/$semya, 6);
								if (($res->Rows[$k]["oshkv"]=="8") or (($res->Rows[$k]["oshkv"]=="9"))) {
			                        $pokaz=$res->Rows[$k]["pokkv"]; // Если была замена счетчика - начинаем отсчет с первых показаний
			                     // вставка Пинск
			                        $znak = mb_strlen($res->Rows[$k]["pokkv"]); $znak_2 = $znak;
			                    }

						if ($alltarif["norma"]>0) { // Если есть норма
                                                    // Узнаем количество учетов для нормы
			                            if (isset($sem["mt"])) {
			                            	if ($sem["mt"]>0) {$uch_count = 2;}  else {$uch_count =1;}
			                            } else { $uch_count =1; }

			                            $alltarif["norma"] = $alltarif["norma"]/$uch_count;
					            $norma = $alltarif["norma"];
                    			            $maxnorma=$norma;
						}
						else { // Если нормы нет
						     $norma = 0;
						     $normsum = 0;
                                                }


								//------------------------------------------------------------------
								// Получаем список промежутков норм и их тарифов с учетом льготников
								$mass_tar = Get_mass_tar($alltarif, $semya, $semlg);
								//pr($k, false);
								//pr($mass_tar, false);
								//------------------------------------------------------------------
								$mas_shifr = array();
								// В цикле бегаем по массиву норм
								$kvhour = 0;
								$sum = ($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]);
								for ($i=0;$i<count($mass_tar);$i++) {
									//pr($sum, false);
									//pr($mass_tar[$i]["tar"], false);
									$k1 = $sum/$mass_tar[$i]["tar"];
									//pr($k1, false);
									if ($k1>($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"])) {
										$kvhour = $kvhour + $mass_tar[$i]["p2"]-$mass_tar[$i]["p1"];
										$sum = $sum - ($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"]) * $mass_tar[$i]["tar"];
										$mas_shifr[$i]["tar"] = round($mass_tar[$i]["tar"], 6);
										$mas_shifr[$i]["kvt"] = round($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"], 1);
										$mas_shifr[$i]["sum"] = round(($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"]) * $mass_tar[$i]["tar"], 6);
										//pr($mas_shifr[$i], false);
									} else {
										$kvhour = $kvhour + $k1;
										$mas_shifr[$i]["tar"] = round($mass_tar[$i]["tar"], 6);
										$mas_shifr[$i]["kvt"] = round($k1, 1);
										$mas_shifr[$i]["sum"] = round($k1 * $mass_tar[$i]["tar"], 6);
										//pr($mas_shifr[$i], false);
										break;
									}
								}
								//pr($mas_shifr, false);
								$res->Rows[$k]["mas_shifr"] = $mas_shifr;

								//$skvhour = $kilovaty;
								//if ($k!=$numbel-1) { // Если первая квитанция - то считать ее как начало расчета
									$skvhour= $skvhour + $kvhour;
									$star= $star + $tar;
								//}
								$stroka[] = $lstroka;

			            	// Эта строка для вывода реальной суммы квитанции, т.к. другая нужна для расчетов и она суммируется
			            	if ($k!=0) { $res->Rows[$k-1]["realsumkv"]= $res->Rows[$k-1]["sumkv"]; }
						} else {
			                // Суммируем с предыдущей, если две проплаты за месяц
			                $res->Rows[$k-1]["realsumkv"]= $res->Rows[$k-1]["sumkv"];
							$res->Rows[$k-1]["sumkv"]= $res->Rows[$k-1]["sumkv"]+$res->Rows[$k]["sumkv"];
							$res->Rows[$k-1]["penkv"]= $res->Rows[$k-1]["penkv"]+$res->Rows[$k]["penkv"];
						}

			} else {
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// Рассчеты по СТАРЫМ тарифам !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
						$dates[$a] = $pred_day.".".date("m.Y", strtotime($res->Rows[$k]["imeskv"]));
						$days_tar[$a] = ($days_mes-$pred_day+1)/$days_mes;

						//pr($days_tar, false);
						$skvhour = 0;
						$star    = 0;
						$spokaz  = 0;
			            $kol_days_norma = 0;
			            $stroka = "";
			            // Проверяем следующую квитанцию, может она = начало расчета - тогда ее рассчитать сразу без суммирования
			            if (($k==0) or ($k==$numbel-1) or ($clear_imes!=$res->Rows[$k-1]["imeskv"]) or ($res->Rows[$k]["oshkv"]=="8") or ($res->Rows[$k]["oshkv"]=="9") or ($res->Rows[$k-1]["oshkv"]=="8") or ($res->Rows[$k-1]["oshkv"]=="9")) // Это проверка на двойную проплату за месяц
						{
							for ($n=0; $n<count($days_tar);$n++) {
								$oplata=0;
								$alltarif= TarifF6($kn, $nom, $uch, $dates[$n], $cur_res);

								if ($alltarif["idl"]) {$islg = true;} else {$islg = false;}
								$kodtar = $alltarif["kod"]; // Код тарифа
								// Для учета значности счетчика по началу расчета
								if ($res->Rows[$k]["oshkv"]>="8") { $znak = mb_strlen($res->Rows[$k]["pokkv"]); $znak_2 = $znak;}
								else {
									if ($znak_2 == 0) {
										$znak = GetZnak($scmas, $res->Rows[$k]["datekv"]);
									} else {$znak = $znak_2; $znak_2 = 0;}
								}

								$sem = $alltarif["tarhist"];
			                    $fulltar = $alltarif["cena"];
								if ($sem) {
			                        $q = count($days_tar)-$n-1;  // Вычисляем какой из тарифов за этот месяц взять (если их 2)
									if (count($sem)<=$q) {$q=0;} // На тот случай, если оплатили на дату раньше, чем завели карточку
									$res->Rows[$k]["mt"] = $sem["mt"];
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
			                        }
									$semya = $sem["semya"];
									$semlg = $sem["semlg"];
									if ($semya==0) {$semya=1;}
								} else {
									 $semya = 1;
									 $semlg = 0;
								}
						//$tar = round($fulltar * ($semya-SemLg($alltarif["idl"]))/$semya,2);
						$tar = round($fulltar * ($semya-$semlg*0.5)/$semya, 6);
								if (($res->Rows[$k]["oshkv"]=="8") or (($res->Rows[$k]["oshkv"]=="9"))) {
			                        $pokaz=$res->Rows[$k]["pokkv"]; // Если была замена счетчика - начинаем отсчет с первых показаний
			                     // вставка Пинск
			                        $znak = mb_strlen($res->Rows[$k]["pokkv"]); $znak_2 = $znak;
			                    }
								// Если есть льготник
								$lstroka = $dates[$n]."|";
								$lstroka = $lstroka.$fulltar."|".$tar."|";
								$lstroka = $lstroka.round($days_tar[$n]*$days_mes)."|";
								if  ($tar!=$fulltar) {
									if ($alltarif["norma"]>0) { // Если есть норма
			                            $kol_days_norma = $kol_days_norma + $days_tar[$n];
			                            // Узнаем количество учетов для нормы
			                            if (isset($sem["mt"])) {
			                            	if ($sem["mt"]>0) {$uch_count = 2;}  else {$uch_count =1;}
			                            } else { $uch_count =1; }

			                            $alltarif["norma"] = $alltarif["norma"]/$uch_count;
			                            $lstroka = $lstroka.round($alltarif["norma"]*$days_tar[$n])."|";
										$normsum = $alltarif["norma"] * $semya * $tar;

										// Оплата по норме
										if ($normsum>=($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"])) { // Если вписываемся в норму
											if ($tar!=0) {$kvhour = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]) / $tar);} else {$kvhour=0;}
											$aa = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"])*$days_tar[$n], 6);
											$lstroka = $lstroka.$aa."|";
											if ($tar!=0) {$aa = round($aa / $tar, 6);}
											$lstroka = $lstroka.$aa."|";
										} else {// Если выше нормы
											if ($tar!=0) {$kvhour = round($normsum / $tar);} else {$kvhour=0;}
											$lstroka = $lstroka.round($normsum*$days_tar[$n], 6)."|";
											if ($tar!=0) { $lstroka = $lstroka.round($normsum*$days_tar[$n] / $tar, 6)."|"; } else { $lstroka = $lstroka."|"; }
											$kvhour = $kvhour + round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"] - $normsum) / $fulltar);
											$aa = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]-$normsum)*$days_tar[$n], 6);
											$lstroka = $lstroka.$aa."|";
											$aa = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]-$normsum)*$days_tar[$n] / $fulltar, 6);
											$lstroka = $lstroka.$aa."|";
										}
										$norma = $alltarif["norma"];
                    			                            $maxnorma=$norma;
									}
									else { // Если нормы нет
										$norma = 0;
										$normsum = 0;
										if ($tar!=0) {$kvhour = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]) / $tar);} else {$kvhour=0;}
									}
								}
								else { // Если нет льготника
									if ($fulltar!=0) { // На тот случай если тарифа еще нет или он 0
										$kvhour = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]) / $fulltar);
									} else {$kvhour = 0;}
									$tar=$fulltar;
									$norma=0;
									if ($res->Rows[$k]["oshkv"]==0) { // Если не начало расчета, то формируем строку
										$aa = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"])*$days_tar[$n], 6);
										$lstroka = $lstroka."|||".$aa."|";
										$aa = round(($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"])*$days_tar[$n] / $tar, 6);
										$lstroka = $lstroka.$aa."|";
									}
								}
								//if ($k!=$numbel-1) { // Если первая квитанция - то считать ее как начало расчета
									$skvhour= $skvhour+ $kvhour*$days_tar[$n];
									$star= $star+ $tar*$days_tar[$n];
								//}
								$stroka[] = $lstroka;
							}
			            	// Эта строка для вывода реальной суммы квитанции, т.к. другая нужна для расчетов и она суммируется
			            	if ($k!=0) { $res->Rows[$k-1]["realsumkv"]= $res->Rows[$k-1]["sumkv"]; }
						} else {
			                // Суммируем с предыдущей, если две проплаты за месяц
			                $res->Rows[$k-1]["realsumkv"]= $res->Rows[$k-1]["sumkv"];
							$res->Rows[$k-1]["sumkv"]= $res->Rows[$k-1]["sumkv"]+$res->Rows[$k]["sumkv"];
							$res->Rows[$k-1]["penkv"]= $res->Rows[$k-1]["penkv"]+$res->Rows[$k]["penkv"];
						}
			}
			$res->Rows[$k]["islg"] = $islg; // Есть ли льгота в этой квитанции
			$skvhour = round($skvhour);
			$pokaz = $pokaz + $skvhour;
			if ($pokaz>=pow(10, $znak)) {$pokaz=$pokaz-pow(10, $znak);}  // Переход через ноль
			if (mb_strlen($pokaz)>$znak) {$pokaz = $pokaz % pow(10, $znak);} // Отсекаем старшие разряды
			$res->Rows[$k]["imeskv"] = dodate($res->Rows[$k]["imeskv"],1);
			$res->Rows[$k]["datekv"] = dodate($res->Rows[$k]["datekv"]);
			$res->Rows[$k]["kvhour"] =$skvhour;
			$res->Rows[$k]["tarif"] = round($star, 6);
			$res->Rows[$k]["pokaz"]= ForZn(round($pokaz), $znak);
			//echo $res->Rows[$k]["pokaz"].' '.$znak.'<br>';
			$res->Rows[$k]["norma"] = $maxnorma*$semya;
			$res->Rows[$k]["norma_lg"] = $norma*$semlg;
			$res->Rows[$k]["fulltar"]=$fulltar;
			$res->Rows[$k]["kodtar"] = $kodtar;
			$res->Rows[$k]["semlg"] = $semlg;
			$res->Rows[$k]["semya"] = $semya;
			$res->Rows[$k]["koef_norma"] = $kol_days_norma;
			$pokkv_razn = $res->Rows[$k]["pokkv"] - $pokkv_old;
			$pokaz_razn = $res->Rows[$k]["pokaz"] - $pokaz_old;
			$pokkv_old = $res->Rows[$k]["pokkv"];
			$pokaz_old = $res->Rows[$k]["pokaz"];
			if ($pokkv_razn!=$pokaz_razn) {$res->Rows[$k]["errkvit"] = 1;} else {$res->Rows[$k]["errkvit"] = 0;}
			$res->Rows[$k]["stroka"] = $stroka;


		}
		$res->Rows[0]["norma"]=$maxnorma*$semya;
		$res->Rows[0]["fulltar"]=$fulltar;
	}
	return($res->Rows);
}

//*******************************************************************************************************************
function FAST_TarifF6($kn, $nom, $uch, $cur_res)
{
	global $dblink;
   	$sql = "select id, idt, idl, mt, ddate, semya, semlg
            from ".$cur_res."_tarhist_sem a
            where a.kn='".$kn."' and a.nom='".$nom."' and a.uch='".$uch."' and dlt=0
            order by ddate desc, id desc";
	$tarhist = db_ExtQuery($dblink, $sql);
	return $tarhist->Rows;
}

//*******************************************************************************************************************
function GetScF6($kn, $nom, $uch, $cur_res)
{
	global $dblink;
	$sql = "select a.maxn, a.dateust from ".$cur_res."_mainsc a where a.kn='".$kn."' and a.nom='".$nom."' and dlt=0 order by dateust desc, a.id desc";
	$res = db_ExtQuery($dblink, $sql);
	if ($res->Num_Rows>0) {return ($res->Rows);} else {return false;};
}

//*******************************************************************************************************************
function TarifF6($kn, $nom, $uch, $imes, $cur_res){
	global $dblink;

	$tarhist = Get_Tarif($imes);
	if ($tarhist!="") {
		// Узнаем код тарифа
		$centar = Get_Centar($tarhist["idt"], $imes);
		// Проверяем есть ли льготники
		if ($tarhist["semlg"]>0) {
	    	$sql = "select a.idl, b.tip from ".$cur_res."_sem_lg a, _vidlg b where a.idtarhist='".$tarhist["id"]."' and a.idl=b.id";
			$lg = db_ExtQuery($dblink, $sql);
			$centar["idl"]=$lg->Rows; // Сохраняем весь список льготников
		}
		else {$centar["idl"]="";}
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

//*******************************************************************************************************************
function GetCalculatorFullF6($knnom, $kvtch, $uch=1, $ddate="", $cur_res) {

	global $FCentar;
	global $FTarhist;

	$kn = mb_substr($knnom,0,4);
	$nom = mb_substr($knnom,4,3);

	$FCentar = FAST_Centar();
	$FTarhist = FAST_TarifF6($kn, $nom, $uch, $cur_res);

	if ($ddate=="") { $ddate = date('d.m.Y'); }
	$alltarif = TarifF6($kn, $nom, $uch, $ddate, $cur_res);
	$itogo = array();
	$it = 0;
	// Если новые тарифы
	if ($alltarif["tarhist"]["idt"]>4) {
		$mass_tar = Get_mass_tar($alltarif, $alltarif["tarhist"]["semya"],0);
		$str='';
		$summ = 0;
		for ($k=0;$k<count($mass_tar);$k++) {
			if ($kvtch<($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])) {
				$summ = $summ + $kvtch*$mass_tar[$k]["tar"];
				$itogo[$k]["kvt"] = $kvtch;
				$itogo[$k]["tar"] = $mass_tar[$k]["tar"];
				$itogo[$k]["summ"] = $kvtch*$mass_tar[$k]["tar"];
				$it = $it + $itogo[$k]["summ"];
				//$str= $str.'(<b>'.$kvtch.'</b> * '.$mass_tar[$k]["tar"].') ';
				break;
			} else {
				$summ = $summ + ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])*$mass_tar[$k]["tar"];
				$kvtch = $kvtch - ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"]);
				$a = ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"]);

				$itogo[$k]["kvt"] = $mass_tar[$k]["p2"]-$mass_tar[$k]["p1"];
				$itogo[$k]["tar"] = $mass_tar[$k]["tar"];
				$itogo[$k]["summ"] = ($mass_tar[$k]["p2"]-$mass_tar[$k]["p1"])*$mass_tar[$k]["tar"];
				$it = $it + $itogo[$k]["summ"];
			}
		}
	} else {
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
	        }
			$semya = $sem["semya"];
			$semlg = $sem["semlg"];
			if ($semya==0) {$semya=1;}
		} else {
			 $semya = 1;
			 $semlg = 0;
		}

		$tar = round($fulltar * ($semya-SemLg($alltarif["idl"]))/$semya, 6);
		$itogo[0]["kvt"] = $kvtch;
		$itogo[0]["tar"] = $tar;
		$itogo[0]["summ"] = round($kvtch*$tar, 6);
		$it = $itogo[0]["summ"];
	}
	$itogo[0]["sum"] = $it;
	return $itogo;
}

function get_is_from_cityF6($kn,$nom,$cur_ress){
	$output = 0;
	$query = "
		SELECT     _tip_np.tip_np, _tip_np.is_city
			FROM         _tip_np INNER JOIN ".$cur_ress."_np ON _tip_np.id = ".$cur_ress."_np.id_tip
				WHERE     (".$cur_ress."_np.id =
                	(SELECT     id_np
                    	FROM	_tip_street INNER JOIN ".$cur_ress."_street ON _tip_street.id = ".$cur_ress."_street.id_tip_street
                            WHERE (".$cur_ress."_street.id =
                            	(SELECT     id_ul
                            		FROM ".$cur_ress."_dom	WHERE id =
                                    	(SELECT     id_dom
                                        	FROM          ".$cur_ress."_main
                                            	WHERE      kn = '$kn' AND nom = '$nom' AND (dlt is NULL or dlt = 0))))))
	";
	$res = dbQuery($query);
	if (dbRowsCount($res)){
		$row = dbFetchAssoc($res);
		return  $row['is_city'];
	}
	else {
		return 0;
	}
}
?>