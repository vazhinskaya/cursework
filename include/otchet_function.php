<?php
require_once ('core.php');
require_once ('lib_general.php');
require_once ('lib_db.php');

 	$action = get_input ( 'a' );
	$step = get_input('step');
	$errors = array ();
	$report_str = '';
	$perpercent = 1000;
	$photos_dir = DOCS_DIR."/photo/";
	$udost_dir  = DOCS_DIR.'/udostoverenia/';

/****************************************************************************************/
/*             ОБЩИЕ ДЛЯ ВСЕХ ОТЧЁТОВ: СТИЛИ, ДАННЫЕ                                    */
/****************************************************************************************/
	$objPHPExcel  = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

	$style_8_r = array(
			'font'    	=> array('normal'  	=> true, 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_8_l = array(
			'font'    	=> array('normal'  	=> true, 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_8_c = array(
			'font'    	=> array('normal'  	=> true, 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_8_r = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_8_l = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_8_c = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_8_r_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_8_l_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_8_c_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,	'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_8_c_c = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '8'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_9_c = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '9'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_9_l_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '9'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,   'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_9_r_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '9'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_9_c_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '9'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_9_l_boutline = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '9'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,   'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('outline' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_9_r_bdash = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '9'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('vertical' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED),
						 'horizontal' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
						 'outline' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
	);
	$style_a_10_c = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '10'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_10_l = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '10'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_a_10_r_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '10'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_10_l_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '10'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_10_c_b = array(
			'font'    	=> array('normal'  	=> true, 'name'	=> 'Arial', 'size' => '10'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_a_10_bold_c_c = array(
			'font'    	=> array('bold'  	=> true, 'name'	=> 'Arial', 'size' => '10'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER),
			'borders'	=> array('allborders' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)),
	);
	$style_12_l = array(
			'font'    	=> array('normal'  	=> true, 'size' => '12'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_12_l_bold = array(
			'font'    	=> array('bold'  	=> true, 'normal'  	=> true, 'size' => '12'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);
	$style_12_l_bold_u = array(
			'font'    	=> array('bold'  	=> true, 'normal'  	=> true, 'size' => '12'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
			'borders'	=> array('bottom' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
	);
	$style_14_l_bold = array(
			'font'    	=> array('bold'  	=> true, 'normal'  	=> true, 'size' => '14'),
			'alignment' 	=> array('horizontal' 	=> \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM),
	);

	$style_border_dot = array(
			'borders'	=> array('allborders'	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED))
	);
	$style_border_indot_outthin = array(
			'borders'	=> array('inside' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED),
						 'outline' 	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
	);
	$style_border_top = array(
			'borders'	=> array('top'		=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN))
	);
	$style_border_bottom_dot = array(
			'borders'	=> array('bottom'	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED))
	);
	$style_border_left_dot = array(
			'borders'	=> array('left'		=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED))
	);
	$style_border_right_dot = array(
			'borders'	=> array('right'	=> array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED))
	);

	$style_font_bold = array(
			'font'    	=> array('bold'  	=> true)
	);
	$style_underline = array(
			'font'		=> array('underline' 	=> \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE),
	);
	$style_vert_center = array(
			'alignment' 	=> array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER),
	);


/**********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Ведомость оплаты (ФОРМА 1)                           */
/**********************************************************************************************/
	if ($action == 'f_01'){
		require_once("report/f_01.php");
	}
/***********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Общий отчёт                                           */
/***********************************************************************************************/
    if ($action == 'common_report'){
        require_once("report/common_report.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Сведения о задолженности (ФОРМА 4)                    */
/* *********************************************************************************************/
    if ($action == 'f_04'){
        require_once("report/f_04.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Акт сверки поступления платежей от населения(ФОРМА 5) */
/* *********************************************************************************************/
    if ($action == 'f_05'){
        require_once("report/f_05.php");
    }
/***********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры) По предоставлению категориям граждан льгот (ФОРМА 6)   */
/***********************************************************************************************/
    if ($action == 'f_06'){
        require_once("report/f_06.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Учет движения счетчиков в разрезе тарифов  (ФОРМА 7)  */
/* *********************************************************************************************/
    if ($action == 'f_07'){
        require_once("report/f_07.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Сведения о неплательщиках(ФОРМА 8)                    */
/* *********************************************************************************************/
    if ($action == 'f_08'){
        require_once("report/f_08.php");
    }
/***********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Льготники по принадлежности домов и по с\с            */
/***********************************************************************************************/
    if ($action == 'report_lgotniki_prinadl'){
        require_once("report/lgotniki_prinadl.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Абоненты в разрезе типов построек по регионам	       */
/* *********************************************************************************************/
	if ($action == 'tipy_postroek'){
        require_once("report/tipy_postroek.php");
    }
/***********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Конструктор отчета     				               */
/***********************************************************************************************/
	if ($action == 'f_constructor'){
		require_once("report/f_constructor.php");
	}
/***********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Отчет по потере льготников                            */
/***********************************************************************************************/
    if ($action == 'lose_lg'){
        require_once("report/lose_lg.php");
    }
/***********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры): Реестр возврата квитанций (за услуги)                 */
/***********************************************************************************************/
    if ($action == 'reestr_vozv'){
        require_once("report/reestr_vozv.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры):Контрольно-накопительная ведомость поступления платежей*/
/* *********************************************************************************************/
    if ($action == 'kontr_nak_ved'){
        require_once("report/kontr_nak_ved.php");
    }
/* *********************************************************************************************/
/*   Отчеты (формы, ведомости, реестры):Привязка*/
/* *********************************************************************************************/
    if ($action == 'priviazka'){
        require_once("report/priviazka.php");
    }

/****************************************************************************************/
/*                         Функции общего назначения                                    */
/* ************************************************************************************ */
/* ************************************************************************************ */
/* ************************************************************************************ */
/*                Формирование списка улиц для населенного пункта                       */
/* ************************************************************************************ */
    if ($action == 'get_streets_for_np'){
        $id_np = get_input('id_np','int');
        $query = "
            SELECT a.*, b.tip_street
            FROM ".CURRENT_RES."_street as a
            LEFT JOIN _tip_street as b on a.id_tip_street = b.id
            where id_np = '$id_np' order by tip_street asc, street asc";
        $res = $dblink->query($query);
        if ($res->rowCount() > 0){
            $output = '<select name="f_obhod_street" id="f_obhod_street" size="1">';
            $output .= '<option value="0">----------------ВСЕ----------------------</option>';
            while ($row = $res->fetch()){
                $output .= '<option value="'.$row['id'].'">'.$row['tip_street'].'-'.$row['street'].'</option>';
            }
            $output .= '</select>';
        }
        else{
            $output = '<select name="f_obhod_street" id="f_obhod_street" size="1">
                <option value="0">Выбрать</option>
                <option value="0">-----------------------------------------</option>
            </select>';
        }
        echo $output;
    }
    if ($action == 'get_streets_for_np_copy'){
        $id_np = get_input('id_np','int');
        $query = "
            SELECT a.*, b.tip_street
            FROM ".CURRENT_RES."_street as a
            LEFT JOIN _tip_street as b on a.id_tip_street = b.id
            where id_np = '$id_np' order by tip_street asc, street asc";
        $res = dbQuery($query);
        if (dbRowsCount($res)){
            $output = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Улица:<select name="filter_street" id="filter_street" size="1">';
            $output .= '<option value="0">----------------ВСЕ----------------------</option>';
            while ($row = dbFetchAssoc($res)){
                $output .= '<option value="'.$row['id'].'">'.$row['tip_street'].'-'.$row['street'].'</option>';
            }
            $output .= '</select>';
        }
        else{
           $output = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Улица:<select name="filter_street" id="filter_street" size="1">
                <option value="0">Выбрать</option>
                <option value="0">-----------------------------------------</option>
            </select>';
        }
        echo $output;
    }
    if ($action == 'get_streets_for_np_copy2'){
        $id_np = get_input('id_np','int');
        $query = "
            SELECT a.*, b.tip_street
            FROM ".CURRENT_RES."_street as a
            LEFT JOIN _tip_street as b on a.id_tip_street = b.id
            where id_np = '$id_np' order by tip_street asc, street asc";
        $res = dbQuery($query);
        if (dbRowsCount($res)){
            $output = '<select name="streets" id="streets" size="1" onchange="regions_for_street();">';
            while ($row = dbFetchAssoc($res)){
                $output .= '<option value="'.$row['id'].'">'.$row['tip_street'].'-'.$row['street'].'</option>';
            }
            $output .= '</select>';
        }
        else{
           $output = '<select name="street" id="street" size="1" onchange="regions_for_street();>
                <option value="0">Выбрать</option>
                <option value="0">-----------------------------------------</option>
            </select>';
        }
        echo $output;
    }
    if ($action == 'set_res_for_np'){
        $result = dbFetchArray(dbQuery("Select * from _res where type_res = '".CURRENT_RES."'" ));
        $output = '<select name="res" size="1" id="res" >';
        if (count($result)>1){
            $id_np = get_input('id_np','int');
            $tip_np = dbOne("SELECT id_tip FROM ".CURRENT_RES."_np where id='$id_np'" );
            $is_gorod = dbOne("Select count(*) FROM _tip_np where tip_np like '%ород%' and id = '$tip_np' ");
                foreach ($result as $row){
                    $output .= '<option value="'.$row['id'].'"';
                    if ($is_gorod && mb_strstr($row['res'],'ород'))        $output .= ' selected="selected" ';
                    elseif (!$is_gorod && mb_strstr($row['res'],'ельс'))   $output .= ' selected="selected" ';
                    $output .= '">'.$row['res'].'</option>';
                }
        }
        else{
            foreach ($result as $row){
                    $output .= '<option value="'.$row['id'].'"';
                    $output .= '">'.$row['res'].'</option>';
                }
        }
        $output .= '</select>';
        echo $output;
    }

/****************************************************************************************/
/*                Подгрузка списка улиц для выбранного населенного пункта               */
/****************************************************************************************/
    if($action == 'gen_func'){
        if ($step == 'getStreetsforNP'){
            $np = get_input('np');
            $np = explode(' ',trim(str_replace(',',' ',$np)));
            if (count($np)>1){
                $temp = '';
                foreach ($np as $value) if (intval($value)) $temp .= " $value, ";
                $list = dbFetchArray(dbQuery("
                    SELECT  tip_np.tip_np, np.np, tip_street.tip_street, street.id, street.street
                        FROM ".CURRENT_RES."_street as street
                        LEFT JOIN _tip_street as tip_street on tip_street.id = street.id_tip_street
                        LEFT JOIN ".CURRENT_RES."_np as np on np.id = street.id_np
                        LEFT JOIN _tip_np as tip_np on tip_np.id = np.id_tip
                        WHERE (street.id_np in ($temp -1))
                        ORDER by tip_np.tip_np, np.np, tip_street.tip_street, street.street"));
                echo "<div class='filter_head'><b>Улица</b></div>";
                echo '<select id="streets" name="streets" size="9" multiple="multiple" onchange="getDomsforStreet();">';
                echo '<option value="all">------------Учитывать все улицы------------</option>';
                foreach ($list as $row){
                    echo "<option value={$row['id']}>{$row['tip_np']} {$row['np']} - {$row['tip_street']} {$row['street']}</option>";
                }
                echo '</select>';
                echo '</div>';
                exit();
            }
            elseif(count($np)==1){
                $list = dbFetchArray(dbQuery("
                    SELECT s.id, tip_street, street
                        FROM ".CURRENT_RES."_street as s, _tip_street as ts
                        WHERE (s.id_tip_street = ts.id) and
                              (s.id_np = {$np['0']})
                        ORDER by tip_street, street"));
                if (count($list)){
                    echo "<div class='filter_head'><b>Улица</b></div>";
                    echo '<select id="streets" name="streets" size="9" multiple="multiple" onchange="getDomsforStreet();">';
                    echo '<option value="all">------------Учитывать все улицы------------</option>';
                    foreach ($list as $row){
                        echo "<option value={$row['id']}>{$row['tip_street']} {$row['street']}</option>";
                    }
                    echo '</select>';
                    echo '</div>';
                }
                else{
                    echo "<div class='filter_head'><b>Улица</b></div>";
                    echo '<select name="streets" id="streets" name="streets" size="9" multiple="multiple">';
                    echo "<option value='all'}>---------Нет улиц для данного НП--------</option>";
                        foreach ($list as $row){
                            echo "<option value={$row['id']}>{$row['tip_street']} {$row['street']}</option>";
                        }
                    echo '</select>';
                    echo '</div>';
                }
            }
            else{
                echo "<div class='filter_head'><b>Улица</b></div>";
                echo '<select name="streets" id="streets" name="streets" size="9" multiple="multiple">';
                echo "<option value='all'}>---------Нет улиц для данного НП--------</option>";
                foreach ($list as $row){
                    echo "<option value={$row['id']}>{$row['tip_street']} {$row['street']}</option>";
                }
                echo '</select>';
                echo '</div>';
            }
        }

/****************************************************************************************/
/*                Подгрузка списка ДОМОВ для выбраннЫХ УЛИЦ населенного пункта          */
/*                                Выбирает для множества улиц                           */
/****************************************************************************************/
        elseif ($step == 'getDomsforStreet'){
            $streets = get_input('streets');
            $streets = explode(',',$streets);
            $street_list = "";
            $street_nums = 0;
            foreach($streets as $street){
                $street = intval($street);
                if ($street) {
                    $street_list .= "$street," ;
                    $street_nums += 1;
                }
            }
            $street_list .= '-1';

            if ($street_nums>1){
                $list = dbFetchArray(dbQuery("
                        SELECT
                            dom.id, dom.dom, dom.domadd, convert(integer, dom) as ddom,
                            street.street,
                            tip_street.tip_street
                            FROM ".CURRENT_RES."_dom as dom
                            LEFT JOIN ".CURRENT_RES."_street as street on street.id = dom.id_ul
                            LEFT JOIN _tip_street as tip_street on tip_street.id = street.id_tip_street
                            WHERE dom.id_ul in ($street_list)
                            order by tip_street.tip_street asc, street.street, ddom "));
            }
            else{
                $list = dbFetchArray(dbQuery("
                        SELECT
                            dom.id, dom.dom, dom.domadd, cast(dom as integer) as ddom,
                            street.street,
                            tip_street.tip_street
                            FROM ".CURRENT_RES."_dom as dom
                            LEFT JOIN ".CURRENT_RES."_street as street on street.id = dom.id_ul
                            LEFT JOIN _tip_street as tip_street on tip_street.id = street.id_tip_street
                            WHERE dom.id_ul in ($street_list)
                            order by ddom "));
            }
            if (count($list)){
                if ($street_nums>1){
                    echo "<div class='filter_head'><b>Дом</b></div>";
                    echo '<select name="doms" id="doms" size="9" multiple="multiple">';
                    echo '<option value="all">------------Учитывать все дома-------------</option>';
                    foreach ($list as $row){
                        echo "<option value={$row['id']}>{$row['tip_street']} {$row['street']} {$row['dom']}";
                        if (mb_strlen(trim($row['domadd']))>0) echo "/{$row['domadd']}";
                        echo "</option>";
                    }
                    echo '</select>';
                    echo '</div>';
                }
                else{
                    echo "<div class='filter_head'><b>Дом</b></div>";
                    echo '<select name="doms" id="doms" size="9" multiple="multiple">';
                    echo '<option value="all">------------Учитывать все дома-------------</option>';
                    foreach ($list as $row){
                        echo "<option value={$row['id']}>{$row['dom']}";
                       if (mb_strlen(trim($row['domadd']))>0) echo "/{$row['domadd']}";
                        echo "</option>";
                    }
                    echo '</select>';
                    echo '</div>';
                }
            }
            else{
                echo "<div class='filter_head'><b>Дом</b></div>";
                echo '<select name="doms" id="doms" size="9" multiple="multiple">';
                echo "<option value='all'}>-----------Нет домов для улицы----------</option>";
                    foreach ($list as $row){
                        echo "<option value={$row['id']}>{$row['tip_street']} {$row['street']}</option>";
                    }
                echo '</select>';
                echo '</div>';
            }
    }

/****************************************************************************************/
/*                Изготовление удостоверения для сотрудника                             */
/****************************************************************************************/
        elseif($step=='do_udost_for_personal'){
            require_once("report/do_udost_for_personal.php");
        }
	}
?>
