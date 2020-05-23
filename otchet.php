<?php
require_once('include/core.php');
require_once('include/lib_general.php');
require_once('include/lib_db.php');

$smarty->assign('current_date',date(DATE_FORMAT_SHOT));
$smarty->assign('current_year',date("Y"));
$smarty->assign('prev_year_date',date(DATE_FORMAT_SHOT,strtotime('-1 year',strtotime('01.'.date('m.Y')))));
$smarty->assign('current_month_b','01.'.date('m.Y'));
$smarty->assign('current_month_e',date('d.m.Y', strtotime("last day of this month")));
$prev_month_b = '01.'.date('m.Y',strtotime('-25 day'));
$prev_month_e = date('d.m.Y',strtotime('-1 day',strtotime('+1 month',strtotime($prev_month_b))));
$smarty->assign('prev_month_b',$prev_month_b);
$smarty->assign('prev_month_e',$prev_month_e);
$prev_month_e_3mon = date('d.m.Y',strtotime('-2 month',strtotime($prev_month_b)));
$smarty->assign('prev_month_e_3mon',$prev_month_e_3mon);
$smarty->assign('main_area','report/general.html');

	$title = 'Бытовая';
	$main = '';
	$errors = array();
	$smarty->assign("title", $title);
	$smarty->assign("main",$main);

	$a = get_input('a');
	$step = get_input('step');

	if ($a == 'f_01'){
		$action = "otchet_form";
		$temp = REPORT_TEMPLATES_DIR.'FORMA_1_template.htm';
		if (is_file($temp)){
			$temp = str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp);
			$smarty->assign('template',$temp);
		}
		else
			$smarty->assign('template','');

        $smarty->assign('otchet_name', 'Ведомость оплаты (Форма 1)');
        $smarty->assign('title','Отчеты: Ведомость оплаты (Форма 1)');
        $smarty->assign('main_area','report/f_01.html');
	}

	elseif ($a == 'common_report'){
        $action = 'otchet_form';
        $temp = REPORT_TEMPLATES_DIR.'common_report_template.htm';
        if (is_file($temp)){
            $smarty->assign('template',str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp));
        }
        else $smarty->assign('template','');
        $smarty->assign('nres',dbFetchArray(dbQuery("SELECT * FROM _res where type_res='".CURRENT_RES."' order by id ")));
        $smarty->assign("otchet_name", "Информация по общему отчёту");
        $smarty->assign('title','Отчеты: Информация по общему отчёту');
        $smarty->assign('main_area','report/common_report.html');
    }

	elseif ($a == 'lgotniki_prinadl'){
		$action = "otchet_form";
		/********************************************************************************/
		/*                         Формирование списка принадлежности                   */
		/********************************************************************************/
		$query = "Select id, tipps from ".CURRENT_RES."_tip_prinadl where label_dom=1 order by tipps";
		$res = dbQuery($query);
		$smarty->assign("tp", $res->FetchAll());
		/********************************************************************************/
		/*                           Формирование списка сельсоветов                    */
		/********************************************************************************/
		$query = "Select region from ".CURRENT_RES."_region where region <> '' GROUP BY region order by region";
		$res = dbQuery($query);
		$smarty->assign('ss', $res->FetchAll());

        $smarty->assign('otchet_name', 'Списки льготников по принадлежности');
        $smarty->assign('title','Отчеты: Списки льготников по принадлежности');
        $smarty->assign('main_area','report/lgotniki_prinadl.html');
	}

    /************************************************************************************/
    /*  По предоставлению отдельным категориям граждан льгот по оплате э/э (ФОРМА 6)    */
    /************************************************************************************/
	elseif ($a == 'f_06'){
		$action = "otchet_form";
		$smarty->assign('otchet_name', 'По предоставлению отдельным категориям граждан льгот по оплате электроэнергии (ФОРМА 6)');
        $smarty->assign('nres',dbFetchArray(dbQuery("SELECT * FROM _res where type_res='".CURRENT_RES."' order by id ")));
        $smarty->assign('title','Отчеты: По предоставлению отдельным категориям граждан льгот по оплате электроэнергии (ФОРМА 6)');
        $smarty->assign('main_area','report/f_06.html');
	}

	/********************************************************************************/
	/*                 Задолженность населения за электроэнергию                    */
	/********************************************************************************/
	elseif ($a == 'f_04'){
        $action = "otchet_form";
        /********************************************************************************/
        /*              Формирование списка сельсоветов(регионов)                       */
        /********************************************************************************/
        $query = "Select id_np, region from ".CURRENT_RES."_region where region <> '' order by id_np asc, region asc";
        $res = dbQuery($query);
        $ss = array();
        while ($row = dbFetchAssoc($res)){
            if (!in_array($row['region'],$ss)) $ss[] = $row['region'];
        }
        $smarty->assign("ss", $ss);

        $query = "Select * from _tip_np";
        $res = dbQuery($query);
        $smarty->assign('tipnasp',dbFetchArray($res));
        $smarty->assign('nres',dbFetchArray(dbQuery("SELECT * FROM _res where type_res='".CURRENT_RES."' order by id ")));
        $temp = REPORT_TEMPLATES_DIR.'FORMA_4_template.htm';
        if (is_file($temp)){
            $temp = str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp);
            $smarty->assign('template',$temp);
        }
        else
            $smarty->assign('template','');

        $smarty->assign('otchet_name', 'Задолженность населения за электроэнергию');
        $smarty->assign('title','Отчеты: Задолженность населения за электроэнергию');
        $smarty->assign('main_area','report/f_04.html');
    }
	/********************************************************************************/
	/*                 Неплательщики свыше двух месяцев                             */
	/********************************************************************************/
	elseif ($a == 'f_08'){
        $action = "otchet_form";
        $smarty->assign('nres',dbFetchArray(dbQuery("SELECT * FROM _res where type_res='".CURRENT_RES."' order by id ")));
        $temp = REPORT_TEMPLATES_DIR.'FORMA_8_template.htm';
        if (is_file($temp)){
            $temp = str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp);
            $smarty->assign('template',$temp);
        }
        else
            $smarty->assign('template','');

        $smarty->assign('otchet_name', 'Сведения по неплательщикам');
        $smarty->assign('title','Отчеты: Сведения по неплательщикам');
        $smarty->assign('main_area','report/f_08.html');
    }

    /********************************************************************************/
    /*              АКТ СВЕРКИ поступления платежей от населения                    */
    /********************************************************************************/
    elseif ($a == 'f_05'){
        $action = "otchet_form";
        $temp = REPORT_TEMPLATES_DIR.'FORMA_5_template.htm';
        if (is_file($temp)){
            $temp = str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp);
            $smarty->assign('template',$temp);
        }
        else
            $smarty->assign('template','');
        // Приёмщики платежей
        $smarty->assign('plpr',dbFetchArray(dbQuery("SELECT plpr.* FROM _plpr as plpr order by plpr.npr ")));
        $smarty->assign('otchet_name', "Акт сверки поступления платежей от населения (Форма 5)");
        $smarty->assign('title','Отчеты: Акт сверки поступления платежей от населения (Форма 5)');
        $smarty->assign('main_area','report/f_05.html');
    }
    /********************************************************************************/
    /* Структура учётов ЭЭ у бытовых абонентов в разрезе тарифов                    */
    /********************************************************************************/
    elseif ($a == 'f_07') {
        $temp = REPORT_TEMPLATES_DIR.'FORMA_7_template.htm';
        if (is_file($temp)){
            $smarty->assign('template',str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp));
        }
        else $smarty->assign('template','');
        $date1 = "26.".date("m.Y",strtotime("-50 days"));
        $date2 = "25.".date("m.Y",strtotime("-25 days"));
        $smarty->assign("date1",$date1);
        $smarty->assign("date2",$date2);
        $smarty->assign('nres',dbFetchArray(dbQuery("SELECT * FROM _res where type_res='".CURRENT_RES."' order by id ")));
	$smarty->assign('otchet_name', 'Учет движения счетчиков в разрезе тарифов');
	$smarty->assign('title','Отчеты: Учет движения счетчиков в разрезе тарифов');
        $smarty->assign('main_area','report/f_07.html');
	}

	/********************************************************************************/
	/*                 Абоненты в разрезе типов построек		                    */
	/********************************************************************************/
	elseif ($a == 'tipy_postroek') {
		$action = 'otchet_form';
		$smarty->assign('otchet_name', 'Абоненты в разрезе типов построек');
		$smarty->assign('title','Отчеты: Абоненты в разрезе типов построек');
        $smarty->assign('main_area','report/tipy_postroek.html');
	}

	/********************************************************************************/
	/*              	Конструктор отчетов      		 		         	        */
	/********************************************************************************/
    elseif ($a == 'f_constructor') {
	    $action = "otchet_form";
    	//населенные пункты
	    $smarty->assign('np',dbFetchArray(dbQuery("Select a.*, b.tip_np from ".CURRENT_RES."_np as a, _tip_np as b where a.id_tip = b.id order by b.tip_np, a.np ")));
	    //регионы
	    $smarty->assign("region", dbFetchArray(dbQuery("Select region from ".CURRENT_RES."_region GROUP BY region order by region")));
	    //типа счетчиков
	    $smarty->assign('sc',dbFetchArray(dbQuery("SELECT id, tipch, v, a, label, dlt FROM _tip_sc order by label, tipch")));
	    //типы построек
	    $smarty->assign('tip_postr',dbFetchArray(dbQuery("SELECT * FROM _tip_postr order by postr")));
	    //типы льгот
	    $smarty->assign('vidlg',dbFetchArray(dbQuery("SELECT * FROM _vidlg where (datee is NULL) or (datee > '".date(DATE_FORMAT_YMD)."') order by vidlg")));
	    //тарифы
	    $smarty->assign('vidtar',dbFetchArray(dbQuery("SELECT * FROM _vidtar order by vidtar")));
        //состав семьи
        $smarty->assign('sostav_semjis',dbFetchArray(dbQuery("SELECT semya FROM ".CURRENT_RES."_tarhist_sem group by semya order by cast(semya as unsigned)")));
	    //СЧЕТЧИКИ: класс точности
        $smarty->assign('klass_toch',dbFetchArray(dbQuery("SELECT toch FROM _tip_sc GROUP BY toch")));
        //принадлежность счетчиков
	    $smarty->assign('tip_prinadl_sc',dbFetchArray(dbQuery("SELECT * FROM ".CURRENT_RES."_tip_prinadl where label_sc = 1 order by tipps")));
	    //принадлежность домов -> должно быть тоже самое, т.к. пока используется один и тот же справочник и для того и для другого
	    $smarty->assign('tip_prinadl_dom',dbFetchArray(dbQuery("SELECT * FROM ".CURRENT_RES."_tip_prinadl where label_dom = 1 order by tipps")));
        // КТП, линия, к которой подключен потребитель
	    $smarty->assign('ktp',dbFetchArray(dbQuery("SELECT id, tip_lep, n_lep, id_ps, tip_ps, n_ps FROM ".CURRENT_RES."_ktp order by n_ps, n_lep")));
        // NOMR - Доп Информация по абоненту
        $smarty->assign('nomrs',dbFetchArray(dbQuery("SELECT id, simv, nomr FROM _spr_nomr order by nomr")));
        // otkl - Отключения
        $smarty->assign('otkls',dbFetchArray(dbQuery("SELECT id, simv, otkl FROM _spr_otkl order by otkl")));

        // Персонал
        $smarty->assign('personal',dbFetchArray(dbQuery("SELECT tbn, fio FROM ".CURRENT_RES."_personal order by fio")));

        // года выпуска счетчиков
        $smarty->assign('sc_yearvyps',dbFetchArray(dbQuery("SELECT yearvyp FROM ".CURRENT_RES."_mainsc where(".CURRENT_RES."_mainsc.dlt is NULL or ".CURRENT_RES."_mainsc.dlt = 0) group by yearvyp order by cast(yearvyp as integer)")));
        // производитель счетчиков
        $smarty->assign('sc_izgs',dbFetchArray(dbQuery("SELECT izg FROM _tip_sc group by izg order by izg")));
        $smarty->assign('nres',dbFetchArray(dbQuery("SELECT * FROM _res where type_res='".CURRENT_RES."' order by id ")));

        $smarty->assign('otchet_name', 'Конструктор отчета');
	    $smarty->assign('title','Отчеты: Конструктор отчета');
        $smarty->assign('main_area','report/f_constructor.html');
	}
    elseif ($a == 'lose_lg'){
        $action = 'lose_lg';
        $smarty->assign("otchet_name", "Отчет по потере льготников (В разработке)");
        $smarty->assign('title','Отчеты: Отчет по потере льготников (В разработке)');
        $temp = REPORT_TEMPLATES_DIR.'lose_lg.htm';
        if (is_file($temp)) $smarty->assign('template',str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp));
        $smarty->assign('main_area','report/lose_lg.html');
    }
    /********************************************************************************/
    /*                 Реестр возврата квитанций (за услуги)                        */
    /********************************************************************************/
    elseif ($a == 'reestr_vozv'){
        $action = 'lose_lg';
        $smarty->assign("otchet_name", "Реестр возврата квитанций (за услуги)");
        $smarty->assign('title','Отчеты: Реестр возврата квитанций (за услуги)');
        $temp = REPORT_TEMPLATES_DIR.'Reestr_vozvrata_kvit_template.htm';
        if (is_file($temp)) $smarty->assign('template',str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp));
        $smarty->assign('main_area','report/reestr_vozv.html');
    }
    /********************************************************************************/
    /*    КОНТРОЛЬНО-НАКОПИТЕЛЬНАЯ ВЕДОМОСТЬ поступления платежей от населения      */
    /********************************************************************************/
    elseif ($a == 'kontr_nak_ved'){
        $action = "otchet_form";
        $temp = REPORT_TEMPLATES_DIR.'kontr_nak_ved_template.htm';
        if (is_file($temp)){
            $temp = str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp);
            $smarty->assign('template',$temp);
        }
        else
            $smarty->assign('template','');
        $smarty->assign('otchet_name', "Контрольно-накопительная ведомость поступления платежей");
        $smarty->assign('title','Отчеты: Контрольно-накопительная ведомость поступления платежей');
        $smarty->assign('main_area','report/kontr_nak_ved.html');
    }
    /********************************************************************************/
    /*    О привязке точек учета      */
    /********************************************************************************/
    elseif ($a == 'priviazka'){
        $action = "otchet_form";
        $temp = REPORT_TEMPLATES_DIR.'priviazka.htm';
        if (is_file($temp)){
            $temp = str_replace(SERVER_ROOT_DIR,SERVER_ROOT,$temp);
            $smarty->assign('template',$temp);
        }
        else {
            $smarty->assign('template','');
        }
        $smarty->assign('otchet_name', "О привязке точек учета к распредсети");
        $smarty->assign('title','Отчеты: О привязке точек учета к распредсети');
        $smarty->assign('main_area','report/priviazka.html');
    }


	if ($a == '') $a = "general";

	if ($a == 'general'){
		$smarty->assign('title','Отчеты');
	    $smarty->assign('main_area','report/general.html');
	}
    $smarty->display('layout.html');
?>