<?php
require_once('include/core.php');
require_once('include/lib_general.php');
require_once('include/lib_db.php');

global $dblink;
load_settings();

$title = 'Бытовая';
$main = '';
$errors = array();
$smarty->assign("title", $title);
$smarty->assign("main", $main);

$action = get_input('act');
$step = get_input('step');
if ($action == '') $action = "list";

if ($action == 'dom') {
  $smarty->assign('title','СПРАВОЧНИКИ: Дома');
  $smarty->assign('sprav_name','Дома');
  require_once('sprav_dom.php');
}

// страница с доступными справочниками
elseif ($action == 'list') {
        $smarty->assign('title','СПРАВОЧНИКИ: Список доступных справочников.');
        $smarty->assign('sprav_name','Список доступных справочников');
        $smarty->assign('main_area', 'sprav/general_sprav.html');
}

// различные вспомогательные выборки
elseif ($action == 'enum_func') {

    // выборка регионов для справочника населенных пунктов
    if($step == 'regions_for_np') {
        $np = get_input('np','int');
        $result = dbFetchArray(dbQuery("Select * from ".CURRENT_RES."_region where id_np = '$np' order by region" ));
        echo "<select name='region'>";
        foreach($result as $row){
            echo "<option value='{$row['id']}'>";
            echo $row['region'];
            echo "</option>";
        }
        echo "</select>";
        exit();
    }

    // выборка регионов для справочника улиц
    if($step == 'regions_for_street') {
        $np = get_input('np','int');
        $street = get_input('street','int');
        $result0 = dbFetchArray(dbQuery("Select * from ".CURRENT_RES."_region where id_np = '$np' order by region" ));
        $result = dbOne("Select * from ".CURRENT_RES."_region where id = (Select id_region from ".CURRENT_RES."_street where id = '$street') order by region" );
        echo "<select name='region'>";
        foreach($result0 as $row){
            echo "<option value='{$row['id']}' ";
            if ($row['id'] == $result) echo " selected";
            echo ">".$row['region'];
            echo "</option>";
        }
        echo "</select>";
        exit();
    }

    // выборка линий для ТП
    if($step == 'lep_for_ps10') {
        $id = get_input('id','int');
        $result0 = dbFetchArray(dbQuery("SELECT DISTINCT id as id_vlkl, tip_lep, n_lep from ".CURRENT_RES."_ktp where id_ps10 = ".$id." order by n_lep"));
        echo "<select name='id_vlkl' id='id_vlkl' size='1'>";
        foreach($result0 as $row){
            echo "<option value='{$row['id_vlkl']}' ";
            if ($row['id_vlkl'] == $id) echo " selected";
            echo ">".$row['tip_lep'].'-'.$row['n_lep'];
            echo "</option>";
        }
        echo "</select>";
        exit();
    }

    // выборка ТП для РЭС
    if($step == 'ps10_for_res') {
        $id = get_input('id','int');
        $result0 = dbFetchArray(dbQuery("SELECT DISTINCT id_ps10, tip_ps10, n_ps10 from ".CURRENT_RES."_ktp order by n_ps10"));
        echo '<select name="id_ps10" id="id_ps10" size="1" onchange="lep_for_ps10(this.value);">';
        echo '<option value="0"></option>';
        foreach($result0 as $row){
            echo "<option value='{$row['id_ps10']}' ";
            echo ">".$row['tip_ps10'].'-'.$row['n_ps10'];
            echo "</option>";
        }
        echo "</select>";
        exit();
    }
}

else {
  // вид справочника
  $sprName = array('tip_np', 'tip_postr', 'tip_prinadl', 'tip_street', 'tip_res',
                'np', 'region', 'vidtar', 'vidlg', 'tips10c',
                'personal', 'plpr', 'kalkul', 'nomr', 'nomr_otkl',
                'ktp', 'diapazon', 'street', 'dom', 'config');
  // имя таблицы справочника
  $sprTableName = array('_tip_np', '_tip_postr', CURRENT_RES.'_tip_prinadl', '_tip_street', '_res',
                CURRENT_RES.'_np', CURRENT_RES.'_region', '_vidtar', '_vidlg', '_tip_sc',
                CURRENT_RES.'_personal', '_plpr', '_kalkul', '_spr_nomr', '_spr_otkl',
                CURRENT_RES.'_ktp', '_diapazon_knnom', CURRENT_RES.'_street', CURRENT_RES.'_dom', CURRENT_RES.'_config');
  // русское наименование справочника
  $sprNameRus = array('Типы населенных пунктов', 'Типы построек', 'Принадлежность счетчиков/домов', 'Типы улиц', 'Типы РЭС-ов',
   'Населенные пункты', 'Регионы', 'Тарифы', 'Виды льгот', 'Типы счетчиков',
   'Сотрудники', 'Приемщики платежей', 'Калькуляции', 'Буквенные обозначения: Общее', 'Буквенные обозначения: Отключения',
   'КТП', 'Диапазон номеров для абонентов по РЭСам', 'Улицы', 'Дома', 'Персональные настройки РЭС');
  // количество столбцов для вывода (включая "редактировать" и "удалить")
  $colspan = array(3,3,5,4,3, 6,4,8,6,11, 7,5,9,4,4, 4,5,3,3,3);
  // запрос для проверки возможности удаления записи из справочника
  $sprQueryCheck =  array("select * from ".CURRENT_RES."_np where id_tip=",
            "select * from ".CURRENT_RES."_dom where id_postr=",
            "select * from ".CURRENT_RES."_dom where id_prinadl=",
            "select * from ".CURRENT_RES."_street where id_tip_street=",
            "select * from ".CURRENT_RES."_dom where id_res=",
            "select * from ".CURRENT_RES."_street where id_np=",
            "select * from ".CURRENT_RES."_street where id_region=",
            "select * from ".CURRENT_RES."_tarhist_sem where idt=",
            "",
            "",
            "",
            "select * from ".CURRENT_RES."_pachka where kod=",
            "select * from ".CURRENT_RES."_kvitxvp a, _kalkul b where a.typekv='2' and a.meskv=b.number and b.id=",
            "select * from ".CURRENT_RES."_main where nomr LIKE '$id,%' or nomr LIKE '%,$id,%'",
            "select * from ".CURRENT_RES."_otkl where id_pr=",
            "select * from ".CURRENT_RES."_dom where id_ktp=",
            "",
            "select * from ".CURRENT_RES."_dom where id_ul="
  );
  // наименования полей справочника
  $sprField= array(array('', 'Название'),
            array('', 'Название'),
            array('', 'Название', 'Для счетчиков', 'Для домов'),
            array('', 'Название', 'Сокращенное название'),
            array('', 'Название'),
            array('', 'Название', 'Тип населенного пункта', 'Долгота', 'Широта'),
            array('', 'Название', 'Населенный пункт'),
            array('', 'Название', 'Число тарифов', 'Многотарифность', 'Дата начала действия', 'Дата окончания действия', 'Тип'),
            array('', 'Название', 'Дата начала действия', 'Дата окончания действия', 'Тип'),
            array('', 'Наименование', 'Изготовитель', 'Напряжение', 'Ток', 'Кол-во оборотов', 'Число тарифов', 'Кол-во разрядов', 'Срок поверки', 'Класс точности'),
            array('', 'Должность', 'Табельный номер', 'ФИО', 'Категория', 'Уволен'),
            array('', 'Название', 'Код банка', 'Комис.сбор'),
            array('', 'Номер', 'Название', 'Сумма за работу (руб.)', 'Время (мин.)', 'НДС', 'Ездки'),
            array('', 'Символьное обозначение', 'Название'),
            array('', 'Символьное обозначение', 'Название'),
            array('', 'Тип ПС', 'Номер ПС' , 'Тип ВЛ(КЛ)', 'Номер ВЛ(КЛ)' ),
            array('', 'РЭС', 'Начало диапазона', 'Конец диапазона')
  );
  // выравнивание полей справочника
  $sprAlign= array(array('', 'left'),
            array('', 'left'),
            array('', 'left', 'center', 'center'),
            array('', 'left', 'left'),
            array('', 'left'),
            array('', 'left', 'left', 'center', 'center'),
            array('', 'left', 'left'),
            array('', 'left', 'center', 'left', 'center', 'center', 'center'),
            array('', 'left', 'center', 'center', 'center'),
            array('', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left', 'left'),
            array('', 'left', 'center', 'left', 'center', 'center'),
            array('', 'left', 'left', 'left'),
            array('', 'center', 'left', 'right', 'center', 'center', 'center'),
            array('', 'center', 'left'),
            array('', 'center', 'left'),
            array('', 'left', 'left', 'left', 'left'),
            array('', 'center', 'center', 'center')
  );
  // ширины полей справочника
  $sprWidth= array(array('', '80'),
            array('', '80'),
            array('', '50', '15', '15'),
            array('', '60', '20'),
            array('', '80'),
            array('', '25', '25', '15', '15'),
            array('', '50', '30'),
            array('', '25', '5', '25', '10', '10', '5'),
            array('', '40', '15', '15', '10'),
            array('', '25', '20', '5', '5', '5', '5', '5', '5', '5'),
            array('', '20', '10', '20', '10', '10'),
            array('', '40', '25', '15'),
            array('', '8', '40', '8', '8', '8', '8'),
            array('', '15', '65'),
            array('', '15', '65'),
            array('', '20', '20', '20', '20'),
            array('', '40', '20', '20')
  );
  // checkbox либо ширина поля для редактирования
  $sprType = array(array('', '16'),
            array('', '32'),
            array('', '64', 'checkbox', 'checkbox'),
            array('', '16', '10'),
            array('', '20'),
            array('', '40', '16', '10', '10'),
            array('', '64', '16'),
            array('', '16', '1', '16', '10', '10', '1'),
            array('', '128', '10', '10', '1'),
            array('', '50', '50', '10', '8', '12', '1', '1', '1', '3'),
            array('', '64', '4', '32', '1', 'checkbox'),
            array('', '32', '20', '5'),
            array('', '5', '255', '10', '3', 'checkbox', '1'),
            array('', '1', '50'),
            array('', '1', '50'),
            array('', '3', '3', '3', '3'),
            array('', '3', '4', '4')
  );

  $key = array_search($action, $sprName);
  if ($sprName[$key] == 'config' && ($step == 'list' || (mb_strlen($step) == 0))) $step = 'config_list';
  if ($sprName[$key] == 'config' && $step == 'do_edit') $step = 'config_do_edit';
  if ($sprName[$key] == 'vidtar' && $step == 'edit') $step = 'vidtar_edit';
  if ($sprName[$key] == 'street') {
      $np_list = dbFetchArray(dbQuery("select a.np, a.id, tip.tip_np from ".CURRENT_RES."_np as a, _tip_np as tip where tip.id=a.id_tip order by tip.tip_np, a.np"));
      $smarty->assign('np_list', $np_list);
      $tip_street = dbFetchArray(dbQuery("Select * from _tip_street order by tip_street"));
      $smarty->assign('tip_street', $tip_street);
  }
  if ($sprName[$key] == 'np') {
        $np_list = dbFetchArray(dbQuery("Select id, tip_np from _tip_np"));
        $smarty->assign('np_list',$np_list);
    }
  if ($sprName[$key] == 'region') {
      $np_list = dbFetchArray(dbQuery("Select id, np from ".CURRENT_RES."_np order by np"));
      $smarty->assign('np_list',$np_list);
  }

  if ($key !== false) {
	$smarty->assign('title','СПРАВОЧНИКИ: '.$sprNameRus[$key].'.');
	$smarty->assign('sprav_name', $sprNameRus[$key]);
    if (mb_strlen($step) == 0) $step = 'list';

    // удаление строки из справочника
    if ($step == 'delete') {
        $id = get_input('id');
        $rowsCount1 = 0;
        $rowsCount2 = 0;
        if ($sprQueryCheck[$key]) {
            $res  = $dblink->query($sprQueryCheck[$key].$id);
            $rowsCount1 = dbRowsCount($res);
        }
        if ($sprName[$key] == 'tip_prinadl') {
            $sql = "select * from ".CURRENT_RES."_mainsc where ps10 = '$id'";
            $res = $dblink->query($sql);
            $rowsCount2 = dbRowsCount($res);
        }
        if ($rowsCount1 > 0 || $rowsCount2 > 0) {
            $errors[] = "Невозможно удалить! Запись используется!";
            $smarty -> assign('errors', $errors);
            $smarty -> assign('label_error', 1);
            $step = 'list';
        }
        else {
            $dblink->query("delete from ".$sprTableName[$key]." where id=".$id);
            if ($sprName[$key]=='vidtar') $dblink->query("DELETE from _centar where kod = '$id'");
            $step = 'list';
        }
    }

    // добавление либо редактирование строки из справочника
    elseif ($step == 'do_add' || $step == 'do_edit') {
        $name = trim(get_input('name'));
        if ($step == 'do_edit') {
            $id = get_input('id','int');
            $id_str = 'id';
        }
        else {
            $id = '';
            $id_str = '';
        }
        if (mb_strlen($name) == 0 && $sprName[$key] <> 'street') {
            $errors[] = "Введено недопустимое значение";
        }
        else {
            switch ($sprName[$key]) {
                case 'tip_np'      : $data = array('tip_np'   => "$name");  break;
                case 'tip_postr'   : $data = array('postr'    => "$name");  break;
                case 'tip_prinadl' : $data = array('tipps10'    => "$name", 'label_sc' => get_input('field2', 'int'), 'label_dom' => get_input('field3', 'int'));  break;
                case 'tip_street'  : $data = array('tip_street'=>"$name", 'short_tip_street' => trim(get_input('field2')));  break;
                case 'tip_res'     : $data = array('res'      => "$name");  break;
                case 'np'          : $data = array('np'       => "$name", 'id_tip' => get_input('filter_tip','int'), 'lon' => trim(get_input('field3')), 'lat' => trim(get_input('field4')));  break;
                case 'region'      : $data = array('region'   => "$name", 'id_np' => get_input('field2','int'));  break;
                case 'vidtar'      : $data = array('vidtar'   => "$name", 'mt' => get_input('mt','int'), 'vidmt' => trim(get_input('vidmt')), 'dateb' => trim(get_input('dateb','dateYMD')), 'datee' => trim(get_input('datee','dateYMD')), 'tip' => get_input('field6','int'));  break;
                case 'vidlg'       : $data = array('vidlg'    => "$name", 'dateb' => trim(get_input('field2','dateYMD')), 'datee' => trim(get_input('field3','dateYMD')), 'tip' => get_input('field4','int'));  break;
                case 'tips10c'       : $data = array('tipch'    => "$name", 'izg' => $field2, 'v' => $field3, 'a' => $field4, 'kolob' => $field5, 'chtar' => $field6);  break;
                case 'personal'    : $data = array('doljn'    => "$name", 'tbn' => get_input('field2','int'), 'fio' => trim(get_input('field3')), 'kat' => get_input('field4','int'), 'uvolen' => get_input('field5','int'));  break;
                case 'plpr'        : $data = array('npr'      => "$name", 'kodp' => trim(get_input('field2')), 'schet' => trim(get_input('field3')), 'unn' => trim(get_input('field4')), 'prc' => str_replace(',','.',trim(get_input('field5'))));  break;
                case 'kalkul'      : $data = array('number'   => "$name", 'name' =>  trim(get_input('field2')), 'summa' => floatval(trim(get_input('field3'))), 'time_is' => trim(get_input('field4')), 'nds' => trim(get_input('field5','int')), 'ezd' => trim(get_input('ezd','int')));  break;
                case 'nomr'        : $data = array('simv'     => "$name", 'nomr' => trim(get_input('field2')));  break;
                case 'nomr_otkl'   : $data = array('simv'     => "$name", 'otkl' => trim(get_input('field2')));  break;
                case 'ktp'         : $data = array('tip_ps10' => "$name", 'n_ps10' => get_input('field2','int'), 'tip_lep' => get_input('field2'), 'n_lep' => get_input('field2','int') );  break;
                case 'diapazon'    : $data = array('res'      => "$name", 'd1' => trim(get_input('field2')), 'd2' => trim(get_input('field3')));  break;
                case 'street'      : $data = array('id_np'    => trim(get_input('np','int')), 'id_tip_street' => get_input('tip_street','int'), 'street' => trim(get_input('street')));  break;
                case 'dom'         : $data = true;  break;
                case 'config'      : $data = array('value_'   => "$name");  break;
            }
            if (!copy_exists($sprTableName[$key], $data, '', '', $id_str, $id)) {
                if ($step == 'do_add') {
                    $data1 = array_merge(array('id'=>last_id($sprTableName[$key],'id')+1), $data);
                    dbPerform($sprTableName[$key], $data1);
                }
                else {
                    if ($sprName[$key] == 'street') {
                        $data = array('id_np'    => trim(get_input('np','int')), 'id_tip_street' => get_input('tip_street','int'), 'street' => trim(get_input('street')), 'id_region' => get_input('region','int'), 'km' => get_input('km','int'));
                    }
                    $parametr = " id = $id ";
                    dbPerform($sprTableName[$key], $data, 'update', $parametr);
                }
                redirect(SERVER_ROOT.'/sprav.php?act='.$sprName[$key]);
            }
            else $errors[] = "Такая запись уже существует";
        }
        if (count($errors)) {
            $smarty -> assign('errors', $errors);
            $smarty -> assign('label_error', 1);
            if ($step == 'do_add') $step = 'add';
            else $step = 'edit';
        }
    }

    // редактирование строки в справочнике
    if ($step == 'edit') {
        $id = get_input('id','int');
        if ($id < 1) redirect(SERVER_ROOT.'/sprav.php?act='.$sprName[$key]);

        if ($sprName[$key]=='street') {
            $query = "SELECT s.id, s.id_np, s.km, s.street,
                tip.tip_street, tip.id as id_tip_street, np.np, tnp.tip_np, r.region, r.id as id_region
                FROM ".CURRENT_RES."_street as s
                LEFT JOIN ".CURRENT_RES."_np as np on np.id = s.id_np
                LEFT JOIN _tip_street as tip on tip.id = s.id_tip_street
                LEFT JOIN _tip_np as tnp on tnp.id = np.id_tip
                LEFT JOIN ".CURRENT_RES."_region as r on r.id = s.id_region
                where s.id = '$id' ";
            $result = dbQuery($query);
            $object = dbFetchArray($result);
            $object[0]['id'] = $id;
            $result = dbFetchArray(dbQuery("Select * from ".CURRENT_RES."_region where id_np = '{$object[0]['id_np']}' order by region" ));
            $smarty->assign('regions',$result);
            $smarty->assign('object',$object[0]);
            $smarty->assign("main_area", "sprav/street_edit.html");
        }
        else {
            $res = $dblink->query('select * from '.$sprTableName[$key].' where id='.$id);
            $object = $res->fetch(PDO::FETCH_BOTH);
            if ($sprName[$key]=='vidlg') {
                if ($object['2']) $object['2'] = dateDMY($object['dateb']);
                if ($object['3']) $object['3'] = dateDMY($object['datee']);
            }
            $smarty->assign('sprav', $sprName[$key]);
            $smarty->assign('spravTable', $sprTableName[$key]);
            $smarty->assign('spravField', $sprField[$key]);
            $smarty->assign('spravWidth', $sprWidth[$key]);
            $smarty->assign('spravAlign', $sprAlign[$key]);
            $smarty->assign('spravType', $sprType[$key]);
            $smarty->assign('colspanTable', $colspan[$key]);
            $smarty->assign('object', $object);
            $smarty->assign('main_area', 'sprav/sprav_edit.html');
        }
        $smarty->assign('step', 'edit');
    }

    // добавление строки в справочник
    elseif ($step == 'add') {
        if ($sprName[$key]=='street') {
            $object = array();
            if (isset($id_np)) $object['id_np'] = $id_np;
            else $object['id_np'] = $np_list[0]['id'];
            if (isset($id_tip_street)) $object['id_tip_street'] = $id_tip_street;
            if (isset($street)) $object['street'] = $street;
            if (isset($id_region)) $object['id_region'] = $id_region;
            if (isset($km)) $object['km'] = $km;
            $smarty->assign('object',$object);
            if ($object['id_np']) {
                $result = dbFetchArray(dbQuery("Select * from ".CURRENT_RES."_region where id_np = '{$object['id_np']}' order by region" ));
                $smarty->assign('regions',$result);
            }
            $smarty->assign("main_area", "sprav/street_edit.html");
        }
        else {
            $smarty->assign('sprav', $sprName[$key]);
            $smarty->assign('spravTable', $sprTableName[$key]);
            $smarty->assign('spravField', $sprField[$key]);
            $smarty->assign('spravWidth', $sprWidth[$key]);
            $smarty->assign('spravAlign', $sprAlign[$key]);
            $smarty->assign('spravType', $sprType[$key]);
            $smarty->assign('colspanTable', $colspan[$key]);
            $smarty->assign('object', $object);
            $smarty->assign('main_area', 'sprav/sprav_edit.html');
        }
        $smarty->assign('step','add');
    }

    // вывод всего справочника
    elseif ($step == 'list') {
        $where = $order_by = $order = '';
        $smarty->assign("main_area", "sprav/sprav_view.html");
        $order_by = trim(get_input('order_by'));
        if ($order_by) {
            $order = trim(get_input('order'));
            if ($order != 'desc') $order = 'asc';
            $orderbystr = " order by $order_by $order";
        }

        if ($sprName[$key]=='street') {
            $filter_name = $filter_tip = "";
            $smarty->assign("main_area", "sprav/street_view.html");
            $filter_name = trim(get_input('filter_name'));
            $filter_np  = trim(get_input('filter_np','int'));
            $order = trim(get_input('order'));
            if ($order != 'desc') $order = 'asc';
            $order_by = trim(get_input('order_by'));
            if ($order_by != 'np' && $order_by != 'street' && $order_by != 'region' && $order_by != 'tip_street')                $order_by = 'street';
            if ($order_by == 'np')          $order_by_new = " tnp.tip_np $order, np.np $order, tip.tip_street $order, s.street $order, r.region ";
            if ($order_by == 'tip_street')  $order_by_new = " tip.tip_street $order, s.street $order, r.region ";
            if ($order_by == 'street')      $order_by_new = " s.street $order, r.region ";
            if ($order_by == 'region')      $order_by_new = " r.region ";
            if (mb_strlen($filter_name)>0) $where .= " s.street LIKE '%$filter_name%' AND ";
            if ($filter_np > 0) $where .= " np.id = $filter_np AND ";
            $where .= " 1=1 ";
        }

        // деление на страницы
        if ($sprName[$key]=='street') {
            $count = Paginator("SELECT count(*) FROM ".CURRENT_RES."_street as s
                    LEFT JOIN ".CURRENT_RES."_np as np on np.id = s.id_np
                    LEFT JOIN _tip_street as tip on tip.id = s.id_tip_street
                    LEFT JOIN _tip_np as tnp on tnp.id = np.id_tip
                    LEFT JOIN ".CURRENT_RES."_region as r on r.id = s.id_region
                    WHERE ".$where);
        }
        elseif ($sprName[$key]=='region') {
            $count = Paginator("select count(*) from ".CURRENT_RES."_np as n, ".CURRENT_RES."_region as r where n.id=r.id_np");
        }
        elseif ($sprName[$key] == 'np') {
            $count = Paginator("select count(*) from ".CURRENT_RES."_np as a, _tip_np where _tip_np.id=a.id_tip");
        }
        else {
            $count = Paginator("select count(*) from ".$sprTableName[$key]);
        }

        if ($sprName[$key]=='street') {
            $query = "SELECT s.id, s.id_np, s.km, s.street,
                    tip.tip_street, np.np, tnp.tip_np, r.region
                    FROM ".CURRENT_RES."_street as s
                    LEFT JOIN ".CURRENT_RES."_np as np on np.id = s.id_np
                    LEFT JOIN _tip_street as tip on tip.id = s.id_tip_street
                    LEFT JOIN _tip_np as tnp on tnp.id = np.id_tip
                    LEFT JOIN ".CURRENT_RES."_region as r on r.id = s.id_region
                    WHERE $where ORDER BY ".$order_by_new.$order.$limit;
        }
        elseif ($sprName[$key] == 'region') {
            $query = "select r.id, r.region, n.np from ".CURRENT_RES."_np as n, ".CURRENT_RES."_region as r where n.id=r.id_np ".$orderbystr.$limit;
        }
        elseif ($sprName[$key] == 'np') {
            $query = "select a.id, a.np, _tip_np.tip_np, a.lat, a.lon from ".CURRENT_RES."_np as a, _tip_np where _tip_np.id=a.id_tip ".$orderbystr.$limit;
        }
        elseif ($sprName[$key] == 'vidtar') {
            $query = "select id, vidtar, mt, vidmt, date_format(dateb, '%d.%m.%Y') as dateb, date_format(datee, '%d.%m.%Y') as datee, tip from ".$sprTableName[$key].$orderbystr.$limit;
        }
        elseif ($sprName[$key] == 'vidlg') {
            $query = "select id, vidlg, date_format(dateb, '%d.%m.%Y') as dateb, date_format(datee, '%d.%m.%Y') as datee, tip from ".$sprTableName[$key].$orderbystr.$limit;
        }
        else {
            $query = "select * from ".$sprTableName[$key].$orderbystr.$limit;
        }
        $res = $dblink->query($query);
        $object = $res->fetchAll(PDO::FETCH_BOTH);
        if ($sprName[$key]<>'street' && $sprName[$key]<>'ktp') {
            $keys = array_keys($object[0]);
            if (!$order_by) $order_by = $keys[2];
        }

        $smarty->assign('order', $order);
        $smarty->assign('order_by', $order_by);
        $smarty->assign('pager', $pager);
        $smarty->assign('count_pager', count($pager));
        $smarty->assign('page', $page-1);
        $smarty->assign('on_page', $on_page);
        $smarty->assign('isPagination', $isPagination);
        $smarty->assign("count_object", dbRowsCount($res));
        $smarty->assign('sprav', $sprName[$key]);
        $smarty->assign('spravTable', $sprTableName[$key]);
        $smarty->assign('spravField', $sprField[$key]);
        $smarty->assign('spravWidth', $sprWidth[$key]);
        $smarty->assign('spravAlign', $sprAlign[$key]);
        $smarty->assign('spravType', $sprType[$key]);
        $smarty->assign('colspanTable', $colspan[$key]);
        $smarty->assign('object', $object);
    }


    //  шаги по обработке только справочника настройки РЭС
    elseif ($step == 'config_do_edit' && HaveAccess('config','edit')) {
        $query = 'select * from '.CURRENT_RES.'_config' ;
        $res = dbQuery($query);
        while($row = dbFetchAssoc($res)) {
            $data = array('value_' => str_replace("'", '"', get_input($row['name'])));
            $parametr = " name = '{$row['name']}' ";
            dbPerform(CURRENT_RES.'_config',$data,'update',$parametr);
        }
        redirect(SERVER_ROOT.'/sprav.php?act=list');
    }

    elseif ($step == 'config_list') {
        $objects = array();
        $query = 'select * from '.CURRENT_RES.'_config' ;
        $res = dbQuery($query);
        while($row = dbFetchAssoc($res)){
            $temp = array('name'   => $row['name'],
                          'value_' => htmlspecialchars($row['value_']));
            $objects[] = $temp;
        }
        $smarty->assign('main_area', 'sprav/config_edit.html');
        $smarty->assign('objects', $objects);
    }


    // шаги по обработке только справочника тарифов
    elseif ($step == 'vidtar_edit') {
        $id = get_input('id','int');
        if ($id < 1) redirect(SERVER_ROOT.'/sprav.php?act=vidtar');
        $object['id'] = $id;
        list($object['name'], $object['mt'], $object['vidmt'], $object['dateb'], $object['datee']) = dbFetchRow(dbQuery("select vidtar, mt, vidmt, date_format(dateb, '%d.%m.%Y'), date_format(datee, '%d.%m.%Y') from _vidtar where id=$id"));
        $history_list = array();
        $query = "Select kod, ddate, cena1, cena2, cena3, cena4, norma, cena_base from _centar where kod=$id order by ddate" ;
        $res = dbQuery($query);
        if (dbRowsCount($res)) {
            while ($row = dbFetchAssoc($res)) {
                $history_list[] = array(
                    'kod'     => $row['kod'],
                    'ddate'   => date(DATE_FORMAT_SHOT,strtotime($row['ddate'])),
                    'cena1'   => $row['cena1'],
                    'cena2'   => $row['cena2'],
                    'cena3'   => $row['cena3'],
                    'cena4'   => $row['cena4'],
                    'norma'   => $row['norma'],
                    'cena_base'=>$row['cena_base'],
                    'editable'=> 0
                );
            }
            $history_list[count($history_list)-1]['editable'] = 1;
        }
        $smarty->assign('title', "СПРАВОЧНИКИ. Виды тарифов: ".$object['name']);
        $smarty->assign('sprav_name', "Виды тарифов: ".$object['name']);
        $smarty->assign('history_list', $history_list);
        $smarty->assign('history_list_count', count($history_list));
        $smarty->assign('object', $object);
        $smarty->assign("main_area", "sprav/vidtar_edit.html");
        $smarty->assign('step', 'edit');
        $smarty->assign('tar_id', $object['id']);
    }

    elseif ($step=='vidtar_do_edit_history') {
        $id = get_input('id','int');
        $ddate = trim(get_input('ddate'));
        if ($id<1) redirect(SERVER_ROOT.'/sprav.php?act=vidtar');
        $cena1   = floatval(str_replace(',','.',trim(get_input('cena1'))));
        $cena2   = floatval(str_replace(',','.',trim(get_input('cena2'))));
        $cena3   = floatval(str_replace(',','.',trim(get_input('cena3'))));
        $cena4   = floatval(str_replace(',','.',trim(get_input('cena4'))));
        $norma   = floatval(str_replace(',','.',trim(get_input('norma'))));
        $cena_base = floatval(str_replace(',','.',trim(get_input('cena_base'))));

        if ($cena1 <= 0) {
            $errors[] = "Введены пустые значения";
        }
        if(count($errors)) {
            $smarty -> assign('errors', $errors);
            $smarty -> assign('label_error', 1);
            $step = 'vidtar_edit_history';
        }
        else {
            $query = "Select kod, ddate, cena1, cena2, cena3, cena4, norma, cena_base from _centar where kod=$id order by ddate desc";
            $res = dbQuery($query);
            $row = dbFetchAssoc($res);
            $parametr = " (kod=".$row['kod'];
            $parametr .= " and ddate='".dateYMD($row['ddate'])."'";
            $parametr .= " and cena1 = ".$row['cena1'];
            $parametr .= " and cena2 = ".$row['cena2'];
            $parametr .= " and cena3 = ".$row['cena3'];
            $parametr .= " and cena4 = ".$row['cena4'];
            $parametr .= " and norma = ".$row['norma'];
            $parametr .= " and cena_base=".$row['cena_base'];
            $parametr .= " )";
            $data = array(
                'ddate'   => dateYMD($ddate),
                'cena1'    => $cena1,
                'cena2'    => $cena2,
                'cena3'    => $cena3,
                'cena4'    => $cena4,
                'norma'    => $norma,
                'cena_base'=> $cena_base
                );
            dbPerform('_centar',$data,'update',$parametr);
            redirect(SERVER_ROOT.'/sprav.php?act=vidtar&step=edit&id='.$id);
        }
    }

    elseif ($step=='vidtar_do_add_history') {
        $id = get_input('id','int');
        $ddate = trim(get_input('ddate'));
        if ($id<1) redirect(SERVER_ROOT.'/sprav.php?act=vidtar');

        $cena1   = floatval(str_replace(',','.',trim(get_input('cena1'))));
        $cena2   = floatval(str_replace(',','.',trim(get_input('cena2'))));
        $cena3   = floatval(str_replace(',','.',trim(get_input('cena3'))));
        $cena4   = floatval(str_replace(',','.',trim(get_input('cena4'))));
        $norma   = floatval(str_replace(',','.',trim(get_input('norma'))));
        $cena_base=floatval(str_replace(',','.',trim(get_input('cena_base'))));

        if ($cena1 <= 0) {
            $errors[] = "Введены пустые значения";
        }
        if(count($errors)) {
            $smarty -> assign('errors', $errors);
            $smarty -> assign('label_error', 1);
            $step = 'vidtar_add_history';
        }
        else {
            $data = array(
                'kod'     => $id,
                'ddate'   => dateYMD($ddate),
                'cena1'   => $cena1,
                'cena2'   => $cena2,
                'cena3'   => $cena3,
                'cena4'   => $cena4,
                'norma'   => $norma,
                'cena_base'=>$cena_base
                );
            dbPerform('_centar',$data);
            redirect(SERVER_ROOT.'/sprav.php?act=vidtar&step=edit&id='.$id);
        }
    }

    elseif ($step == 'vidtar_edit_history') {
        $id = get_input('id','int');
        list($tarifname, $vidmt) = dbFetchRow(dbQuery("Select vidtar,vidmt from _vidtar where id=$id"));
        if ($id < 1) redirect(SERVER_ROOT.'/sprav.php?act=vidtar');
        $query = "Select kod, ddate, cena1, cena2, cena3, cena4, norma, cena_base from _centar where kod=$id order by ddate desc" ;
        $res = dbQuery($query);
        if (dbRowsCount($res) < 1) redirect(SERVER_ROOT.'/sprav.php?act=vidtar');
        $row = dbFetchAssoc($res);
        $object = array(
            'tarifname'=> $tarifname.' - '.$vidmt,
            'id'       => $id,
            'kod'      => $row['kod'],
            'ddate'    => date(DATE_FORMAT_SHOT,strtotime($row['ddate'])),
            'cena1'    => $row['cena1'],
            'cena2'    => $row['cena2'],
            'cena3'    => $row['cena3'],
            'cena4'    => $row['cena4'],
            'norma'    => $row['norma'],
            'cena_base'=> $row['cena_base']
        );
        $smarty->assign('title',"СПРАВОЧНИКИ. Виды тарифов: ".$tarifname.' - '.$vidmt);
        $smarty->assign('sprav_name',"Виды тарифов: ".$tarifname.' - '.$vidmt);
        $smarty->assign('object',$object);
        $smarty->assign('step','vidtar_edit_history');
        $smarty->assign("main_area", "sprav/vidtar_edithistory.html");
    }

    elseif ($step == 'vidtar_add_history') {
        $id = get_input('id','int');
        if ($id < 1) redirect(SERVER_ROOT.'/sprav.php?act=vidtar');
        list($tarifname, $vidmt) = dbFetchRow(dbQuery("Select vidtar,vidmt from _vidtar where id=$id"));
        $object['id'] = $id;
        $smarty->assign('title',"СПРАВОЧНИКИ. Виды тарифов: ".$tarifname." - ".$vidmt);
        $smarty->assign('sprav_name',"Виды тарифов: ".$tarifname." - ".$vidmt);
        $smarty->assign('object',$object);
        $smarty->assign('tarifname',$tarifname);
        $smarty->assign('vidmt',$vidmt);
        $smarty->assign('step','vidtar_add_history');
        $smarty->assign("main_area", "sprav/vidtar_edithistory.html");
    }

    // импорт справочника счетчиков из формата JSON
    elseif ($step == 'import_tips10c') {
        $errors = array();
        if (count($errors)==0) {
            // Загружаем данные из файла в строку
            // http://suee.brest.energo.net/get-catalog-register-all-json.php?short=1
            $string = file_get_contents(SERVER_ROOT_DIR."/docs/IMPORT/tips10c.json");
            // Превращаем строку в объект
            $json = json_decode($string);
            pr($json);
            // Отлавливаем ошибки возникшие при превращении
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    break;
                case JSON_ERROR_DEPTH:
                    $errors[] = 'Достигнута максимальная глубина стека'; break;
                case JSON_ERROR_STATE_MISMATCH:
                    $errors[] = 'Неверный или не корректный JSON'; break;
                case JSON_ERROR_CTRL_CHAR:
                    $errors[] = 'Ошибка управляющего символа, возможно верная кодировка'; break;
                case JSON_ERROR_SYNTAX:
                    $errors[] = 'Синтаксическая ошибка'; break;
                case JSON_ERROR_UTF8:
                    $errors[] = 'Некорректные символы UTF-8, возможно неверная кодировка'; break;
                default:
                    $errors[] = 'Неизвестная ошибка'; break;
            }
        }
        if (count($errors)==0) {
           $x = 0;
            $query = '';
            foreach ($json as $key=>$result) {
                $data = array();
                $data['id'] = intval($result['code']);
                $data['label'] = $result['phase'];
                $data['tipch'] = $result['name'];
                $data['izg'] = $result['vendor'];
                $data['v'] = $result['voltage'];
                $data['a'] = $result['current'];
                if (trim($result['type'])=='Эл.-механический') $data['kolob'] = 'эл-мех.';
                elseif (trim($result['type'])=='Электронный')  $data['kolob'] = 'электрон';
                else                                           $data['kolob'] = $result['tc'];
                $data['chtar'] = $result['tarif'];
                $data['maxn'] = $result['cdc'];
                $data['spov'] = intval($result['gcp'])/12;
                $data['toch'] = $result['ac'];
                $data['dlt'] = 0;

                $error = 0;
                if (mb_strstr($data['v'],'100') != NULL ) $error = 1;
                if (mb_strstr($data['v'],'127') != NULL ) $error = 1;

                if ($error == 0) {
                    $query = dbPerform('_tip_sc',$data);
                    $x++;
                }
            }

            redirect(SERVER_ROOT.'/sprav.php?act=tips10c');
        }
        else {
            $step = 'list';
        }
    }

    // импорт справочника КТП
    elseif ($step == 'import_ktp') {
        $errors = array();
        //        $dataready = get_input('dataready','int');
        //        if ($dataready==0) {
        //            if (!exec('copy \\\SERVER2\\PROM\\CLIPPER5\\SP1CH.DBF '.SERVER_ROOT_DIR.'/docs/SC_IMPORT/SP1CH.DBF')){
        //              $errors[] = "Невозможно установить связь с файлом импорта";
        //       }
        if (count($errors)==0) {
//            $file_lep04 = fopen(SERVER_ROOT_DIR."/docs/IMPORT/".$_COOKIE['userresid']."_lep04.txt", "rb");
 //           $file_ps10  = fopen(SERVER_ROOT_DIR."/docs/IMPORT/".$_COOKIE['userresid']."_ps10.txt", "rb");
  //          $file_lep10 = fopen(SERVER_ROOT_DIR."/docs/IMPORT/".$_COOKIE['userresid']."_lep10.txt", "rb");
            $file_lep04 = fopen(SERVER_ROOT_DIR."/docs/IMPORT/19_lep04.txt", "rb");
            $file_ps10  = fopen(SERVER_ROOT_DIR."/docs/IMPORT/19_ps10.txt", "rb");
            $file_lep10 = fopen(SERVER_ROOT_DIR."/docs/IMPORT/19_lep10.txt", "rb");
            while ($s1 = fgets($file_lep04)) {
                $s_lep04 = explode("\t", $s1);
                switch ($s_lep04[1]) {
                    case '1' : $tip_lep04 = 'ВЛ'; break;
                    case '2' : $tip_lep04 = 'КЛ'; break;
                }
                // ИД ЛЭП ; id_tip ; tip_v ; Номер ЛЭП ; ИД ПС;
                if ($s_lep04[0] > 0) {
                    $sql = "select * from ".CURRENT_RES."_ktp where id=".$s_lep04[0];
                    $res = $dblink->query($sql);
                    $rows = $res->fetchAll();
                    // Если нет записи с таким ИД
                    if ($res->rowCount() == 0) {
                        while ($s2 = fgets($file_ps10)) {
                            $s_ps10 = explode("\t", $s2);
                            switch ($s_ps10[1]) {
                                case '1' : $tip_ps10 = 'ОРУ'; break;
                                case '2' : $tip_ps10 = 'ТЭЦ'; break;
                                case '3' : $tip_ps10 = 'ПС'; break;
                                case '4' : $tip_ps10 = 'РП'; break;
                                case '5' : $tip_ps10 = 'ТП'; break;
                                case '6' : $tip_ps10 = 'КТП'; break;
                                case '7' : $tip_ps10 = 'МТП'; break;
                                case '8' : $tip_ps10 = 'СТП'; break;
                            }
                            // ИД ПС ; id_tip ; tip_v ; Номер ПС ; ИД ПС;
                            if ($s_ps10[0] == $s_lep04[4]) {
                                while ($s3 = fgets($file_lep10)) {
                                    $s_lep10 = explode("\t", $s3);
                                    if ($s_lep10[0] == $s_ps10[6]) {
                                        switch ($s_lep10[1]) {
                                            case '1' : $tip_lep10 = 'ВЛ'; break;
                                            case '2' : $tip_lep10 = 'КЛ'; break;
                                        }
                                        $sql = "insert into ".CURRENT_RES."_ktp (id, tip_lep, n_lep, id_ps, tip_ps, n_ps, id_lep10, tip_lep10, n_lep10, id_ps330) VALUES ('$s_lep04[0]', '$tip_lep04', '$s_lep04[3]', '$s_ps10[0]', '$tip_ps10', '$s_ps10[3]', '$s_lep10[0]', '$tip_lep10', '$s_lep10[3]', '$s_lep10[4]')";
                                        $res = $dblink->query($sql);
                                    }
                                }
                                rewind($file_lep10);
                            }
                        }
                        rewind($file_ps10);
                    }
                }
            }
            fclose ($file_lep04);
            fclose ($file_ps10);
            fclose ($file_lep10);
            redirect(SERVER_ROOT.'/sprav.php?act=ktp');
        }
        else {
            $step = 'list';
        }
    }
  }
}

$smarty->display('layout.html');
?>