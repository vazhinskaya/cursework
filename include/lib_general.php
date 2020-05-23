<?php
require_once 'lib_db.php';

function redirect($url) {
	header('Location: ' . $url);
	session_write_close();
	exit;
}

function check_date($year, $month, $mday) {
	if ($mday == 0 || $month == 0 || $year == 0 )
		return false;

    if (($year>30 && $year<1900) || $year>2050)
        return false;

	$no_of_days = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if (is_leap_year($year) == true)
		$no_of_days[2] = 29;
	if ($mday > $no_of_days[$month])
		return false;
	return true;
}

// Загрузка настроек из БД {RES}_config в $GLOBALS["Settings"]
function load_settings() {
	global $dblink;

	$query = "select * from ".CURRENT_RES."_config";
	$res = $dblink->query($query);
	if ($res->rowCount() > 0 ) {
       	while ($info = $res->fetch(PDO::FETCH_ASSOC)) {
        		$GLOBALS["Settings"][$info["name"]] = $info["value_"];
        	}
    }
	$query = "select knaim from _res where type_res='".CURRENT_RES."' LIMIT 1";
	if ($res = dbOne($query)) {
        	$GLOBALS["Settings"]['knaim'] = $res;
	}
	$GLOBALS["smarty"]->assign("settings", $GLOBALS["Settings"]);
}

// поиск дубликата в таблице
function copy_exists($table, $data, $type='and', $type_ = '' , $id_field_name='', $id=0){
	global $dblink;

	if (mb_strlen($table)==0) return 0;
	if (count($data) == 0) return 0;
	$type = strtolower($type);
	if ($type != 'and' && $type != 'or') $type = 'and';
	if ($type_ != '%' && $type_ != '') $type_ = '%';
	if (mb_strlen(trim($id_field_name))==0) $id = 0;
	if (is_array($id)) $id = implode(',',$id);

	$query = '';
	$search_fields = "(";

	foreach ($data as $key=>$value ) {
		$value = str_replace("'",'"',$value);
        $is_date = preg_match('/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/', $value);
	if ($is_date)
            $search_fields .= " $key = '$value' $type ";
        elseif ($key == 'sumpl')
            $search_fields .= " $key = '$value' $type ";
        elseif ($key == 'dlt')
            $search_fields .= " (dlt is NULL or dlt = 0) $type ";
        else
            $search_fields .= " $key Like '$type_".$value."$type_' $type ";
	}

	$search_fields = mb_substr($search_fields,0,mb_strlen($search_fields)-mb_strlen($type)-1);
	$search_fields .= ")";

	if (mb_strlen($id_field_name)) {
		$search_fields .= " and $id_field_name not in ($id)" ;
	}
	$query = "Select * from $table WHERE $search_fields";
	$output = $dblink->query($query);
	return $output->rowCount();
}

function last_id($table, $field) {
	if (mb_strlen($table)==0 || mb_strlen($field)==0) return 0;
	$query = "SELECT $field FROM $table ORDER BY $field DESC LIMIT 1";
	$res = dbQuery($query);
	$row = dbFetchAssoc($res);
	$value = $row["$field"];
	return $value;
}

function convert_win_utf($value) {
	return iconv('cp1251', 'utf-8', $value);
}

function convert_utf_win($value) {
	return iconv( 'utf-8', 'cp1251', $value);
}

function getmicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

// Возвращает адрес абонента в каком-то странном виде
function get_address($kn, $nom) {
	$output = '';
	list($id_dom,$kb) = dbFetchRow(dbQuery("Select id_dom,kb from ".CURRENT_RES."_main where kn='$kn' AND nom='$nom' ".DLTHIDE));
	list($dom,$domadd,$id_street) = dbFetchRow(dbQuery("Select dom,domadd,id_ul from ".CURRENT_RES."_dom where id = '$id_dom'"));
	list($street,$tip_street,$short_tip_street,$id_np) = dbFetchRow(dbQuery("SELECT s.street, t.tip_street, t.short_tip_street, id_np FROM _tip_street t INNER JOIN ".CURRENT_RES."_street s ON t.id = s.id_tip_street WHERE (s.id = '$id_street')"));
	list($tip_np,$np) = dbFetchRow(dbQuery("SELECT t.tip_np, ".CURRENT_RES."_np.np FROM _tip_np t INNER JOIN ".CURRENT_RES."_np ON t.id = ".CURRENT_RES."_np.id_tip WHERE (".CURRENT_RES."_np.id = '$id_np')"));
    $kb =mb_strlen(trim($kb))>0 ? ", кв.".trim($kb) : '';
	$output = trim($np.", ".$short_tip_street.trim($street).", ".domnumberfull($dom,$domadd)."$kb");
	return $output;
}

// определяет - является ли абонент из города
function get_is_from_city($kn, $nom) {
	$output = 0;
	$query = "SELECT _tip_np.tip_np, _tip_np.is_city FROM _tip_np
	          INNER JOIN ".CURRENT_RES."_np ON _tip_np.id = ".CURRENT_RES."_np.id_tip
			  WHERE (".CURRENT_RES."_np.id = (SELECT id_np FROM _tip_street
			    INNER JOIN ".CURRENT_RES."_street ON _tip_street.id = ".CURRENT_RES."_street.id_tip_street
                  WHERE (".CURRENT_RES."_street.id = (SELECT id_ul FROM ".CURRENT_RES."_dom
                    WHERE id = (SELECT id_dom FROM ".CURRENT_RES."_main
                      WHERE kn = '$kn' AND nom = '$nom' AND dlt = 0)))))";
	$res = dbQuery($query);
	if (dbRowsCount($res)) {
		$row = dbFetchAssoc($res);
		return $row['is_city'];
	}
	else {
		return 0;
	}
}

// возвращает тип населенного пункта для абонента
function get_typenp($kn, $nom) {
	$output = 0;
	$query = "SELECT _tip_np.id FROM _tip_np
	          RIGHT JOIN ".CURRENT_RES."_np ON _tip_np.id = ".CURRENT_RES."_np.id_tip
				WHERE (".CURRENT_RES."_np.id = (SELECT id_np FROM _tip_street
				  RIGHT JOIN ".CURRENT_RES."_street ON _tip_street.id = ".CURRENT_RES."_street.id_tip_street
                    WHERE (".CURRENT_RES."_street.id = (SELECT id_ul FROM ".CURRENT_RES."_dom
                      WHERE id = (SELECT id_dom FROM ".CURRENT_RES."_main
                        WHERE kn = '$kn' AND nom = '$nom' AND dlt = 0)))))";
	$res = dbQuery($query);
	if (dbRowsCount($res)) {
		$row = dbFetchAssoc($res);
		return  $row['id'];
	}
	else {
		return 0;
	}
}

// Возвращает РЭС, к которому абонент относится
function get_abonent_res($kn, $nom) {
	$output = 0;
	$query = "SELECT id_res FROM ".CURRENT_RES."_dom
			    WHERE id = (SELECT id_dom FROM ".CURRENT_RES."_main
                  WHERE kn = '$kn' AND nom = '$nom' ".DLTHIDE." )";
	$res = dbQuery($query);
	if (dbRowsCount($res)) {
		$row = dbFetchAssoc($res);
		return  $row['id_res'];
	}
	else {
		return -1;
	}
}

// Возвращает километраж до абонента
function get_km_to_abonent($kn,$nom){
	$km = dbOne("SELECT km FROM ".CURRENT_RES."_street
			       WHERE (id = (SELECT id_ul FROM ".CURRENT_RES."_dom
                	 WHERE ".CURRENT_RES."_dom.id = (SELECT id_dom FROM ".CURRENT_RES."_main
                       WHERE kn = '$kn' AND nom = '$nom' and dlt=0)))",'int');
	if ($km) return $km;
		else return 0;
}

// возвращение имени абонента
function get_abonent_name($kn, $nom, $full=true) {
	$fio = false;
    if (mb_strlen($kn)==4 && mb_strlen($nom)==3) {
		$query = "Select fam, im, ot from ".CURRENT_RES."_main where kn='$kn' and nom='$nom' AND (dlt is NULL or dlt = 0)";
		$res = dbQuery($query);
		if (dbRowsCount($res)) {
			$row = dbFetchAssoc($res);
			if ($full) {
                $fio = array('fam'=>'','im'=>'','ot'=>'');
			    $fio['fam'] = trim( $row['fam'] );
			    $fio['im'] 	= trim( $row['im'] );
			    $fio['ot'] 	= trim( $row['ot'] );
			    return $fio;
			}
			else {
			    $f = trim( $row['fam'] );
			    $i = trim( $row['im'] );
			    $o = trim( $row['ot'] );
			    $name = $f;
			    if (mb_strlen(trim($i))>0) $name .= " ".strtoupper(trim(mb_substr($i,0,1))).".";
			    if (mb_strlen(trim($o))>0) $name .= strtoupper(trim(mb_substr($o,0,1))).".";
			    return $name;
			}
		}
		else return $fio;
	}
	else return $fio;
}

//Определение типа принадлежности абонента
function tippr_by_knnomnom($kn, $nom) {
	$tipp = '';
	$id_dom = 0;
	$id_dom = dbOne("Select id_dom from ".CURRENT_RES."_main where kn='$kn' AND nom='$nom' AND (dlt is NULL or dlt = 0)");
	if ($id_dom<1) return '';
	$id_pri = dbOne("Select id_prinadl from ".CURRENT_RES."_dom where id='$id_dom'");
	if ($id_pri < 0) return '';
	$tippr = dbOne("Select tipps from ".CURRENT_RES."_tip_prinadl where id = '$id_pri'");
	return $tippr;
}

// Вычисление коэффициента льготы
function get_lg_koef($kn, $nom, $uch) {
	$query = "Select id,idl,semya,semlg from ".CURRENT_RES."_tarhist_sem where kn='$kn' and nom='$nom' and uch='$uch' order by ddate desc";
	list($id,$idl,$semya,$semlg) = dbFetchRow(dbQuery($query));
	if ($semya < $semlg) $semya = $semlg;
	if ($idl && $semlg) {
		$query = "SELECT t.tip FROM ".CURRENT_RES."_sem_lg s
				  INNER JOIN _vidlg t ON s.idl = t.id
				  WHERE (s.idtarhist = '$id')";
		$res = dbQuery($query);
		$summ = 0;
		while ($row = $res->fetchAll()) {
			if ($row['tip'] == 2){
				$summ += 1;
			}
			elseif ($row['tip'] == 1) {
				$summ += 0.5;
			}
		}
		$temp = ($summ)/$semya;
		$temp = (1-$temp)*100;
		return round($temp);
	}
	else {
		return 100;
	}
}

// функция добивает число val нулями в начало до заданной длины col
function nulls_beg($col, $value) {
	$temp = strval($value);
	$nulls = '';
	for($i = 1; $i <= $col; ++$i){
		$nulls .= '0';
	}
	if (mb_strlen($value) >= $col) return $value;

	return mb_substr($nulls.$temp,-$col);
}

// функция возвращает сведения об абоненте, необходимые для пересчета квитанций -
// инф. о адресе, инф. о тарифе, инф. о счетчике с учетом даты для записи в поле details
function abonent_detail_info($kn, $nom, $uch, $ddate) {
    $detail_info = '';

    // 1 - фазность счетчика
    $temp = dbOne("Select ts from ".CURRENT_RES."_mainsc where kn='$kn' and nom = '$nom' and dateust <= '$ddate' and (datesn >= '$ddate' or datesn is NULL)".DLTHIDE) ;
    if ($temp >= 3000){
        $detail_info = '3';}
    else{
        $detail_info = '1';}

    // 2 - город-ПГТ-село
    $temp = get_typenp($kn,$nom);
    if ($temp == 3) $detail_info = $detail_info.'1';
    if ($temp == 5) $detail_info = $detail_info.'2';
    if ($temp == 1 || $temp == 2 || $temp == 4) $detail_info = $detail_info.'3';

    // 3 - РЭС
    $temp = get_abonent_res($kn, $nom);
    $detail_info = $detail_info.$temp;

    // 4 - Тариф
    $temp1 = dbOne("select idt from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."' and ddate <= '".$ddate."'".DLTHIDE." order by ddate desc, id desc limit 1",'int');
    $temp = $temp1;
    if ($temp == 0){  // На тот случай, если оплатили на дату раньше, чем завели карточку
         $temp = dbOne("select idt from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."'".DLTHIDE." order by ddate asc, id desc limit 1");
    }
    $detail_info = $detail_info.$temp;

    // 5 - Тариф макс., мин.
    $temp = 0;
    if ($temp1 == 0) {  // На тот случай, если оплатили на дату раньше, чем завели карточку
         $temp = dbOne("select mt from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."'".DLTHIDE." order by ddate asc, id desc limit 1");
    }
    else {
         $temp = dbOne("select mt from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."' and ddate<= '".$ddate."'".DLTHIDE." order by ddate desc, id desc limit 1",'int');
    }
    $detail_info = $detail_info.$temp;

    // 6 - Семья
    $temp = 0;
    if ($temp1 == 0) {  // На тот случай, если оплатили на дату раньше, чем завели карточку
         $temp = dbOne("select semya from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."'".DLTHIDE." order by ddate asc, id desc limit 1");
    }
    else {
         $temp = dbOne("select semya from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."' and ddate<= '".$ddate."'".DLTHIDE." order by ddate desc, id desc limit 1",'int');
    }
    $detail_info = $detail_info.$temp;

    // 7,8 - Льгота
    $temp = 0;
    if ($temp1 == 0) {  // На тот случай, если оплатили на дату раньше, чем завели карточку
         $temp = dbOne("select idl from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."'".DLTHIDE." order by ddate asc, id desc limit 1");
    }
    else {
         $temp = dbOne("select idl from ".CURRENT_RES."_tarhist_sem where kn='".$kn."' and nom='".$nom."' and uch='".$uch."' and ddate<= '".$ddate."'".DLTHIDE." order by ddate desc, id desc limit 1",'int');
    }
    $detail_info = $detail_info.$temp;

    return $detail_info;
}

// Функция читает первую строку файла в зависимости от типа файла
function read_first_str_import_pachka($f,$dir_name) {

	$type = 0;

	$type_1 = 'ЕРИП';  // ЕРИП-овский файл (6-я версия)
	$type_5 = 'other';  // все остальные файлы - их на простой просмотр и удаление
	$type_6 = 'empty';  // пустой файл любого типа

	$status = array(
		array('Не импортирован'	,0),
		array('Импортирован'	,1),
		array('Разнесен'	,2),
                array('Не используется' ,3),
		array('Неизвестный'  	,4),
                array('Неизвестный'  	,5)
	);

    $rs = isset($Settings['r_schet'])?$Settings['r_schet']:'3012125081018';

	$temp = explode('.',$f);
	if (isset($temp[0])) $file_name = $temp[0]; else $file_name = 'Имя не определено';
	if (isset($temp[1])) $filial = $temp[1];    else $filial = 'Филиал не определен';

	if ( ($fo = fopen($dir_name.$f,'r')) === false) exit();

    $first_str = fgets($fo);

    if (preg_match("[0-9]",$file_name) || $filial == '210') $type = 1;
	else $type = 5;
    if (mb_strlen(trim($first_str)) == 0) $type = 6;

    $first_str_array = array();
	$npachka = '';
	$summa_real = 0;
    switch ($type) {
        case 1:     // ЕРИП
            $n_usl = 0;
            while (!feof($fo)){
                $first_str = trim(convert_cyr_string(fgets($fo),'a','w'));
                if (preg_match_all( "/$rs/",$first_str,$out) || preg_match_all( "/#/",$first_str,$out) || mb_strlen($first_str)<10){
                    // значит это не строка квитанции а что-то другое
                }
                else{
                    $str_array = explode('^',$first_str);
                    if (isset($str_array[6]))
                        $summa_real += (float)($str_array[6]);
                    if(isset($str_array[1])){
                        if (intval($str_array[1]) != 111)
                            $n_usl = $n_usl+1;
                    }
                }
            }
            rewind($fo);
            $first_str = trim(fgets($fo));

            $first_str_array = explode('^',$first_str);
            if (isset($first_str_array[1]) && isset($first_str_array[2]) && isset($first_str_array[3]) && isset($first_str_array[4]) && isset($first_str_array[5]) && isset($first_str_array[6]) &&
                isset($first_str_array[7]) && isset($first_str_array[8]) && isset($first_str_array[9]) && isset($first_str_array[10]) && isset($first_str_array[11]) && isset($first_str_array[12]) &&
                isset($first_str_array[13]) && isset($first_str_array[14]) ) {

				if ($first_str_array[0] =='6') $type = 0; //6-я версия протокола
                $filial = $first_str_array[5];
                $data = $first_str_array[3];
                $data = date('d.m.Y',mktime(mb_substr($data,8,2),mb_substr($data,10,2),mb_substr($data,12,2),mb_substr($data,4,2),mb_substr($data,6,2),mb_substr($data,0,4)));
                $file_data = array(
                                'full_file_name'=> $f,
                                'file_name'     => $file_name,
                                'filial'        => $filial,
                                'plat_por'      => $first_str_array[9],
                                'data'          => $data,
                                'kol'           => $first_str_array[4],
                                'summa'         => round(floatval($first_str_array[12]), 2),
                                'summa_real'    => $summa_real,
                                'summa_summa'   => round(floatval($first_str_array[12])-$summa_real, 2),
                                'summa_sbor'    => round(floatval($first_str_array[12])-floatval($first_str_array[14]), 2),
                                'n_usl'         => $n_usl,
                                'status'        => 0,
                                'type'          => $type,
                                'npachka'       => ''
                );
                $temp1 = array(
                      'ppor'        => $first_str_array[9],
                      'dat_form'    => $data,
                      'sumpl'       => round(floatval($first_str_array[12]), 2),
                      'avar'        => 2,
                      'dlt'         => 0
                );
                $temp2 = array(
                      'ppor'        => $first_str_array[9],
                      'dat_form'    => $data,
                      'sumpl'       => round(floatval($first_str_array[12]), 2),
                      'avar'        => 1,
                      'dlt'         => 0
                );
                $temp3 = array(
                      'ppor'        => $first_str_array[9],
                      'dat_form'    => $data,
                      'sumpl'       => round(floatval($first_str_array[12]), 2),
                      'avar'        => 0,
                      'dlt'         => 0
                );
                if (copy_exists(CURRENT_RES."_pachka",$temp1))
                    $file_data['status'] = $status[2];
                elseif(copy_exists(CURRENT_RES."_pachka",$temp2) || copy_exists(CURRENT_RES."_pachka",$temp3)) {
                        $file_data['status'] = $status[1];
                        $file_data['npachka'] = dbOne("
                            Select npachka from ".CURRENT_RES."_pachka where
                            ppor = '$first_str_array[9]' and
                            dat_form = '$data' and
                            pcol = '{$first_str_array[4]}' and  avar<2  and
	                    (sumpl = '".(round(floatval($first_str_array[12]),2))."') or (sumpl = '".(round(floatval($first_str_array[12])/10000,2))."')
			    ".DLTHIDE);
                }
                else $file_data['status'] = $status[0];
            }
            else{
                $pachka_status = $status[4][0];
                $file_data = array(
                            'full_file_name'    => $f,
                            'file_name'         => $file_name,
                            'filial'            => $pachka_status,
                            'plat_por'          => 'Н/Д',
                            'data'              => 'Н/Д',
                            'kol'               => 'Н/Д',
                            'summa'             => 'Н/Д',
                            'summa_real'        => 'Н/Д',
                            'summa_summa'       => 'Н/Д',
                            'summa_sbor'        => 'Н/Д',
                            'n_usl'             => $n_usl,
                            'status'            => $status[4],
                            'type'              => 5,
                            'npachka'           => $pachka_status,
                );
            }
            break;

        case 5:     // Неизвестный
            $status = $status[4];
            $file_data = array(
                            'full_file_name'     => $f,
                            'file_name'     => $file_name,
                            'filial'        => 'Нет данных',
                            'plat_por'      => 'Н/Д',
                            'data'          => 'Н/Д',
                            'kol'           => 'Н/Д',
                            'summa'         => 'Н/Д',
                            'summa_real'    => 'Н/Д',
                            'summa_summa'   => 'Н/Д',
                            'summa_sbor'    => 'Н/Д',
                            'status'        => $status,
                            'type'          => 5,
                            'npachka'       => 'Нет данных'
            );
            break;

        case 6:     // Пустой
            $status = $status[5];
            $file_data = array(
                            'full_file_name'     => $f,
                            'file_name'     => $file_name,
                            'filial'        => 'Нет данных',
                            'plat_por'      => 'Н/Д',
                            'data'          => 'Н/Д',
                            'kol'           => 'Н/Д',
                            'summa'         => 'Н/Д',
                            'summa_real'    => 'Н/Д',
                            'summa_summa'   => 'Н/Д',
                            'summa_sbor'    => 'Н/Д',
                            'status'        => $status,
                            'type'          => 6,
                            'npachka'       => 'Нет данных'
                );
            break;
    }
    fclose($fo);
    return $file_data;
}

/***********************************************************************************/
/*** функция разбирает отдельную строку файла при импорте квитанций              ***/
/***********************************************************************************/
function parse_import_str($str, $type, $npachka, $pper, $numkv) {
	$errors = array();
	$data = array();

	if (isset($_COOKIE['userid']))  $personal = $_COOKIE['userid'];

	if ($type == 0) {
        // 210 - ЕРИП (6-я версия) "^" - delimiter
            //[0]:1     - номер
            //[1]:111   - Код услуги
            //[2]:0089070   - Лицевой счет
            //[3]:Омельянюк М К     - ФИО
            //[4]:Брест!ул.3-я Хлебная!6!   - Адрес
            //[5]:02.2011       - Период оплаты
            //[6]:26469.00      - Сумма оплаты
            //[7]:0.00          - Сумма пени
            //[8]:25807.00      - Перечисленная сумма
            //[9]:20110311205521    -Дата операции
            //[10]:1~100~1~5729~5829~100~0~~ - Оплаченные показания
            //[11]:20110208165425   - Дата формирования требования к оплате
            //[12]:25001670     - Учетный номер операции в центральном узле
            //[13]:175331       - Учетный номер операции в расчетном агенте
            //[14]:888080888    - Идентификатор терминала
            //[15]:MS   	- Способ авторизации
            //[16]: 		- Дополнительные сведения
            //[17]:221.25;295.00;120 - Дополнительные данные
	        //[17]^0.1094;0;0;*;28.91;261 - Дополнительные данные для многотарифных с АСКУЭ
            //[18]:     	- Индентификатор средства авторизации (номер карточки)
            //[19]:     	- Тип устройства
        $str = iconv('cp866', 'utf-8', $str);
        $str_array  = explode('^',$str);

        if (intval($str_array[1]) == 111)   $vid_plat = 1; // ЗА ЭЛЕКТРОЭНЕРГИЮ
        else $vid_plat = 2; // УСЛУГИ
        if ($vid_plat == 1) {
            $kn         = mb_substr($str_array[2],0,4);
            $nom        = mb_substr($str_array[2],4,3);
            $uch        = 1;

            $oshkv      = 1; // тип ввода - автоматический
            $all_is_true= 1;
            $npachka    = $npachka;

            $month      = mb_substr($str_array[5],0,2);
            $year       = mb_substr($str_array[5],3,4);
            $imeskv     = $year.'.'.$month.'.'.'01';
            $penya      = $str_array[7];
            $summ_gen   = $str_array[6];
            $sumper     = $str_array[8];
            $opl_sec    = mb_substr($str_array[9],12,2);
            $opl_min    = mb_substr($str_array[9],10,2);
            $opl_hour   = mb_substr($str_array[9],8,2);
            $opl_day    = mb_substr($str_array[9],6,2);
            $opl_month  = mb_substr($str_array[9],4,2);
            $opl_year   = mb_substr($str_array[9],0,4);
            $meskv      = $month.'.'.$year;
            $is_card    = 0;
            if ($str_array[15] == 'MS' || $str_array[15] == 'BC') $is_card = 1;
            //Проверка по фамилии, которая указана в квитанции с фамилией абонента [3]:Омельянюк М.К.
            $fio1 = $str_array['3'];
            if (mb_strlen($fio1)) {
                $fio2 = get_abonent_name($kn, $nom, false);
                if (trim(strtolower($fio1)) != trim(strtolower($fio2))){
                    $errors[] = "ФИО абонента (".trim($fio2).") не соответствует ФИО, указанному в квитанции (".trim($fio1).")";
                }
            }
            //Проверка адреса, который указан в квитанции, с адресом абонента [4]:Брест, ул.3-я Хлебная, д.6, кв.13
            $address1 = trim($str_array['4']);
            if (mb_strlen($address1)) {
                if (trim(strtolower($address1)) != trim(strtolower(get_address($kn, $nom))))
                        $errors[] = "Адрес абонента (".get_address($kn, $nom).") не совпадает с адресом, указанным в квитанции (".trim($address1).")";
            }
            //Разбираем показания по квитанции
            //[10]: 1~200~1~1592~1792~200~ ~~   - 5-я версия
		    //[10]: 1~125~36876~~~1~6495~~6620~125  - 6-я версия
		    //[10]: 2~1414~666110~~~1~107~~529~422~2~351~~1343~992  - 6-я версия (многотарифный)
		    //и есть ещё трёхтарифный теперь с 01.08.2019
            $substr = $str_array[10];
            $substr = explode('~',$substr);
            if (intval($substr[0]) > 1 ) {
                // Если НЕ один счетчик
	            $substr2 = $str_array[17];
 	            $substr2 = explode(';',$substr2);
			    $substr3 = explode('~',$str_array[17]);

			    $sum_logt_tmp = explode("~",$str_array[17]);
			    $sum_logt_tmp = explode(";",$sum_logt_tmp[0]);
			    $sum_lgot = intval($sum_logt_tmp[2]);

		        if ($substr2[3] == '*' 				//многотарифный с АСКУЭ
				    && intval($substr[0]) < 3) {	//(доп. 16.09.2019: проверяем, чтобы не был 3-хтарифный платёж, его обработаем ниже)
					if ($summ_gen <= $substr2[4]) {
					    $summ_kvit_1 = round($summ_gen, 2);
						$summ_kvit_2 = 0;
					 } else {
						$summ_kvit_1 = round($substr2[4], 2);
						$summ_kvit_2 = $summ_gen - $summ_kvit_1;
					 }
		        } else {			// многотарифный без АСКУЭ (или 3-хтарифный с АСКУЭ)
				    if (intval($substr[0]) == 2) {	//если только 2 счётчика, то считаем по старому
						$mt_kvt1 = $substr[9];		//422 - показ после оплаты
						$mt_tar1 = $substr2[0];		//0.3143 - для 3-х тарифного это - Общий 2 макс
						$mt_tar2 = $substr2[1];		//0
						$mt_norm = $substr2[2];		//0 - норма, по состоянию на 01.08.2019 нормы отменены
						if ($mt_norm > 0) {
							if ($mt_kvt1 <= $mt_norm) $mt_sum1 = $mt_tar1 * $mt_kvt1;
							else $mt_sum1 = $mt_tar1 * $mt_norm + $mt_tar2 * ($mt_kvt1 - $mt_norm);
						}
					    else $mt_sum1 = $mt_tar1 * $mt_kvt1;
					    $pos_pok_1 = $substr[8];  //4
					    $pos_pok_2 = $substr[13];  //10
					    $summ_kvit_1 = round($mt_sum1, 2);
					    $summ_kvit_2 = $summ_gen - $summ_kvit_1;
					}
					else if (intval($substr[0]) == 3) {	//01.08.2019: если 3 счётчика (скорее всего 3-х тарифный)
						//пример оплаты по 3-м тарифам, каждый тариф по 1 Квт оплачен:
						//1^111^0001001^Иванов И.И.^Брест, ул.Жукова, д.15, кв.56^06.2019^0.54^0.01^0.53^20190730115921^3~3~.53~~~1~8~~9~1~2~0~~1~1~3~0~~1~1^20190729103403^2561426768^2010851899^IB^MS^^0.3143;0;0~по счётчику: 1~1~0,3143~0,31 BYN~по счётчику: 2~1~0,1048~0,1 BYN~по счётчику: 3~1~0,1222~0,12 BYN^557884******9814^2

				        //считаем почти также, только вычитать будем ещё и 3юю сумму от общей в квитанции:
						$mt_kvt1 = $substr[9];		//422 - показ до оплаты
						$mt_tar1 = $substr2[0];		//0.3143 - для 3-х тарифного это - Общий 2 макс
						$mt_tar2 = $substr2[1];		//0
						$mt_norm = $substr2[2];		//0 - норма, по состоянию на 01.08.2019 нормы отменены
						if ($mt_norm > 0) {
							if ($mt_kvt1 <= $mt_norm) $mt_sum1 = $mt_tar1 * $mt_kvt1;
							else $mt_sum1 = $mt_tar1 * $mt_norm + $mt_tar2 * ($mt_kvt1 - $mt_norm);
						}
						else $mt_sum1 = $mt_tar1 * $mt_kvt1;
						$pos_pok_1 = $substr[8];  //9   - показание после оплаты счётчик 1
						$pos_pok_2 = $substr[13];  //1 - показание после оплаты счётчик 2
						$pos_pok_3 = $substr[18];
						if ($substr3[1] == '0 BYN')	{
							$summ_kvit_1 = 0;
							$summ_kvit_2 = parseFloatVal($substr3[5]);//$summ_gen - $summ_kvit_3 - $summ_kvit_1;
							$numkv      += 1;
							$summ_kvit_3 = parseFloatVal($substr3[9]);
						}
						else {
							$summ_kvit_1 = parseFloatVal($substr3[4]);
							$summ_kvit_2 = parseFloatVal($substr3[8]);//$summ_gen - $summ_kvit_3 - $summ_kvit_1;
							$summ_kvit_3 = parseFloatVal($substr3[12]);
						}

						//Если 3-х тафифный с АСКУЕ:
						if ($substr2[3] == '*' && $substr2[6] == '*') {
							$numkv = 0;
							$summ_kvit_2 = parseFloatVal($substr2[4]);//$summ_gen - $summ_kvit_3 - $summ_kvit_1;
							$summ_kvit_3 = parseFloatVal($substr2[7]);
							$summ_kvit_1 = $summ_gen - ($summ_kvit_2 + $summ_kvit_3);
						}

						//Отлавливаем 3-хставочника льготника:
						if($sum_lgot > 0) {
							$summ_kvit_1 = parseFloatVal($substr3[4]) - parseFloatVal($substr3[7]);
							$summ_kvit_2 = parseFloatVal($substr3[13]) - parseFloatVal($substr3[16]); //$summ_gen - $summ_kvit_3 - $summ_kvit_1;
							$summ_kvit_3 = parseFloatVal($substr3[22]) - parseFloatVal($substr3[25]);
						}

						//Если остался остаток, то закидываем в первую ставку:
						if ($summ_gen - ($summ_kvit_1 + $summ_kvit_2 + $summ_kvit_3) > 0) {
							$summ_kvit_1 += $summ_gen - ($summ_kvit_1 + $summ_kvit_2 + $summ_kvit_3);
							$summ_kvit_1 = round($summ_kvit_1, 2);
						}
					}
				}

				// до 01.08.2019 было так:
				if (intval($substr[0]) < 3) {
					if ( $summ_kvit_1 <= 0.01) {
						$summ_kvit_1 = 0;
						$summ_kvit_2 = $summ_gen;
					}

					if ( $summ_kvit_2 <= 0.01){
						$summ_kvit_2 = 0;
						$summ_kvit_1 = $summ_gen;
					}
				}


					/*а теперь так:
					*//*
					if ( $summ_kvit_1 <= 0.01){
                        $summ_kvit_1 = 0;
						if ($summ_kvit_3 < 0.01)
						{
							$summ_kvit_3 = 0;
							$summ_kvit_2 = $summ_gen;
						}

                    }

					if ( $summ_kvit_2 <= 0.01){
                        $summ_kvit_2 = 0;
						if ($sum_kvit_3 < 0.01)
						{
							$sum_kvit_3 = 0;
							$summ_kvit_1 = $summ_gen;
						}
                    }

					if ( $summ_kvit_3 <= 0.01){
                        $summ_kvit_3 = 0;
						if ($sum_kvit_2 < 0.01)
						{
							$sum_kvit_2 = 0;
							$summ_kvit_1 = $summ_gen;
						}
                    }*/

		        if ($summ_kvit_1 > 0) {
        		    $numkv      += 1;
		            $data[] = array(
        		        'numkv'     => $numkv,
                		'knkv'      => $kn,
		                'nomkv'     => $nom,
        		        'uchkv'     => 1,
                		'sumkv'     => $summ_kvit_1,
		                'sumper'    => round($pper * (round($summ_kvit_1 + ($penya*$summ_kvit_1)/$summ_gen,2)), 2),
		                'meskv'     => $meskv,
        		        'pokkv'     => $pos_pok_1,
                		'penkv'     => round(($penya*$summ_kvit_1)/$summ_gen, 2),
		                'npachkv'   => $npachka,
    		            'imeskv'    => $imeskv,
           				'oshkv'     => $oshkv,
		                'labelkv'   => kvit_is_neyasn($kn,$nom,1),
    		            'typekv'    => $vid_plat,
           				'datekv'    => date(DATE_FORMAT_FULL, mktime($opl_hour,$opl_min,$opl_sec,$opl_month,$opl_day,$opl_year)),
		                'tbn'       => $personal,
    		            'is_card'   => $is_card,
           				'details'   => mb_substr(trim($str),0,254)
		            );
        		}
		        if ($summ_kvit_2 > 0) {
        		    $numkv      += 1;
		            $data[] = array(
        		        'numkv'     => $numkv,
                		'knkv'      => $kn,
		                'nomkv'     => $nom,
        		        'uchkv'     => 2,
                		'sumkv'     => $summ_kvit_2,
		                'sumper'    => round($pper * (round($summ_kvit_2 + ($penya*$summ_kvit_2)/$summ_gen,2)), 2),
                		'meskv'     => $meskv,
		                'pokkv'     => $pos_pok_2,
       					'penkv'     => round(($penya*$summ_kvit_2)/$summ_gen, 2),
		                'npachkv'   => $npachka,
        		        'imeskv'    => $imeskv,
                		'oshkv'     => $oshkv,
		                'labelkv'   => kvit_is_neyasn($kn,$nom,1),
        		        'typekv'    => $vid_plat,
                		'datekv'    => date (DATE_FORMAT_FULL, mktime ($opl_hour,$opl_min,$opl_sec,$opl_month,$opl_day,$opl_year)),
		                'tbn'       => $personal,
       					'is_card'   => $is_card,
		                'details'   => mb_substr(trim($str),0,254)
        		    );
		        }
				if ($summ_kvit_3 > 0) {
		            $numkv      += 1;
        		    $data[] = array(
                		'numkv'     => $numkv,
		                'knkv'      => $kn,
    		            'nomkv'     => $nom,
           				'uchkv'     => 3,
		                'sumkv'     => $summ_kvit_3,
        		        'sumper'    => round($pper * (round($summ_kvit_3 + ($penya*$summ_kvit_3)/$summ_gen,2)), 2),
                		'meskv'     => $meskv,
		                'pokkv'     => $pos_pok_3,
    		            'penkv'     => round(($penya*$summ_kvit_3)/$summ_gen, 2),
           				'npachkv'   => $npachka,
		                'imeskv'    => $imeskv,
    		            'oshkv'     => $oshkv,
           				'labelkv'   => kvit_is_neyasn($kn,$nom,1),
		                'typekv'    => $vid_plat,
       					'datekv'    => date (DATE_FORMAT_FULL, mktime ($opl_hour,$opl_min,$opl_sec,$opl_month,$opl_day,$opl_year)),
                		'tbn'       => $personal,
		                'is_card'   => $is_card,
       					'details'   => mb_substr(trim($str),0,254)
		            );
        		}
		    }
    		else {
		        $numkv      += 1;
    		    $data[] = array(
        		    'numkv'     => $numkv,
		            'knkv'      => $kn,
       				'nomkv'     => $nom,
		            'uchkv'     => $uch,
    		        'sumkv'     => $summ_gen,
           			'sumper'    => $sumper,
		            'meskv'     => $meskv,
    		        'pokkv'     => $substr[8],
           			'penkv'     => $penya,
		            'npachkv'   => $npachka,
       		    	'imeskv'    => $imeskv,
           			'oshkv'     => $oshkv,
		            'labelkv'   => kvit_is_neyasn($kn,$nom,$uch),
    		        'typekv'    => $vid_plat,
           			'datekv'    => date(DATE_FORMAT_FULL, mktime($opl_hour,$opl_min,$opl_sec,$opl_month,$opl_day,$opl_year)),
            		'tbn'       => $personal,
		            'is_card'   => $is_card,
        		    'details'   => mb_substr(trim($str),0,254)
        		);
    		}
		}
        elseif($vid_plat == 2) {
            $numkv      += 1;
            $kn         = mb_substr($str_array[2],0,4);
            $nom        = mb_substr($str_array[2],4,3);
            $uch        = '1';

            $oshkv      = 1; // тип ввода - автоматический
            $all_is_true= 1;
            $npachka    = $npachka;

            $month      = mb_substr($str_array[5],0,2);
            $year       = mb_substr($str_array[5],3,4);
            $imeskv     = $year.'.'.$month.'.'.'01';

            $penya      = $str_array[7];
            $summ_gen   = $str_array[6];
            $sumper     = $str_array[8];
            $opl_sec    = mb_substr($str_array[9],12,2);
            $opl_min    = mb_substr($str_array[9],10,2);
            $opl_hour   = mb_substr($str_array[9],8,2);
            $opl_day    = mb_substr($str_array[9],6,2);
            $opl_month  = mb_substr($str_array[9],4,2);
            $opl_year   = mb_substr($str_array[9],0,4);
            $meskv      = $month.'.'.$year;
            $is_card    = 0;
            $str_array[15] = trim($str_array[15]);
            if ($str_array[15] == 'MS' || $str_array[15] == 'BC') $is_card = 1;
            $data[] = array(
                'numkv'     => $numkv,
                'knkv'      => $kn,
                'nomkv'     => $nom,
                'uchkv'     => $uch,
                'sumkv'     => $summ_gen,
                'sumper'    => $sumper,
                'meskv'     => '',
                'pokkv'     => '',
                'penkv'     => 0,
                'npachkv'   => $npachka,
                'imeskv'    => 'NULL',
                'oshkv'     => $oshkv,              // 0 - ручной ввод
                'labelkv'   => 0,                   // 0-ясная/1-неясная
                'typekv'    => $vid_plat,           // вид платежа
                'datekv'    => date(DATE_FORMAT_FULL, mktime ($opl_hour,$opl_min,$opl_sec,$opl_month,$opl_day,$opl_year)),
                'tbn'       => $personal,
                'is_card'   => $is_card,
                'details'   => mb_substr(trim($str),0,254)
            );
        }
    }

	foreach ($data as $data_row) {
		// Проверка на "неясность", если kn,nom не указаны либо не существуют то квитанция автоматически становится "неясной"
		if ($data_row['labelkv'] == 0) $kvit_is_neyasn = 1;
		//Проверка УЧ по таблице _mainsc
		if ($data_row['uchkv'] == '2') {
			if (!check_mainsc_knnomuch2_exist($data_row['knkv'],$data_row['nomkv'])){
				$errors[] = 'Нет такой записи в базе счетчиков (_mainsc)';
			}
		}
		if ($data_row['typekv'] == 1) {
            // Проверка Месяца за который платят (явно указан в квитанции)
		    $temp = explode('.',$data_row['meskv']);
		    $month = (isset($temp[0]))?$temp[0]:date('m', $data_row['datekv']);
		    $year  = (isset($temp[1]))?$temp[1]:date('Y', $data_row['datekv']);
			$datemeskv 	= mktime (0, 0, 0, $month, 1, $year);
			$date_minus = strtotime("-210 day");
			$date_plus  = strtotime("+40 day");
		    if ( $datemeskv < $date_minus || $datemeskv > $date_plus) {
			    $errors[] = 'Месяц квитанции ВОЗМОЖНО указан неверно';
		    }
		}
		// Проверка Даты оплаты
		$date_minus = date(DATE_FORMAT_FULL, "-40 days");
		if ($data_row['datekv'] < $date_minus) $errors[] = 'Дата оплаты ВОЗМОЖНО указана неверно';

        // Проверка Показания/ПЕНЯ/Сумма
        if ($data_row['pokkv']  <  0 && $data_row['typekv'] == 1) 	$errors[] = 'Последнее показание указано неверно';
		if ($data_row['penkv'] 	<  0) 	$errors[] = 'Пеня не указана либо указана неверно';
		if ($data_row['sumkv'] 	<  0) 	$errors[] = 'Сумма платежа не указана либо указана неверно';

		// Проверка на отключенного абонента
		if (check_switch_off($data_row['knkv'],$data_row['nomkv'])) $errors[] = 'Абонент отключен';

	    // Пишем имеющиеся ошибки в лог импортирования
        if (count($errors)){
            $module = 'kvit_import';
            // $key1 = npachka file date_formir
            // $key2 = kn nom uch summaopl
            $key1[] = 'Номер пачки: '.$npachka;
            $key1[] = 'Данные по файлу: '.implode(' ',dbFetchArray(dbQuery("Select file_name, dat_form from ".CURRENT_RES."_pachka where npachka = '$npachka'")));
            $key2[] = 'Номер квитанции: '.$data_row['numkv'].'.  Абонент: <a href='.SERVER_ROOT.'/index.php?act=search_simple&s_text='.$data_row['knkv'].$data_row['nomkv'].' target=_blank>'.$data_row['knkv'].$data_row['nomkv'].'</a>';
            $key2[] = 'Сумма платежа: '.$data_row['sumkv'];
            $value = $errors;
            write_log($module, $key1, $key2, $value);
        }
	}
    $data[-1] = $numkv;
	return $data;
}

function kvit_is_neyasn($kn, $nom, $uch=1){
    if (($uch == 2) and check_mainsc_knnomuch2_exist($kn, $nom) == 0) return 1;
    if ($uch == 1) {
        if (check_mainsc_knnomuch2_exist($kn, $nom) == 0) {
           if (dbOne("Select COUNT(*) from ".CURRENT_RES."_main where kn='$kn' and nom='$nom' ".DLTHIDE,'int') == 0){
              return 1;
           }
       	}
    }
    return 0;
}

function check_mainsc_knnomuch1_exist($kn, $nom) {
    if ($kn && $nom){
        $x = dbOne("Select COUNT(*) from ".CURRENT_RES."_mainsc where kn='$kn' and nom='$nom' and pokusc1<>'' and (pokssc1='' or pokssc1 is NULL) ".DLTHIDE,'int');
        if ($x == 1) return 1;
    }
    return 0;
}

function check_mainsc_knnomuch2_exist($kn, $nom) {
	if ($kn && $nom){
		$x = dbOne("Select COUNT(*) from ".CURRENT_RES."_mainsc where kn='$kn' and nom='$nom' and pokusc2<>'' and (pokssc2='' or pokssc2 is NULL) ".DLTHIDE,'int');
		if ($x == 1) return 1;
	}
	return 0;
}

function write_log($module, $key1, $key2, $value, $tbn='') {
	$module_array = array(
			'general',
			'kvit_import',
			'kvit_vvod'
	);
	if (!in_array($module,$module_array)) $module = $module_array[0];
	if (is_array($key1)) $key1 = implode('<br>',$key1);
	if (is_array($key2)) $key2 = implode('<br>',$key2);
	if (is_array($value)) $value = implode('<br>',$value);
	if ($tbn=='') $tbn = $GLOBALS['userid'];

	$data = array(
		'module_' 	=> $module,
		'key1_'		=> $key1,
		'key2_'		=> $key2,
		'value_'	=> $value,
		'tbn_'		=> $tbn,
		'date_'		=> date(DATE_FORMAT_FULL)
	);
	dbPerform(CURRENT_RES.'_logs',$data);
	return 1;
}

function check_switch_off($kn, $nom) {
    $id_otkl = array();
    $temp = dbQuery("SELECT id from _spr_otkl where (otkl like '%Откл%') or (otkl like '%ОТКЛ%') or (otkl like '%откл%')");
    while($row = dbFetchAssoc($temp)){
        $id_otkl[] = $row['id'];
    }
    $id_last_otkl = dbOne("Select id_pr from ".CURRENT_RES."_otkl where kn='$kn' and nom='$nom' and dateend is NULL ".DLTHIDE." order by datebeg desc, id desc");
    if (in_array($id_last_otkl, $id_otkl)) return 1;
    else return 0;
}

function switch_typekv($type_int) {
	if ($type_int) {
		switch ($type_int) {
			case '1': return 'НЕЯСНАЯ'; break;
			case '2': return 'Услуги'; break;
			case '3': return 'Непромышленные'; break;
			case '4': return 'Посторонние'; break;
			case '5': return 'По актам'; break;
			case '6': return 'Возврат ПП'; break;
			case '7': return 'Другой РЭС'; break;
		}
	}
	else {
		return 'не определён';
	}
}

/* Вычисляет сколько денег должен заплатить абонент за определенное кол-во киловат */
function kvt_from_summ($kvt, $full_tarif, $tarif, $norma) {
    if ($norma >= $kvt){
        return $kvt*$tarif ;
    }
    else{
        return (($kvt-$norma)*$full_tarif + $norma*$tarif);
    }
}

/* Приводим в порядок добавочное для номера дома */
function fix_domadd($domadd){
    $domadd = trim($domadd);
    if (mb_strlen($domadd)){
        if (is_null($domadd)) return "";
        if (is_int($domadd)){
           return $domadd;
        }
        else {
            $domadd = str_replace ( array ('\'', '"'), '', $domadd );
            $domadd = str_replace ( array ('.', ',', '\\','\\\\','-','+','--','_','=','*',' ','#',';',':','(',')'), '', $domadd );
            $domadd = strtoupper($domadd);
            $domadd = strtr($domadd,
                "ABVGDEZIJKLMNOPRSTUFHabvgdezijklmnoprstufh",
                "АБВГДЕЗИЙКЛМНОПРСТУФХАБВГДЕЗИЁКЛМНОПРСТУФХ");
            //if (mb_strlen($domadd)<1) $domadd = "";
            return $domadd;
        }
    }
    else{
        return "";
    }
}

function domnumberfull($dom, $domadd) {
    $dom = (mb_strlen(trim($dom))>0) ? 'д.'.trim($dom) : '';
    $domadd = trim($domadd);
    if (mb_strlen($domadd)==0) return $dom;
    if (intval($domadd)) return $dom."/".$domadd;
    if (mb_strlen($domadd)) return $dom.$domadd;
}

function check_dom_abc() {
    return
        dbOne("SELECT COUNT(*) FROM ".CURRENT_RES."_dom where
                dom LIKE '%А%' or
                dom LIKE '%а%' or
                dom LIKE '%A%' or
                dom LIKE '%a%' or
                dom LIKE '%Б%' or
                dom LIKE '%б%' or
                dom LIKE '%В%' or
                dom LIKE '%в%' or
                dom LIKE '%Г%' or
                dom LIKE '%г%' or
                dom LIKE '%х%' or
                dom LIKE '%Х%' or
                dom LIKE '%С%' or
                dom LIKE '%с%' ")
        ;
}

function nulldate($date) {
    if( $date == NULL || strtotime($date) < 1 )
        return '';
    else
        return $date;
}

function nullimes($date) {
    if( strtotime('01.'.$date) < 1 )
        return '';
    else
        return $date;
}

/******************************************************************************************************/
/*                     Пишем вспомогательную информацию: дата отчета, персонал и т.п.                 */
/*                                      Пишем отчет на диск                                           */
/******************************************************************************************************/
function Write_report($reportname, $objPHPExcel, $date_beg, $date_end, $dop_inf, $key2, $key3, $key4=true, $xls='Xls') {
global $style_a_8_l;

    $cols = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V');
    $personal_fio = '';
    $personal_dol = '';
    if (isset($_COOKIE['userid'])) {
        list($personal_fio, $personal_dol) = dbFetchRow(dbQuery("Select fio, doljn from ".CURRENT_RES."_personal where tbn LIKE '".$_COOKIE['userid']."'"));
    }
    $search = array('{date_print}','{date_report_begin}','{date_report_end}','{year_report}','{org_name}','{dop_inf}');
    $replace = array(date(DATE_FORMAT_SHOT),$date_beg,$date_end,date('Y',strtotime($date_beg)),$GLOBALS["Settings"]['org'],$dop_inf);

    foreach ($cols as $col) {
        for($i=1; $i <= 9; $i++) {
            $value = $objPHPExcel->getActiveSheet()->getCell($col.$i)->getValue();
            $value_new = str_replace($search,$replace,$value);
            $objPHPExcel->getActiveSheet()->setCellValue($col.$i,$value_new);
        }
    }
    if ($key2>0) {
        $objPHPExcel->getActiveSheet()->getStyle("B$key2")->applyFromArray($style_a_8_l);
        $objPHPExcel->getActiveSheet()->setCellValue("B$key2", "Отчет подготовил(а): $personal_dol $personal_fio");
    }
    if ($key4) {
        if ($key3>0) {
            $objPHPExcel->getActiveSheet()->getStyle("B".($key2+2))->applyFromArray($style_a_8_l);
            $objPHPExcel->getActiveSheet()->setCellValue("B".($key2+2),"Мастер службы сбыта _____________________________ (________________________)");
        }
      	$objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, $xls);
        $report_name  = REPORT_DIR. CURRENT_RES."_".$reportname." (".date('Y-m-d H-i-s').").".strtolower($xls);
        $report_name2 = REPORT_DIR2.CURRENT_RES."_".$reportname." (".date('Y-m-d H-i-s').").".strtolower($xls);
        $objWriter->save($report_name);

        echo "<br>Формирование файла отчета закончено";
        echo "<br><br>";
        echo "<a href='$report_name2'> Файл отчета ($reportname) </a><br><br>";
    }
}

//вспомогательная функция преобразования чисел с запятой в числа с плавающеё точкой:
function parseFloatVal($flt) {
	return floatval(str_replace(",",".",$flt));
}

// ДЕЛЕНИЕ НА СТРАНИЦЫ
function Paginator($query) {
    global $page;
    global $pager;
    global $on_page;
    global $isPagination;
    global $limit;

    $page = $pager = $on_page = 0;
    $on_page = get_input('on_page');
    $page = get_input('page', 'int');
    if ($on_page == '') $on_page = '100';
    elseif ($on_page == 'all') {$page = 1; }
    else {$on_page = intval($on_page);}
    if ($page==0) $page = 1;

    $res = dbOne($query);
    if ($res > 10) $isPagination = 1; else $isPagination = 0;
    $pager = array();
    if ($on_page != 'all'){
        $num_all = $res;
        $num_of_page = ceil($num_all/$on_page);
        for ($i=1; $i<=$num_of_page; $i++){
                if ($i == $page) {$pager[] = 1; }
                    else {$pager[] = 0; }
        }
        $limit=" limit ".($page-1)*$on_page.",".$on_page;
    }
	else $limit = "";
    return $res;
}

$userid = GetUserTbnCookie();
load_settings();
?>