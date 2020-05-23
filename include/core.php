<?php

session_start();
include_once __DIR__.'/../vendor/autoload.php';
require_once ("setup.php");
require_once ("lib_user.php");
require_once ("lib_tarif.php");
require_once ("lib_db.php");

// other settings
//date_default_timezone_set('Europe/Minsk');
//ini_set ("display_errors",         true);
//ini_set ("display_startup_errors", true);
//ini_set ("error_reporting",        E_ALL);
ini_set('max_execution_time', 72000);

// User types definitions
define ("DLTHIDE",     " and (dlt=0 or dlt is NULL) ");
define ('DATE_FORMAT_SHOT','d.m.Y');
define ('DATE_FORMAT_YMD', 'Y.m.d');
define ('DATE_FORMAT_FULL','Y.m.d H:i:s');
define ("COUNT_UCH", 4);
define ("ROUND_TO", 2);
$TransactionError = 0;

// SMARTY
$smarty = new Smarty;
$smarty->compile_dir   = SERVER_ROOT_DIR."/templates_c/";
$smarty->template_dir  = SERVER_ROOT_DIR."/templates/";
$smarty->force_compile = false;
$smarty->caching       = false;
$smarty->compile_check = true;
$smarty->debugging     = false;

$dblink = dbConnect();
global $db_name;
$smarty->assign('db_name', $db_name);
$smarty->assign("SERVER_ROOT", SERVER_ROOT);
$smarty->assign("SERVER2019_ROOT", SERVER2019_ROOT);
$smarty->assign("_ERROR",   1);
$smarty->assign("_SUCCESS", 0);
$smarty->assign("title", "Бытовые абоненты.");

$userid= GetUserTbnCookie();
// если пользователь не авторизирован - отправляем на форму аутентификации
if ($userid == '-1') {
    list($lastfes, $lastfesid, $lastres, $lastuser) = GetLastCookie();
    $smarty->assign('fess', GetFesForLogin());
    $smarty->assign('fessid', GetFesForLogin()); /////????????????????????????????????????????
    $smarty->assign('ress', GetResForLogin($lastfes));
    $smarty->assign('users', GetUsers($lastres));
    $smarty->assign('lastfes', $lastfes);
    $smarty->assign('lastfesid', $lastfesid);
    $smarty->assign('lastres', $lastres);
    $smarty->assign('lastuser', $lastuser);
    $smarty->assign("title", "Бытовые абоненты. Авторизация---dddddddddddddddddddddddddddlllllllllllllllsdaaaaaaaaaaaaaaaa.");
    $smarty->display("login.html");
    exit();
}
$userres = GetUserResCookie();
define ('CURRENT_RES', $userres);
define ("DOCS_DIR",            SERVER_ROOT_DIR.'/docs/'.CURRENT_RES.'/');
define ("REPORT_TEMPLATES_DIR",SERVER_ROOT_DIR.'/docs_templ/');
define ("REPORT_DIR2",         SERVER_ROOT.'/docs/'.CURRENT_RES.'/report/');
define ("REPORT_DIR",          DOCS_DIR    .'report/');
define ("BANK_DOCS_DIR",       DOCS_DIR    .'bank/');
define ("ARCHIVE_DOCS_DIR",    DOCS_DIR    .'archive/');
define ("MOBI_DIR",            DOCS_DIR    .'mobi/');
$user_access = GetUserAccess($userid);
$username = GetUserName($userid);
$smarty->assign("username", $username);                 // USER NAME   - табельный номер
$smarty->assign("user_access", $user_access);           // USER ACCESS - уровень доступа

// @brief Register globals with NULL value if not isset
function RegisterGlobalNULL () {
    global $_GET, $_POST, $_SERVER, $_FILES;

    $args = func_get_args();
    foreach ($args as $key) {
        $value = NULL;
        if (isset($_GET[$key])) $value  = $_GET[$key];
        if (isset($_POST[$key])) $value = $_POST[$key];

        if (!ini_get ('magic_quotes_gpc')) {
            if (!is_array($value))
                $value = addslashes($value);
            else
                $value = SlashArray($value);
        }
        $GLOBALS[$key] = $value;
        unset($value);
    }
}

// @brief Recursive add slashes to an array
function SlashArray ($a) {
    foreach($a as $k=>$v) {
        if (!is_array($v))
            $a[$k] = addslashes($v);
        else
            $a[$k] = SlashArray($v);
    }
    reset ($a);
    return ($a);
}

////////////////////////////////////////////////////////////////////////////////
function pr($str, $die = true, $name = '', $error=false)
{
    echo "<pre>";
    if ($name)
        if ($error)
            echo "<span style='color: red'>".$name.":</span>";
        else
            echo "<span style='color: green'>".$name.":</span>";
    print_r($str);
    echo "</pre>";
    if ($die) die();
}

////////////////////////////////////////////////////////////////////////////////
function GetCookieName($name) {
    $def_lng = '31';

    global $_COOKIE;
    return (isset($_COOKIE[$name]) && $_COOKIE[$name]) ? strtolower($_COOKIE[$name]    ) : strtolower($def_lng);
}

////////////////////////////////////////////////////////////////////////////////
function GetSqlCookie() {
    $sql_text = "";
    global $_COOKIE;
    return (isset($_COOKIE['sql_text']) && $_COOKIE['sql_text']) ? strtolower($_COOKIE['sql_text']    ) : strtolower($sql_text);
}

////////////////////////////////////////////////////////////////////////////////
function GetRes() {
    global $dblink;
    $res = $dblink->query("select * from _res order by id");
    return ($res->fetchAll());
}

////////////////////////////////////////////////////////////////////////////////
function GetTipNasp() {
    global $dblink;
    $res = $dblink->query("SELECT id, tip_np FROM _tip_np");
    return ($res->fetchAll());
}

///////////////////////////////////////////////////////////////////////////////
function GetNasp($id) {
    global $dblink, $userres;
    $res = $dblink->query("select id, np from ".CURRENT_RES."_np where id_tip='".$id."' order by np");
    return ($res->fetchAll());
}

///////////////////////////////////////////////////////////////////////////////
function GetStreet($id) {
    global $dblink, $userres;
    $res = $dblink->query("select a.id, b.tip_street, a.street  from ".CURRENT_RES."_street a, _tip_street b where a.id_np='".$id."' and a.id_tip_street=b.id order by 2,3");
    return ($res->fetchAll());
}
///////////////////////////////////////////////////////////////////////////////

function GetDom($id) {
    global $dblink, $userres;
    $res = $dblink->query("select *, CAST(dom as UNSIGNED) as ddom from ".CURRENT_RES."_dom where id_ul='".$id."'".DLTHIDE." order by ddom,3");
    return ($res->fetchAll());
}
///////////////////////////////////////////////////////////////////////////////

function GetRegion($id_town) {
    global $dblink, $userres;
    $res = $dblink->query("SELECT id, region FROM {$userres}_region where id_np=".$id_town);
    return ($res->fetchAll());
}

///////////////////////////////////////////////////////////////////////////////
function GetPostr() {
    global $dblink;
    $res = $dblink->query("SELECT * FROM _tip_postr");
    return ($res->fetchAll());
}

///////////////////////////////////////////////////////////////////////////////
function GetPrinadl() {
    global $dblink;
    $res = $dblink->query("SELECT * FROM ".CURRENT_RES."_tip_prinadl");
    return ($res->fetchAll());
}

////////////////////////////////////////////////////////////////////////////////
function GetNomr() {
    global $dblink;
    $res = $dblink->query("select * from _spr_nomr order by nomr");
    return ($res->fetchAll());
}

////////////////////////////////////////////////////////////////////////////////
function GetGenSpr($id) {
    global $dblink;
    $res = $dblink->query("select * from _gen_sprav where cat_id='$id' and inn_id<>'0' order by res_value");
    return $res->fetchAll();
}

// Проверка на украденность счетчика ***************************** >>
function CheckStolenSc($zn) {
    global $dblink;
    $stolen="";
    $res0 = $dblink->query("select distinct type_res from _res");
    for($k=0; $k < $res0->rowCount(); $k++) {
        // Проверка на "ворованный" счетчик
        $res1 = $res0->fetch();
        $res = $dblink->query("select * from  ".$res1["type_res"]."_mainsc where zn='".$zn."' and reas1=5");
        if ($res->rowCount()>0) { $stolen=$res1["kn"].$res1["nom"]; }
    }
    return $stolen;
}

// Форматировать число к определенному количеству знаков
function ForZn($z, $znak){
    while ((mb_strlen($z."a")-$znak)<=0){ $z="0".$z;}
    return $z;
}

//  Преобразования дат ************************************ >>
function dateYMD($date=false) {
    if ($date) return date("Y.m.d", strtotime($date));
    else       return date("Y.m.d");
}

function dateDMY($date=false) {
    if ($date) return date("d.m.Y", strtotime($date));
    else       return date("d.m.Y");
}

// Функция проверяет и преобразовывает в даты "2007-12-20","2007.12.20","2007/12/20" из "20.12.2007","12.2007,"2007" и наоборот
function preobrDate($dd) {
    $z = explode(' ', $dd);
    $z = str_replace(array(',','.',':','/','-'),'.',$z[0]);
    $z = explode('.', $z);
    if (mb_strlen($z[0]) == 4) {
        if (count($z) == 3) {
            if (checkdate(intval($z[1]),intval($z[2]),intval($z[0]))) {
                return($z[2].".".$z[1].".".$z[0]);
            }
            else return false;
        }
        elseif (count($z) == 2) {
            if (checkdate(intval($z[1]),1,intval($z[0]))) {
                return($z[1].".".$z[0]);
            }
            else return false;
        }
        elseif (count($z) == 1) {
            if (checkdate(1,1,intval($z[0]))) {
                return($z[0]);
            }
            else return false;
        }
    }
    else {
        if (count($z) == 3) {
            if (checkdate(intval($z[1]),intval($z[0]),intval($z[2]))) {
                return($z[2].".".$z[1].".".$z[0]);
            }
            else return false;
        }
        elseif (count($z) == 2) {
            if (checkdate(intval($z[0]),1,intval($z[1]))) {
                return($z[1].".".$z[0]);
            }
            else return false;
        }
        elseif (count($z) == 1) {
            if (checkdate(1,1,intval($z[0]))) {
                return($z[0]);
            }
            else return false;
        }
    }
    return false;
}

// Функция преобразовывает дату "2007-12-20" в "20.12.2007"
function dodate($dd, $nomes=0) {
    //pr($dd);
    $z=explode(' ', $dd);
    $z=explode('-', $z[0]);
    if (count($z)>1)
    {
        if ($nomes==0) {return($z[2].".".$z[1].".".$z[0]);} else {return($z[1].".".$z[0]);}
    } else return $dd;
}

// Функция преобразовывает дату в "2007-12-20" из "20.12.2007"
function dodate2($dd, $nomes=0) {
    $z=explode(' ', $dd);
    $z=explode('.', $z[0]);
    if (count($z)>1)
    {
        if ($nomes==0) {return($z[2]."-".$z[0]."-".$z[1]);} else {return($z[1].".".$z[0]);}
    } else return $dd;
}

//////////////////////////////////////////////////////////////////
function HaveAccess($part, $action, $user_access = -2) {
    global $userid;
    $Access = array();
    $Access['kvit_import'] = array('1'=>'01','2'=>'11','3'=>'11','4'=>'11','5'=>'11','6'=>'11');
    $Access['pachka']      = array('1'=>'01','2'=>'11','3'=>'11','4'=>'11','5'=>'11','6'=>'11');
    $Access['config']      = array('1'=>'01','2'=>'01','3'=>'01','4'=>'01','5'=>'11','6'=>'11');

    switch ($action) {
        case 'edit': $action = 0; break;
        case 'view': $action = 1; break;
        default: $action = 0;
    }

    if ($user_access == -2) $user_access = GetUserAccess($userid);
    if ($user_access == -1) return 0;
    if (isset($Access[$part])){
        return $Access[$part]["$user_access"]["$action"];
    }
    else{
        return 0;
    }
}

// Поиск абонентов
function GetSearchSpis (&$page_count, $kn, $nom, $fam, $im, $ot, $np, $street, $dom, $domadd, $kb, $order_num=0, $page_num, $schet="", $plomba="",$skolko=50, $dlt=0, $fulladdr='') {
    global $dblink;
    global $userres;

    $order = array('kn', 'nom', 'nas_punkt', 'street', 'dom1', 'kb', 'fam', 'im', 'ot');
    if ($order_num == 0) $order = array('kn', 'nom', 'nas_punkt', 'street', 'dom1', 'kb', 'fam', 'im', 'ot');
    if ($order_num == 1) $order = array('nom', 'kn', 'nas_punkt', 'street', 'dom1', 'kb', 'fam', 'im', 'ot');
    if ($order_num == 2) $order = array('nas_punkt', 'street', 'dom1', 'kb', 'kn', 'nom' , 'fam', 'im', 'ot');
    if ($order_num == 6) $order = array('fam', 'im', 'ot', 'kn', 'nom', 'nas_punkt', 'street', 'dom1', 'kb');
    $order_by = implode(",", $order);
    $order_by_desc = implode(" DESC,", $order);

    $sql="";
    if ($kn=="Все абоненты") {
        $sql= $sql. " and (a.kn<>'".$kn."')";
    } else {if ($kn!="")  { $sql= $sql. " and (a.kn='".$kn."')";}}
    if ($nom!="") { $sql= $sql. " and (a.nom='".$nom."')";}
    if ($fam!="") { $sql= $sql. " and (fam='".$fam."')";}
    if ($im!="")  { $sql= $sql. " and (im='".$im."')";}
    if ($ot!="")  { $sql= $sql. " and (ot='".$ot."')";}
    if ($np!="")  { $sql= $sql. " and (b.id='".$np."')";}
    if ($street!="") { $sql= $sql. " and (c.id='".$street."')";}
    if ($dom!="") { $sql= $sql. " and (dom='".$dom."')";}
    if ($domadd!="") { $sql= $sql. " and (domadd='".$domadd."')";}
    else {
        if ($dom!="") { $sql= $sql. " and (domadd='')";} // Для того чтобы искались без "/А"
    }
    if ($kb!="")  { $sql= $sql. " and (kb='".$kb."')";}
    if ($schet!="") { $sql= $sql. " and (a.kn=f.kn and a.nom=f.nom and f.zn like '".$schet."' and f.dlt=0 )"; $_mainsc=", ".CURRENT_RES."_mainsc f";}
    else {$_mainsc="";}

    if ($plomba!="") { $sql= $sql. " and ((a.plmbvu1 = '".$plomba."') or (a.plmbvu2 = '".$plomba."') or (a.plmbs0 = '".$plomba."') or ( a.kn+a.nom in (select kn+nom from ".CURRENT_RES."_mainsc where plmb = '".$plomba."')) ) ";}
    // поиск по адресу
    if (!empty($fulladdr)) { $sql.= " and (upper('/'+b.np +'/ /'+ ifnull(c.street, '') +'/ /'+ ifnull(e.dom, '') +'/'+ ifnull(e.domadd, '')+'-'+ifnull(a.kb, '')) like '".'%'.str_replace(' ', '%', strtoupper($fulladdr)).'%'."') "; }

    if ($sql!="") {$sql = "from ".CURRENT_RES."_main a, ".CURRENT_RES."_np b, ".CURRENT_RES."_street c, ".CURRENT_RES."_dom e".$_mainsc." where a.id_dom=e.id and e.id_ul=c.id and c.id_np=b.id and a.dlt=".$dlt." ".$sql;}
    else { $sql=stripslashes(GetSqlCookie()); }
    setcookie("sql_text", $sql, time()+60*60*24*30, "/" ); //30 days
    if ($sql=="") { return; }  // Заглушка если не установлены куки

    //===далее считаем, сколько всего записей в базе
	$count = dbOne("select count(*) ".$sql);
    if ($count>$skolko) {
        $x = abs($count - ($page_num-1) * $skolko);
        $page_count = ceil($count/$skolko);
        $sql = "select * from (select a.*, b.np as nas_punkt, c.street, CAST(e.dom as UNSIGNED) as dom1, e.domadd ".$sql." order by ".$order_by_desc." DESC LIMIT ".$x.") as tab1 order by ".$order_by." LIMIT ".$skolko;
     	$res = $dblink->query($sql);
    } else {
       $sql_sel = "select a.*, b.np as nas_punkt, c.street, e.domadd, CAST(e.dom as UNSIGNED) as dom1   ".$sql." order by ".$order_by;
    	$res = $dblink->query($sql_sel);
    }
    return $res;
}





function GetKvitttttttttttt($kn, $nom, $uch="1", $last=false, $lastdate= null) {
    $pokkv_old = 0;
    $pokaz_old = 0;
    $norma     = 0;
    $znak_2 = 0;
    $lstroka = ""; // Строка, в которую будет сохраняться расшифровка расчетов льготника
    $islg = false;

    $ukazatel_na_obeshaniya_chinovnikov = 0;
    global $dblink;
    global $FCentar;
    global $FTarhist;
    $kodes_dif3 = array(14,15,16); // Массив кодов диф.тарифов Т3
    //$FCentar = FAST_Centar();
    //pr($kn.$nom.$uch);
    if ( empty($FCentar)) $FCentar = FAST_Centar();
    $FTarhist = FAST_Tarif($kn, $nom, $uch);
    //pr($FTarhist);
if($lastdate){
    $strlastdate= " and (datekv<='$lastdate')";
} else {
    $strlastdate= '';
}
    if ($last) {
        $sql = "select id, sumkv, pokkv, penkv, meskv, imeskv, npachkv, oshkv, datekv, reason
            from ".CURRENT_RES."_kvit
            where knkv='".$kn."' and nomkv='".$nom."' and uchkv='".$uch."' and dlt=0 {$strlastdate} order by imeskv desc, YEAR(datekv) desc, MONTH(datekv) desc, DAY(datekv) desc, id desc";
    }
    else {
        $sql = "select a.* , p.fio
            from ".CURRENT_RES."_kvit a LEFT JOIN ".CURRENT_RES."_personal p ON a.tbn=p.tbn
            where a.knkv='".$kn."' and a.nomkv='".$nom."' and a.uchkv='".$uch."' and dlt=0 {$strlastdate} order by a.imeskv desc, YEAR(a.datekv) desc, MONTH(a.datekv) desc, DAY(a.datekv) desc, a.id desc";
    }
    $res = db_ExtQuery($dblink, $sql);
    //pr($res->Rows);
    if ($res->Num_Rows>0) {
        $numbel = $res->Num_Rows;
        $res->Rows[$numbel-1]["realsumkv"] = ""; // Добавлено 16.02.2012
        $res->Rows[$numbel-1]["sumdolg"] = ""; // Добавлено 14.04.2016
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

        $scmas=GetSc($kn, $nom, $uch);
        //pr($scmas);
        $pokaz=0;
        $sdolg=0;
        $sdolg_comment='';
        // ************************************************************************ //
        //     Расчет квитанций >>                                                  //
        // ************************************************************************ //
        if ($numbel>0) {$pokaz=$res->Rows[$numbel-1]["pokkv"];}
        // По квитанциям с конца
        //pr($FTarhist);
        $num_last_tarif = count($FTarhist);
        //pr($num_last_tarif,false);
        //????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
        // Основной цикл - бегаем по квитанциям
        //&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;&iquest;
        for ($k=$numbel-1; $k>=0;$k--) {
            $maxnorma     = 0;
            $days_mes = date('t', strtotime($res->Rows[$k]["imeskv"]));
            $clear_imes=$res->Rows[$k]["imeskv"];
            $res->Rows[$k]["imeskv"] = mb_substr($res->Rows[$k]["imeskv"],0,8).$days_mes;
            $days_tar = array(); // Массив из количества дней в месяце, что проработал тариф до изменения на другой
            $dates = array();    // Массив дат изменения тарифа за месяц
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
                                $pred_day = $td;
                                $a++;
                                $num_last_tarif = $num_last_tarif++;
                        }

                        $kodes_tar[0] = $FTarhist[$n]["idt"];
                    }
                }
            }
            //if (!isset($FTarhist[0]["idt"])) { $FTarhist[0]["idt"]=0; }
            if (!isset($kodes_tar[0]) and isset($FTarhist[0]["idt"])) { $kodes_tar[0] = $FTarhist[0]["idt"]; } elseif (!isset($kodes_tar[0]) and !isset($FTarhist[0]["idt"])) $kodes_tar[0]=0;
            $dates[$a] = $pred_day.".".date("m.Y", strtotime($res->Rows[$k]["imeskv"]));
            //pr($kodes_tar);
            $days_tar[$a] = ($days_mes-$pred_day+1)/$days_mes;
            // если выставлен долг, расчитывать квитанцию не надо ?
            if ($res->Rows[$k]["oshkv"]=="7") {
                $sdolg=$sdolg+$res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"];
                $sdolg_comment=$res->Rows[$k]["reason"];
                $res->Rows[$k]["realsumkv"] = $res->Rows[$k]["sumkv"];
                $skvhour = 0;
            }
            // иначе - расчет
            else {
                // выставлена отриц.сумма
                /*if ($sdolg<0){
                    $skvhour = 0;
                    $sdolg=$sdolg+$res->Rows[$k]["sumkv"];
                    $res->Rows[$k]["realsumkv"] = $res->Rows[$k]["sumkv"];
                    $res->Rows[$k]["sumkv"] = 0;
                    if ($sdolg>0){
                        $res->Rows[$k]["sumkv"]=$sdolg;
                        $sdolg=0;
                        //$res->Rows[$k]["penkv"]=0;
                    }
                }*/
                if ($sdolg>0){
                    $skvhour = 0;
                    $sdolg=round($sdolg-($res->Rows[$k]["sumkv"]-$res->Rows[$k]["penkv"]),2);
                    $res->Rows[$k]["realsumkv"] = $res->Rows[$k]["sumkv"];
                    $res->Rows[$k]["sumkv"] = $res->Rows[$k]["penkv"];
                    if ($sdolg<0){
                        $res->Rows[$k]["sumkv"]=round($res->Rows[$k]["penkv"]-$sdolg,2);
                        $sdolg=0;
                        //$res->Rows[$k]["penkv"]=0;
                    }
                }
                //else {*/
                    // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
                    // Великое РАЗДЕЛЕНИЕ ТАРИФОВ НА ДИФ, КАК ОБЕЩАЛИ ЧИНОВНИКИ ВРЕМЕННОЕ ДО 15-го года $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
                    // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
                    // Если дата установки больше 01.02.2013 и код тарифа больше 4-х - значит новые правила расчета
                    // июль 2019!!  для новых диф.тарифов код тарифа >13
                    if (in_array($kodes_tar[0], $kodes_dif3) or $kodes_tar[0]==13) {$ukazatel_na_obeshaniya_chinovnikov = 2;}
                    elseif ((strtotime($dates[0])>=strtotime('01.02.2013')) and ($kodes_tar[0] > 4)) {$ukazatel_na_obeshaniya_chinovnikov = 1;} // С этого момента всё остальные записи пойдут по новым диф тар.
                    else {$ukazatel_na_obeshaniya_chinovnikov = 0;}






                    if ($ukazatel_na_obeshaniya_chinovnikov == 1) {
                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // Рассчеты по НОВЫМ тарифам !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!






                        // $res->Rows - содержит квитанции
                        // $k - счетчик квитанций с конца
                        // $numbel - кол-во квитанций

                        $dates[0] = $pred_day.".".date("m.Y", strtotime($res->Rows[$k]["imeskv"]));
                        $days_tar[0] = ($days_mes-$pred_day+1)/$days_mes;

                        $skvhour = 0;
                        $star = 0;
                        $spokaz = 0;
                        $kol_days_norma = 0;
                        $stroka = "";
                        // Проверяем следующую квитанцию, может она = начало расчета - тогда ее рассчитать сразу без суммирования
                        if (($k==0) or ($k==$numbel-1) or ($clear_imes!=$res->Rows[$k-1]["imeskv"]) or ($res->Rows[$k]["oshkv"]=="8") or ($res->Rows[$k]["oshkv"] == "9") or ($res->Rows[$k-1]["oshkv"] == "8") or ($res->Rows[$k-1]["oshkv"]=="9")) // Это проверка на двойную проплату за месяц
                        {
                            $oplata=0;
                            $alltarif = Tarif($kn, $nom, $uch, $dates[0]);

                            //pr($alltarif);
                            if ($alltarif["idl"]) {$islg = true;} else {$islg = false;}
                            $kodtar = $alltarif["kod"]; // Код тарифа
                            // Для учета значности счетчика по началу расчета
                            if ($res->Rows[$k]["oshkv"]>="8") { $znak = mb_strlen($res->Rows[$k]["pokkv"]);$znak_2 = $znak;}
                            else {
                                if ($znak_2 == 0) {
                                    $znak = GetZnak($scmas, $res->Rows[$k]["datekv"]);
                                } else {$znak = $znak_2;$znak_2 = 0;}
                            }

                            $sem = $alltarif["tarhist"];
                            if ($alltarif["kod"]==9) {$fulltar = $alltarif["cena2"];}
                            else {$fulltar = $alltarif["cena"];}
                            if ($sem) {
                                if (count($sem) <= 0) {$q = 0;} // На тот случай, если оплатили на дату раньше, чем завели карточку
                                $res->Rows[$k]["mt"] = $sem["mt"];
                                switch ($sem["mt"]) {
                                    case "0":
                                        //$fulltar = $alltarif["cena"]; // Полная стоимость тарифа
                                        if ($alltarif["kod"]==9) {$fulltar = $alltarif["cena2"];} else {
                                            $fulltar = $alltarif["cena"];}
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
                                if ($semya == 0) {
                                    $semya = 1;
                                }
                            } else {
                                $semya = 1;
                                $semlg = 0;
                            }

                            if ($alltarif["kod"]==9) { $tar = round($alltarif["cena"]*0.5, 6); } else {
                            $tar = round($fulltar * ($semya - SemLg($alltarif["idl"])) / $semya, 6);}

                            if (($res->Rows[$k]["oshkv"] == "8") or (($res->Rows[$k]["oshkv"] == "9"))) {
                                $pokaz = $res->Rows[$k]["pokkv"]; // Если была замена счетчика - начинаем отсчет с первых показаний
                                // вставка Пинск
                                $znak = mb_strlen($res->Rows[$k]["pokkv"]);
                                $znak_2 = $znak;
                            }
                            //------------------------------------------------------------------
                            // Получаем список промежутков норм и их тарифов с учетом льготников
                            //pr($alltarif,false);
                            $mass_tar = Get_mass_tar($alltarif, $semya, $semlg);
                            //pr($k, false);
                            //pr($mass_tar,false);
                            //------------------------------------------------------------------
                            $mas_shifr = array();
                            // В цикле бегаем по массиву норм
                            $kvhour = 0;
                            $lkvhour = 0;
                            $sum = ($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]);//+$sdolg;
                            $j = -1;
                            for ($i = 0; $i < count($mass_tar); $i++) {
                                //pr($sum, false);
                                //pr($i,false);
                                //pr($mass_tar[$i]["tar"], false);
                                $k1 = $sum / $mass_tar[$i]["tar"];
                                //pr($k1, false);
                                //pr($mass_tar[$i]["p2"]-$mass_tar[$i]["p1"],false);
                                if ($k1 > ($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"])) {
                                    $kvhour = $kvhour + $mass_tar[$i]["p2"] - $mass_tar[$i]["p1"];
                                    if ($mass_tar[$i]["lg"] == 1) {
                                        $lkvhour = $lkvhour + $mass_tar[$i]["p2"] - $mass_tar[$i]["p1"];

                                        $j++;
                                        $mas_shifr[$j]["tar"] = round($mass_tar[$i]["tar"] * $semya / ($semya * 2 - $semlg), 6);
                                        $mas_shifr[$j]["kvt"] = round(($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"]) / $semya * $semlg,1);
                                        $mas_shifr[$j]["sum"] = round($mas_shifr[$j]["kvt"] * $mas_shifr[$j]["tar"],6);
                                        $mas_shifr[$j]["lg"] = 1;
                                        $sum = $sum - $mas_shifr[$j]["sum"];

                                        if ($semya > $semlg) {
                                            $j++;
                                            $mas_shifr[$j]["tar"] = round($mass_tar[$i]["tar"] * $semya / ($semya * 2 - $semlg) * 2, 6);
                                            $mas_shifr[$j]["kvt"] = round(($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"]) / $semya * ($semya - $semlg),1);
                                            $mas_shifr[$j]["sum"] = round($mas_shifr[$j]["kvt"] * $mas_shifr[$j]["tar"],6);
                                            $sum = $sum - $mas_shifr[$j]["sum"];
                                        }
                                    } else {
                                        if ($j > -1 && $mas_shifr[$j]["tar"] == round($mass_tar[$i]["tar"], 6)) {
                                            $mas_shifr[$j]["kvt"] += round($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"],1);
                                            $mas_shifr[$j]["sum"] += round(($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"]) * $mass_tar[$i]["tar"],6);
                                            $sum = $sum - round(($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"]) * $mass_tar[$i]["tar"],6);
                                        } else {
                                            $j++;
                                            $mas_shifr[$j]["tar"] = round($mass_tar[$i]["tar"], 6);
                                            $mas_shifr[$j]["kvt"] = round($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"],1);
                                            $mas_shifr[$j]["sum"] = round(($mass_tar[$i]["p2"] - $mass_tar[$i]["p1"]) * $mass_tar[$i]["tar"],6);
                                            $sum = $sum - $mas_shifr[$j]["sum"];
                                        }
                                        //pr($mas_shifr[$i], false);
                                    }
                                } else {
                                    $kvhour = $kvhour + $k1;
                                    if ($mass_tar[$i]["lg"] == 1) {
                                        $lkvhour = $lkvhour + $k1;

                                        $j++;
                                        $mas_shifr[$j]["tar"] = round($mass_tar[$i]["tar"] * $semya / ($semya * 2 - $semlg), 6);
                                        $mas_shifr[$j]["kvt"] = round($k1 / $semya * $semlg,1);
                                        $mas_shifr[$j]["sum"] = round($mas_shifr[$j]["kvt"] * $mas_shifr[$j]["tar"],6);
                                        $mas_shifr[$j]["lg"] = 1;
                                        $sum = $sum - $mas_shifr[$j]["sum"];
                                        if ($semya > $semlg) {
                                            $j++;
                                            $mas_shifr[$j]["tar"] = round($mass_tar[$i]["tar"] * $semya / ($semya * 2 - $semlg) * 2, 6);
                                            $mas_shifr[$j]["kvt"] = round($k1 / $semya * ($semya - $semlg),1);
                                            $mas_shifr[$j]["sum"] = round($mas_shifr[$j]["kvt"] * $mas_shifr[$j]["tar"],6);
                                            $sum = $sum - $mas_shifr[$j]["sum"];
                                        }
                                        break;
                                    } else {
                                        if ($j > -1 && $mas_shifr[$j]["tar"] == round($mass_tar[$i]["tar"], 6)) {
                                            $mas_shifr[$j]["kvt"] += round($k1,1);
                                            $mas_shifr[$j]["sum"] += round($k1 * $mass_tar[$i]["tar"],6);
                                            $sum = $sum - round($k1 * $mass_tar[$i]["tar"],6);
                                            break;
                                        } else {
                                            $j++;
                                            $mas_shifr[$j]["tar"] = round($mass_tar[$i]["tar"], 6);
                                            $mas_shifr[$j]["kvt"] = round($k1,1);
                                            $mas_shifr[$j]["sum"] = round($k1 * $mass_tar[$i]["tar"],6);
                                            $sum = $sum - $mas_shifr[$j]["sum"];
                                            //pr($mas_shifr[$i], false);
                                            break;
                                        }
                                    }
                                }
                            }
                            $mas_shifr[$j]["sum"] += $sum;
                            for ($i = count($mas_shifr) - 1; $i > 1; $i--) {
                                if ($mas_shifr[$i]["kvt"] == 0) {
                                    $mas_shifr[$i - 1]["sum"] += $mas_shifr[$i]["sum"];
                                    $mas_shifr[$i]["sum"] = 0;
                                }
                            }
                            //pr($mas_shifr,false);





                            $res->Rows[$k]["mas_shifr"] = $mas_shifr;
                            //$skvhour = $kilovaty;
                            if ($k != $numbel - 1) { // Если первая квитанция - то считать ее как начало расчета
                                $skvhour = $skvhour + $kvhour;
                                $star = $star + $tar;
                            }
                            $lstroka = "||||||" . round($lkvhour / $semya * $semlg) . "|";
                            $stroka[] = $lstroka;
                            //$norma= round($lkvhour/$semya*$semlg);
                            $norma = round($lkvhour / $semya);
                            // Эта строка для вывода реальной суммы квитанции, т.к. другая нужна для расчетов и она суммируется
                            if ($k != 0) {
                                $res->Rows[$k - 1]["realsumkv"] = $res->Rows[$k - 1]["sumkv"];
                            }
                        } else {
                            // Суммируем с предыдущей, если две проплаты за месяц
                            $res->Rows[$k - 1]["realsumkv"] = $res->Rows[$k - 1]["sumkv"];
                            $res->Rows[$k - 1]["sumkv"] = $res->Rows[$k - 1]["sumkv"] + $res->Rows[$k]["sumkv"];
                            $res->Rows[$k - 1]["penkv"] = $res->Rows[$k - 1]["penkv"] + $res->Rows[$k]["penkv"];
                        }

                    }
                    //////////////////////////////////////////
                    else {
                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // Рассчеты по СТАРЫМ тарифам !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        //pr($days_tar, false);
                        $skvhour = 0;
                        $star = 0;
                        $spokaz = 0;
                        $kol_days_norma = 0;
                        $stroka = "";
                        // Проверяем следующую квитанцию, может она = начало расчета - тогда ее рассчитать сразу без суммирования
                        if (($k == 0) or ($k == $numbel - 1) or ($clear_imes != $res->Rows[$k - 1]["imeskv"]) or ($res->Rows[$k]["oshkv"] == "8") or ($res->Rows[$k]["oshkv"] == "9") or ($res->Rows[$k - 1]["oshkv"] == "8") or ($res->Rows[$k - 1]["oshkv"] == "9")) // Это проверка на двойную проплату за месяц
                        {
                            for ($n = 0; $n < count($days_tar); $n++) {
                                $oplata = 0;
                                $alltarif = Tarif($kn, $nom, $uch, $dates[$n]);

                                if ($alltarif["idl"]) {
                                    $islg = true;
                                } else {
                                    $islg = false;
                                }
                                $kodtar = $alltarif["kod"]; // Код тарифа
                                // Для учета значности счетчика по началу расчета
                                if ($res->Rows[$k]["oshkv"] >= "8") {
                                    $znak = mb_strlen($res->Rows[$k]["pokkv"]);
                                    $znak_2 = $znak;
                                } else {
                                    if ($znak_2 == 0) {
                                        $znak = GetZnak($scmas, $res->Rows[$k]["datekv"]);
                                    } else {
                                        $znak = $znak_2;
                                        $znak_2 = 0;
                                    }
                                }

                                $sem = $alltarif["tarhist"];
                                $fulltar = $alltarif["cena"];
                                if ($sem) {
                                    $q = count($days_tar) - $n - 1;  // Вычисляем какой из тарифов за этот месяц взять (если их 2)
                                    if (count($sem) <= $q) {
                                        $q = 0;
                                    } // На тот случай, если оплатили на дату раньше, чем завели карточку
                                    $res->Rows[$k]["mt"] = $sem["mt"];
                                    switch ($sem["mt"]) {
                                        case "0":
                                            $fulltar = $alltarif["cena"]; // Полная стоимость тарифа
                                            break;
                                        case "1":
                                            $fulltar = $alltarif["cenamax"];
                                            break;
                                        case "2":
                                            $fulltar = $alltarif["cenamin"];
                                            break;
                                        case "3":
                                            $fulltar = $alltarif["cena2"];
                                            break;
                                    }
                                    $semya = $sem["semya"];
                                    $semlg = $sem["semlg"];
                                    if ($semya == 0) {
                                        $semya = 1;
                                    }
                                } else {
                                    $semya = 1;
                                    $semlg = 0;
                                }
                                $tar = round($fulltar * ($semya - SemLg($alltarif["idl"])) / $semya, 6);
                                // для Пинска   $tar = round($fulltar * ($semya-$semlg*0.5)/$semya,2);
                                if (($res->Rows[$k]["oshkv"] == "8") or (($res->Rows[$k]["oshkv"] == "9"))) {
                                    $pokaz = $res->Rows[$k]["pokkv"]; // Если была замена счетчика - начинаем отсчет с первых показаний
                                    // вставка Пинск
                                    $znak = mb_strlen($res->Rows[$k]["pokkv"]);
                                    $znak_2 = $znak;
                                }
                                // Если есть льготник
                                $lstroka = $dates[$n] . "|";
                                $lstroka = $lstroka . $fulltar . "|" . $tar . "|";
                                $lstroka = $lstroka . round($days_tar[$n] * $days_mes) . "|";
                                //echo "$tar - $fulltar norma:".$alltarif["norma"]." <br>";
                                if ($tar != $fulltar) {
                                    if ($alltarif["norma"] > 0) { // Если есть норма
                                        $kol_days_norma = $kol_days_norma + $days_tar[$n];
                                        // Узнаем количество учетов для нормы
                                        if (isset($sem["mt"])) {
                                            if ($sem["mt"] > 0) {
                                                $uch_count=in_array($kodtar,$kodes_dif3)?3:2;
                                            } else {
                                                $uch_count = 1;
                                            }
                                        } else {
                                            $uch_count = 1;
                                        }

                                        $alltarif["norma"] = $alltarif["norma"] / $uch_count;
                                        $lstroka = $lstroka . round($alltarif["norma"] * $days_tar[$n],2) . "|";
                                        $normsum = $alltarif["norma"] * $semya * $tar;

                                        // Оплата по норме
                                        if ($normsum >= ($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"])) { // Если вписываемся в норму
                                            if ($tar != 0) {
                                                $kvhour = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]) / $tar);
                                            } else {
                                                $kvhour = 0;
                                            }
                                            $aa = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]) * $days_tar[$n],6);
                                            $lstroka = $lstroka . $aa . "|";
                                            if ($tar != 0) {
                                                $aa = round($aa / $tar,6);
                                            }
                                            $lstroka = $lstroka . $aa . "|";
                                        } else {// Если выше нормы
                                            if ($tar != 0) {
                                                $kvhour = round($normsum / $tar);
                                            } else {
                                                $kvhour = 0;
                                            }
                                            $lstroka = $lstroka . round($normsum * $days_tar[$n],6) . "|";
                                            if ($tar != 0) {
                                                $lstroka = $lstroka . round($normsum * $days_tar[$n] / $tar) . "|";
                                            } else {
                                                $lstroka = $lstroka . "|";
                                            }
                                            $kvhour = $kvhour + round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"] - $normsum) / $fulltar);
                                            $aa = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"] - $normsum) * $days_tar[$n],2);
                                            $lstroka = $lstroka . $aa . "|";
                                            $aa = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"] - $normsum) * $days_tar[$n] / $fulltar,2);
                                            $lstroka = $lstroka . $aa . "|";
                                        }
                                        $norma = $alltarif["norma"];
                                        $maxnorma = $norma;
                                    } else { // Если нормы нет

                                        $norma = 0;
                                        $normsum = 0;
                                        if ($tar != 0) {
                                            $kvhour = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]) / $tar);
                                        } else {
                                            $kvhour = 0;
                                        }
                                    }
                                } else { // Если нет льготника
                                    if ($fulltar != 0) { // На тот случай если тарифа еще нет или он 0
                                        $kvhour = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]) / $fulltar);
                                    } else {
                                        $kvhour = 0;
                                    }
                                    $tar = $fulltar;
                                    $norma = 0;
                                    if ($res->Rows[$k]["oshkv"] == 0) { // Если не начало расчета, то формируем строку
                                        $aa = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]) * $days_tar[$n],2);
                                        $lstroka = $lstroka . "|||" . $aa . "|";
                                        $aa = round(($res->Rows[$k]["sumkv"] - $res->Rows[$k]["penkv"]) * $days_tar[$n] / $tar,2);
                                        $lstroka = $lstroka . $aa . "|";
                                    }
                                }
                                if ($k != $numbel - 1) { // Если первая квитанция - то считать ее как начало расчета
                                    $skvhour = $skvhour + $kvhour * $days_tar[$n];
                                    $star = $star + $tar * $days_tar[$n];
                                }
                                $stroka[] = $lstroka;
                            }
                            // Эта строка для вывода реальной суммы квитанции, т.к. другая нужна для расчетов и она суммируется
                            if ($k != 0) {
                                $res->Rows[$k - 1]["realsumkv"] = $res->Rows[$k - 1]["sumkv"];
                            }
                        } else {
                            // Суммируем с предыдущей, если две проплаты за месяц
                            $res->Rows[$k - 1]["realsumkv"] = $res->Rows[$k - 1]["sumkv"];
                            $res->Rows[$k - 1]["sumkv"] = $res->Rows[$k - 1]["sumkv"] + $res->Rows[$k]["sumkv"];
                            $res->Rows[$k - 1]["penkv"] = $res->Rows[$k - 1]["penkv"] + $res->Rows[$k]["penkv"];
                        }
                        //pr($star.'  '.$fulltar,false);
                    }
                //}
            }
            /// долг

            /// end долг
            $res->Rows[$k]["islg"] = $islg; // Есть ли льгота в этой квитанции
            $skvhour = round($skvhour);
            $pokaz = $pokaz + $skvhour;
            if ($pokaz>=pow(10, $znak)) {$pokaz=$pokaz-pow(10, $znak);}  // Переход через ноль
            if (mb_strlen($pokaz)>$znak) {$pokaz = $pokaz % pow(10, $znak);} // Отсекаем старшие разряды
            $res->Rows[$k]["sumdolg"] = $sdolg;
            if ($sdolg>0){$res->Rows[$k]["reason"] = $sdolg_comment;}
            $res->Rows[$k]["imeskv"] = dodate($res->Rows[$k]["imeskv"],1);
            $res->Rows[$k]["datekv"] = dodate($res->Rows[$k]["datekv"]);
            $res->Rows[$k]["kvhour"] =$skvhour;
            $res->Rows[$k]["tarif"] = round($star,6);
            $res->Rows[$k]["pokaz"]= ForZn(round($pokaz,0), $znak);
            //echo $res->Rows[$k]["pokaz"].' '.$znak.'<br>';
            $res->Rows[$k]["norma"] = $maxnorma*$semya;
            $res->Rows[$k]["norma_lg"] = $norma*$semlg;
            //$res->Rows[$k]["norma_lg"] = $norma;
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



?>