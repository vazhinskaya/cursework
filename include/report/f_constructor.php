<?php
$arc_color = '969696';
$normal_color = '000000';
$cols_ABC = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP');

    $output_constructor = array();
    $output_constructor_cell = array(
                'col_abc' => 'A',
                'width'   => 10,
                'rows_num'=> 1,
                'data'    => array()
    );

	/*******************************************************/
	/*      ПОЛУЧЕНИЕ ВХОДНЫХ ПАРАМЕТРОВ                   */
	/*******************************************************/
    $resr = get_input('nres','int');

    if ($resr==0) $res2=" 1=1 ";
    else          $res2=" (dom.id_res=".$resr.") ";

    // Период расчета
    $with_date = get_input('with_date','int');
    $f_c_dateb = get_input('f_c_dateb');
    $f_c_datee = get_input('f_c_datee');
    $f_c_datebeg = mb_strlen($f_c_dateb) ? dateYMD($f_c_dateb) : dateYMD(1);
    $f_c_dateend = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
    //ПЕРИОД. СЧЕТЧИК: установлен with_date_sc_ust
    $with_date_sc_ust = get_input('with_date_sc_ust','int');
    $f_c_dateb_sc_ust = trim(get_input('f_c_dateb_sc_ust'));
    $f_c_datee_sc_ust = trim(get_input('f_c_datee_sc_ust'));
     //ПЕРИОД. СЧЕТЧИК: установлен with_date_sc_sn
    $with_date_sc_sn = get_input('with_date_sc_sn','int');
    $f_c_dateb_sc_sn = trim(get_input('f_c_dateb_sc_sn'));
    $f_c_datee_sc_sn = trim(get_input('f_c_datee_sc_sn'));
    //СЧЕТЧИК: просроченные на год
    $with_date_sc_prosr = get_input('with_date_sc_prosr','int');
    $f_c_date_sc_prosr = get_input('f_c_date_sc_prosr','int');
    //НЕПЛАТЕЛЬЩИКИ С:
    $with_date_neplat_s = get_input('with_date_neplat_s','int');
    $f_c_dateb_neplat_s = trim(get_input('f_c_dateb_neplat_s'));
	//ДОГОВОР ЗАКЛЮЧЕН
    $with_date_dogovor = get_input('with_date_dogovor','int');
    $f_c_dateb_dogovor = trim(get_input('f_c_dateb_dogovor'));
    $f_c_datee_dogovor = trim(get_input('f_c_datee_dogovor'));
     //БЕЗ ОБХОДОВ С:
    $with_date_bezobhod_s = get_input('with_date_bezobhod_s','int');
    $f_c_dateb_bezobhod_s = trim(get_input('f_c_dateb_bezobhod_s'));
    //ДАТА ПОСЛЕДНЕГО ОБХОДА С.. ПО..
    $with_date_obhod = get_input('with_date_obhod','int');
    $f_c_dateb_obhod = trim(get_input('f_c_dateb_obhod'));
    $f_c_datee_obhod = trim(get_input('f_c_datee_obhod'));
    $f_c_raznica_obhod = trim(get_input('f_c_raznica_obhod'));
    //ДАТА ОТКЛЮЧЕНИЯ С.. ПО..
    $with_date_otkl = get_input('with_date_otkl','int');
    $f_c_dateb_otkl = trim(get_input('f_c_dateb_otkl'));
    $f_c_datee_otkl = trim(get_input('f_c_datee_otkl'));
	//Ведомость для проверки мобильного контролера  - диапазон
	$f_c_dateb_contr_check = trim(get_input('f_c_dateb_contr_check'));
    $f_c_datee_contr_check = trim(get_input('f_c_datee_contr_check'));
    // Общие условия выборки
    $with_kn = get_input('with_kn','int');
    $f_c_knbeg = trim(get_input('f_c_knbeg'));
    $f_c_knend = trim(get_input('f_c_knend'));
    $with_address = get_input('with_address','int');
    $nps = get_input('nps');
    $streets = get_input('streets');
    $doms = trim(get_input('doms'));
    $with_region = get_input('with_region','int');
    $regions = get_input('regions');
    $with_alldb = get_input('with_alldb','int');
    if ($with_alldb) {$with_kn = $with_address = $with_region = 0;}
	//Дополнительные ограничения
    $with_sn_sc = get_input('with_sn_sc','int');
    $with_without_dog = get_input('with_without_dog','int');
    $with_without_otkl = get_input('with_without_otkl','int');
    $with_with_arch = get_input('with_with_arch','int');
    $with_uch2 = get_input('with_uch2','int');
    $with_diftarif = get_input('with_diftarif','int');
    $with_askue = get_input('with_askue','int');
	// Фильтры
    $with_tip_sc = get_input('with_tip_sc','int');
    $tip_scs = get_input('tip_scs');
    $with_vid_sc = get_input('with_vid_sc','int');
    $vid_scs = get_input('vid_scs');
    $with_klass_toch = get_input('with_klass_toch','int');
    $klass_tochs = get_input('klass_tochs');
    $with_sc_yearvyp = get_input('with_sc_yearvyp','int');
    $sc_yearvyps = get_input('sc_yearvyps');
    $with_sc_izg = get_input('with_sc_izg','int');
    $sc_izgs = get_input('sc_izgs');
    $with_sc = get_input('with_sc','int');
    $scs = get_input('scs');
    $with_tip_prinadl_sc = get_input('with_tip_prinadl_sc');
    $tip_prinadls_sc = get_input('tip_prinadls_sc');
    $with_tip_prinadl_dom = get_input('with_tip_prinadl_dom');
    $tip_prinadls_dom = get_input('tip_prinadls_dom');
    $with_tip_postr = get_input('with_tip_postr','int');
    $tip_postrs = get_input('tip_postrs');
    $with_vidlg = get_input('with_vidlg','int');
    $vidlgs = get_input('vidlgs');
    $with_vidtar = get_input('with_vidtar','int');
    $vidtars = get_input('vidtars');
    $with_sostav_semji = get_input('with_sostav_semji','int');
    $sostav_semjis = get_input('sostav_semjis');
    $with_ktp = get_input('with_ktp','int');
    $ktp = get_input('ktp');
    $with_nomr = get_input('with_nomr','int');
    $nomr = get_input('nomr');
    $with_otkl = get_input('with_otkl','int');
    $otkl = get_input('otkl');
    // Не выводить ошибочные квитанции в ведомость
    $without_osh_kvit = get_input('without_osh_kvit','int');
    // Не выводить служебные отметки в ведомость
    $without_sl_otm = get_input('without_sl_otm','int');
    // Не выводить оплаченное показание в ведомость
    $without_opl_pok = get_input('without_opl_pok','int');
    // Не выводить тип постройки в ведомость
    $without_tip_postr = get_input('without_tip_postr','int');
    // Не выводить ошибочные квитанции в ведомость (шаблон с тремя строками)
    $without_osh_kvit_3str = get_input('without_osh_kvit_3str','int');
    // Не выводить служебные отметки в ведомость (шаблон с тремя строками)
    $without_sl_otm_3str = get_input('without_sl_otm_3str','int');
    // Не выводить оплаченное показание в ведомость (шаблон с тремя строками)
    $without_opl_pok_3str = get_input('without_opl_pok_3str','int');
    // Не выводить тип постройки в ведомость (шаблон с тремя строками)
    $without_tip_postr_3str = get_input('without_tip_postr_3str','int');
    // Не выводить ошибочные квитанции в ведомость (мобильный контролер)
    $without_osh_kvit_mobi = get_input('without_osh_kvit_mobi','int');
    // Не выводить служебные отметки в ведомость (мобильный контролер)
    $without_sl_otm_mobi = get_input('without_sl_otm_mobi','int');
    // Не выводить оплаченное показание в ведомость (мобильный контролер)
    $without_opl_pok_mobi = get_input('without_opl_pok_mobi','int');
    // Не выводить тип постройки в ведомость (мобильный контролер)
    $without_tip_postr_mobi = get_input('without_tip_postr_mobi','int');

    $persona = get_input('persona');
	$persona2 = get_input('persona2');

    //var_dump($_POST);
    //var_dump($_GET);

    /* ****************************************************************************************** */
    /*         ПОЛЯ ДЛЯ ВЫВОДА В                                                                  */
    /* ****************************************************************************************** */
    // НОМЕР ПО ПОРЯДКУ
    $out_number = get_input('out_number','int');
    $out_number_col = get_input('out_number_col');
    $out_number_width = get_input('out_number_width');
    // ДАННЫЕ АБОНЕНТА: НОМЕР АБОНЕНТА
    $out_knnom	= get_input('out_knnom','int');
    $out_knnom_col = get_input('out_knnom_col','int');
    $out_knnom_width = get_input('out_knnom_width','int');
    // ДАННЫЕ АБОНЕНТА: ФИО АБОНЕНТА
    $out_fio = get_input('out_fio','int');
    $out_fio_col = get_input('out_fio_col','int');
    $out_fio_width = get_input('out_fio_width','int');
    // ДАННЫЕ АБОНЕНТА: АДРЕС АБОНЕНТА
    $out_address = get_input('out_address','int');
    $out_address_col = get_input('out_address_col','int');
    $out_address_width = get_input('out_address_width','int');
    // ДАННЫЕ АБОНЕНТА: ТЕЛЕФОН
    $out_tel = get_input('out_tel','int');
    $out_tel_col = get_input('out_tel_col','int');
    $out_tel_width = get_input('out_tel_width','int');
    // ДАННЫЕ АБОНЕНТА: ТЕЛЕФОН - МОБИЛЬНЫЙ
    $out_mobtel = get_input('out_mobtel','int');
    $out_mobtel_col = get_input('out_mobtel_col','int');
    $out_mobtel_width = get_input('out_mobtel_width','int');
    // ДАННЫЕ АБОНЕНТА: ДАТА ДОГОВОРА
    $out_data_dog = get_input('out_data_dog','int');
    $out_data_dog_col = get_input('out_data_dog_col','int');
    $out_data_dog_width = get_input('out_data_dog_width','int');
    // ДАННЫЕ АБОНЕНТА: Буквенная ДопИнформация
    $out_nomr = get_input('out_nomr','int');
    $out_nomr_col = get_input('out_nomr_col','int');
    $out_nomr_width = get_input('out_nomr_width','int');
    // ДАННЫЕ АБОНЕНТА: Пломба вв.устр.1
    $out_plmbvu1 = get_input('out_plmbvu1','int');
    $out_plmbvu1_col = get_input('out_plmbvu1_col','int');
    $out_plmbvu1_width = get_input('out_plmbvu1_width','int');
    // ДАННЫЕ АБОНЕНТА: Пломба вв.устр.2
    $out_plmbvu2 = get_input('out_plmbvu2','int');
    $out_plmbvu2_col = get_input('out_plmbvu2_col','int');
    $out_plmbvu2_width = get_input('out_plmbvu2_width','int');
    // ДАННЫЕ АБОНЕНТА: Пломба Ш0
    $out_plmbs0 = get_input('out_plmbs0','int');
    $out_plmbs0_col = get_input('out_plmbs0_col','int');
    $out_plmbs0_width = get_input('out_plmbs0_width','int');

    // **************************** ДАННЫЕ ПО ДОМУ *****************************************//
    // Регион
    $out_region = get_input('out_region','int');
    $out_region_col = get_input('out_region_col','int');
    $out_region_width = get_input('out_region_width','int');
    // КТП дома
    $out_ktp = get_input('out_ktp','int');
    $out_ktp_col = get_input('out_ktp_col','int');
    $out_ktp_width = get_input('out_ktp_width','int');
    // ВЛ дома
    $out_vl = get_input('out_vl','int');
    $out_vl_col = get_input('out_vl_col','int');
    $out_vl_width = get_input('out_vl_width','int');
    // ОПОРА дома
    $out_opora = get_input('out_opora','int');
    $out_opora_col = get_input('out_opora_col','int');
    $out_opora_width = get_input('out_opora_width','int');

// **************************************       ТАРИФ    **************************** //
    // ТИП ТАРИФА
    $out_tarif = get_input('out_tarif','int');
    $out_tarif_col = get_input('out_tarif_col','int');
    $out_tarif_width = get_input('out_tarif_width','int');
    // СОСТАВ СЕМЬИ
    $out_semya = get_input('out_semya','int');
    $out_semya_col = get_input('out_semya_col','int');
    $out_semya_width = get_input('out_semya_width','int');
    // СОСТАВ СЕМЬИ: КОЛ-ВО ЛЬГОТНИКОВ
    $out_semlg = get_input('out_semlg','int');
    $out_semlg_col = get_input('out_semlg_col','int');
    $out_semlg_width = get_input('out_semlg_width','int');

// ************************************      ПОСЛ. КВИТАНЦИЯ      **************************** //
    // ПОСЛ.ОПЛАЧЕННОЕ: ПОКАЗАНИЕ
    $out_last_kvt = get_input('out_last_kvt','int');
    $out_last_kvt_col = get_input('out_last_kvt_col','int');
    $out_last_kvt_width = get_input('out_last_kvt_width','int');
    // ПОСЛ.ОПЛАЧЕННОЕ: ДАТА
    $out_last_date = get_input('out_last_date','int');
    $out_last_date_col = get_input('out_last_date_col','int');
    $out_last_date_width = get_input('out_last_date_width','int');
    // ПОСЛ.ОПЛАЧЕННОЕ: ПОКАЗАНИЕ АБОНЕНТА
    $out_last_pokkv = get_input('out_last_pokkv','int');
    $out_last_pokkv_col = get_input('out_last_pokkv_col','int');
    $out_last_pokkv_width = get_input('out_last_pokkv_width','int');
    // СРЕД.МЕС. ПОТРЕБЛЕНИЕ ЗА ПЕРИОД КВтч
    $out_avg_mon_kvt = get_input('out_avg_mon_kvt','int');
    $out_avg_mon_kvt_col = get_input('out_avg_mon_kvt_col','int');
    $out_avg_mon_kvt_width = get_input('out_avg_mon_kvt_width','int');
    // ПОТРЕБЛЕНИЕ: оплаченные КВтч за период, указанный в "ПЕРИОД. ОПЛАТА"
    $out_period_kvts = get_input('out_period_kvts','int');
    $out_period_kvts_col = get_input('out_period_kvts_col','int');
    $out_period_kvts_width = get_input('out_period_kvts_width','int');
    // ПОТРЕБЛЕНИЕ: сумма в руб. за период, указанный в "ПЕРИОД. ОПЛАТА"
    $out_period_sum = get_input('out_period_sum','int');
    $out_period_sum_col = get_input('out_period_sum_col','int');
    $out_period_sum_width = get_input('out_period_sum_width','int');
// *******************    ПОСЛЕДНИЙ ОБХОД  *******************************************//
    // ПОСЛ.ОБХОД.: ДАТА
    $out_last_obh_date = get_input('out_last_obh_date','int');
    $out_last_obh_date_col = get_input('out_last_obh_date_col','int');
    $out_last_obh_date_width = get_input('out_last_obh_date_width','int');
   // ПОСЛ.ОБХОД.: ПОКАЗАНИЕ
    $out_last_obh_pok = get_input('out_last_obh_pok','int');
    $out_last_obh_pok_col = get_input('out_last_obh_pok_col','int');
    $out_last_obh_pok_width = get_input('out_last_obh_pok_width','int');
    // ОБХОДЫ: СРЕД.МЕС.ПОТРЕБЛЕНИЕ ЗА ПЕРИОД
    $out_obh_avg_mon        = get_input('out_obh_avg_mon','int');
    $out_obh_avg_mon_col    = get_input('out_obh_avg_mon_col','int');
    $out_obh_avg_mon_width  = get_input('out_obh_avg_mon_width','int');
// *************************************   СЧЕТЧИК     **************************** //
    // СЧЕТЧИК: КОД
    $out_sc_kod = get_input('out_sc_kod','int');
    $out_sc_kod_col = get_input('out_sc_kod_col','int');
    $out_sc_kod_width = get_input('out_sc_kod_width','int');
    // СЧЕТЧИК: ТИП
    $out_sc_tip = get_input('out_sc_tip','int');
    $out_sc_tip_col = get_input('out_sc_tip_col','int');
    $out_sc_tip_width = get_input('out_sc_tip_width','int');
    // СЧЕТЧИК: ГОД ВЫПУСКА
    $out_sc_gv = get_input('out_sc_gv','int');
    $out_sc_gv_col = get_input('out_sc_gv_col','int');
    $out_sc_gv_width = get_input('out_sc_gv_width','int');
    // СЧЕТЧИК: ГОД ПОВЕРКИ
    $out_sc_gpr = get_input('out_sc_gpr','int');
    $out_sc_gpr_col = get_input('out_sc_gpr_col','int');
    $out_sc_gpr_width = get_input('out_sc_gpr_width','int');
    // СЧЕТЧИК: ДАТА УСТАНОВКИ
    $out_sc_dateust = get_input('out_sc_dateust','int');
    $out_sc_dateust_col = get_input('out_sc_dateust_col','int');
    $out_sc_dateust_width = get_input('out_sc_dateust_width','int');
    // СЧЕТЧИК: ДАТА СНЯТИЯ
    $out_sc_datesn = get_input('out_sc_datesn','int');
    $out_sc_datesn_col = get_input('out_sc_datesn_col','int');
    $out_sc_datesn_width = get_input('out_sc_datesn_width','int');
    // СЧЕТЧИК: ПОКАЗАНИЕ УСТАНОВКИ
    $out_sc_pokusc = get_input('out_sc_pokusc','int');
    $out_sc_pokusc_col = get_input('out_sc_pokusc_col','int');
    $out_sc_pokusc_width = get_input('out_sc_pokusc_width','int');
    // СЧЕТЧИК: ЗАВОДСКОЙ НОМЕР
    $out_sc_zn = get_input('out_sc_zn','int');
    $out_sc_zn_col = get_input('out_sc_zn_col','int');
    $out_sc_zn_width = get_input('out_sc_zn_width','int');
    // СЧЕТЧИК: ПРИНАДЛЕЖНОСТЬ
    $out_sc_ps = get_input('out_sc_ps','int');
    $out_sc_ps_col = get_input('out_sc_ps_col','int');
    $out_sc_ps_width = get_input('out_sc_ps_width','int');
    // СЧЕТЧИК: ОДНО/ТРЁХ ФАЗНЫЙ
    $out_sc_1_3 = get_input('out_sc_1_3','int');
    $out_sc_1_3_col = get_input('out_sc_1_3_col','int');
    $out_sc_1_3_width = get_input('out_sc_1_3_width','int');
    // СЧЕТЧИК: НОМЕР ПЛОМБИРА
    $out_sc_plmb = get_input('out_sc_plmb','int');
    $out_sc_plmb_col = get_input('out_sc_plmb_col','int');
    $out_sc_plmb_width = get_input('out_sc_plmb_width','int');

    /* **************************************************************************************** */
    /*    Определяем куда будут выводится данные: по конструктору или в стандатную форму        */
    /*    Если стандартная форма, то задаем для каждой формы поля, необходимые для отчета       */
    /* **************************************************************************************** */
    $out_use = get_input('out_use');
    if ($out_use == 'standart_form'){
        echo "<br>Используем установленную форму:";
        $out_use_standart_form = get_input('out_use_standart_form');
        if ($out_use_standart_form == 'search_result_count') {
             echo "'<b>Количество найденных абонентов</b>'<br>";
        }
        elseif ($out_use_standart_form == 'search_result_knnom_link') {
            echo "'Лицевые счета абонентов в ссылках'<br>";
            $out_fio = $out_address = $out_knnom  = 1;
        }
        elseif ($out_use_standart_form == 'f_obhod' || $out_use_standart_form == 'f_obhod_3str' || $out_use_standart_form == 'f_obhod_mobi' || $out_use_standart_form == 'f_obhod_contr_check') {
            if ($out_use_standart_form == 'f_obhod_contr_check') {
                 echo "'Ведомость для проверки мобильного контролера '<br>";}
            else echo "'Ведомость оплаты за ЭЭ, для обходов'<br>";
            $with_date = $out_number = $out_knnom = $out_tarif = $out_fio = $out_address = $out_tel = $out_mobtel =
            $out_data_dog = $out_nomr =
            $out_last_date = $out_last_pokkv = $out_last_kvt =
            $out_last_obh_date = $out_last_obh_pok = $out_avg_mon_kvt = $out_obh_avg_mon =
            $out_sc_1_3 = $out_sc_tip = $out_sc_ps = $out_sc_zn = $out_sc_dateust = $out_sc_gv = $out_sc_gpr =
            $out_sc_plmb = $out_plmbvu1 = $out_plmbvu2 = $out_plmbs0 =
            $out_ktp = $out_vl = $out_opora = 1;
            $with_uch2 = 1;
        }
        elseif($out_use_standart_form == 'f_sc_zamena') {
            echo "'Ведомость замены счетчиков'<br>";
            $out_number = $out_knnom = $out_tarif = $out_fio = $out_address = $out_tel = $out_data_dog =
            $out_sc_tip = $out_sc_ps = $out_sc_zn = $out_sc_plmb = $out_sc_gv = $out_sc_gpr =
            $out_last_date = $out_last_kvt = 1;
            $with_uch2 = 1;
        }
        elseif($out_use_standart_form == 'f_sc_poverka') {
            echo "'Ведомость поверки счетчиков'<br>";
        }
        elseif($out_use_standart_form == 'f_kartochka_ucheta') {
            echo "'Карточка учета счетчиков'<br>";
            $out_knnom = $out_fio = $out_address = $out_sc_tip = $out_sc_zn = $out_sc_1_3 = 1;
        }
        else{
            $errors[] = "Не указана форма для вывода отчета в стандартную форму";
        }
    }
    else{
        $out_use_standart_form = '';
        $out_use = 'constructor';
    }

	// ПРОВЕРКИ НА ОШИБКИ ЗАДАНИЯ ПАРАМЕТРОВ ФОРМИРОВАНИЯ ОТЧЕТОВ
	if ($with_kn == 1 && mb_strlen($f_c_knbeg)<4 && mb_strlen($f_c_knend)<4){
		$errors[] = 'Неправильно сформирован запрос.';
	}

	$query = '';
	$query_select = '';
    $query_from = '';
	$query_join = '';
	$query_where = ' 1=1 ';
    $query_order = ' ';
	$temp = '';

    // ФОРМИРУЕМ ЗАПРОС К БД ПО ВХОДНЫМ ПАРАМЕТРАМ
    // ДОБАВЛЯЕМ ЧЕРЕЗ JOIN ВСЕ НЕОБХОДИМЫЕ ТАБЛИЦЫ
    $query_select = " main.kn, main.nom, tarhist.uch, main.fam, main.im, main.ot, concat (main.fam,' ',main.im,' ',main.ot) as fio, main.archive ";
    $query_from = CURRENT_RES."_main as main ";
    $query_join = "
        LEFT JOIN ".CURRENT_RES."_dom as dom on dom.id = main.id_dom and
           ".$res2."
        LEFT JOIN _tip_postr as tip_postr on tip_postr.id = dom.id_postr
        LEFT JOIN ".CURRENT_RES."_ktp as ktp on ktp.id = dom.id_vlkl
        LEFT JOIN ".CURRENT_RES."_street as street on street.id = dom.id_ul
        LEFT JOIN _tip_street as tip_street on tip_street.id = street.id_tip_street
        LEFT JOIN ".CURRENT_RES."_np as np on np.id = street.id_np
        LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
        LEFT JOIN ".CURRENT_RES."_region as region on region.id = dom.id_region
        LEFT JOIN ".CURRENT_RES."_mainsc as mainsc on main.kn = mainsc.kn and main.nom = mainsc.nom
        LEFT JOIN _tip_sc as tip_sc on mainsc.ts = tip_sc.id
        LEFT JOIN ".CURRENT_RES."_tarhist_sem as tarhist on main.kn = tarhist.kn and main.nom = tarhist.nom
        LEFT JOIN _vidtar as vidtar on tarhist.idt = vidtar.id ";
    $query_where .= " and ".$res2." and (main.dlt is NULL or main.dlt = 0) and (mainsc.dlt is NULL or mainsc.dlt = 0) and (tarhist.dlt is NULL or tarhist.dlt = 0)";

    /* ********************************************************************************************** */
    /*                 Общие условия выборки                                                          */
    /* ********************************************************************************************** */
    if ($with_alldb == 0 && $with_kn == 0 && $with_address == 0 && $with_region == 0 && $res2 == " 1=1 ")
        $errors[] = "Не задано ни одно из общих условий выборки";

    // КНИГА
    if ($with_kn) {
        if (mb_strlen($f_c_knbeg)==4 && mb_strlen($f_c_knend)==4) $query_where .= " and (main.kn >= '$f_c_knbeg') and (main.kn <= '$f_c_knend')";
        elseif (mb_strlen($f_c_knend)==4) $query_where .= " and (main.kn = '$f_c_knend')";
        elseif (mb_strlen($f_c_knbeg)==4) $query_where .= " and (main.kn = '$f_c_knbeg')";
        else $errors[] = "Неправильно задан промежуток для `КНИГА`";
        if (mb_strlen($f_c_knbeg)==4 && mb_strlen($f_c_knend)==4){
            if (intval($f_c_knbeg) > intval($f_c_knend)){
                $errors[] = "Неправильно задан промежуток для `КНИГА`";
            }
        }
    }
    // АДРЕС
    if ($with_address) {
        if (mb_strlen($nps) < 2 && mb_strlen($streets) < 2 && mb_strlen($doms) <2){
            $errors[] = "Неправильно задан `АДРЕС`";
        }
        //Населенные пункты
        if ($nps != "") {
            $nps = explode(',',$nps);
            foreach ($nps as $np) if (intval($np)) $temp .= " $np, ";
            if (mb_strlen($temp) > 2) $query_where .= " and (np.id IN ($temp -1))  ";
        }
        //Улицы
        if ($streets != "") {
            $streets = explode(',',$streets);
            if (count($streets)){
            $temp = "";
                foreach ($streets as $street) if (intval($street)) $temp .= " $street, ";
                if (mb_strlen($temp) > 2) $query_where .= " and (street.id IN ($temp -1)) ";
            }
        }
        // Дом
        if ($doms != "") {
            $doms = explode(',',$doms);
            if (count($doms)){
                $temp = "";
                foreach ($doms as $dom) if (intval($dom)) $temp .= " $dom, ";
                if (mb_strlen($temp) > 2) $query_where .= " and (dom.id IN ($temp -1)) ";
            }
        }
    }
    // РЕГИОН
    if ($with_region) {
        $temp = '';
        $regions = mb_substr($regions,0,mb_strlen($regions)-1);
        if ($regions != "") $regions = explode(',', $regions);
        foreach ($regions as $regi) $temp .= "'".$regi."', ";
        $temp = mb_substr($temp,0,mb_strlen($temp)-2);
        $rows = dbFetchArray(dbQuery("Select id from ".CURRENT_RES."_region where region IN ($temp)"));
        if (mb_strlen($temp)==0) {
            $errors[] = "Неправильно задан `РЕГИОН`"."'".$temp."'";
        }
        $temp = '';
        foreach ($rows as $value){
            $temp .= $value['id'].", ";
        }
        if (isset($temp[mb_strlen($temp)-2])) $temp[mb_strlen($temp)-2] = ' ';
        if (mb_strlen($temp)>1) {
            $query_where .= " and (region.id in ($temp)) ";
        }
        else {
            $errors[] = "Неправильно задан `РЕГИОН`"."'".$temp."'";
        }
    }

    /* *********************************************************************************** */
    /*                   Периоды для расчетов                                              */
    /* *********************************************************************************** */
    // ПЕРИОД. КВИТАНЦИИ: ПЕРИОД ОПЛАТЫ
    if ($with_date) {
        $f_c_dateb = $f_c_datebeg = mb_strlen($f_c_dateb) ? dateYMD($f_c_dateb) : dateYMD(1);
        $f_c_datee = $f_c_dateend = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
    }
    // ПЕРИОД. СЧЕТЧИК: установлен
    if ($with_date_sc_ust) {
        $f_c_dateb_sc_ust = mb_strlen($f_c_dateb_sc_ust) ? dateYMD($f_c_dateb_sc_ust) : dateYMD(1);
        $f_c_datee_sc_ust = mb_strlen($f_c_datee_sc_ust) ? dateYMD($f_c_datee_sc_ust) : dateYMD();
        $query_where .= " and (mainsc.dateust >='$f_c_dateb_sc_ust' and mainsc.dateust <='$f_c_datee_sc_ust')";
    }
    // ПЕРИОД. СЧЕТЧИК: снят
    if ($with_date_sc_sn) {
        $f_c_dateb_sc_sn = mb_strlen($f_c_dateb_sc_sn) ? dateYMD($f_c_dateb_sc_sn) : dateYMD(1);
        $f_c_datee_sc_sn = mb_strlen($f_c_datee_sc_sn) ? dateYMD($f_c_datee_sc_sn) : NULL;
        if ($f_c_dateb_sc_sn && $f_c_datee_sc_sn)
            $query_where .= " and (mainsc.datesn >='$f_c_dateb_sc_sn' and mainsc.datesn <='$f_c_datee_sc_sn')";
        else
            $query_where .= " and (mainsc.datesn >='$f_c_dateb_sc_sn'
                                or mainsc.datesn IS NULL
                                or mainsc.id = (SELECT s.id FROM ".CURRENT_RES."_mainsc as s where s.kn = mainsc.kn and s.nom = mainsc.nom and (s.dlt is NULL or s.dlt = 0)
                               ORDER BY s.dateust desc, s.datesn, s.id desc LIMIT 1))";
    }
    // СЧЕТЧИК: просроченные на год
    if ($with_date_sc_prosr) {
        $query_where .= "and (mainsc.yearprov <= ($f_c_date_sc_prosr - tip_sc.spov) or mainsc.yearprov = 0 )";
    }
    // Без платежей с
    if ($with_date_neplat_s) {
        $query_where .= ' and (SELECT COUNT(*) FROM '.CURRENT_RES.'_kvit as k where k.knkv = main.kn and k.nomkv = main.nom and k.uchkv = tarhist.uch and (k.dlt is NULL or k.dlt = 0) and k.datekv >= \''.$f_c_dateb_neplat_s.'\')=0';
    }
    // ДОГОВОР ЗАКЛЮЧЕН
    if ($with_date_dogovor) {
        $f_c_dateb_dogovor = mb_strlen($f_c_dateb_dogovor) ? dateYMD($f_c_dateb_dogovor) : dateYMD(1);
        $f_c_datee_dogovor = mb_strlen($f_c_datee_dogovor) ? dateYMD($f_c_datee_dogovor) : dateYMD();
        $query_where .= " and (main.datadog >= '$f_c_dateb_dogovor' and main.datadog <= '$f_c_datee_dogovor')";
    }
    // Без ОБХОДОВ С
    if ($with_date_bezobhod_s) {
        $query_where .= " and (obhod.dateobh <= '$f_c_dateb_bezobhod_s')";
    }
    // ДАТА ПОСЛЕДНЕГО ОБХОДА
    if ($with_date_obhod) {
        $f_c_dateb_obhod = mb_strlen($f_c_dateb_obhod) ? dateYMD($f_c_dateb_obhod) : dateYMD(1);
        $f_c_datee_obhod = mb_strlen($f_c_datee_obhod) ? dateYMD($f_c_datee_obhod) : dateYMD();
        $query_where .= " and (obhod.dateobh >= '$f_c_dateb_obhod') and (obhod.dateobh <= '$f_c_datee_obhod') and obhod.ob_pok>'' ";
    }
    // ДАТА ОТКЛЮЧЕНИЯ
    $otkl_join = 0;
    if ($with_date_otkl) {
        $f_c_dateb_otkl = mb_strlen($f_c_dateb_otkl) ? dateYMD($f_c_dateb_otkl) : dateYMD(1);
        $f_c_datee_otkl = mb_strlen($f_c_datee_otkl) ? dateYMD($f_c_datee_otkl) : dateYMD();
        $query_join  .= " LEFT JOIN ".CURRENT_RES."_otkl as otkl on otkl.kn = main.kn and otkl.nom=main.nom and otkl.dlt=0 ";
        $otkl_join = 1;
        $query_where .= " and (otkl.id = (SELECT ot.id FROM ".CURRENT_RES."_otkl as ot where ot.kn = main.kn and ot.nom = main.nom and otkl.dlt=0 and otkl.datebeg >='$f_c_dateb_otkl' and otkl.datebeg <='$f_c_datee_otkl' and otkl.dateend is NULL ORDER BY ot.datebeg desc, id desc LIMIT 1))";
        $query_where .= " and (otkl.id_pr in (SELECT id FROM _spr_otkl where otkl LIKE '%Отключен%'))";
    }
    /* ***************************************************************************** */
    /*                 Дополнительные ограничения                                    */
    /* ***************************************************************************** */
    // СЧЕТЧИК: снятые    учитывать в том числе и снятые счетчики
    // Если не заданы периоды для счетчиков, то ставим ограничение чтоб брался только установленный счетчик
    if ($with_sn_sc == 0) {
        $query_where .= " and (mainsc.datesn IS NULL
                           or  mainsc.datesn >'".dateYMD()."'
                           or  mainsc.id = (SELECT s.id FROM ".CURRENT_RES."_mainsc as s where s.kn = mainsc.kn and s.nom = mainsc.nom and (s.dlt is NULL or s.dlt = 0)
                          ORDER BY s.dateust desc, s.datesn, s.id desc LIMIT 1))";
    }
    // ДОГОВОР НЕ ЗАКЛЮЧЕН
    if ($with_without_dog) {
        $query_where .= " and (main.datadog is NULL or main.datadog = 0)";
    }
    // БЕЗ ОТКЛЮЧЕННЫХ АБОНЕНТОВ:   не выбирать отключенных на данный момент времени абонентов
    if ($with_without_otkl) {
        if ($otkl_join==0) $query_join .= " LEFT JOIN ".CURRENT_RES."_otkl as otkl on otkl.kn = main.kn and otkl.nom=main.nom and otkl.dlt=0 ";
        $otkl_str = '-1';
        $otkl = explode(' ',trim(str_replace('|',' ',$otkl)));
        if (count($otkl)) {
            foreach ($otkl as $key => $value) {
                $otkl_str .= ",$value";
            }
            $query_where .= " and (otkl.id = (SELECT ot.id FROM ".CURRENT_RES."_otkl as ot where ot.kn = main.kn and ot.nom = main.nom and ot.dateend is NULL and ot.dlt=0 ORDER BY ot.datebeg desc, id desc LIMIT 1))";
            $query_where .= " and (otkl.id_pr in (".$otkl_str."))";
            $query_where .= " and (otkl.datebeg < '".dateYMD()."') and (otkl.dateend is NULL)";
        }
        else $errors[] = 'Отмечен пункт, но не заданы `Отключения`';
    }
    // АРХИВНЫЕ АБОНЕНТЫ: включить в отчет архивных абонентов(в ведомостях для обходов будут выделены серым цветом)
    if (!$with_with_arch) {
        $query_where .= ' and (main.archive = 0) ';
    }
    // ДИФТАРИФНЫЕ: учитывать только дифтарифных абонентов;
    if ($with_diftarif) {
        $query_where .= " and (tarhist.mt > 0)";
        $query_where .= " and (tarhist.id = (SELECT a.id FROM ".CURRENT_RES."_tarhist_sem as a where a.kn = main.kn and a.nom = main.nom and a.uch = tarhist.uch and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc LIMIT 1))";
    }
    else {
      // МНОГОТАРИФНЫЕ: учитывать каждый тариф отдельно (для ведомости обходов и ведомости замены устанавливается автоматически);
       if (!$with_uch2) {
           $query_where .= " and (tarhist.mt < 2)";
           $query_where .= " and (tarhist.id = (SELECT a.id FROM ".CURRENT_RES."_tarhist_sem as a where a.kn = main.kn and a.nom = main.nom and a.mt<2 and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc LIMIT 1))";
       }
       else {
         $query_where .= " and (tarhist.id = (SELECT a.id FROM ".CURRENT_RES."_tarhist_sem as a where a.kn = main.kn and a.nom = main.nom and a.uch = tarhist.uch and (a.dlt is NULL or a.dlt = 0) ORDER BY a.ddate desc LIMIT 1))";
       }
    }
    // АСКУЭ: включить в отчет только дома с АСКУЭ
    if ($with_askue) {
        $query_where .= ' and (dom.askue > 0) ';
    }
    /* ****************************************************************************** */
    /*                      ФИЛЬТРЫ                                                   */
    /* ****************************************************************************** */
    // ТИП СЧЕТЧИКА // 1-фазный счетчик/3-фазный счетчик
    if ($with_tip_sc) {
        if      ($tip_scs == 1 ) $query_where .= " and (tip_sc.label = 1) ";
        elseif  ($tip_scs == 3 ) $query_where .= " and (tip_sc.label = 3) ";
        else  $errors[] = "Отмечен пункт, но не задан `СЧЕТЧИК: Вид счетчика`";
    }
    // ВИД СЧЕТЧИКА // электронный - эл.-механический - индукционный
    if ($with_vid_sc) {
        if (count($vid_scs)) {
           if     ($vid_scs == '1')  $query_where .= " and (tip_sc.id IN (SELECT b.id from _tip_sc as b where b.kolob     LIKE '%электрон%')) ";
           elseif ($vid_scs == '2')  $query_where .= " and (tip_sc.id IN (SELECT b.id from _tip_sc as b where b.kolob     LIKE '%эл-мех%')) ";
           elseif ($vid_scs == '3')  $query_where .= " and (tip_sc.id IN (SELECT b.id from _tip_sc as b where b.kolob NOT LIKE '%эл%')) ";
           elseif ($vid_scs == '12') $query_where .= " and (tip_sc.id IN (SELECT b.id from _tip_sc as b where b.kolob     LIKE '%эл%')) ";
           elseif ($vid_scs == '23') $query_where .= " and (tip_sc.id IN (SELECT b.id from _tip_sc as b where b.kolob NOT LIKE '%электрон%')) ";
           elseif ($vid_scs == '13') $query_where .= " and (tip_sc.id IN (SELECT b.id from _tip_sc as b where b.kolob NOT LIKE '%эл-мех%')) ";
        }
        else $errors[] = "Отмечен пункт, но не задан `СЧЕТЧИК: Вид счетчика`";
    }
    // СЧЕТЧИКИ: класс точности with_klass_toch // 0.5 1.0 1.5 2.0 2.5  klass_toch
    if ($with_klass_toch) {
        $klass_tochs = explode(' ',trim(str_replace('|',' ',$klass_tochs)));
        $klass_tochs_ = '';
        if (count($klass_tochs)) {
            foreach($klass_tochs as $klass_toch) $klass_tochs_ .= "'$klass_toch',";
            $klass_tochs_[mb_strlen($klass_tochs_)-1] = ' ';
            $query_where .= " and (tip_sc.toch IN ($klass_tochs_)) ";
        }
        else{
            $errors[] = 'Отмечен пункт, но не задан `СЧЕТЧИК: Класс точности`';
        }
    }
    // СЧЕТЧИКИ: год выпуска
    if ($with_sc_yearvyp) {
        $sc_yearvyps = explode(' ',trim(str_replace('|',' ',$sc_yearvyps)));
        $sc_yearvyps_ = '';
        if (count($sc_yearvyps)) {
            foreach($sc_yearvyps as $sc_yearvyp) $sc_yearvyps_ .= "'$sc_yearvyp',";
            $sc_yearvyps_[mb_strlen($sc_yearvyps_)-1] = ' ';
            $query_where .= " and (mainsc.yearvyp IN ($sc_yearvyps_)) ";
        }
        else {
            $errors[] = 'Отмечен пункт, но не задан `СЧЕТЧИК: Год выпуска`';
        }
    }
    // СЧЕТЧИКИ: изготовитель
    if ($with_sc_izg) {
        $sc_izgs = explode('|',$sc_izgs);
        $sc_izgs_ = '';
        if (count($sc_izgs)) {
            foreach($sc_izgs as $sc_izg) $sc_izgs_ .= "'".$sc_izg."',";
            $sc_izgs_[mb_strlen($sc_izgs_)-1] = ' ';
            $query_where .= " and (tip_sc.izg IN ($sc_izgs_)) ";
        }
        else{
            $errors[] = 'Отмечен пункт, но не задан `СЧЕТЧИК: Изготовитель`';
        }
    }
    // КОНКРЕТНЫЙ СЧЕТЧИК
    if ($with_sc) {
        $scs = explode(',',trim(str_replace(',',' ',$scs)));
        if (count($scs)) $scs_ = str_replace(' ',',',implode($scs));
            else $errors[] = 'Отмечен пункт, но не задан `КОНКРЕТНЫЙ СЧЕТЧИК`';
        $query_where .= " and (mainsc.ts in ($scs_))";
    }
    // ПРИНАДЛЕЖНОСТЬ СЧЕТЧИКА
    if ($with_tip_prinadl_sc) {
        $tip_prinadls_scs = explode(',',trim(str_replace(',',' ',$tip_prinadls_sc)));
        if (count($tip_prinadls_scs)) $tip_prinadls_scs_ = str_replace(' ',',',implode($tip_prinadls_scs));
            else $errors[] = 'Отмечен пункт, но не задана `ПРИНАДЛЕЖНОСТЬ СЧЕТЧИКА`';
        $query_where .= " and (mainsc.ps in ($tip_prinadls_scs_))";
    }
    // ПРИНАДЛЕЖНОСТЬ ДОМОВ
    if ($with_tip_prinadl_dom) {
        $tip_prinadls_dom = explode(',',trim(str_replace(',',' ',$tip_prinadls_dom)));
        if (count($tip_prinadls_dom)) $tip_prinadls_dom_ = str_replace(' ',',',implode($tip_prinadls_dom));
            else $errors[] = 'Отмечен пункт, но не задана `ПРИНАДЛЕЖНОСТЬ ДОМА`';
        $query_where .= " and (dom.id_prinadl in ($tip_prinadls_dom_))";
    }
    // ТИП ПОСТРОЙКИ
    if ($with_tip_postr) {
        $tip_postrs = explode(',',trim(str_replace(',',' ',$tip_postrs)));
        if (count($tip_postrs)) $tip_postrs_ = str_replace(' ',',',implode($tip_postrs));
            else $errors[] = 'Отмечен пункт, но не задан `ТИП ПОСТРОЙКИ`';
        $query_where .= " and ( (dom.id_postr in ($tip_postrs_) and (main.postr = 0 or main.postr is NULL)) or (main.postr in ($tip_postrs_) ) )";
    }
    // ВИД ЛЬГОТЫ
    if ($with_vidlg){
        $vidlgs = explode(',',trim(str_replace(',',' ',$vidlgs)));
        if (count($vidlgs)) $vidlgs_ = str_replace(' ',',',implode($vidlgs));
            else $errors[] = 'Отмечен пункт, но не задан `ВИД ЛЬГОТЫ`';
        $query_join .= " LEFT JOIN ".CURRENT_RES."_sem_lg as sem_lg on main.kn = sem_lg.kn and main.nom = sem_lg.nom and sem_lg.idtarhist=tarhist.id";
        $query_join .= " LEFT JOIN _vidlg as vidlg on sem_lg.idl = vidlg.id";
        $query_where .= " and (sem_lg.idl in ($vidlgs_))";
//        $query_where .= " and (sem_lg.idtarhist=tarhist.id)";
    }
    // ТАРИФ
    if ($with_vidtar){
        $vidtars = explode(',',trim(str_replace(',',' ',$vidtars)));
        if (count($vidtars)) $vidtars_ = str_replace(' ',',',implode($vidtars));
            else $errors[] = 'Отмечен пункт, но не задан `ТАРИФ`';
        $query_where .= " and (tarhist.idt in ($vidtars_))";
    }
    // ТАРИФ: состав семьи
    if ($with_sostav_semji){
        $sostav_semjis = explode(' ',trim(str_replace('|',' ',$sostav_semjis)));
        $sostav_semji_ = '';
        if (count($sostav_semjis)) {
            foreach($sostav_semjis as $sostav_semji) $sostav_semji_ .= "'$sostav_semji',";
            $sostav_semji_[mb_strlen($sostav_semji_)-1] = ' ';
            $query_where .= " and (tarhist.semya IN ($sostav_semji_)) ";
        }
        else{
            $errors[] = 'Отмечен пункт, но не задан `СОСТАВ СЕМЬИ`';
        }
    }
    // КТП
    if ($with_ktp){
        $ktp = explode(',',trim(str_replace(',',' ',$ktp)));
        if (count($ktp)) $ktp_ = str_replace(' ',',',implode($ktp));
            else $errors[] = 'Отмечен пункт, но не задана `КОНКРЕТНАЯ ПС, ВЛ(КЛ)`';
        $query_where .= " and (dom.id_vlkl IN ($ktp_))";
    }
    // NOMR
    if ($with_nomr) {
        $nomr_str = '';
        $nomr = explode(' ',trim(str_replace('|',' ',$nomr)));
        if (count($nomr)){
            foreach ($nomr as $key => $nomr1) {
                $nomr_str .= " (main.nomr LIKE ('%,$nomr1,%') or main.nomr LIKE ('$nomr1,%') or main.nomr LIKE ('%,$nomr1')) ";
                if (isset($nomr[$key+1])) $nomr_str .= ' or ';
            }
            $query_where .= " and ($nomr_str) ";
        }
        else $errors[] = 'Отмечен пункт, но не задана `ДопИнформация`';
    }

    // OTKL
    if ($with_otkl) {
        if ($otkl_join==0) $query_join  .= " LEFT JOIN ".CURRENT_RES."_otkl as otkl on otkl.kn = main.kn and otkl.nom=main.nom and otkl.dlt=0 ";
        $otkl_str = '-1';
        $otkl = explode(' ',trim(str_replace('|',' ',$otkl)));
        if (count($otkl)) {
            foreach ($otkl as $key => $value) {
                $otkl_str .= ",$value";
            }
            $query_where .= " and (otkl.id = (SELECT ot.id FROM ".CURRENT_RES."_otkl as ot where ot.kn = main.kn and ot.nom = main.nom and ot.dlt=0 ORDER BY ot.datebeg desc, id desc LIMIT 1))";
            $query_where .= " and (otkl.id_pr in (".$otkl_str."))";
            $query_where .= " and (otkl.datebeg < '".dateYMD()."') and (otkl.dateend is NULL)";
        }
        else $errors[] = 'Отмечен пункт, но не заданы `Отключения`';
    }

    if (count($errors)){
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
        exit();
    }


    /* ************************************************************* */
    /*     Формирование конструкции выходной строки отчета           */
    /* ************************************************************* */
    $query_order_min = 100;
    $out_col_pnum = 2; // Порядковый номер столбца - позволит автоматом вписать все нужные данные в строку
    // НОМЕР ПО ПОРЯДКУ out_number
    if ($out_number) {
        if ($out_number_col < 1) {$out_number_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_number_width = ($out_number_width < 3)?10:$out_number_width;
        if (isset($output_constructor[$out_number_col])) {
            $output_constructor[$out_number_col]['width'] =  ($output_constructor[$out_number_col]['width']<$out_number_width)?$out_number_width:$output_constructor[$out_number_col]['width'];
            $output_constructor[$out_number_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_number_col] = $output_constructor_cell;
            $output_constructor[$out_number_col]['col_abc'] = $cols_ABC[$out_number_col];
            $output_constructor[$out_number_col]['width'] = $out_number_width;
            $output_constructor[$out_number_col]['rows_num'] = 1;
        }
        $output_constructor[$out_number_col]['data'][] = array('field_eng'=>'numrer','field_rus'=>'№ п.п.');
    }
    // ДАННЫЕ АБОНЕНТА: НОМЕР АБОНЕНТА
    if ($out_knnom) {
        if ($out_knnom_col < 1) {$out_knnom_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_knnom_width = ($out_knnom_width < 3)?10:$out_knnom_width;
        if (isset($output_constructor[$out_knnom_col])) {
            $output_constructor[$out_knnom_col]['width'] =  ($output_constructor[$out_knnom_col]['width']<$out_knnom_width)?$out_knnom_width:$output_constructor[$out_knnom_col]['width'];
            $output_constructor[$out_knnom_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_knnom_col] = $output_constructor_cell;
            $output_constructor[$out_knnom_col]['col_abc'] = $cols_ABC[$out_knnom_col];
            $output_constructor[$out_knnom_col]['width'] = $out_knnom_width;
            $output_constructor[$out_knnom_col]['rows_num'] = 1;
        }
        $output_constructor[$out_knnom_col]['data'][] = array('field_eng'=>'knnom','field_rus'=>'Номер');
        if ($out_knnom_col < $query_order_min) {
            $query_order = " main.kn, main.nom"; $query_order_min = $out_knnom_col;
        }
    }
    // ДАННЫЕ АБОНЕНТА: ФИО АБОНЕНТА
    if ($out_fio) {
        if ($out_fio_col < 1) {$out_fio_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_fio_width = ($out_fio_width < 3)?10:$out_fio_width;
        if (isset($output_constructor[$out_fio_col])) {
            $output_constructor[$out_fio_col]['width'] =  ($output_constructor[$out_fio_col]['width']<$out_fio_width)?$out_fio_width:$output_constructor[$out_fio_col]['width'];
            $output_constructor[$out_fio_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_fio_col] = $output_constructor_cell;
            $output_constructor[$out_fio_col]['col_abc'] = $cols_ABC[$out_fio_col];
            $output_constructor[$out_fio_col]['width'] = $out_fio_width;
            $output_constructor[$out_fio_col]['rows_num'] = 1;
        }
        $output_constructor[$out_fio_col]['data'][] = array('field_eng'=>'fio','field_rus'=>'ФИО');
        if ($out_fio_col < $query_order_min) {
            $query_order = " fio "; $query_order_min = $out_fio_col;
        }
    }
    // ДАННЫЕ АБОНЕНТА: АДРЕС АБОНЕНТА
    if ($out_address) {
        $query_select .= ", main.kb, dom.dom, dom.domadd, tip_street.tip_street, tip_street.short_tip_street, street.street, tip_np.tip_np, np.np ";
        if ($out_address_col < 1) {$out_address_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_address_width = ($out_address_width < 3)?10:$out_address_width;
        if (isset($output_constructor[$out_address_col])) {
            $output_constructor[$out_address_col]['width'] =  ($output_constructor[$out_address_col]['width']<$out_address_width)?$out_address_width:$output_constructor[$out_address_col]['width'];
            $output_constructor[$out_address_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_address_col] = $output_constructor_cell;
            $output_constructor[$out_address_col]['col_abc'] = $cols_ABC[$out_address_col];
            $output_constructor[$out_address_col]['width'] = $out_address_width;
            $output_constructor[$out_address_col]['rows_num'] = 1;
        }
        $output_constructor[$out_address_col]['data'][] = array('field_eng'=>'address','field_rus'=>'Адрес');
        if ($out_address_col < $query_order_min) {
            $query_order = " np.np, street.street, cast(dom.dom as integer), dom.domadd, length(main.kb), main.kb"; $query_order_min = $out_address_col;
        }
    }
    // ДАННЫЕ АБОНЕНТА: Телефон
    if ($out_tel) {
        $query_select .= " ,main.tel ";
        if ($out_tel_col < 1) {$out_tel_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_tel_width = ($out_tel_width < 3)?10:$out_tel_width;
        if (isset($output_constructor[$out_tel_col])) {
            $output_constructor[$out_tel_col]['width'] =  ($output_constructor[$out_tel_col]['width']<$out_tel_width)?$out_tel_width:$output_constructor[$out_tel_col]['width'];
            $output_constructor[$out_tel_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_tel_col] = $output_constructor_cell;
            $output_constructor[$out_tel_col]['col_abc'] = $cols_ABC[$out_tel_col];
            $output_constructor[$out_tel_col]['width'] = $out_tel_width;
            $output_constructor[$out_tel_col]['rows_num'] = 1;
        }
        $output_constructor[$out_tel_col]['data'][] = array('field_eng'=>'tel','field_rus'=>'Телефон');
        if ($out_tel_col < $query_order_min) {
            $query_order = " main.tel"; $query_order_min = $out_tel_col;
        }
    }
    // ДАННЫЕ АБОНЕНТА: Телефон - МОБИЛЬНЫЙ
    if ($out_mobtel) {
        $query_select .= ", main.mobtel ";
        if ($out_mobtel_col < 1) {$out_mobtel_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_mobtel_width = ($out_mobtel_width < 3)?10:$out_mobtel_width;
        if (isset($output_constructor[$out_mobtel_col])) {
            $output_constructor[$out_mobtel_col]['width'] =  ($output_constructor[$out_mobtel_col]['width']<$out_mobtel_width)?$out_mobtel_width:$output_constructor[$out_mobtel_col]['width'];
            $output_constructor[$out_mobtel_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_mobtel_col] = $output_constructor_cell;
            $output_constructor[$out_mobtel_col]['col_abc'] = $cols_ABC[$out_mobtel_col];
            $output_constructor[$out_mobtel_col]['width'] = $out_mobtel_width;
            $output_constructor[$out_mobtel_col]['rows_num'] = 1;
        }
        $output_constructor[$out_mobtel_col]['data'][] = array('field_eng'=>'mobtel','field_rus'=>'Мобильный');
        if ($out_mobtel_col < $query_order_min) {
            $query_order = " main.mobtel"; $query_order_min = $out_mobtel_col;
        }
    }
    // ДАННЫЕ АБОНЕНТА: ДАТА ДОГОВОРА
    if ($out_data_dog) {
        $query_select .= " , main.datadog ";
        if ($out_data_dog_col < 1) {$out_data_dog_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_data_dog_width = ($out_data_dog_width < 3)?10:$out_data_dog_width;
        if (isset($output_constructor[$out_data_dog_col])) {
            $output_constructor[$out_data_dog_col]['width'] = ($output_constructor[$out_data_dog_col]['width']<$out_data_dog_width)?$out_data_dog_width:$output_constructor[$out_data_dog_col]['width'];
            $output_constructor[$out_data_dog_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_data_dog_col] = $output_constructor_cell;
            $output_constructor[$out_data_dog_col]['col_abc'] = $cols_ABC[$out_data_dog_col];
            $output_constructor[$out_data_dog_col]['width'] = $out_data_dog_width;
            $output_constructor[$out_data_dog_col]['rows_num'] = 1;
        }
        $output_constructor[$out_data_dog_col]['data'][] = array('field_eng'=>'data_dog','field_rus'=>'Дата договора');
    }
    // ДАННЫЕ АБОНЕНТА: Буквенная ДопИнформация
    if ($out_nomr) {
        $query_select .= " , main.nomr ";
        if ($out_nomr_col < 1) {$out_nomr_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_nomr_width = ($out_nomr_width < 3)?10:$out_nomr_width;
        if (isset($output_constructor[$out_nomr_col])) {
            $output_constructor[$out_nomr_col]['width'] = ($output_constructor[$out_nomr_col]['width']<$out_nomr_width)?$out_nomr_width:$output_constructor[$out_nomr_col]['width'];
            $output_constructor[$out_nomr_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_nomr_col] = $output_constructor_cell;
            $output_constructor[$out_nomr_col]['col_abc'] = $cols_ABC[$out_nomr_col];
            $output_constructor[$out_nomr_col]['width'] = $out_nomr_width;
            $output_constructor[$out_nomr_col]['rows_num'] = 1;
        }
        $output_constructor[$out_nomr_col]['data'][] = array('field_eng'=>'nomr','field_rus'=>'БукДопИнф');
    }

    // ДАННЫЕ АБОНЕНТА: Пломба вв.устр.1
    if ($out_plmbvu1) {
        $query_select .= " , main.plmbvu1 ";
        if ($out_plmbvu1_col < 1) {$out_plmbvu1_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_plmbvu1_width = ($out_plmbvu1_width < 3)?10:$out_plmbvu1_width;
        if (isset($output_constructor[$out_plmbvu1_col])) {
            $output_constructor[$out_plmbvu1_col]['width'] = ($output_constructor[$out_plmbvu1_col]['width']<$out_plmbvu1_width)?$out_plmbvu1_width:$output_constructor[$out_plmbvu1_col]['width'];
            $output_constructor[$out_plmbvu1_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_plmbvu1_col] = $output_constructor_cell;
            $output_constructor[$out_plmbvu1_col]['col_abc'] = $cols_ABC[$out_plmbvu1_col];
            $output_constructor[$out_plmbvu1_col]['width'] = $out_plmbvu1_width;
            $output_constructor[$out_plmbvu1_col]['rows_num'] = 1;
        }
        $output_constructor[$out_plmbvu1_col]['data'][] = array('field_eng'=>'plmbvu1','field_rus'=>'Пломба вв.устр.1');
    }
    // ДАННЫЕ АБОНЕНТА: Пломба вв.устр.2
    if ($out_plmbvu2) {
        $query_select .= " , main.plmbvu2 ";
        if ($out_plmbvu2_col < 1) {$out_plmbvu2_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_plmbvu2_width = ($out_plmbvu2_width < 3)?10:$out_plmbvu2_width;
        if (isset($output_constructor[$out_plmbvu2_col])) {
            $output_constructor[$out_plmbvu2_col]['width'] = ($output_constructor[$out_plmbvu2_col]['width']<$out_plmbvu2_width)?$out_plmbvu2_width:$output_constructor[$out_plmbvu2_col]['width'];
            $output_constructor[$out_plmbvu2_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_plmbvu2_col] = $output_constructor_cell;
            $output_constructor[$out_plmbvu2_col]['col_abc'] = $cols_ABC[$out_plmbvu2_col];
            $output_constructor[$out_plmbvu2_col]['width'] = $out_plmbvu2_width;
            $output_constructor[$out_plmbvu2_col]['rows_num'] = 1;
        }
        $output_constructor[$out_plmbvu1_col]['data'][] = array('field_eng'=>'plmbvu2','field_rus'=>'Пломба вв.устр.2');
    }
    // ДАННЫЕ АБОНЕНТА: Пломба Ш0
    if ($out_plmbs0) {
        $query_select .= " , main.plmbs0 ";
        if ($out_plmbs0_col < 1) {$out_plmbs0_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_plmbs0_width = ($out_plmbs0_width < 3)?10:$out_plmbs0_width;
        if (isset($output_constructor[$out_plmbs0_col])) {
            $output_constructor[$out_plmbs0_col]['width'] = ($output_constructor[$out_plmbs0_col]['width']<$out_plmbs0_width)?$out_plmbs0_width:$output_constructor[$out_plmbs0_col]['width'];
            $output_constructor[$out_plmbs0_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_plmbs0_col] = $output_constructor_cell;
            $output_constructor[$out_plmbs0_col]['col_abc'] = $cols_ABC[$out_plmbs0_col];
            $output_constructor[$out_plmbs0_col]['width'] = $out_plmbs0_width;
            $output_constructor[$out_plmbs0_col]['rows_num'] = 1;
        }
        $output_constructor[$out_plmbs0_col]['data'][] = array('field_eng'=>'plmbs0','field_rus'=>'Пломба Ш0');
    }
    // **************************** ДАННЫЕ ПО ДОМУ *****************************************//
    // Регион
    if ($out_region) {
        $query_select .= ", region.region ";
        if ($out_region_col < 1) {$out_region_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_region_width = ($out_region_width < 3)?10:$out_region_width;
        if (isset($output_constructor[$out_region_col])) {
            $output_constructor[$out_region_col]['width'] = ($output_constructor[$out_region_col]['width']<$out_region_width)?$out_region_width:$output_constructor[$out_region_col]['width'];
            $output_constructor[$out_region_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_region_col] = $output_constructor_cell;
            $output_constructor[$out_region_col]['col_abc'] = $cols_ABC[$out_region_col];
            $output_constructor[$out_region_col]['width'] = $out_region_width;
            $output_constructor[$out_region_col]['rows_num'] = 1;
        }
        $output_constructor[$out_region_col]['data'][] = array('field_eng'=>'region','field_rus'=>'Регион');
    }
    // КТП
    if ($out_ktp) {
        $query_select .= ", ktp.tip_ps, ktp.n_ps ";
        if ($out_ktp_col < 1) {$out_ktp_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_ktp_width = ($out_ktp_width < 3)?10:$out_ktp_width;
        if (isset($output_constructor[$out_ktp_col])) {
            $output_constructor[$out_ktp_col]['width'] = ($output_constructor[$out_ktp_col]['width']<$out_ktp_width)?$out_ktp_width:$output_constructor[$out_ktp_col]['width'];
            $output_constructor[$out_ktp_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_ktp_col] = $output_constructor_cell;
            $output_constructor[$out_ktp_col]['col_abc'] = $cols_ABC[$out_ktp_col];
            $output_constructor[$out_ktp_col]['width'] = $out_ktp_width;
            $output_constructor[$out_ktp_col]['rows_num'] = 1;
        }
        $output_constructor[$out_ktp_col]['data'][] = array('field_eng'=>'ktp','field_rus'=>'ПС');
    }
    // ВЛ
    if ($out_vl) {
        $query_select .= ", ktp.tip_lep, ktp.n_lep ";
        if ($out_vl_col < 1) {$out_vl_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_vl_width = ($out_vl_width < 3)?10:$out_vl_width;
        if (isset($output_constructor[$out_vl_col])) {
            $output_constructor[$out_vl_col]['width'] = ($output_constructor[$out_vl_col]['width']<$out_vl_width)?$out_vl_width:$output_constructor[$out_vl_col]['width'];
            $output_constructor[$out_vl_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_vl_col] = $output_constructor_cell;
            $output_constructor[$out_vl_col]['col_abc'] = $cols_ABC[$out_vl_col];
            $output_constructor[$out_vl_col]['width'] = $out_vl_width;
            $output_constructor[$out_vl_col]['rows_num'] = 1;
        }
        $output_constructor[$out_vl_col]['data'][] = array('field_eng'=>'vl','field_rus'=>'ВЛ(КЛ)');
    }
    // ОПОРА
    if ($out_opora) {
        $query_select .= ", dom.opora ";
        if ($out_opora_col < 1) {$out_opora_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_opora_width = ($out_opora_width < 3)?10:$out_opora_width;
        if (isset($output_constructor[$out_opora_col])) {
            $output_constructor[$out_opora_col]['width'] = ($output_constructor[$out_opora_col]['width']<$out_opora_width)?$out_opora_width:$output_constructor[$out_opora_col]['width'];
            $output_constructor[$out_opora_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_opora_col] = $output_constructor_cell;
            $output_constructor[$out_opora_col]['col_abc'] = $cols_ABC[$out_opora_col];
            $output_constructor[$out_opora_col]['width'] = $out_opora_width;
            $output_constructor[$out_opora_col]['rows_num'] = 1;
        }
        $output_constructor[$out_opora_col]['data'][] = array('field_eng'=>'opora','field_rus'=>'Опора');
    }
// **************************************       ТАРИФ    **************************** //
     // ТАРИФ
    if ($out_tarif) {
        $query_select .= " , vidtar.vidtar, vidtar.vidmt, tarhist.idl, tarhist.mt, tarhist.semya, tarhist.semlg ";
        if ($out_tarif_col < 1) {$out_tarif_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_tarif_width = ($out_tarif_width < 3)?10:$out_tarif_width;
        if (isset($output_constructor[$out_tarif_col])) {
            $output_constructor[$out_tarif_col]['width'] = ($output_constructor[$out_tarif_col]['width']<$out_tarif_width)?$out_tarif_width:$output_constructor[$out_tarif_col]['width'];
            $output_constructor[$out_tarif_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_tarif_col] = $output_constructor_cell;
            $output_constructor[$out_tarif_col]['col_abc'] = $cols_ABC[$out_tarif_col];
            $output_constructor[$out_tarif_col]['width'] = $out_tarif_width;
            $output_constructor[$out_tarif_col]['rows_num'] = 1;
        }
        $output_constructor[$out_tarif_col]['data'][] = array('field_eng'=>'tarif','field_rus'=>'Тариф');
    }
     // СОСТАВ СЕМЬИ
    if ($out_semya) {
        $query_select .= " , tarhist.semya ";
        if ($out_semya_col < 1) {$out_semya_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_semya_width = ($out_semya_width < 3)?10:$out_semya_width;
        if (isset($output_constructor[$out_semya_col])) {
            $output_constructor[$out_semya_col]['width'] = ($output_constructor[$out_semya_col]['width']<$out_semya_width)?$out_semya_width:$output_constructor[$out_semya_col]['width'];
            $output_constructor[$out_semya_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_semya_col] = $output_constructor_cell;
            $output_constructor[$out_semya_col]['col_abc'] = $cols_ABC[$out_semya_col];
            $output_constructor[$out_semya_col]['width'] = $out_semya_width;
            $output_constructor[$out_semya_col]['rows_num'] = 1;
        }
        $output_constructor[$out_semya_col]['data'][] = array('field_eng'=>'semya','field_rus'=>'Семья');
    }
     // СОСТАВ СЕМЬИ: КОЛ-ВО ЛЬГОТНИКОВ
    if ($out_semlg) {
        $query_select .= " , tarhist.semlg ";
        if ($out_semlg_col < 1) {$out_semlg_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_semlg_width = ($out_semlg_width < 3)?10:$out_semlg_width;
        if (isset($output_constructor[$out_semlg_col])) {
            $output_constructor[$out_semlg_col]['width'] = ($output_constructor[$out_semlg_col]['width']<$out_semlg_width)?$out_semlg_width:$output_constructor[$out_semlg_col]['width'];
            $output_constructor[$out_semlg_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_semlg_col] = $output_constructor_cell;
            $output_constructor[$out_semlg_col]['col_abc'] = $cols_ABC[$out_semlg_col];
            $output_constructor[$out_semlg_col]['width'] = $out_semlg_width;
            $output_constructor[$out_semlg_col]['rows_num'] = 1;
        }
        $output_constructor[$out_semlg_col]['data'][] = array('field_eng'=>'semlg','field_rus'=>'Кол-во льготников');
    }
    // ************************************      ПОСЛ. КВИТАНЦИЯ      **************************** //
    // ПОСЛ.ОПЛАЧЕННОЕ: ПОКАЗАНИЕ
    if ($out_last_kvt) {
        if ($out_last_kvt_col < 1) {$out_last_kvt_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_last_kvt_width = ($out_last_kvt_width < 3)?10:$out_last_kvt_width;
        if (isset($output_constructor[$out_last_kvt_col])) {
            $output_constructor[$out_last_kvt_col]['width'] = ($output_constructor[$out_last_kvt_col]['width']<$out_last_kvt_width)?
            $out_last_kvt_width:$output_constructor[$out_last_kvt_col]['width'];
            $output_constructor[$out_last_kvt_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_last_kvt_col] = $output_constructor_cell;
            $output_constructor[$out_last_kvt_col]['col_abc'] = $cols_ABC[$out_last_kvt_col];
            $output_constructor[$out_last_kvt_col]['width'] = $out_last_kvt_width;
            $output_constructor[$out_last_kvt_col]['rows_num'] = 1;
        }
        $output_constructor[$out_last_kvt_col]['data'][] = array('field_eng'=>'last_kvt','field_rus'=>'Оплач. показание');
    }
    // ПОСЛ.ОПЛАЧЕННОЕ: ДАТА ОПЛАТЫ
    if ($out_last_date) {
        if ($out_last_date_col < 1) {$out_last_date_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_last_date_width = ($out_last_date_width < 3)?10:$out_last_date_width;
        if (isset($output_constructor[$out_last_date_col])) {
            $output_constructor[$out_last_date_col]['width'] = ($output_constructor[$out_last_date_col]['width']<$out_last_date_width)?
            $out_last_date_width:$output_constructor[$out_last_date_col]['width'];
            $output_constructor[$out_last_date_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_last_date_col] = $output_constructor_cell;
            $output_constructor[$out_last_date_col]['col_abc'] = $cols_ABC[$out_last_date_col];
            $output_constructor[$out_last_date_col]['width'] = $out_last_date_width;
            $output_constructor[$out_last_date_col]['rows_num'] = 1;
        }
        $output_constructor[$out_last_date_col]['data'][] = array('field_eng'=>'last_date','field_rus'=>'Дата Посл.опл');
    }
    // ПОСЛ.ОПЛАЧЕННОЕ: ПОКАЗАНИЕ АБОНЕНТА
    if ($out_last_pokkv) {
        if ($out_last_pokkv_col < 1) {$out_last_pokkv_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_last_pokkv_width = ($out_last_pokkv_width < 3)?10:$out_last_pokkv_width;
        if (isset($output_constructor[$out_last_pokkv_col])) {
            $output_constructor[$out_last_pokkv_col]['width'] = ($output_constructor[$out_last_pokkv_col]['width']<$out_last_pokkv_width)?
            $out_last_pokkv_width:$output_constructor[$out_last_pokkv_col]['width'];
            $output_constructor[$out_last_pokkv_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_last_pokkv_col] = $output_constructor_cell;
            $output_constructor[$out_last_pokkv_col]['col_abc'] = $cols_ABC[$out_last_pokkv_col];
            $output_constructor[$out_last_pokkv_col]['width'] = $out_last_pokkv_width;
            $output_constructor[$out_last_pokkv_col]['rows_num'] = 1;
        }
        $output_constructor[$out_last_pokkv_col]['data'][] = array('field_eng'=>'last_pokkv','field_rus'=>'Посл. пок. абон.');
    }
    // СРЕДНЕМЕСЯЧНОЕ ПОТРЕБЛЕНИЕ АБОНЕНТА ЗА УКАЗАННЫЙ ПЕРИОД
    if ($out_avg_mon_kvt) {
        if ($out_avg_mon_kvt_col < 1) {$out_avg_mon_kvt_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_avg_mon_kvt_width = ($out_avg_mon_kvt_width < 3)?10:$out_avg_mon_kvt_width;
        if (isset($output_constructor[$out_avg_mon_kvt_col])) {
            $output_constructor[$out_avg_mon_kvt_col]['width'] = ($output_constructor[$out_avg_mon_kvt_col]['width']<$out_avg_mon_kvt_width)?$out_avg_mon_kvt_width:$output_constructor[$out_avg_mon_kvt_col]['width'];
            $output_constructor[$out_avg_mon_kvt_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_avg_mon_kvt_col] = $output_constructor_cell;
            $output_constructor[$out_avg_mon_kvt_col]['col_abc'] = $cols_ABC[$out_avg_mon_kvt_col];
            $output_constructor[$out_avg_mon_kvt_col]['width'] = $out_avg_mon_kvt_width;
            $output_constructor[$out_avg_mon_kvt_col]['rows_num'] = 1;
        }
        $output_constructor[$out_avg_mon_kvt_col]['data'][] = array('field_eng'=>'avg_mon_kvt','field_rus'=>'Ср.мес. по опл.');
    }
    // ПОТРЕБЛЕНИЕ: оплаченные КВтч за период, указанный в "ПЕРИОД. ОПЛАТА"
    if ($out_period_kvts) {
        if ($out_period_kvts_col < 1) {$out_period_kvts_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_period_kvts_width = ($out_period_kvts_width < 3)?10:$out_period_kvts_width;
        if (isset($output_constructor[$out_period_kvts_col])) {
            $output_constructor[$out_period_kvts_col]['width'] = ($output_constructor[$out_period_kvts_col]['width']<$out_period_kvts_width)?$out_period_kvts_width:$output_constructor[$out_period_kvts_col]['width'];
            $output_constructor[$out_period_kvts_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_period_kvts_col] = $output_constructor_cell;
            $output_constructor[$out_period_kvts_col]['col_abc'] = $cols_ABC[$out_period_kvts_col];
            $output_constructor[$out_period_kvts_col]['width'] = $out_period_kvts_width;
            $output_constructor[$out_period_kvts_col]['rows_num'] = 1;
        }
        $output_constructor[$out_period_kvts_col]['data'][] = array('field_eng'=>'period_kvts','field_rus'=>'Потребление за период');
    }
    // ОПЛАТА: оплаченная сумма в руб. за период, указанный в "ПЕРИОД. ОПЛАТА"
    if ($out_period_sum) {
        if ($out_period_sum_col < 1) {$out_period_sum_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_period_sum_width = ($out_period_sum_width < 3)?10:$out_period_sum_width;
        if (isset($output_constructor[$out_period_sum_col])) {
            $output_constructor[$out_period_sum_col]['width'] = ($output_constructor[$out_period_sum_col]['width']<$out_period_sum_width)?$out_period_sum_width:$output_constructor[$out_period_sum_col]['width'];
            $output_constructor[$out_period_sum_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_period_sum_col] = $output_constructor_cell;
            $output_constructor[$out_period_sum_col]['col_abc'] = $cols_ABC[$out_period_sum_col];
            $output_constructor[$out_period_sum_col]['width'] = $out_period_sum_width;
            $output_constructor[$out_period_sum_col]['rows_num'] = 1;
        }
        $output_constructor[$out_period_sum_col]['data'][] = array('field_eng'=>'period_sum','field_rus'=>'Оплата за период');
    }
    // *******************    ПОСЛЕДНИЙ ОБХОД  *******************************************//
    // ПОСЛ.ОБХОД.: ДАТА
    if ($out_last_obh_date || $out_last_obh_pok || $out_obh_avg_mon || $with_date_bezobhod_s || $with_date_obhod) {
        $query_join .= " LEFT JOIN ".CURRENT_RES."_obhod as obhod on obhod.knobh = mainsc.kn and obhod.nomobh = mainsc.nom and obhod.uchobh = tarhist.uch and LENGTH(TRIM(obhod.ob_pok))>0 and (obhod.dlt is NULL or obhod.dlt = 0)";
        $query_where .= " and ((obhod.id is NULL) or (obhod.id = 0) or obhod.id = (SELECT b.id FROM ".CURRENT_RES."_obhod as b where LENGTH(TRIM(b.ob_pok))>0 and b.knobh = mainsc.kn and b.nomobh = mainsc.nom and b.uchobh = tarhist.uch and (b.dlt is NULL or b.dlt = 0)  ORDER BY b.dateobh desc, b.id desc LIMIT 1)) ";
    }
    if ($out_last_obh_date) {
        $query_select .= ", obhod.dateobh, obhod.ktotbn";
        if ($out_last_obh_date_col < 1) {$out_last_obh_date_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_last_obh_date_width = ($out_last_obh_date_width < 3)?10:$out_last_obh_date_width;
        if (isset($output_constructor[$out_last_obh_date_col])) {
            $output_constructor[$out_last_obh_date_col]['width'] = ($output_constructor[$out_last_obh_date_col]['width']<$out_last_obh_date_width)?
            $out_last_obh_date_width:$output_constructor[$out_last_obh_date_col]['width'];
            $output_constructor[$out_last_obh_date_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_last_obh_date_col] = $output_constructor_cell;
            $output_constructor[$out_last_obh_date_col]['col_abc'] = $cols_ABC[$out_last_obh_date_col];
            $output_constructor[$out_last_obh_date_col]['width'] = $out_last_obh_date_width;
            $output_constructor[$out_last_obh_date_col]['rows_num'] = 1;
        }
        $output_constructor[$out_last_obh_date_col]['data'][] = array('field_eng'=>'last_obh_date','field_rus'=>'Дата Посл.обход');

		if ($out_use_standart_form == 'f_obhod_contr_check') {
			$query_where .= " and obhod.ktotbn = ".$persona2;
		}
    }
    // ПОСЛ.ОБХОД.: ПОКАЗАНИЕ
    if ($out_last_obh_pok) {
        $query_select .= ", obhod.ob_pok";
        if ($out_last_obh_pok_col < 1) {$out_last_obh_pok_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_last_obh_pok_width = ($out_last_obh_pok_width < 3)?10:$out_last_obh_pok_width;
        if (isset($output_constructor[$out_last_obh_pok_col])) {
            $output_constructor[$out_last_obh_pok_col]['width'] = ($output_constructor[$out_last_obh_pok_col]['width']<$out_last_obh_pok_width)?
            $out_last_obh_pok_width:$output_constructor[$out_last_obh_pok_col]['width'];
            $output_constructor[$out_last_obh_pok_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_last_obh_pok_col] = $output_constructor_cell;
            $output_constructor[$out_last_obh_pok_col]['col_abc'] = $cols_ABC[$out_last_obh_pok_col];
            $output_constructor[$out_last_obh_pok_col]['width'] = $out_last_obh_pok_width;
            $output_constructor[$out_last_obh_pok_col]['rows_num'] = 1;
        }
        $output_constructor[$out_last_obh_pok_col]['data'][] = array('field_eng'=>'last_obh_pok','field_rus'=>'Посл. обход. показание');
    }
    // ОБХОДЫ: СРЕД.МЕС.ПОТРЕБЛЕНИЕ ЗА ПЕРИОД
    if ($out_obh_avg_mon) {
        $query_select .= ", obhod.ob_wsr";
        if ($out_obh_avg_mon_col < 1) {$out_obh_avg_mon_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_obh_avg_mon_width = ($out_obh_avg_mon_width < 3)?10:$out_avg_mon_kvt_width;
        if (isset($output_constructor[$out_obh_avg_mon_col])) {
            $output_constructor[$out_obh_avg_mon_col]['width'] = ($output_constructor[$out_obh_avg_mon_col]['width']<$out_obh_avg_mon_width)?$out_obh_avg_mon_width:$output_constructor[$out_obh_avg_mon_col]['width'];
            $output_constructor[$out_obh_avg_mon_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_obh_avg_mon_col] = $output_constructor_cell;
            $output_constructor[$out_obh_avg_mon_col]['col_abc'] = $cols_ABC[$out_obh_avg_mon_col];
            $output_constructor[$out_obh_avg_mon_col]['width'] = $out_obh_avg_mon_width;
            $output_constructor[$out_obh_avg_mon_col]['rows_num'] = 1;
        }
        $output_constructor[$out_obh_avg_mon_col]['data'][] = array('field_eng'=>'obh_avg_mon','field_rus'=>'Ср.мес. по обходам.');
    }
// *************************************   СЧЕТЧИК     **************************** //
    //  СЧЕТЧИК: КОД
    if ($out_sc_kod) {
        $query_select .= ", tip_sc.id as tip_sc_kod ";
        if ($out_sc_kod_col < 1) {$out_sc_kod_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_kod_width = ($out_sc_kod_width < 3)?10:$out_sc_kod_width;
        if (isset($output_constructor[$out_sc_kod_col])) {
            $output_constructor[$out_sc_kod_col]['width'] = ($output_constructor[$out_sc_kod_col]['width']<$out_sc_kod_width)?
            $out_sc_kod_width:$output_constructor[$out_sc_kod_col]['width'];
            $output_constructor[$out_sc_kod_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_kod_col] = $output_constructor_cell;
            $output_constructor[$out_sc_kod_col]['col_abc'] = $cols_ABC[$out_sc_kod_col];
            $output_constructor[$out_sc_kod_col]['width'] = $out_sc_kod_width;
            $output_constructor[$out_sc_kod_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_kod_col]['data'][] = array('field_eng'=>'sc_kod','field_rus'=>'Счетчик: КОД');
    }
    //  СЧЕТЧИК: ТИП
    if ($out_sc_tip) {
        $query_select .= ", tip_sc.tipch, tip_sc.a";
        if ($out_sc_tip_col < 1) {$out_sc_tip_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_tip_width = ($out_sc_tip_width < 3)?10:$out_sc_tip_width;
        if (isset($output_constructor[$out_sc_tip_col])) {
            $output_constructor[$out_sc_tip_col]['width'] = ($output_constructor[$out_sc_tip_col]['width']<$out_sc_tip_width)?
            $out_sc_tip_width:$output_constructor[$out_sc_tip_col]['width'];
            $output_constructor[$out_sc_tip_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_tip_col] = $output_constructor_cell;
            $output_constructor[$out_sc_tip_col]['col_abc'] = $cols_ABC[$out_sc_tip_col];
            $output_constructor[$out_sc_tip_col]['width'] = $out_sc_tip_width;
            $output_constructor[$out_sc_tip_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_tip_col]['data'][] = array('field_eng'=>'sc_tip','field_rus'=>'Счетчик: ТИП');
    }
    //  СЧЕТЧИК: ГОД ВЫПУСКА
    if ($out_sc_gv) {
        $query_select .= ", mainsc.yearvyp";
        if ($out_sc_gv_col < 1) {$out_sc_gv_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_gv_width = ($out_sc_gv_width < 3)?10:$out_sc_gv_width;
        if (isset($output_constructor[$out_sc_gv_col])) {
            $output_constructor[$out_sc_gv_col]['width'] = ($output_constructor[$out_sc_gv_col]['width']<$out_sc_gv_width)?
            $out_sc_gv_width:$output_constructor[$out_sc_gv_col]['width'];
            $output_constructor[$out_sc_gv_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_gv_col] = $output_constructor_cell;
            $output_constructor[$out_sc_gv_col]['col_abc'] = $cols_ABC[$out_sc_gv_col];
            $output_constructor[$out_sc_gv_col]['width'] = $out_sc_gv_width;
            $output_constructor[$out_sc_gv_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_gv_col]['data'][] = array('field_eng'=>'sc_gv','field_rus'=>'Счетчик: ГОД ВЫПУСКА');
    }
    //  СЧЕТЧИК: ГОД ПОВЕРКИ
    if ($out_sc_gpr) {
        $query_select .= ", mainsc.yearprov";
        if ($out_sc_gpr_col < 1) {$out_sc_gpr_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_gpr_width = ($out_sc_gpr_width < 3)?10:$out_sc_gpr_width;
        if (isset($output_constructor[$out_sc_gpr_col])) {
            $output_constructor[$out_sc_gpr_col]['width'] = ($output_constructor[$out_sc_gpr_col]['width']<$out_sc_gpr_width)?
            $out_sc_gpr_width:$output_constructor[$out_sc_gpr_col]['width'];
            $output_constructor[$out_sc_gpr_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_gpr_col] = $output_constructor_cell;
            $output_constructor[$out_sc_gpr_col]['col_abc'] = $cols_ABC[$out_sc_gpr_col];
            $output_constructor[$out_sc_gpr_col]['width'] = $out_sc_gpr_width;
            $output_constructor[$out_sc_gpr_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_gpr_col]['data'][] = array('field_eng'=>'sc_gpr','field_rus'=>'Счетчик: ГОД ПОВЕРКИ');
    }
    //  СЧЕТЧИК: ДАТА УСТАНОВКИ
    if ($out_sc_dateust) {
        $query_select .= ", mainsc.dateust ";
        if ($out_sc_dateust_col < 1) {$out_sc_dateust_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_dateust_width = ($out_sc_dateust_width < 3)?10:$out_sc_dateust_width;
        if (isset($output_constructor[$out_sc_dateust_col])) {
            $output_constructor[$out_sc_dateust_col]['width'] = ($output_constructor[$out_sc_dateust_col]['width']<$out_sc_dateust_width)?
                $out_sc_dateust_width:$output_constructor[$out_sc_dateust_col]['width'];
            $output_constructor[$out_sc_dateust_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_dateust_col] = $output_constructor_cell;
            $output_constructor[$out_sc_dateust_col]['col_abc'] = $cols_ABC[$out_sc_dateust_col];
            $output_constructor[$out_sc_dateust_col]['width'] = $out_sc_dateust_width;
            $output_constructor[$out_sc_dateust_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_dateust_col]['data'][] = array('field_eng'=>'sc_dateust','field_rus'=>'Счетчик: ДАТА УСТАНОВКИ (номер наряда)');
    }
    //  СЧЕТЧИК: ДАТА СНЯТИЯ
    if ($out_sc_datesn) {
        $query_select .= ", mainsc.datesn ";
        if ($out_sc_datesn_col < 1) {$out_sc_datesn_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_datesn_width = ($out_sc_datesn_width < 3)?10:$out_sc_datesn_width;
        if (isset($output_constructor[$out_sc_datesn_col])) {
            $output_constructor[$out_sc_datesn_col]['width'] = ($output_constructor[$out_sc_datesn_col]['width']<$out_sc_datesn_width)?
                        $out_sc_datesn_width:$output_constructor[$out_sc_datesn_col]['width'];
            $output_constructor[$out_sc_datesn_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_datesn_col] = $output_constructor_cell;
            $output_constructor[$out_sc_datesn_col]['col_abc'] = $cols_ABC[$out_sc_datesn_col];
            $output_constructor[$out_sc_datesn_col]['width'] = $out_sc_datesn_width;
            $output_constructor[$out_sc_datesn_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_datesn_col]['data'][] = array('field_eng'=>'sc_datesn','field_rus'=>'Счетчик: ДАТА СНЯТИЯ');
    }
    //  СЧЕТЧИК: ПОКАЗАНИЕ УСТАНОВКИ
    if ($out_sc_pokusc) {
        $query_select .= ", mainsc.pokusc1, mainsc.pokusc2";
        if ($out_sc_pokusc_col < 1) {$out_sc_pokusc_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_pokusc_width = ($out_sc_pokusc_width < 3)?10:$out_sc_pokusc_width;
        if (isset($output_constructor[$out_sc_pokusc_col])) {
            $output_constructor[$out_sc_pokusc_col]['width'] = ($output_constructor[$out_sc_pokusc_col]['width']<$out_sc_pokusc_width)?
                $out_sc_pokusc_width:$output_constructor[$out_sc_pokusc_col]['width'];
            $output_constructor[$out_sc_pokusc_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_pokusc_col] = $output_constructor_cell;
            $output_constructor[$out_sc_pokusc_col]['col_abc'] = $cols_ABC[$out_sc_pokusc_col];
            $output_constructor[$out_sc_pokusc_col]['width'] = $out_sc_pokusc_width;
            $output_constructor[$out_sc_pokusc_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_pokusc_col]['data'][] = array('field_eng'=>'sc_pokusc','field_rus'=>'Счетчик: ПОКАЗАНИЕ УСТАНОВКИ');
    }
    //  СЧЕТЧИК: ЗАВОДСКОЙ НОМЕР
    if ($out_sc_zn) {
        $query_select .= ", mainsc.zn";
        if ($out_sc_zn_col < 1) {$out_sc_zn_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_zn_width = ($out_sc_zn_width < 3)?10:$out_sc_zn_width;
        if (isset($output_constructor[$out_sc_zn_col])) {
        $output_constructor[$out_sc_zn_col]['width'] = ($output_constructor[$out_sc_zn_col]['width']<$out_sc_zn_width)?
            $out_sc_zn_width:$output_constructor[$out_sc_zn_col]['width'];
        $output_constructor[$out_sc_zn_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_zn_col] = $output_constructor_cell;
            $output_constructor[$out_sc_zn_col]['col_abc'] = $cols_ABC[$out_sc_zn_col];
            $output_constructor[$out_sc_zn_col]['width'] = $out_sc_zn_width;
            $output_constructor[$out_sc_zn_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_zn_col]['data'][] = array('field_eng'=>'sc_zn','field_rus'=>'Счетчик: ЗАВОДСКОЙ НОМЕР');
    }
    //  СЧЕТЧИК: ПРИНАДЛЕЖНОСТЬ
    if ($out_sc_ps) {
        $query_join .= " LEFT JOIN ".CURRENT_RES."_tip_prinadl as tippr_sc on mainsc.ps = tippr_sc.id";
        $query_select .= ", tippr_sc.tipps as tipps_sc";
        if ($out_sc_ps_col < 1) {$out_sc_ps_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_ps_width = ($out_sc_ps_width < 3)?10:$out_sc_ps_width;
        if (isset($output_constructor[$out_sc_ps_col])) {
            $output_constructor[$out_sc_ps_col]['width'] = ($output_constructor[$out_sc_ps_col]['width']<$out_sc_ps_width)?
            $out_sc_ps_width:$output_constructor[$out_sc_ps_col]['width'];
            $output_constructor[$out_sc_ps_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_ps_col] = $output_constructor_cell;
            $output_constructor[$out_sc_ps_col]['col_abc'] = $cols_ABC[$out_sc_ps_col];
            $output_constructor[$out_sc_ps_col]['width'] = $out_sc_ps_width;
            $output_constructor[$out_sc_ps_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_ps_col]['data'][] = array('field_eng'=>'sc_ps','field_rus'=>'Счетчик: ПРИНАДЛЕЖНОСТЬ');
    }
    //  СЧЕТЧИК: 1/3 ФАЗНЫЙ
    if ($out_sc_1_3) {
        $query_select .= ", mainsc.ts";
        if ($out_sc_1_3_col < 1) {$out_sc_1_3_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_1_3_width = ($out_sc_1_3_width < 3)?10:$out_sc_1_3_width;
        if (isset($output_constructor[$out_sc_1_3_col])) {
            $output_constructor[$out_sc_1_3_col]['width'] = ($output_constructor[$out_sc_1_3_col]['width']<$out_sc_1_3_width)?
                $out_sc_1_3_width:$output_constructor[$out_sc_1_3_col]['width'];
            $output_constructor[$out_sc_1_3_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_1_3_col] = $output_constructor_cell;
            $output_constructor[$out_sc_1_3_col]['col_abc'] = $cols_ABC[$out_sc_1_3_col];
            $output_constructor[$out_sc_1_3_col]['width'] = $out_sc_1_3_width;
            $output_constructor[$out_sc_1_3_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_1_3_col]['data'][] = array('field_eng'=>'sc_1_3','field_rus'=>'Счетчик: 1/3-фазный');
    }
    //  СЧЕТЧИК: НОМЕР ПЛОМБИРА
    if ($out_sc_plmb) {
        $query_select .= ", mainsc.plmb";
        if ($out_sc_plmb_col < 1) {$out_sc_plmb_col =  $out_col_pnum;  $out_col_pnum += 1;}
        $out_sc_plmb_width = ($out_sc_plmb_width < 3)?10:$out_sc_plmb_width;
        if (isset($output_constructor[$out_sc_plmb_col])) {
            $output_constructor[$out_sc_plmb_col]['width'] = ($output_constructor[$out_sc_plmb_col]['width']<$out_sc_plmb_width)?
                $out_sc_plmb_width:$output_constructor[$out_sc_plmb_col]['width'];
            $output_constructor[$out_sc_plmb_col]['rows_num'] += 1;
        }
        else {
            $output_constructor[$out_sc_plmb_col] = $output_constructor_cell;
            $output_constructor[$out_sc_plmb_col]['col_abc'] = $cols_ABC[$out_sc_plmb_col];
            $output_constructor[$out_sc_plmb_col]['width'] = $out_sc_plmb_width;
            $output_constructor[$out_sc_plmb_col]['rows_num'] = 1;
        }
        $output_constructor[$out_sc_plmb_col]['data'][] = array('field_eng'=>'sc_plmb','field_rus'=>'Счетчик: Номер пломбира');
    }

    // Проверяет конструктор - если не задан выдаём ошибку
    if (count($output_constructor)==0 && $out_use == 'constructor') {
        $errors[] = "Неправильно заданы ПОЛЯ ДЛЯ ВЫВОДА";
    }

    if (count($errors)) {
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
        exit();
    }


    // ИТОГОВЫЙ ЗАПРОС К БАЗЕ ДАННЫХ
    // Анализируем $query_order
    if (mb_strlen(trim($query_order))) $query_order = " ORDER BY $query_order"; else $query_order = '';
    // Исправляем способы вывода и сортировку для стандартных отчетов
    // Если надо просто вывести количество абонентов, то меняем $query_select, $query_order, выводим и exit();
    if ($out_use == 'standart_form') {
        if ($out_use_standart_form == 'search_result_count') {
            $query_select = ' main.kn, main.nom ';
            $query_order = ' ';
        }
        elseif ($out_use_standart_form == 'f_obhod' || $out_use_standart_form == 'f_obhod_3str' || $out_use_standart_form == 'f_obhod_mobi' || $out_use_standart_form == 'f_sc_zamena' || $out_use_standart_form == 'f_kartochka_ucheta' || $out_use_standart_form == 'f_obhod_contr_check') {
            $query_order = ' ';
            if ($with_kn || $with_alldb) {
                $query_order = ' ORDER BY main.kn, main.nom ';
            }
            elseif ($with_address || $with_region) {
                $query_order = ' ORDER BY np.np, tip_street.tip_street, street.street, cast(dom.dom as integer), dom.domadd, length(main.kb), main.kb, main.kn, main.nom ';
            }
            if ($without_tip_postr==0 || $without_tip_postr_3str==0) {
	         $query_select .= " , tip_postr.postr, main.postr as postr_dop ";
	    }
        }
        elseif($out_use_standart_form == 'search_result_knnom_link') {
            $query_order = ' ORDER BY main.kn, main.nom ';
        }
    }

    if ($out_use_standart_form == 'f_obhod_mobi' ) {
	    $query_select .= ", main.paspnum, main.paspident, main.paspdata, main.paspkem, main.comment, obhod.ob_pr, mainsc.maxn, tip_sc.spov ";
    }

    if ($out_use_standart_form == 'f_sc_poverka' ) {
        if ($with_alldb == 1) {
            $for_shapka = 'по всей базе';
            $query_select  = ' main.kn, MAX(np.np) as np, MAX(street.street) as street, COUNT(main.kn) as sc_vsego, AVG(mainsc.yearprov) as sc_sr_god_vs';
            $query_select2 = ' main.kn, COUNT(main.kn) as sc_prosroch, AVG(mainsc.yearprov) as sc_sr_god_ps';
            $query_group = ' GROUP BY main.kn ';
            $query_order = ' ORDER BY main.kn ';
        }
        elseif ($with_kn == 1) {
            $for_shapka = 'с номера книги '.$f_c_knbeg.' по номер '.$f_c_knend;
            $query_select  = ' main.kn, MAX(np.np) as np, MAX(street.street) as street, COUNT(main.kn) as sc_vsego, AVG(mainsc.yearprov) as sc_sr_god_vs';
            $query_select2 = ' main.kn, COUNT(main.kn) as sc_prosroch, AVG(mainsc.yearprov) as sc_sr_god_ps';
            $query_group = ' GROUP BY main.kn ';
            $query_order = ' ORDER BY main.kn ';
        }
        elseif ($with_address == 1) {
            $for_shapka = 'по адресам';
            $query_select  = ' MIN(main.kn) as kn, np.np, tip_street.short_tip_street, street.street, COUNT(main.kn) as sc_vsego, AVG(mainsc.yearprov) as sc_sr_god_vs';
            $query_select2 = ' np.np, tip_street.short_tip_street, street.street, COUNT(main.kn) as sc_prosroch, AVG(mainsc.yearprov) as sc_sr_god_ps';
            $query_group = ' GROUP BY np.np, tip_street.short_tip_street, street.street ';
            $query_order = ' ORDER BY np.np, tip_street.short_tip_street, street.street ';
        }
        elseif ($with_region == 1) {
            $for_shapka = 'по регионам';
            $query_select  = ' MIN(main.kn) as kn, region.region as np, COUNT(main.kn) as sc_vsego, AVG(mainsc.yearprov) as sc_sr_god_vs';
            $query_select2 = ' region.region as np, COUNT(main.kn) as sc_prosroch, AVG(mainsc.yearprov) as sc_sr_god_ps';
            $query_group = ' GROUP BY region.region ';
            $query_order = ' ORDER BY region.region ';
        }
        $query_where2 = $query_where." and (mainsc.yearprov <= (".date("Y")." - tip_sc.spov) or mainsc.yearprov = 0 )";
        $query = "SELECT $query_select  FROM $query_from $query_join WHERE $query_where  $query_group $query_order";
        $query2 ="SELECT $query_select2 FROM $query_from $query_join WHERE $query_where2 $query_group $query_order";

        $res = dbQuery($query2);
        $res_filter = array();
        while ($row = dbFetchAssoc($res)){
            $res_filter[] = $row;
        }
        $res2 = $res_filter;
    }
    else $query = "SELECT $query_select FROM $query_from $query_join WHERE $query_where $query_order";
    echo '<br>',$query, '<br>';


    /* ************************************************ */
    /*            Выполнение выборки                    */
    /* ************************************************ */
    $time_start = getmicrotime();
    $res = dbQuery($query);

    //Если есть то фильтруем итоговую выборку по дополнительным условиям
    $res_filter = array();
    while ($row = dbFetchAssoc($res)) {
        $under_filter = 0;
        // отфильтровать отключенных
        if ($with_without_otkl) {
            if (dbOne("SELECT id FROM ".CURRENT_RES."_otkl WHERE kn='{$row['kn']}' and nom='{$row['nom']}' and dlt=0 and
                        id_pr in (SELECT id FROM _spr_otkl WHERE (LOWER(otkl) LIKE '%ОТКЛ%') and dateend is NULL) ORDER BY datebeg desc limit 1",'int') > 0)
            $under_filter = 1;
        }
        // отфильтровать еще что-нибудь
        if ($under_filter == 0) $res_filter[] = $row;
    }
    $res = $res_filter;

    if ($out_use_standart_form == 'f_sc_poverka' ) {
        echo "<br>Найдено <font color=red>".count($res)."</font> объектов, соответствующих параметрам выборки.<br>";
    }
    else {
        echo "<br>Найдено <font color=red>".count($res)."</font> абонентов, соответствующих параметрам выборки.<br>";
    }

    if (count($res) == 0) {
        $errors[] = "Нет абонентов, соответствующих параметрам выборки.";
    }
    if (count($errors)) {
        $smarty->assign('label_error',1);
        $smarty->assign('errors',$errors);
        $smarty->display('general_errors.html');
        exit();
    }


    /* ******************************************************* */
    /*    ПИШЕМ ОТЧЕТ В ФАЙЛ ПО ЗАДАННОМУ КОНСТРУКТОРУ         */
    /* ******************************************************* */
    if ($out_use == 'constructor') {
        $key = 10;                      // Строка с которой начинаем писать отчет
        $style = $style_a_8_l_b;        // Единый стиль для всех ячеек
        $style_head =  $style_a_8_c_b;  // Единый стиль для строчек шапки
        $row_Height = 13;               // Высота строк
        $rows_num = 1;                  // Количество строк для объединения для одной записи выходного массива
        foreach($output_constructor as $cell) {
            $rows_num = ($cell['rows_num'] > $rows_num)?$cell['rows_num']:$rows_num;
        }
        if (count($res)*$rows_num > 65500){
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error',1);
            $smarty->assign('errors',$errors);
            $smarty->display('general_errors.html');
            exit();
        }
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);

        // ПИШЕМ СТРОКУ ШАПКИ ПО КОНСТРУКТОРУ
        // ИЩЕМ МИНИМАЛЬНОВЫВОДИМОЕ ЗНАЧИМОЕ ПОЛЕ И ЗАДАЕМ СОРТИРОВКУ ПО НЕМУ
        foreach($output_constructor as $cell) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($cell['col_abc'])->setWidth($cell['width']);
            for($i=0; $i<$rows_num; $i++) {
                $objPHPExcel->getActiveSheet()->getStyle("{$cell['col_abc']}".($key+$i))->applyFromArray($style_head);
                if (isset($cell['data'][$i]['field_rus'])) {
                    $objPHPExcel->getActiveSheet()->setCellValue("{$cell['col_abc']}".($key+$i), $cell['data'][$i]['field_rus']);
                }
            }
            if ($rows_num>1 && ($cell['rows_num'] == 1)) {
                $objPHPExcel->getActiveSheet()->mergeCells($cell['col_abc'].$key.":".$cell['col_abc'].($key+$rows_num-1));
            }
        }

        // ПИШЕМ ДАННЫЕ СРАЗУ НЕПОСРЕДСТВЕННО В EXCEL ОБЪЕКТ
        // $row = данные по абоненту
        $key_abonent = 0;
        foreach ($res as $row) {
            $key += $rows_num;
            ++$key_abonent;
            // ПО КОНСТРУКТОРУ В ЦИКЛЕ СОСТАВЛЯЕМ СТРОКУ ПО ЯЧЕЙКАМ
            foreach($output_constructor as $cell) {
                for($i=0; $i<$rows_num; $i++){
                    if (isset($cell['data'][$i]['field_rus'])) {
                        // АНАЛИЗИРУЕМ ТО ЧТО НАДО ВСТАВИТЬ В ЯЧЕЙКУ
                        $field_eng = $cell['data'][$i]['field_eng'];
                        $field_data = $key_abonent;
                        if ($field_eng == 'number')   $field_data = $key_abonent;
                        if ($field_eng == 'knnom')    $field_data = $row['kn'].'-'.$row['nom'];
                        if ($field_eng == 'fio')      $field_data = $row['fio']; //strtoupper($row['fam'].' '.mb_substr($row['im'],0,1).' '.mb_substr($row['ot'],0,1));
                        if ($field_eng == 'address')  $field_data = trim($row['np'].", ".$row['short_tip_street'].trim($row['street']).", ".domnumberfull($row['dom'],$row['domadd']).",".$row['kb']);
                        if ($field_eng == 'tel')      $field_data = ' '.$row['tel'];
                        if ($field_eng == 'mobtel')   $field_data = ' '.$row['mobtel'];
                        if ($field_eng == 'data_dog') $field_data = dateDMY($row['datadog']);
                        if ($field_eng == 'nomr') {
                            if (mb_strlen($row['nomr'])>1 && $row['nomr']!='-1') {
                                $nomr = $row['nomr'];
                                $nomr = implode(',',explode(' ',trim(str_replace(',',' ',$nomr))));
                                $temp = dbFetchArray(dbQuery("SELECT simv FROM _spr_nomr WHERE id in (-1,".$nomr.")"));
                                $nomr = '';
                                foreach($temp as $value)  $nomr .= $value['simv'].', ';
                                 $field_data = mb_substr($nomr, 0, mb_strlen($nomr)-2);
                            }
                            else $field_data = '';
                        }
                        if ($field_eng == 'plmbvu1') $field_data = ' '.$row['plmbvu1'];
                        if ($field_eng == 'plmbvu2') $field_data = ' '.$row['plmbvu2'];
                        if ($field_eng == 'plmbs0')  $field_data = ' '.$row['plmbs0'];
                        if ($field_eng == 'region')  $field_data = $row['region'];
                        if ($field_eng == 'ktp')	 $field_data = ' '.$row['tip_ps'].'-'.$row['n_ps'];
                        if ($field_eng == 'vl')      $field_data = ' '.$row['tip_lep'].'-'.$row['n_lep'];
                        if ($field_eng == 'opora')   $field_data = ' '.$row['opora'];
                        if ($field_eng == 'tarif') {
                            $temp = $row['vidtar'].'-'.$row['vidmt'];
                            if ($row['idl']>0) $temp .= '-Льг.';
                            $temp .= " (".$row['semya']."/".$row['semlg'].")";
                            $field_data = $temp;
                        }
                        if ($field_eng == 'semya')   $field_data = $row['semya'];
                        if ($field_eng == 'semlg')   $field_data = $row['semlg'];

                        if ($field_eng == 'period_kvts') {
                            $field_data = 0;
                            $field_data = dbOne("select SUM(KVTKV) from ".CURRENT_RES."_kvit
                            where knkv='{$row['kn']}' and nomkv='{$row['nom']}' and uchkv='{$row['uch']}' and
                            datekv >= '$f_c_datebeg' and datekv <= '$f_c_dateend' and dlt=0 ");
                        }
                        if ($field_eng == 'period_sum') {
                            $field_data = 0;
                            $field_data = dbOne("select SUM(SUMKV) from ".CURRENT_RES."_kvit
                            where knkv='{$row['kn']}' and nomkv='{$row['nom']}' and uchkv='{$row['uch']}' and
                            datekv >= '$f_c_datebeg' and datekv <= '$f_c_dateend' and dlt=0 ");
                        }

                        if ($field_eng == 'last_kvt'    ||
                            $field_eng == 'last_date'   ||
                            $field_eng == 'last_pokkv'  ||
                            $field_eng == 'period_kvts' ||
                            $field_eng == 'avg_mon_kvt') {

                            $kvits = GetKvit($row['kn'],$row['nom'],$row['uch']);
                            if (isset($kvits[0])) {
                                if ($field_eng == 'last_kvt') $field_data = $kvits[0]['pokaz'];
                                if ($field_eng == 'last_pokkv') {
                                    if ( $kvits[0]['oshkv'] == 8)    $field_data = 'Н.расч.*';
                                    elseif ($kvits[0]['oshkv'] == 9) $field_data = 'Н.расч.';
                                    else                             $field_data = $kvits[0]['pokkv'];
                                }
                                if ($field_eng == 'last_date')  $field_data = $kvits[0]['datekv'];
                                if ($field_eng == 'avg_mon_kvt') {
                                    // ПРОБЕГАЕМ ПО КВИТАНЦИЯМ - выбираем те, которые входят в указанный промежуток
                                    // для них вычисляем среднее потребление за месяц
                                    $temp_num = array();
                                    $temp_kvt = 0;
                                    for ($xx=0; $xx<=count($kvits)-1; $xx++) {
                                        $kvit_time = dateYMD($kvits[$xx]['datekv']);
                                        if ($kvit_time >= $f_c_dateb && $kvit_time <= $f_c_datee) {
                                            $temp_kvt += $kvits[$xx]['kvtkv'];
                                        }
                                    }
                                    $f_c_dateb_a = explode('.',$f_c_dateb);
                                    $f_c_datee_a = explode('.',$f_c_datee);
                                    $temp_num = (mktime(0,0,0,$f_c_datee_a[1],$f_c_datee_a[2],$f_c_datee_a[0]) - mktime(0,0,0,$f_c_dateb_a[1],$f_c_dateb_a[2],$f_c_dateb_a[0]))/(60*60*24*30);
                                    if ($temp_num>0 && $temp_num<0.51) $temp_num = 1;
                                    else $temp_num = round($temp_num);
                                    if ($temp_num && $temp_kvt) $field_data = round(($temp_kvt/$temp_num),2);
                                    else  $field_data = 0;
                                }
                                if ($field_eng == 'period_kvts') {
                                    // ПРОБЕГАЕМ ПО КВИТАНЦИЯМ - выбираем те, которые входят в указанный промежуток
                                    $temp_kvt = 0;
                                    for ($xx=0; $xx<=count($kvits)-1; $xx++){
                                        $kvit_time = dateYMD($kvits[$xx]['datekv']);
                                        if ($kvit_time >= $f_c_dateb && $kvit_time <= $f_c_datee) {
                                            $temp_kvt += $kvits[$xx]['kvtkv'];
                                        }
                                    }
                                    if ($temp_kvt) $field_data = $temp_kvt;
                                    else           $field_data = 0;
                                }
                            }
                            else $field_data = '';
                        }
                        if ($field_eng == 'last_obh_date') $field_data = dateDMY($row['dateobh']);
                        if ($field_eng == 'last_obh_pok')  $field_data = ' '.$row['ob_pok'];
                        if ($field_eng == 'obh_avg_mon')   $field_data = 30*$row['ob_wsr'];
                        if ($field_eng == 'sc_kod')   	   $field_data = $row['tip_sc_kod'];
                        if ($field_eng == 'sc_tip') {
                            if (isset($row['tipch']))   $field_data = $row['tipch'].','.$row['a'].'A';
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                $field_data = dbOne("Select _tip_sc.tipch from _tip_sc, ".CURRENT_RES."_mainsc as mainsc where _tip_sc.id = mainsc.ts and mainsc.kn='{$row['kn']}' and mainsc.nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                            }
                        }
                        if ($field_eng == 'sc_gv') {
                            if (isset($row['yearvyp'])) $field_data = $row['yearvyp'];
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                $field_data = dbOne("Select yearvyp from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                            }
                        }
                        if ($field_eng == 'sc_gpr') {
                            if (isset($row['yearprov'])) $field_data = $row['yearprov'];
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                $field_data = dbOne("Select yearprov from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                            }
                        }
                        if ($field_eng == 'sc_dateust') {
                            if (isset($row['dateust'])) {
					            $data_ustanovki_sc = $row['dateust'];
					            $field_data = dateDMY($data_ustanovki_sc).' ('.dbOne("Select nnar from ".CURRENT_RES."_obhod where knobh='{$row['kn']}' and nomobh='{$row['nom']}' and dlt=0 and (dateobh ='$data_ustanovki_sc')").')';
				            }
                            else {
                                if ($with_date_sc_ust) {
					                $f_c_dateb_sc_ust = mb_strlen($f_c_dateb_sc_ust) ? dateYMD($f_c_dateb_sc_ust) : dateYMD();
					                $f_c_datee_sc_ust = mb_strlen($f_c_datee_sc_ust) ? dateYMD($f_c_datee_sc_ust) : dateYMD();
					                $data_ustanovki_sc = dbOne("Select dateust from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (dateust >='$f_c_dateb_sc_ust') and (dateust <='$f_c_datee_sc_ust')");
					                $field_data = dateDMY($data_ustanovki_sc).' ('.dbOne("Select nnar from ".CURRENT_RES."_obhod where knobh='{$row['kn']}' and nomobh='{$row['nom']}' and dlt=0 and (dateobh ='$data_ustanovki_sc')").')';
                                }
                                else {
					                $data_ustanovki_sc = dbOne("Select dateust from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn IS NULL)");
					                $field_data = dateDMY($data_ustanovki_sc).' ('.dbOne("Select nnar from ".CURRENT_RES."_obhod where knobh='{$row['kn']}' and nomobh='{$row['nom']}' and dlt=0 and (dateobh ='$data_ustanovki_sc')").')';
                                }
                            }
                        }
                        if ($field_eng == 'sc_datesn') {
                            if (isset($row['datesn'])) $field_data = dateDMY($row['datesn']);
                            else {
                                if ($with_date_sc_sn) {
                                    $f_c_dateb_sc_sn = mb_strlen($f_c_dateb_sc_sn) ? dateYMD($f_c_dateb_sc_sn) : dateYMD(1);
                                    $f_c_datee_sc_sn = mb_strlen($f_c_datee_sc_sn) ? dateYMD($f_c_datee_sc_sn) : 0;
                                    $temp = "Select datesn from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn >='$f_c_dateb_sc_sn' ";
                                    if ($f_c_datee_sc_sn)  $temp .= " and datesn <= '$f_c_datee_sc_sn')";
                                    else                   $temp .= " or datesn is NULL";
                                    $temp .= ' order by id ';
                                    $field_data = dateDMY(dbOne($temp));
                                }
                                else $field_data = '';
                            }
                        }
                        if ($field_eng == 'sc_pokusc') {
                            if (isset($row['pokusc'])) {
                                if ($row['uch'] == 1) $field_data = ' '.$row['pokusc1'];
                                if ($row['uch'] == 2) $field_data = ' '.$row['pokusc2'];
                            }
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                if ($row['uch'] == 1) $field_data = ' '.dbOne("Select pokusc1 from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                                if ($row['uch'] == 2) $field_data = ' '.dbOne("Select pokusc2 from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                            }
                        }
                        if ($field_eng == 'sc_zn') {
                            if (isset($row['zn'])) $field_data = ' '.$row['zn'];
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                $field_data = ' '.dbOne("Select zn from ".CURRENT_RES."_mainsc where kn='{$row['kn']}' and nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                            }
                        }
                        if ($field_eng == 'sc_ps') {
                            if (isset($row['tipps_sc'])) {
                                $field_data = $row['tipps_sc'];
                            }
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                $field_data = dbOne("Select tip_prinadl.tipps from ".CURRENT_RES."_mainsc as mainsc, ".CURRENT_RES."_tip_prinadl as tip_prinadl  where mainsc.kn='{$row['kn']}' and mainsc.nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee') and mainsc.ps=tip_prinadl.id");
                            }
                        }
                        if ($field_eng == 'sc_1_3') {
                            if (isset($row['sc_1_3'])) {
                                if ( intval($row['ts']) < 2000)  $field_data = '1ф';
                                else                             $field_data = '3ф';
                            }
                            else {
                                if ($with_date) $f_c_datee = mb_strlen($f_c_datee) ? dateYMD($f_c_datee) : dateYMD();
                                else $f_c_datee = dateYMD();
                                $field_data = dbOne("Select _tip_sc.label as sc_1_3 from _tip_sc, ".CURRENT_RES."_mainsc as mainsc where _tip_sc.id = mainsc.ts and mainsc.kn='{$row['kn']}' and mainsc.nom='{$row['nom']}' and (datesn IS NULL or datesn >'$f_c_datee')");
                                if ($field_data == 1)     $field_data='1ф';
                                elseif ($field_data == 3) $field_data='3ф';
                            }
                        }
                        if ($field_eng == 'sc_plmb')      $field_data = ' '."{$row['plmb']} ";

                        // ПИШЕМ ЗНАЧЕНИЕ В ЯЧЕЙКУ
                        $objPHPExcel->getActiveSheet()->setCellValue("{$cell['col_abc']}".($key+$i), $field_data);
                    }
                }
                if ($rows_num > 1 && ($cell['rows_num'] == 1)) {
                    $objPHPExcel->getActiveSheet()->mergeCells($cell['col_abc'].$key.":".$cell['col_abc'].($key+$rows_num-1));
                }
            }
        }
        Write_report("Универсальный_отчет", $objPHPExcel, '', '', '', 0, 0);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    /* ****************************************************** */
    /*    ПИШЕМ ОТЧЕТ В ОДНУ ИЗ СТАНДАРТНЫХ ФОРМ              */
    /* ****************************************************** */

    /* ----------   Количество найденных абонентов   ------------- */
    if ($out_use_standart_form == 'search_result_count') {
        //echo '<br>Найдено <font color=red>'.count($res).'</font> абонентов, соответствующих параметрам выборки.<br>';
    }

    /* ----------   Лицевые счета абонентов в ссылках   ------------- */
    elseif($out_use_standart_form == 'search_result_knnom_link') {
        echo '<table width="100%" cellspacing="1" cellpadding="0" border="0" >';
        foreach ($res as $row) {
            echo '<tr>';
            echo '<td width="150">&nbsp;</td>';
            echo '<td>';
            $address = trim($row['np'].", ".$row['short_tip_street'].trim($row['street']).", ".domnumberfull($row['dom'],$row['domadd']).",".$row['kb']);
            $fio = $row['fio'];
            echo '<a href="/index.php?act=search_simple&s_text='.$row['kn'].$row['nom'].'" target="_blank">';
            echo '<b>'.$row['kn'].'-'.$row['nom'].'</b>';
            echo ' &nbsp;&nbsp;&nbsp;'.$fio.' &nbsp;&nbsp;&nbsp;<b>'.$address.'</b></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    /* ----------   Ведомость обходов (в 2 строки)   ------------- */
    elseif ($out_use_standart_form == 'f_obhod') {
        // Проверка на возможное переполнение файла Exсel
        if (count($res)*2 > 65500) {
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error', 1);
            $smarty->assign('errors', $errors);
            $smarty->display('general_errors.html');
            exit();
        }

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."Constr_obhody_2str_template.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $key2 = 11;
        $key = -1;
        $key_add = 0;
        $year_ago = dateYMD('-1 year');
        $add_col = 0;
        foreach ($res as $str) {
            // Добавочные поля: Ошибочные квитанции, Служебные отметки, Комментарии, Отключения;
            $add_oshkv = array();
            $add_slb = array();
            $add_comment = array();
            $add_otkl = array();

           // Вычисляем квитанции для текущего абонента
           // берем данные из последней квитанции и еще вычисляем среднюю по квитанциям заданного периода
           $temp_pokaz = $temp_pokkv = $temp_data = $temp_avg = $temp_kvt = 0;
           $temp_num = array();
           $kvits = GetKvit($str['kn'],$str['nom'],$str['uch']);
           if (isset($kvits[0])) {
               $kvits = array_reverse($kvits);
               for ($xx=0; $xx<count($kvits); $xx+=1){
                    $kvit_time = date(DATE_FORMAT_YMD,strtotime($kvits[$xx]['datekv']));
                    $temp_pokaz = $kvits[$xx]['pokaz'];
                    $temp_pokkv = $kvits[$xx]['pokkv'];
                    if ( $kvits[$xx]['oshkv'] == 8 || $kvits[$xx]['oshkv'] == 9) $temp_pokkv = 'Н.расч.';
                    $temp_data = dateDMY($kvits[$xx]['datekv']);
                    if ($kvit_time >= $f_c_dateb && $kvit_time <= $f_c_datee){
                        $temp_kvt += $kvits[$xx]['kvhour'];
                    }
                    if (dateYMD($kvits[$xx]['datekv']) > $year_ago && $kvits[$xx]['errkvit'] == 1) {
                        $add_oshkv[] = 'Квит.: * '.date('d.m.Y',strtotime($kvits[$xx]['datekv'])).'     По квит.: '.$kvits[$xx]['meskv'].'     '.$kvits[$xx]['pokkv'].'     '.$kvits[$xx]['sumkv'].' руб.     пеня- '.$kvits[$xx]['penkv'].' руб.     Зачтено: '.$kvits[$xx]['imeskv'].'     '.$kvits[$xx]['pokaz'].'     '.$kvits[$xx]['kvhour'].' кВтч    тариф - '.$kvits[$xx]['tarif'].' руб.';
                    }
                }
                $f_c_dateb_a = explode('.',$f_c_dateb);
                $f_c_datee_a = explode('.',$f_c_datee);
                $temp_num = round((mktime(0,0,0,$f_c_datee_a[1],$f_c_datee_a[2],$f_c_datee_a[0]) - mktime(0,0,0,$f_c_dateb_a[1],$f_c_dateb_a[2],$f_c_dateb_a[0]))/(60*60*24*30));
                if ($temp_num && $temp_kvt) $temp_avg = round(($temp_kvt/$temp_num),2);
                else  $temp_avg = 0;
           }
           else {
               $temp_pokaz = $temp_data = $temp_avg = $temp_pokkv = '';
           }

	       $dolg = 0;
           if ($with_date_obhod) {
                $pokkv=intval($temp_pokaz)*1;
                $pokob=intval($str['ob_pok'])*1;
                if ($pokob<3000) {
                   if ($pokkv>8000 and $pokob<3000) {
                      $pokob=$pokob+10000;
                   }
                   elseif ($pokkv>90000 and $pokob<5000) {
                      $pokob=$pokob+100000;
                   }
                   elseif ($pokkv>990000 and $pokob<5000) {
                      $pokob=$pokob+1000000;
                   }
                   elseif ($pokkv>9990000 and $pokob<5000) {
                      $pokob=$pokob+10000000;
                   }
                }
                elseif ($pokob>8000 and $pokkv<3000) {
                    $pokkv=$pokkv+10000;
                }
                elseif ($pokob>90000 and $pokkv<5000) {
                    $pokkv=$pokkv+100000;
                }
                elseif ($pokob>990000 and $pokkv<5000) {
                    $pokkv=$pokkv+1000000;
                }
                elseif ($pokob>9990000 and $pokkv<5000) {
                    $pokkv=$pokkv+10000000;
                }
                 elseif ($pokob>9990000 and $pokkv<5000) {
                    $pokkv=$pokkv+10000000;
                }
                $dolg = $pokob - $pokkv;
	       }

           if (($with_date_obhod && ($dolg > intval($f_c_raznica_obhod))) || !$with_date_obhod) {
            $key += 1;
            $key1 = 11 + ($key*2) + $key_add;
            $key2 = $key1 + 1;

            $objPHPExcel->getActiveSheet()->mergeCells("A$key1:A$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("A$key1",($key+1));

            $objPHPExcel->getActiveSheet()->setCellValue("B$key1",$str['kn'].'-'.$str['nom']);
            $temp = $str['vidtar'].'-'.$str['vidmt'];
            if ($str['idl']>0) $temp .= '-Льг.';
            $temp .= " (".$str['semya']."/".$str['semlg'].")";
            if (mb_strlen($temp)>19) $objPHPExcel->getActiveSheet()->getStyle("B$key2")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("C$key1",$str['fio']);
            $temp = trim($str['np'].", ".$str['short_tip_street'].trim($str['street']).", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
            if (mb_strlen($temp)>27) $objPHPExcel->getActiveSheet()->getStyle("C$key2")->getFont()->setName('Arial Narrow');
            if (mb_strlen($temp)<=36) $temp = trim($str['np'].", ".$str['street'].", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
            if (mb_strlen($temp)>36) $temp = trim($str['np'].", ".mb_substr($str['street'],0,mb_strlen($str['street'])-(mb_strlen($temp)-36)).", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
            $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("D$key1",$str['tel']);
                            if (mb_strlen($str['nomr'])>1 && $str['nomr']!='-1'){
                                $nomr = $str['nomr'];
                                $nomr = implode(',',explode(' ',trim(str_replace(',',' ',$nomr))));
                                $temp = dbFetchArray(dbQuery("SELECT simv FROM _spr_nomr WHERE id in (-1,$nomr)"));
                                $nomr = '';
                                foreach($temp as $value)    $nomr .= $value['simv'].', ';
                                $temp = mb_substr($nomr, 0, mb_strlen($nomr)-2);
                            }
                            else
                                $temp = '';
            $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("E$key1",$str['mobtel']);
            $temp = (mb_strlen($str['datadog'])>8) ? dateYMD($str['datadog']) : ' ';
            $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("F$key1",$temp_data);
            $objPHPExcel->getActiveSheet()->setCellValue("F$key2",$temp_pokaz - $temp_pokkv);

            if ($without_opl_pok == 0) {
	           $objPHPExcel->getActiveSheet()->setCellValue("G$key1",' '.$temp_pokkv);
        	   $objPHPExcel->getActiveSheet()->setCellValue("G$key2",' '.$temp_pokaz);
		       $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_font_bold);
 	        }

 	        $objPHPExcel->getActiveSheet()->setCellValue("H$key1",dateDMY($str['dateobh']));
            $objPHPExcel->getActiveSheet()->setCellValue("H$key2",' '.$str['ob_pok']);

            $objPHPExcel->getActiveSheet()->setCellValue("I$key1",$temp_avg);
            $objPHPExcel->getActiveSheet()->setCellValue("I$key2",30*$str['ob_wsr']);

            if ( intval($str['ts']) < 2000) $temp = '1ф,';
            else $temp = '3ф,';
            $temp .= $str['tipch'].',('.$str['a'].'A)';
            if (mb_strlen($temp)>16) $objPHPExcel->getActiveSheet()->getStyle("J$key1")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("J$key1",$temp);
            if (mb_strlen($str['tipps_sc'])>16) $objPHPExcel->getActiveSheet()->getStyle("J$key2")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("J$key2",$str['tipps_sc']);

            $objPHPExcel->getActiveSheet()->setCellValue("K$key1",' '.$str['zn']);
            $objPHPExcel->getActiveSheet()->setCellValue("K$key2",dateDMY($str['dateust']));

            $objPHPExcel->getActiveSheet()->setCellValue("L$key1",$str['yearvyp']);
            $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['yearprov']);

            $objPHPExcel->getActiveSheet()->setCellValue("M$key1",' '.$str['plmb']);
            $objPHPExcel->getActiveSheet()->setCellValue("M$key2",' '.$str['plmbs0']);

            $objPHPExcel->getActiveSheet()->setCellValue("N$key1",' '.$str['plmbvu1']);
            $objPHPExcel->getActiveSheet()->setCellValue("N$key2",' '.$str['plmbvu2']);

            $objPHPExcel->getActiveSheet()->mergeCells("O$key1:P$key1");
            $objPHPExcel->getActiveSheet()->setCellValue("O$key1",$str['tip_ps'].'-'.$str['n_ps']);
            $objPHPExcel->getActiveSheet()->setCellValue("O$key2",$str['tip_lep'].'-'.$str['n_lep']);
            $objPHPExcel->getActiveSheet()->setCellValue("P$key2",$str['opora']);

            $objPHPExcel->getActiveSheet()->setCellValue("Q$key1",'');
            $objPHPExcel->getActiveSheet()->setCellValue("Q$key2",'');

            $objPHPExcel->getActiveSheet()->setCellValue("R$key1",'В норме');
            $objPHPExcel->getActiveSheet()->setCellValue("R$key2",'');

	        $objPHPExcel->getActiveSheet()->getStyle("A$key1:R$key2")->applyFromArray($style_border_dot);
	        $objPHPExcel->getActiveSheet()->getStyle("A$key1:R$key1")->applyFromArray($style_border_top);
	        $objPHPExcel->getActiveSheet()->getStyle("A$key2:R$key2")->applyFromArray($style_border_bottom_dot);
	        $objPHPExcel->getActiveSheet()->getStyle("B$key1:C$key1")->applyFromArray($style_font_bold);
	        $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_font_bold);
	        $objPHPExcel->getActiveSheet()->getStyle("K$key1")->applyFromArray($style_font_bold);

            // ДОПИСЫВАЕМ добавочную информацию;
            // Ошибочные квитанции (взяты выше);
            $add_col = 0;
            if (count($add_oshkv) && $without_osh_kvit == 0){
                foreach($add_oshkv as $key_add_temp => $value){
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2+$key_add_temp+1)->setRowHeight(10);
                    $objPHPExcel->getActiveSheet()->setCellValue("C".($key2+$key_add_temp+1),$value);
                }
            }
            else {
                $add_oshkv = array();
            }
            $add_col += count($add_oshkv);

            // Символьные обозначения;
            $str['nomr'] = trim($str['nomr']);
            if ( mb_strlen(strval($str['nomr']))>0 && $str['nomr']!='-1' && $str['nomr']!='-1,'){
                $nomr = $str['nomr'];
                $nomr = implode(',',explode(' ',trim(str_replace(',',' ',$nomr))));
				$nomr = str_replace(",,", ",", $nomr);
                $temp = dbFetchArray(dbQuery("SELECT simv, nomr FROM _spr_nomr WHERE id in (-1,$nomr)"));
                $nomr = '';
                foreach($temp as $value){
                    $nomr .= $value['simv'].'-'.$value['nomr'].';  ';
                }
                $temp = mb_substr($nomr, 0, mb_strlen($nomr)-2);
                $objPHPExcel->getActiveSheet()->getRowDimension($key2+$add_col+1)->setRowHeight(10);
                $objPHPExcel->getActiveSheet()->setCellValue("C".($key2+$add_col+1),'Доп.информация: '.$temp);
                $add_col += 1;
            }
            //Тип постройки;
            if ($without_tip_postr == 0) {
                $temp = $str['postr'];
	         	if ($str['postr_dop'] != '') {
			       $temp .= ',   Доп. тип постройки: '.dbOne("SELECT postr from _tip_postr WHERE id='{$str['postr_dop']}'");
		        }
                $objPHPExcel->getActiveSheet()->getRowDimension($key2+$add_col+1)->setRowHeight(10);
                $objPHPExcel->getActiveSheet()->setCellValue("C".($key2+$add_col+1),'Тип постройки: '.$temp);
                $add_col += 1;
            }
            //Служебные отметки;
            if ($without_sl_otm == 0) {
                $add_slb = array();
                $query_slb = "SELECT * FROM ".CURRENT_RES."_obhod WHERE   knobh   = '{$str['kn']}' and nomobh  = '{$str['nom']}' and uchobh  = '{$str['uch']}' and LENGTH(ob_pok) < 4 and LENGTH(ob_pr) > 0 and dateobh > '".dateYMD('-1 year')."' and (dlt is NULL or dlt = 0)" ;
                $res_slb = dbQuery($query_slb);
                while($row_slb = dbFetchAssoc($res_slb)) {
                    $add_slb[] = array('dateobh' => dateDMY($row_slb['dateobh']),'ob_pr' => $row_slb['ob_pr']);
                }
                if (count($add_slb) && $without_sl_otm == 0) {
                    foreach($add_slb as $key_add_temp => $value) {
                        $objPHPExcel->getActiveSheet()->getRowDimension($key2+$add_col+$key_add_temp+1)->setRowHeight(10);
                        $objPHPExcel->getActiveSheet()->setCellValue("C".($key2+$add_col+$key_add_temp+1),'Служ.отм.:'.$value['dateobh'].'-'.$value['ob_pr']);
                    }
                }
                $add_col += count($add_slb);
            }
            //Комментарии;
            $add_comment = array();
            $temp = dbOne("SELECT comment from ".CURRENT_RES."_main WHERE kn='{$str['kn']}' and nom='{$str['nom']}'");
            $temp = str_replace('\r\n',' ',$temp);
            $temp = str_replace('\n',' ',$temp);
            $temp = str_replace('\r',' ',$temp);
            $temp = trim($temp);
            if (mb_strlen($temp)) {
                $add_comment[] = array('value'  => 'Комментарий: '.$temp);
                $objPHPExcel->getActiveSheet()->getRowDimension($key2+$add_col+1)->setRowHeight(10);
                $objPHPExcel->getActiveSheet()->setCellValue("C".($key2+$add_col+1),'Комментарий: '.$temp);
            }
            $add_col += count($add_comment);
            //Отключения;
            $add_otkl = array();
            $query_otkl = "SELECT _otkl.datebeg, _spr_otkl.otkl, _spr_otkl.simv, _otkl.description
                    FROM ".CURRENT_RES."_otkl as _otkl, _spr_otkl
                    WHERE _otkl.id_pr = _spr_otkl.id
                    and _otkl.kn='{$str['kn']}' and _otkl.nom='{$str['nom']}' and _otkl.dateend is NULL and _otkl.dlt=0
                    ORDER BY _otkl.datebeg desc, _otkl.id desc LIMIT 2";
            $res_otkl = dbQuery($query_otkl);
            while($row_otkl = dbFetchAssoc($res_otkl)) {
               $temp = (mb_strlen(trim($row_otkl['description']))>0) ? ' ('.$row_otkl['description'].')' : '';
               $temp_date = (date('Ymd',strtotime($row_otkl['datebeg']))>'20000101') ? dateYMD($row_otkl['datebeg']).': ' : '';
               $add_otkl[] = array('str' => $temp_date.' '.$row_otkl['simv'].'-'.$row_otkl['otkl'].$temp);
            }
            if (count($add_otkl)) {
                foreach($add_otkl as $key_add_temp => $value) {
                    $objPHPExcel->getActiveSheet()->getRowDimension($key2+$add_col+$key_add_temp+1)->setRowHeight(10);
                    $objPHPExcel->getActiveSheet()->setCellValue("C".($key2+$add_col+$key_add_temp+1),$value['str']);
                }
            }
            $key_add = $key_add + $add_col + count($add_otkl);
	        // Меняем цвета отображения для АРХИВНЫХ АБОНЕНТОВ
            if ($str['archive']==1){
                $objPHPExcel->getActiveSheet()->getStyle("A$key1:R".($key2+$add_col+count($add_otkl)))->getFont()->getColor()->setRGB($arc_color);
	    }
	 }

	 $key2 = $key2 + $add_col + count($add_otkl);
        }
	$objPHPExcel->getActiveSheet()->getStyle("A11:B$key2")->applyFromArray($style_8_c);
	$objPHPExcel->getActiveSheet()->getStyle("A11:A$key2")->applyFromArray($style_vert_center);
	$objPHPExcel->getActiveSheet()->getStyle("C11:D$key2")->applyFromArray($style_8_l);
 	$objPHPExcel->getActiveSheet()->getStyle("E11:I$key2")->applyFromArray($style_8_r);
	$objPHPExcel->getActiveSheet()->getStyle("J11:N$key2")->applyFromArray($style_8_l);
	$objPHPExcel->getActiveSheet()->getStyle("O11:P$key2")->applyFromArray($style_8_c);
	$objPHPExcel->getActiveSheet()->getStyle("R11:R$key2")->applyFromArray($style_a_9_c);
        $objPHPExcel->getActiveSheet()->getStyle("A11:A$key2")->applyFromArray($style_border_left_dot);
        $objPHPExcel->getActiveSheet()->getStyle("S11:S$key2")->applyFromArray($style_border_left_dot);

        // Пишем вспомогательную информацию: даты расчетов, персонал
        if($with_date_sc_ust){
            $f_c_dateb = $f_c_dateb_sc_ust;
            $f_c_datee = $f_c_datee_sc_ust;
        }
        elseif($with_date_sc_sn){
            $f_c_dateb = $f_c_dateb_sc_sn;
            $f_c_datee = $f_c_datee_sc_sn;
        }
        elseif($with_date_neplat_s){
            $f_c_dateb = $f_c_dateb_neplat_s;
            $f_c_datee = date('Y.m.d');
        }
        else{
            $f_c_dateb_a = explode('.',$f_c_dateb);
            $f_c_datee_a = explode('.',$f_c_datee);
            $f_c_dateb = date("d.m.Y",mktime(0,0,0,$f_c_dateb_a[1],$f_c_dateb_a[2],$f_c_dateb_a[0]));
            $f_c_datee = date("d.m.Y",mktime(0,0,0,$f_c_datee_a[1],$f_c_datee_a[2],$f_c_datee_a[0]));
        }

        if ($with_date_obhod ) {
		echo "<br>(из них <font color=red>".($key+1)."</font> абонентов - должников по обходам.)<br>" ;
	}

        if ($key2) $key2 = $key2 + 2;
            else $key2 = 12;
        Write_report("Ведомость_оплаты_2стр", $objPHPExcel, $f_c_dateb, $f_c_dateb, '', $key2, 0);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    /* ----------   Ведомость обходов (в 3 строки)   ------------- */
    /* ----------   Ведомость для проверки мобильного контролера    ------------- */
    elseif ($out_use_standart_form == 'f_obhod_3str' || $out_use_standart_form == 'f_obhod_contr_check') {

        // Проверка на возможное переполнение файла Exсel
        if (count($res)*3 > 65500) {
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error',1);
            $smarty->assign('errors',$errors);
            $smarty->display('general_errors.html');
            exit();
        }

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."Constr_obhody_3str_template.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $key2 = 11;
        $key = -1;
        $key_add = 0;
        $year_ago = dateYMD('-1 year');
        $add_col = 0;
        foreach ($res as $str) {
           // Добавочные поля:Ошибочные квитанции, Служебные отметки, Комментарии, Отключения;
           $add_oshkv = array();
           $add_slb = array();
           $add_comment = array();
           $add_otkl = array();

           // Вычисляем квитанции для текущего абонента
           // берем данные из последней квитанции и еще вычисляем среднюю по квитанциям заданного периода
           $temp_pokaz = $temp_pokkv = $temp_data = $temp_avg = $temp_kvt = 0;
           $temp_num = array();
           $kvits = GetKvit($str['kn'],$str['nom'],$str['uch']);
           if (isset($kvits[0])) {
               $kvits = array_reverse($kvits);
               for ($xx=0; $xx<count($kvits); $xx+=1) {
                    $kvit_time = date(DATE_FORMAT_YMD,strtotime($kvits[$xx]['datekv']));
                    $temp_pokaz = $kvits[$xx]['pokaz'];
                    $temp_pokkv = $kvits[$xx]['pokkv'];
                    if ( $kvits[$xx]['oshkv'] == 8 || $kvits[$xx]['oshkv'] == 9) $temp_pokkv = 'Н.расч.';
                    $temp_data = dateDMY($kvits[$xx]['datekv']);
                    if ($kvit_time >= $f_c_dateb && $kvit_time <= $f_c_datee) {
                        $temp_kvt += $kvits[$xx]['kvhour'];
                    }
                    if (dateYMD($kvits[$xx]['datekv'])> $year_ago && $kvits[$xx]['errkvit']==1) {
                        $add_oshkv[] = 'Квит.: * '.date('d.m.Y',strtotime($kvits[$xx]['datekv'])).'     По квит.: '.$kvits[$xx]['meskv'].'     '.$kvits[$xx]['pokkv'].'     '.$kvits[$xx]['sumkv'].' руб.     пеня- '.$kvits[$xx]['penkv'].' руб.     Зачтено: '.$kvits[$xx]['imeskv'].'     '.$kvits[$xx]['pokaz'].'     '.$kvits[$xx]['kvhour'].' кВтч    тариф - '.$kvits[$xx]['tarif'].' руб.';
                    }
               }
               $f_c_dateb_a = explode('.',$f_c_dateb);
               $f_c_datee_a = explode('.',$f_c_datee);
               $temp_num = round((mktime(0,0,0,$f_c_datee_a[1],$f_c_datee_a[2],$f_c_datee_a[0]) - mktime(0,0,0,$f_c_dateb_a[1],$f_c_dateb_a[2],$f_c_dateb_a[0]))/(60*60*24*30));
               if ($temp_num && $temp_kvt) $temp_avg = round(($temp_kvt/$temp_num),2);
               else  $temp_avg = 0;
           }
           else {
               $temp_pokaz = $temp_data = $temp_avg = $temp_pokkv = '';
           }

           $dolg = 0;
           if ($with_date_obhod) {
               $pokkv=intval($temp_pokaz)*1;
               $pokob=intval($str['ob_pok'])*1;
               if ($pokob<3000) {
                  if ($pokkv>8000 and $pokob<3000) {
                     $pokob=$pokob+10000;
                  }
                  elseif ($pokkv>90000 and $pokob<5000) {
                     $pokob=$pokob+100000;
                  }
                  elseif ($pokkv>990000 and $pokob<5000) {
                     $pokob=$pokob+1000000;
                  }
                  elseif ($pokkv>9990000 and $pokob<5000) {
                     $pokob=$pokob+10000000;
                  }
               }
               elseif ($pokob>8000 and $pokkv<3000) {
                   $pokkv=$pokkv+10000;
               }
               elseif ($pokob>90000 and $pokkv<5000) {
                   $pokkv=$pokkv+100000;
               }
               elseif ($pokob>990000 and $pokkv<5000) {
                   $pokkv=$pokkv+1000000;
               }
               elseif ($pokob>9990000 and $pokkv<5000) {
                   $pokkv=$pokkv+10000000;
               }
               $dolg = $pokob - $pokkv;
           }

           $is_in_range = true;
           //proverjaem datu, vhodit li v diapazon:
           if ($out_use_standart_form == 'f_obhod_contr_check') {
               $persona = $persona2;
               $is_in_range = false;
               $date_from = strtotime($f_c_dateb_contr_check);
               $date_to = strtotime($f_c_datee_contr_check);
               if(strtotime($str['dateobh']) >= $date_from && strtotime($str['dateobh']) <= $date_to) {
                   $is_in_range = true;
               }
               else {
                   $is_in_range = false;
               }
           }

           if ((($with_date_obhod && ($dolg > intval($f_c_raznica_obhod))) || !$with_date_obhod) && $is_in_range) {
                $key += 1;
                $key1 = 11 + ($key*3) + $key_add;
                $key2 = $key1 + 1;
                $key3 = $key1 + 2;

                $objPHPExcel->getActiveSheet()->mergeCells("A$key1:A$key3");
                $objPHPExcel->getActiveSheet()->setCellValue("A$key1",($key+1));

                $objPHPExcel->getActiveSheet()->setCellValue("B$key1",$str['kn'].'-'.$str['nom']);
                $objPHPExcel->getActiveSheet()->setCellValue("B$key2",' ');
                $temp = (mb_strlen($str['datadog'])>8) ? dateYMD($str['datadog']) : ' ';
                $objPHPExcel->getActiveSheet()->setCellValue("B$key3",$temp);

                $objPHPExcel->getActiveSheet()->setCellValue("C$key1",$str['fam']);
                $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['im']);
                $objPHPExcel->getActiveSheet()->setCellValue("C$key3",$str['ot']);

                $temp = trim($str['np'].", ".$str['short_tip_street'].trim($str['street']).", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
                if (mb_strlen($temp)>29) $objPHPExcel->getActiveSheet()->getStyle("D$key1")->getFont()->setName('Arial Narrow');
                if (mb_strlen($temp)<=36) $temp = trim($str['np'].", ".$str['street'].", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
                if (mb_strlen($temp)>36) $temp = trim($str['np'].", ".mb_substr($str['street'],0,mb_strlen($str['street'])-(mb_strlen($temp)-36)).", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
                $objPHPExcel->getActiveSheet()->setCellValue("D$key1",$temp);
                $temp = $str['vidtar'].'-'.$str['vidmt'];
                if ($str['idl']>0) $temp .= '-Льг.';
                $temp .= " (".$str['semya']."/".$str['semlg'].")";
                if (mb_strlen($temp)>29) $objPHPExcel->getActiveSheet()->getStyle("D$key2")->getFont()->setName('Arial Narrow');
                $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$temp);
                $temp = $str['tel'].', '.$str['mobtel'];
                $objPHPExcel->getActiveSheet()->setCellValue("D$key3",$temp);

                $objPHPExcel->getActiveSheet()->setCellValue("E$key1",' ');
                if ($without_opl_pok_3str == 0) {
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key2",' '.$temp_pokkv);
                    $objPHPExcel->getActiveSheet()->setCellValue("E$key3",$temp_pokaz - $temp_pokkv);
                }

                $objPHPExcel->getActiveSheet()->setCellValue("F$key1",$temp_data);
                if ($without_opl_pok_3str == 0) {
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key2",' '.$temp_pokaz);
                    $objPHPExcel->getActiveSheet()->setCellValue("F$key3",round($temp_avg));
                }

                $objPHPExcel->getActiveSheet()->setCellValue("G$key2",' '.$str['ob_pok']);
                $objPHPExcel->getActiveSheet()->setCellValue("G$key3",round(30*$str['ob_wsr']));

                if ($out_use_standart_form == 'f_obhod_contr_check') {
                    //izvlekaem datu predposlednego obhoda:
                    if ($persona > 0) {
                        $rdb_obh1 = dbFetchArray(dbQuery("SELECT * FROM ".CURRENT_RES."_obhod WHERE knobh = '".$str['kn']."' AND nomobh = '".$str['nom']."' ORDER BY dateobh DESC LIMIT 1 "));
                        $rdb_obh2 = dbFetchArray(dbQuery("SELECT * FROM ".CURRENT_RES."_obhod WHERE knobh = '".$str['kn']."' AND nomobh = '".$str['nom']."' AND ktotbn = '".$persona."' ORDER BY dateobh DESC LIMIT 2 "));
                        if ($rdb_obh1[0]['id'] != $rdb_obh2[0]['id']) {
                            $rdb_obh = $rdb_obh1;
                            $rdb_obh[] = $rdb_obh2[0];
                        }
                        else {
                            $rdb_obh = $rdb_obh1;
                            if (count($rdb_obh2) > 1) $rdb_obh[] = $rdb_obh2[1];
                            else                      $rdb_obh[] = $rdb_obh2[0];
                        }
                    }
                    else {
                        $rdb_obh = dbFetchArray(dbQuery("SELECT * FROM ".CURRENT_RES."_obhod WHERE knobh = '".$str['kn']."' AND nomobh = '".$str['nom']."' ORDER BY dateobh DESC LIMIT 2"));
                    }

                    if (count($rdb_obh) == 2) {
                        $objPHPExcel->getActiveSheet()->setCellValue("G$key1",date(DATE_FORMAT_SHOT,strtotime($rdb_obh[1]['dateobh'])));
                        $objPHPExcel->getActiveSheet()->setCellValue("G$key3",$rdb_obh[1]['ob_pok']);
                    }
                    else {
                        $objPHPExcel->getActiveSheet()->setCellValue("G$key1",dateDMY($str['dateobh']));
                    }
                }
                else {
                    $objPHPExcel->getActiveSheet()->setCellValue("G$key1",dateDMY($str['dateobh']));
                }

                $temp = $str['tipch'].',('.$str['a'].'A)';
                if (mb_strlen($temp)>19) $objPHPExcel->getActiveSheet()->getStyle("H$key1")->getFont()->setName('Arial Narrow');
                $objPHPExcel->getActiveSheet()->setCellValue("H$key1",$temp);
                if ( intval($str['ts']) < 2000) $temp = '1ф, '.$str['zn'];
                else $temp = '3ф, '.$str['zn'];
                if (mb_strlen($temp)>19) $objPHPExcel->getActiveSheet()->getStyle("H$key2")->getFont()->setName('Arial Narrow');
                $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$temp);
                $objPHPExcel->getActiveSheet()->setCellValue("H$key3",dateDMY($str['dateust']));

                $objPHPExcel->getActiveSheet()->setCellValue("I$key1",$str['yearprov']);
                $objPHPExcel->getActiveSheet()->setCellValue("I$key2",$str['yearvyp']);
                $objPHPExcel->getActiveSheet()->setCellValue("I$key3",' ');

                $objPHPExcel->getActiveSheet()->setCellValue("J$key1",' '.$str['plmb']);
                $objPHPExcel->getActiveSheet()->setCellValue("J$key2",' '.$str['plmbs0']);
                if (mb_strlen($str['tipps_sc'])>20) $objPHPExcel->getActiveSheet()->getStyle("J$key3")->getFont()->setName('Arial Narrow');
                $objPHPExcel->getActiveSheet()->setCellValue("J$key3",$str['tipps_sc']);

                $objPHPExcel->getActiveSheet()->setCellValue("K$key1",' '.$str['plmbvu1']);
                $objPHPExcel->getActiveSheet()->setCellValue("K$key2",' '.$str['plmbvu2']);

                $objPHPExcel->getActiveSheet()->setCellValue("L$key1",$str['tip_ps'].'-'.$str['n_ps']);
                $objPHPExcel->getActiveSheet()->setCellValue("L$key2",$str['tip_lep'].'-'.$str['n_lep']);
                $objPHPExcel->getActiveSheet()->setCellValue("L$key3",$str['opora']);

                if ($out_use_standart_form == 'f_obhod_contr_check') {
                    if (!$is_in_range) {
                        $objPHPExcel->getActiveSheet()->setCellValue("M$key1",' ');
                    }
                    else {
                        $objPHPExcel->getActiveSheet()->setCellValue("M$key1",dateDMY($str['dateobh']));
                        $objPHPExcel->getActiveSheet()->setCellValue("M$key3",$str['ob_pok']);
                    }
                }
                else {
                    $objPHPExcel->getActiveSheet()->setCellValue("M$key1",' ');
                }
                $objPHPExcel->getActiveSheet()->setCellValue("M$key2",'-----------------');
                $objPHPExcel->getActiveSheet()->setCellValue("M$key3",' ');

                $objPHPExcel->getActiveSheet()->setCellValue("N$key1",'В норме');
                $objPHPExcel->getActiveSheet()->getStyle("N$key1")->applyFromArray($style_border_bottom_dot);
                $objPHPExcel->getActiveSheet()->setCellValue("N$key2",' ');
                $objPHPExcel->getActiveSheet()->setCellValue("N$key3",' ');

                $objPHPExcel->getActiveSheet()->getStyle("A$key1:N$key1")->applyFromArray($style_border_top);
                $objPHPExcel->getActiveSheet()->getStyle("A$key3:N$key3")->applyFromArray($style_border_bottom_dot);
                $objPHPExcel->getActiveSheet()->getStyle("B$key1:D$key1")->applyFromArray($style_font_bold);
                $objPHPExcel->getActiveSheet()->getStyle("F$key2")->applyFromArray($style_font_bold);
                $objPHPExcel->getActiveSheet()->getStyle("H$key2")->applyFromArray($style_font_bold);
                $objPHPExcel->getActiveSheet()->getStyle("F$key1:F$key3")->applyFromArray($style_border_left_dot);
                $objPHPExcel->getActiveSheet()->getStyle("G$key1:G$key3")->applyFromArray($style_border_left_dot);
                $objPHPExcel->getActiveSheet()->getStyle("G$key1:G$key3")->applyFromArray($style_border_right_dot);

                // ДОПИСЫВАЕМ добавочную информацию;
                // Ошибочные квитанции (взяты выше);
                $add_col = 0;
                if (count($add_oshkv) && $without_osh_kvit_3str == 0) {
                    foreach($add_oshkv as $key_add_temp => $value) {
                        $objPHPExcel->getActiveSheet()->getRowDimension($key3+$key_add_temp+1)->setRowHeight(10);
                        $objPHPExcel->getActiveSheet()->setCellValue("C".($key3+$key_add_temp+1),$value);
                    }
                }
                else {
                    $add_oshkv = array();
                }
                $add_col += count($add_oshkv);

                // Символьные обозначения;
                $str['nomr'] = trim($str['nomr']);
                if ( mb_strlen(strval($str['nomr']))>0 && $str['nomr']!='-1' && $str['nomr']!='-1,') {
                    $nomr = $str['nomr'];
                    $nomr = implode(',',explode(' ',trim(str_replace(',',' ',$nomr))));
                    $nomr = str_replace(",,", ",", $nomr);
                    $temp = dbFetchArray(dbQuery("SELECT simv, nomr FROM _spr_nomr WHERE id in (-1,$nomr)"));
                    $nomr = '';
                    foreach($temp as $value) {
                        $nomr .= $value['simv'].'-'.$value['nomr'].';  ';
                    }
                    $temp = mb_substr($nomr, 0, mb_strlen($nomr)-2);
                    $objPHPExcel->getActiveSheet()->getRowDimension($key3+$add_col+1)->setRowHeight(10);
                    $objPHPExcel->getActiveSheet()->setCellValue("C".($key3+$add_col+1),'Доп.информация: '.$temp);
                    $add_col += 1;
                }
                //Тип постройки;
                if ($without_tip_postr_3str == 0) {
                    $temp = $str['postr'];
                    if ($str['postr_dop'] != '') {
                        $temp .= ',   Доп. тип постройки: '.dbOne("SELECT postr from _tip_postr WHERE id='{$str['postr_dop']}'");
                    }
                    $objPHPExcel->getActiveSheet()->getRowDimension($key3+$add_col+1)->setRowHeight(10);
                    $objPHPExcel->getActiveSheet()->setCellValue("C".($key3+$add_col+1),'Тип постройки: '.$temp);
                    $add_col += 1;
                }
                //Служебные отметки;
                if ($without_sl_otm_3str == 0) {
                    $add_slb = array();
                    $query_slb = "SELECT * FROM ".CURRENT_RES."_obhod WHERE knobh = '{$str['kn']}' and nomobh = '{$str['nom']}' and uchobh = '{$str['uch']}' and LENGTH(ob_pok) < 4 and LENGTH(ob_pr) > 0 and dateobh > '".dateYMD('-1 year')."' and (dlt is NULL or dlt = 0)" ;
                    $res_slb = dbQuery($query_slb);
                    while($row_slb = dbFetchAssoc($res_slb)) {
                        $add_slb[] = array('dateobh' => dateDMY($row_slb['dateobh']),'ob_pr' => $row_slb['ob_pr']);
                    }
                    if (count($add_slb)) {
                        foreach($add_slb as $key_add_temp => $value) {
                            $objPHPExcel->getActiveSheet()->getRowDimension($key3+$add_col+$key_add_temp+1)->setRowHeight(10);
                            $objPHPExcel->getActiveSheet()->setCellValue("C".($key3+$add_col+$key_add_temp+1),'Служ.отм.:'.$value['dateobh'].'-'.$value['ob_pr']);
                        }
                    }
                    $add_col += count($add_slb);
                }
                //Комментарии;
                $add_comment = array();
                $temp = dbOne("SELECT comment from ".CURRENT_RES."_main WHERE kn='{$str['kn']}' and nom='{$str['nom']}'");
                $temp = str_replace('\r\n',' ',$temp);
                $temp = str_replace('\n',' ',$temp);
                $temp = str_replace('\r',' ',$temp);
                $temp = trim($temp);
                if (mb_strlen($temp)) {
                    $add_comment[] = array('value'  => 'Комментарий: '.$temp);
                    $objPHPExcel->getActiveSheet()->getRowDimension($key3+$add_col+1)->setRowHeight(10);
                    $objPHPExcel->getActiveSheet()->setCellValue("C".($key3+$add_col+1),'Комментарий: '.$temp);
                }
                $add_col += count($add_comment);
                //Отключения;
                $add_otkl = array();
                $query_otkl = "SELECT _otkl.datebeg, _spr_otkl.otkl, _spr_otkl.simv, _otkl.description
                    FROM ".CURRENT_RES."_otkl as _otkl, _spr_otkl
                    WHERE _otkl.id_pr = _spr_otkl.id
                    and _otkl.kn='{$str['kn']}' and _otkl.nom='{$str['nom']}' and _otkl.dateend is NULL and _otkl.dlt=0
                    ORDER BY _otkl.datebeg desc, _otkl.id desc LIMIT 2";
                $res_otkl = dbQuery($query_otkl);
                while($row_otkl = dbFetchAssoc($res_otkl)) {
                    $temp = (mb_strlen(trim($row_otkl['description']))>0)?' ('.$row_otkl['description'].')':'';
                    $temp_date = (date('Ymd',strtotime($row_otkl['datebeg']))>'20000101') ? dateDMY($row_otkl['datebeg']).': ' : '';
                    $add_otkl[] = array('str' => $temp_date.' '.$row_otkl['simv'].'-'.$row_otkl['otkl'].$temp);
                }
                if (count($add_otkl)) {
                    foreach($add_otkl as $key_add_temp => $value) {
                        $objPHPExcel->getActiveSheet()->getRowDimension($key3+$add_col+$key_add_temp+1)->setRowHeight(10);
                        $objPHPExcel->getActiveSheet()->setCellValue("C".($key3+$add_col+$key_add_temp+1),$value['str']);
                    }
                }
                $key_add = $key_add + $add_col + count($add_otkl);
                // Меняем цвета отображения для АРХИВНЫХ АБОНЕНТОВ
                if ($str['archive']==1) {
                    $objPHPExcel->getActiveSheet()->getStyle("A$key1:N".($key3+$add_col+count($add_otkl)))->getFont()->getColor()->setRGB($arc_color);
                }
            }
            $key3 = $key3 + $add_col + count($add_otkl);
        }

        $objPHPExcel->getActiveSheet()->getStyle("A11:B$key3")->applyFromArray($style_8_c);
        $objPHPExcel->getActiveSheet()->getStyle("A11:A$key3")->applyFromArray($style_vert_center);
        $objPHPExcel->getActiveSheet()->getStyle("C11:D$key3")->applyFromArray($style_8_l);
        $objPHPExcel->getActiveSheet()->getStyle("E11:G$key3")->applyFromArray($style_8_r);
        $objPHPExcel->getActiveSheet()->getStyle("H11:K$key3")->applyFromArray($style_8_l);
        $objPHPExcel->getActiveSheet()->getStyle("L11:M$key3")->applyFromArray($style_8_c);
        $objPHPExcel->getActiveSheet()->getStyle("N11:N$key3")->applyFromArray($style_a_9_c);

        // Пишем вспомогательную информацию: даты расчетов, персонал
        if($with_date_sc_ust) {
            $f_c_dateb = $f_c_dateb_sc_ust;
            $f_c_datee = $f_c_datee_sc_ust;
        }
        elseif($with_date_sc_sn) {
            $f_c_dateb = $f_c_dateb_sc_sn;
            $f_c_datee = $f_c_datee_sc_sn;
        }
        elseif($with_date_neplat_s) {
            $f_c_dateb = $f_c_dateb_neplat_s;
            $f_c_datee = date('Y.m.d');
        }
        else {
            $f_c_dateb_a = explode('.',$f_c_dateb);
            $f_c_datee_a = explode('.',$f_c_datee);
            $f_c_dateb = date("d.m.Y",mktime(0,0,0,$f_c_dateb_a[1],$f_c_dateb_a[2],$f_c_dateb_a[0]));
            $f_c_datee = date("d.m.Y",mktime(0,0,0,$f_c_datee_a[1],$f_c_datee_a[2],$f_c_datee_a[0]));
        }

        if ($with_date_obhod ) {
            echo "<br>(из них <font color=red>".($key+1)."</font> абонентов - должников по обходам.)<br>" ;
        }

        if ($key2) $key2 = $key3 + 2;
        else $key2 = 12;
        if ($out_use_standart_form == 'f_obhod_contr_check') {
            Write_report("Ведомость для проверки мобильного контролера", $objPHPExcel, $f_c_dateb, $f_c_datee, '', $key2, 0);
        }
        else {
            Write_report("Ведомость_оплаты_3стр", $objPHPExcel, $f_c_dateb, $f_c_datee, '', $key2, 0);
        }
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    /* ----------   Ведомость обходов (для мобильного контролера)   ------------- */
    elseif ($out_use_standart_form == 'f_obhod_mobi') {
        // Проверка на возможное переполнение файла Exсel

        if (count($res)*3 > 65500){
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error',1);
            $smarty->assign('errors',$errors);
            $smarty->display('general_errors.html');
            exit();
        }

        $freport1 = fopen (MOBI_DIR."1.txt", "w");

        $key2 = 11;
        $key = -1;
        $key_add = 0;
        $year_ago = dateYMD('-1 year');
        $add_col = 0;
        foreach ($res as $str){
            // Добавочные поля:Ошибочные квитанции, Служебные отметки, Комментарии, Отключения;
            $add_oshkv = array();
            $add_slb = array();
            $add_comment = array();
            $add_otkl = array();

           // Вычисляем квитанции для текущего абонента
           // берем данные из последней квитанции и еще вычисляем среднюю по квитанциям заданного периода
           $temp_pokaz = $temp_pokkv = $temp_data = $temp_avg = $temp_kvt = 0;
           $temp_num = array();
           $kvits = GetKvit($str['kn'],$str['nom'],$str['uch']);
           if (isset($kvits[0])){
               $kvits = array_reverse($kvits);
               for ($xx=0; $xx<count($kvits); $xx+=1){
                    $kvit_time = date(DATE_FORMAT_YMD,strtotime($kvits[$xx]['datekv']));
                    $temp_pokaz = $kvits[$xx]['pokaz'];
                    $temp_pokkv = $kvits[$xx]['pokkv'];
                    if ( $kvits[$xx]['oshkv'] == 8 || $kvits[$xx]['oshkv'] == 9) $temp_pokkv = 'Н.расч.';
                    $temp_data = dateDMY($kvits[$xx]['datekv']);
                    if ($kvit_time >= $f_c_dateb && $kvit_time <= $f_c_datee){
                        $temp_kvt += $kvits[$xx]['kvhour'];
                    }
                    if (dateYMD($kvits[$xx]['datekv']) > $year_ago && $kvits[$xx]['errkvit']==1){
                        $add_oshkv[] = 'Квит.: * '.date('d.m.Y',strtotime($kvits[$xx]['datekv'])).'     По квит.: '.$kvits[$xx]['meskv'].'     '.$kvits[$xx]['pokkv'].'     '.$kvits[$xx]['sumkv'].' руб.     пеня- '.$kvits[$xx]['penkv'].' руб.     Зачтено: '.$kvits[$xx]['imeskv'].'     '.$kvits[$xx]['pokaz'].'     '.$kvits[$xx]['kvhour'].' кВт     тариф - '.$kvits[$xx]['tarif'].' руб.';
                    }
                }
                $f_c_dateb_a = explode('.',$f_c_dateb);
                $f_c_datee_a = explode('.',$f_c_datee);
                $temp_num = round((mktime(0,0,0,$f_c_datee_a[1],$f_c_datee_a[2],$f_c_datee_a[0]) - mktime(0,0,0,$f_c_dateb_a[1],$f_c_dateb_a[2],$f_c_dateb_a[0]))/(60*60*24));// (60*60*24*30)); // изменил это 12.06.2018
                if ($temp_num && $temp_kvt) $temp_avg = round(($temp_kvt/$temp_num),2);
                else  $temp_avg = 0;
           }
           else {
               $temp_pokaz = $temp_data = $temp_avg = $temp_pokkv = '';
           }

	   $dolg = 0;
           if ($with_date_obhod) {
                $pokkv=intval($temp_pokaz)*1;
                $pokob=intval($str['ob_pok'])*1;
		$pokf1=pow(10,mb_strlen($str['ob_pok']));
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
                $dolg = $pokob - $pokkv;
	   }


           if (($with_date_obhod && ($dolg > intval($f_c_raznica_obhod))) || !$with_date_obhod) {

	    $report_str1 = '';
            $key += 1;
            $key1 = 11 + ($key*3) + $key_add;
            $key2 = $key1 + 1;
            $key3 = $key1 + 2;

//            $report_str1 .= $key+1.";";				 //Номер п.п
            $report_str1 .= $str['kn'].$str['nom'].$str['uch'].";";  //Номер абонента
            $report_str1 .= $str['fam'].";";				//Фамилия
            $report_str1 .= $str['im'].";";				 //Имя
            $report_str1 .= $str['ot'].";";				 //Отчество
            $report_str1 .= trim($str['np']).";";		 //Населенный пункт
            $report_str1 .= trim($str['tip_street']).";";//Тип улицы
            $report_str1 .= trim($str['street']).";";	 //Наименование улицы
            $report_str1 .= domnumberfull($str['dom'],$str['domadd']).";";//Номер дома
            $report_str1 .= $str['kb'].";";				 //Номер квартиры
            $report_str1 .= $str['tel'].";";			 //Телефон
            $report_str1 .= $str['mobtel'].";";			 //Моб.телефон
            if ($without_tip_postr_mobi == 0){			 //Тип постройки;
                $temp = $str['postr'];
		if ($str['postr_dop'] != '') {
			$temp .= ', Доп.тип постройки: '.dbOne("SELECT postr from _tip_postr WHERE id='{$str['postr_dop']}'");
		}
		$report_str1 .= $temp.";";
            }
	    else $report_str1 .= " ;";
                        if ($field_eng == 'ktp')     $field_data = ' '.$row['tip_ps'].'-'.$row['n_ps'];
                        if ($field_eng == 'vl')      $field_data = ' '.$row['tip_lep'].'-'.$row['n_lep'];
            $report_str1 .= $str['tip_ps'].'-'.$str['n_ps'].";";   //КТП
            $report_str1 .= $str['tip_lep'].'-'.$str['n_lep'].";"; //ВЛ
            $report_str1 .= $str['opora'].";";				//Опора
            $report_str1 .= $str['paspnum']." ;";			//Номер паспорта
            $report_str1 .= $str['paspident']." ;";			//Идентификационный номер паспорта
            $report_str1 .= $str['paspdata']." ;";			//Дата выдачи паспорта
            $report_str1 .= $str['paspkem']." ;";			//Кем выдан паспорт
            $report_str1 .= $str['comment']." ;";			//Доп.информация
            $temp = (mb_strlen($str['datadog'])>8) ? dateDMY($str['datadog']) : ' ';
            $report_str1 .= $temp.";";					    //Дата договора

            $temp = $str['vidtar'].'-'.$str['vidmt'];
            if ($str['idl']>0) $temp .= '-Льг.';
            $temp .= " (".$str['semya']."/".$str['semlg'].")";
            $report_str1 .= $temp.";";					    //Тариф
            if ($without_opl_pok_mobi == 0){
	            $report_str1 .= $temp_data.";";			    //Дата последней квитанции
	            $report_str1 .= $temp_pokaz.";";			//Показание абонента по последней квитанции
	            $report_str1 .= $temp_pokkv.";";			//Показание по последней квитанции
	            $report_str1 .= round(30*$temp_avg).";";	//Среднемесячное по оплате
	    }
	    else    $report_str1 .= " ; ; ; ;";

            $report_str1 .= dateDMY($str['dateobh']).";";   //Дата последнего обхода
            $report_str1 .= $str['ob_pok'].";";				//Показание последнего обхода
            $report_str1 .= round(30*$str['ob_wsr']).";";	//Среднемесячное по обходам
            $report_str1 .= $str['ob_pr'].";";				//Причина обхода (комментарий)

            if ( intval($str['ts']) < 2000) $temp = '1ф';
            else $temp = '3ф';
            $report_str1 .= $temp." ".$str['tipch'].";";	//Тип счетчика
            $report_str1 .= $str['maxn'].";";				//Значность счетчика
            $report_str1 .= $str['a'].";";				    //Ток счетчика
            $report_str1 .= $str['zn'].";";				    //Заводской номер
            $report_str1 .= dateDMY($str['dateust']).";";   //Дата установки
            $report_str1 .= $str['yearprov'].";";			//Год поверки
            $report_str1 .= $str['spov'].";";				//Срок поверки
            $report_str1 .= $str['yearvyp'].";";			//Год выпуска
            $report_str1 .= $str['plmb'].";";				//Пломба
            $report_str1 .= $str['plmbs0'].";";				//Пломба шины ноль
            $report_str1 .= $str['plmbvu1'].";";			//Пломба вводного устр-ва1
            $report_str1 .= $str['plmbvu2'].";";			//Пломба вводного устр-ва2
            $report_str1 .= $str['tipps_sc'].";";			//Принадлежность счетчика
	  }

	 $key3 = $key3 + $add_col + count($add_otkl);
	 fwrite($freport1,$report_str1."\r\n");

     if ($out_use_standart_form == 'f_obhod_mobi') {
        // Удаляем, если такой обход уже есть
        $q = dbQuery("delete from _obhod_mobile where tbn='".$persona."' and knnomuch= '".$str['kn'].$str['nom'].$str['uch']."' and dateget is null");
        // Добавляем новое
        $q = dbQuery("insert into _obhod_mobile (tbn, res, knnomuch, message, etap, dateform)
                      VALUES ('".$persona."', '".CURRENT_RES."', '".$str['kn'].$str['nom'].$str['uch']."', '".$report_str1."', '0', '".dateYMD()."')");
     }
    }

        // Пишем вспомогательную информацию: даты расчетов, персонал
        if ($key2) $key2 = $key3 + 2;
            else $key2 = 12;

	    fclose($freport1);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    /* ----------   Ведомость замены счетчиков   ------------- */
    elseif($out_use_standart_form =="f_sc_zamena") {
        // Проверка на возможное переполнение файла Exсel
        if (count($res)*2 > 65500) {
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error',1);
            $smarty->assign('errors',$errors);
            $smarty->display('general_errors.html');
            exit();
        }

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."Constr_zamena_sc_template.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $key2 = 11;
        $key = -1;
        foreach ($res as $str) {
            $key += 1;
            $key1 = 11 + ($key*2);
            $key2 = $key1 + 1;

            $objPHPExcel->getActiveSheet()->mergeCells("A$key1:A$key2");
            $objPHPExcel->getActiveSheet()->setCellValue("A$key1",($key+1));

            $objPHPExcel->getActiveSheet()->setCellValue("B$key1",$str['kn'].'-'.$str['nom']);
            $temp = $str['vidtar'].'-'.$str['vidmt'];
            if ($str['idl']>0) $temp .= '-Льг.';
            $temp .= " (".$str['semya']."/".$str['semlg'].")";
            if (mb_strlen($temp)>11) $objPHPExcel->getActiveSheet()->getStyle("B$key2")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("B$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("C$key1",$str['fio']);
            $temp = trim($str['np'].", ".$str['short_tip_street'].trim($str['street']).", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
            if (mb_strlen($temp)>36) $objPHPExcel->getActiveSheet()->getStyle("C$key2")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("D$key1",$str['tel']);
            $temp = (mb_strlen($str['datadog'])>8) ? dateDMY($str['datadog']) : ' ';
            $objPHPExcel->getActiveSheet()->setCellValue("D$key2",$temp);

            $temp = $str['tipch'].' ('.$str['a'].'А)';
            if (mb_strlen($temp)>17) $objPHPExcel->getActiveSheet()->getStyle("E$key1")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("E$key1",$temp);
            $temp = $str['tipps_sc'];
            if (mb_strlen($temp)>20) $objPHPExcel->getActiveSheet()->getStyle("E$key2")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("E$key2",$temp);

            $objPHPExcel->getActiveSheet()->setCellValue("F$key1"," ".$str['zn']);
            $objPHPExcel->getActiveSheet()->setCellValue("F$key2"," ".$str['plmb']);

            $objPHPExcel->getActiveSheet()->setCellValue("G$key1",$str['yearvyp']);
            $objPHPExcel->getActiveSheet()->setCellValue("G$key2",$str['yearprov']);

            $kvits = GetKvit($str['kn'],$str['nom'],$str['uch']);
            if (isset($kvits[0])){
                $temp_pokaz = " ".$kvits[0]['pokaz'];
                if ( $kvits[0]['oshkv'] == 8 || $kvits[0]['oshkv'] == 9) $temp_data = 'Н.расч.';
                    else $temp_data = dateDMY($kvits[0]['datekv']);
            }
            else {$temp_pokaz = $temp_data = '';}
            $objPHPExcel->getActiveSheet()->setCellValue("H$key1",$temp_data);
            $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$temp_pokaz);

	    $objPHPExcel->getActiveSheet()->getStyle("A$key1:O$key2")->applyFromArray($style_border_indot_outthin);
	    $objPHPExcel->getActiveSheet()->getStyle("B$key1:C$key1")->applyFromArray($style_font_bold);
	    $objPHPExcel->getActiveSheet()->getStyle("G$key2")->applyFromArray($style_font_bold);

	    if ($str['archive']==1){
                $objPHPExcel->getActiveSheet()->getStyle("A$key1:O$key2")->getFont()->getColor()->setRGB($arc_color);
        }
        }
	$objPHPExcel->getActiveSheet()->getStyle("A11:B$key2")->applyFromArray($style_8_c);
	$objPHPExcel->getActiveSheet()->getStyle("A11:A$key2")->applyFromArray($style_vert_center);
	$objPHPExcel->getActiveSheet()->getStyle("C11:E$key2")->applyFromArray($style_8_l);
	$objPHPExcel->getActiveSheet()->getStyle("F11:H$key2")->applyFromArray($style_8_r);

        if ($key2) $key2 = $key2 + 2;
            else $key2 = 12;
	$key3 = 1;
        Write_report("Замена_счетчиков", $objPHPExcel, '', '', '', $key2, $key3);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    /* ----------   Ведомость поверки счетчиков   ------------- */
    elseif($out_use_standart_form =="f_sc_poverka") {
        // Проверка на возможное переполнение файла Exсel
        if (count($res) > 65500){
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error',1);
            $smarty->assign('errors',$errors);
            $smarty->display('general_errors.html');
            exit();
        }

        for ($i=0; $i<count($res); $i++) {
            $res[$i]['sc_prosroch']  = 0;
            $res[$i]['sc_sr_god_ps'] = 0;
            for ($j=0; $j<count($res2); $j++){
                if ($with_address) {
                    if($res[$i]['np']==$res2[$j]['np'] && $res[$i]['short_tip_street']==$res2[$j]['short_tip_street'] && $res[$i]['street']==$res2[$j]['street']) {
                        $res[$i]['sc_prosroch']  = $res2[$j]['sc_prosroch'];
                        $res[$i]['sc_sr_god_ps'] = $res2[$j]['sc_sr_god_ps'];
                    }
                }
                elseif ($with_region) {
                    if($res[$i]['np']==$res2[$j]['np']) {
                        $res[$i]['street']  = '';
                        $res[$i]['sc_prosroch']  = $res2[$j]['sc_prosroch'];
                        $res[$i]['sc_sr_god_ps'] = $res2[$j]['sc_sr_god_ps'];
                    }
                }
                else {
                    if($res[$i]['kn']==$res2[$j]['kn']) {
                        $res[$i]['sc_prosroch']  = $res2[$j]['sc_prosroch'];
                        $res[$i]['sc_sr_god_ps'] = $res2[$j]['sc_sr_god_ps'];
                    }
                }
            }
        }

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."Constr_poverka_sc_template.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $key2 = 11;
        $sc_vs = 0;
        $sc_gvs = 0;
        $sc_ps = 0;
        $sc_gps = 0;
        $k01 = 0;
        $k02 = 0;
        $key = -1;
        foreach ($res as $str){
            $key += 1;
            $key2 = 11 + ($key);

            $objPHPExcel->getActiveSheet()->setCellValue("A$key2",($key+1));

            if ($with_address or $with_region) $objPHPExcel->getActiveSheet()->setCellValue("B$key2"," ".$str['kn']."...");
            else $objPHPExcel->getActiveSheet()->setCellValue("B$key2"," ".$str['kn']);

            if ($with_address)    $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['np'].", ".$str['short_tip_street'].$str['street']);
            elseif ($with_region) $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['np']);
            else                  $objPHPExcel->getActiveSheet()->setCellValue("C$key2",$str['np'].", ".$str['street']."...");

            $objPHPExcel->getActiveSheet()->setCellValue("D$key2",(integer)($str['sc_vsego']));
            $sc_vs += $str['sc_vsego'];
            if ($str['sc_vsego'] > 0) $k01++;

            $objPHPExcel->getActiveSheet()->setCellValue("E$key2",(integer)($str['sc_sr_god_vs']));
            $sc_gvs += $str['sc_sr_god_vs'];

            $objPHPExcel->getActiveSheet()->setCellValue("F$key2",(integer)($str['sc_prosroch']));
            $sc_ps += $str['sc_prosroch'];
            if ($str['sc_prosroch'] > 0) $k02++;

            $objPHPExcel->getActiveSheet()->setCellValue("G$key2",(integer)($str['sc_sr_god_ps']));
            $sc_gps += $str['sc_sr_god_ps'];

            $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$str['sc_prosroch']/$str['sc_vsego']);
        }

        $objPHPExcel->getActiveSheet()->setCellValue("D6",$for_shapka);
        $key2++;
        $objPHPExcel->getActiveSheet()->mergeCells("A$key2:B$key2");
        $objPHPExcel->getActiveSheet()->setCellValue("A$key2"," ИТОГО: ");
        $objPHPExcel->getActiveSheet()->setCellValue("C$key2"," ");
        $objPHPExcel->getActiveSheet()->setCellValue("D$key2",(integer)($sc_vs));
        $objPHPExcel->getActiveSheet()->setCellValue("E$key2",(integer)($sc_gvs/$k01));
        $objPHPExcel->getActiveSheet()->setCellValue("F$key2",(integer)($sc_ps));
        $objPHPExcel->getActiveSheet()->setCellValue("G$key2",(integer)($sc_gps/$k02));
        $objPHPExcel->getActiveSheet()->setCellValue("H$key2",$sc_ps/$sc_vs);

	$objPHPExcel->getActiveSheet()->getStyle("A11:B$key2")->applyFromArray($style_a_10_c_b);
	$objPHPExcel->getActiveSheet()->getStyle("C11:C$key2")->applyFromArray($style_a_10_l_b);
	$objPHPExcel->getActiveSheet()->getStyle("D11:H$key2")->applyFromArray($style_a_10_r_b);
	$objPHPExcel->getActiveSheet()->getStyle("A$key2:H$key2")->applyFromArray($style_font_bold);

        if ($key2) $key2 = $key2 + 2;
            else $key2 = 12;
	$key3 = 1;
        Write_report("Поверка_счетчиков", $objPHPExcel, '', '', '', $key2, $key3);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    /* ----------   Карточка учета счетчиков   ------------- */
    elseif($out_use_standart_form =="f_kartochka_ucheta") {
        // Проверка на возможное переполнение файла Exсel
        if (count($res)*2 > 65500) {
            $errors[] = "Выбрано слишком большое кол-во абонетов, что вызовет переполнение файла отчета.<br>Возможно параметры поиска заданы неверно.";
            $smarty->assign('label_error',1);
            $smarty->assign('errors',$errors);
            $smarty->display('general_errors.html');
            exit();
        }

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $objPHPExcel = $objReader->load(REPORT_TEMPLATES_DIR."Constr_kartochka_ucheta_template.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $key = 0;
        foreach ($res as $str) {
	    if ($key > 0) {
              for ($key1=1; $key1<29; $key1++) {
		$key2 = $key + $key1;
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("A$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("A$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("B$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("B$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("C$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("C$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("D$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("D$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("E$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("E$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("F$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("F$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("G$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("G$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("H$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("H$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("I$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("I$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("J$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("J$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("K$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("K$key2",$value);
		$value = convert_utf_win($objPHPExcel->getActiveSheet()->getCell("L$key1")->getValue());
		$objPHPExcel->getActiveSheet()->setCellValue("L$key2",$value);
	      }
 	      $key1 = $key + 1;
 	      $key2 = $key + 3;
	      $objPHPExcel->getActiveSheet()->getStyle("A$key1:L$key2")->applyFromArray($style_a_10_c);
 	      $key1 = $key + 4;
 	      $key2 = $key + 5;
	      $objPHPExcel->getActiveSheet()->getStyle("A$key1:L$key2")->applyFromArray($style_14_l_bold);
 	      $key1 = $key + 6;
 	      $key2 = $key + 16;
	      $objPHPExcel->getActiveSheet()->getStyle("A$key1:L$key2")->applyFromArray($style_12_l);
 	      $key1 = $key + 17;
 	      $key2 = $key + 22;
	      $objPHPExcel->getActiveSheet()->getStyle("A$key1:L$key2")->applyFromArray($style_a_10_l);
 	      $key1 = $key + 23;
 	      $key2 = $key + 29;
	      $objPHPExcel->getActiveSheet()->getStyle("A$key1:L$key2")->applyFromArray($style_12_l);

 	      $key1 = $key + 4;
	      $objPHPExcel->getActiveSheet()->getStyle("I$key1:L$key1")->applyFromArray($style_a_9_l_boutline);
 	      $key1 = $key + 8;
	      $objPHPExcel->getActiveSheet()->getStyle("C$key1:D$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("F$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("J$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 9;
	      $objPHPExcel->getActiveSheet()->getStyle("D$key1:F$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("J$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 12;
	      $objPHPExcel->getActiveSheet()->getStyle("C$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 13;
	      $objPHPExcel->getActiveSheet()->getStyle("F$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 14;
	      $objPHPExcel->getActiveSheet()->getStyle("F$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 23;
	      $objPHPExcel->getActiveSheet()->getStyle("B$key1:C$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 25;
	      $objPHPExcel->getActiveSheet()->getStyle("D$key1:F$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("H$key1:I$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("K$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 26;
	      $objPHPExcel->getActiveSheet()->getStyle("E$key1:L$key1")->applyFromArray($style_a_8_l);
 	      $key1 = $key + 27;
	      $objPHPExcel->getActiveSheet()->getStyle("D$key1:F$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("H$key1:I$key1")->applyFromArray($style_12_l_bold_u);
	      $objPHPExcel->getActiveSheet()->getStyle("K$key1:L$key1")->applyFromArray($style_12_l_bold_u);
 	      $key1 = $key + 28;
	      $objPHPExcel->getActiveSheet()->getStyle("E$key1:L$key1")->applyFromArray($style_a_8_l);
	    }

	    $key1 = $key + 8;
            $temp = $str['tipch'].' ('.$str['a'].'А)';
            if (mb_strlen($temp)>30) $objPHPExcel->getActiveSheet()->getStyle("C$key1")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("C$key1",$temp);

            if (intval($str['ts']) < 2000) $temp='1-фазный';
            else $temp='3-фазный';
            $objPHPExcel->getActiveSheet()->setCellValue("F$key1",$temp);

	    $key1 = $key + 9;
            $objPHPExcel->getActiveSheet()->setCellValue("J$key1"," ".$str['zn']);

	    $key1 = $key + 12;
            $objPHPExcel->getActiveSheet()->setCellValue("C$key1",$str['kn'].'-'.$str['nom']);
            $objPHPExcel->getActiveSheet()->setCellValue("D$key1",$str['fio']);

	    $key1 = $key + 14;
            $temp = trim($str['np'].", ".$str['short_tip_street'].trim($str['street']).", ".domnumberfull($str['dom'],$str['domadd']).",".$str['kb']);
            if (mb_strlen($temp)>80) $objPHPExcel->getActiveSheet()->getStyle("F$key1")->getFont()->setName('Arial Narrow');
            $objPHPExcel->getActiveSheet()->setCellValue("F$key1",$temp);

            $key = $key + 29;

        }

        $key2 = $key + 36;
        $key3 = 0;
        Write_report("Карточка_учета", $objPHPExcel, '', '', '', $key2, $key3);
        echo " Время формирования : ".(round(getmicrotime() - $time_start))." секунд<br>";
        exit();
    }

    else {
		echo $out_use_standart_form . "<br />";
        echo "<br>Не указана форма для вывода отчета в стандартную форму";
    }
?>