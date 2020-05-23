<?php
////////////////////////////////////////////////////////////////////////////////
function GetUserFesCookie() {
    global $_COOKIE;
    $userfes = 'Пружанские ЭС';
    return (isset($_COOKIE['userfes']) && $_COOKIE['userfes']) ? strtoupper($_COOKIE['userfes']) : strtolower($userfes);
}

////////////////////////////////////////////////////////////////////////////////
function GetUserFesIdCookie() {
    global $_COOKIE;
    $userfesid = 52;
    return (isset($_COOKIE['userfesid']) && $_COOKIE['userfesid']) ? strtoupper($_COOKIE['userfesid']) : strtolower($userfesid);
}

////////////////////////////////////////////////////////////////////////////////
function GetUserResCookie() {
    global $_COOKIE;
	$userres = "PRU";
    return (isset($_COOKIE['userres']) && $_COOKIE['userres']) ? strtoupper($_COOKIE['userres']) : strtolower($userres);
}

////////////////////////////////////////////////////////////////////////////////
function GetUserResIdCookie() {
    global $_COOKIE;
    $userresid = 19;
    return (isset($_COOKIE['userresid']) && $_COOKIE['userresid']) ? strtoupper($_COOKIE['userresid']) : strtolower($userresid);
}

////////////////////////////////////////////////////////////////////////////////
function GetUserTbnCookie() {
    global $_COOKIE;
    $userid = "-1";
    return (isset($_COOKIE['userid']) && $_COOKIE['userid']) ? strtolower($_COOKIE['userid']) : strtolower($userid);
}

////////////////////////////////////////////////////////////////////////////////
function GetLastCookie() {
	global $dblink;
	global $_COOKIE;

	$lastfes = (isset($_COOKIE['lastfes']) && $_COOKIE['lastfes']) ? strtoupper($_COOKIE['lastfes']) : 'Пружанские ЭС' ;
    $lastfesid = (isset($_COOKIE['lastfesid']) && $_COOKIE['lastfesid']) ? strtoupper($_COOKIE['lastfesid']) : 52 ;
	$lastres = (isset($_COOKIE['lastres']) && $_COOKIE['lastres']) ? strtoupper($_COOKIE['lastres']) : 'PRU' ;
    $lastresid = (isset($_COOKIE['lastresid']) && $_COOKIE['lastresid']) ? strtoupper($_COOKIE['lastresid']) : 19 ;
	$lastuser = (isset($_COOKIE['lastuser']) && $_COOKIE['lastuser']) ? strtoupper($_COOKIE['lastuser']) : '0000' ;
    $sql = $dblink->query("Select count(*) as countt from {$lastres}_personal where tbn like '{$lastuser}'");
	$rows = $sql->fetchAll();
	if ($rows[0]['countt']) return array($lastfes, $lastfesid, $lastres, $lastresid, $lastuser);
    else return array($lastfes, $lastfesid, $lastres, $lastresid, '0000');
}

////////////////////////////////////////////////////////////////////////////////
function SetUserCookie($userfes="Пружанские ЭС", $userfesid=52, $userres="PRU", $userresid=19, $userid) {
    setcookie("userfes", $userfes, time()+60*60*12*1, "/");   // 8 часов
    setcookie("userfesid",$userfesid,time()+60*60*12*1, "/"); // 8 часов
    setcookie("userres", $userres, time()+60*60*12*1, "/");   // 8 часов
    setcookie("userresid",$userresid,time()+60*60*12*1, "/"); // 8 часов
    setcookie("userid",  $userid,  time()+60*60*12*1, "/");   // 8 часов
    setcookie("lastfes", $userfes, time()+60*60*12*200, "/"); // forever
    setcookie("lastfesid",$userfesid,time()+60*60*12*200,"/");// forever
    setcookie("lastres", $userres, time()+60*60*12*200, "/"); // forever
    setcookie("lastresid",$userresid,time()+60*60*12*200,"/");// forever
    setcookie("lastuser",$userid,  time()+60*60*12*200, "/"); // forever
    return true;
}

////////////////////////////////////////////////////////////////////////////////
function ClearUserCookie() {
    setcookie("userid", "", time()-60*60*24*1, "/");
    return true;
}

////////////////////////////////////////////////////////////////////////////////
function GetUserAccess($userid) {
	global $dblink;
	if ($userid < '0000' ) return 0;
	$sql = $dblink->query("select kat from ".CURRENT_RES."_personal where tbn='".$userid."'");
	$rows = $sql->fetchAll();
	if ($sql->rowCount() > 0) return($rows[0]["kat"]);
	else                      return false;
}

////////////////////////////////////////////////////////////////////////////////
function GetUserName($userid) {
	global $dblink;
	global $userres;
	if ($userid < '0000' ) return '';
	$sql = $dblink->query("select fio from {$userres}_personal where tbn='".$userid."'");
	$rows = $sql->fetchAll();
	if ($sql->rowCount()>0) return($rows[0]["fio"]);
	else                    return false;
}

////////////////////////////////////////////////////////////////////////////////
function GetFesForLogin() {
    global $dblink;
    $sql = $dblink->query("select id, fes from _fes order by id");
    return ($sql->fetchAll());
}

////////////////////////////////////////////////////////////////////////////////
function GetResForLogin($fes = 'Пружанские ЭС') {
    global $dblink;
    $sql = $dblink->query("select max(id), type_res, knaim, id_fes from _res
    	                   where id_fes=(select id from _fes where fes='".$fes."')
    	                   group by knaim, type_res, id_fes
    	                   order by knaim");
    return ($sql->fetchAll());
}

////////////////////////////////////////////////////////////////////////////////
function GetUsers($res = 'PRU') {
    global $dblink;
    $sql = $dblink->query("select * from {$res}_personal order by fio");
    return($sql->fetchAll());
}
?>