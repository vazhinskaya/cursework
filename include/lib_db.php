<?php
// Соединение с базой данных MySQL
function dbConnect () {
    global $db_name;
    global $db_user;
    global $db_pass;
    global $db_host;
    global $db_port;
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass,
      array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
      ));
    return $pdo;
}

// Соединение с базой данных счетчиков
function dbConnect_suee () {
    global $db_name_suee;
    global $db_user_suee;
    global $db_pass_suee;
    global $db_host_suee;
    global $db_port_suee;
    $pdo = new PDO("mysql:host=$db_host_suee; dbname=$db_name_suee", $db_user_suee, $db_pass_suee,
      array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
      ));
    return $pdo;
}

function get_input($key, $type='string') {
    global $_GET;
    global $_POST;
    $var = '';

    if    (isset($_POST[$key]))  $var = $_POST[$key];
    elseif (isset($_GET[$key]))  $var = $_GET[$key];
    if ($type == 'int')     return (int)$var;
    if ($type == 'float')   return (float)$var;
    if ($type == 'bool')    return ((mb_strlen($var) > 0) ? true : false);
    if ($type == 'dateYMD') {
      if ($var=='') return 'NULL';
      else return dateYMD($var);
    }
    if (is_array($var))   return $var;
    else                  return stripslashes($var);
}

// Начало Транзакции ********************************************** >>
function dbBeginTransaction() {
    global $dblink;
    global $TransactionError;
    $TransactionError = 0;
    $dblink->BeginTransaction();
}

// Конец Транзакции ********************************************** >>
function dbEndTransaction() {
    global $dblink;
    global $TransactionError;
    if ($TransactionError == 1) $dblink->RollBack();
    else  $dblink->Commit();
}

function dbQuery($query) {
		global $dblink;
    global $TransactionError;

    $result = $dblink->query($query);
    if ( !$result ){
        $TransactionError = 1;
        pr('<div style="background:#999;color:#F0F0F0;height:100px"><h1>ОШИБКА!</h1>Ошибка при запросе: '.$query.'</div>', false);
    }
    return $result;
}

function dbFetchArray($dbQuery) {
  return $dbQuery->fetchAll(PDO::FETCH_BOTH);
}

function dbFetchAssoc($dbQuery) {
  return $dbQuery->fetch(PDO::FETCH_ASSOC);
}

function dbFetchRow($dbQuery) {
  return $dbQuery->fetch(PDO::FETCH_NUM);
}

function dbRowsCount($dbQuery) {
  return $dbQuery->rowCount();
}

function dbOne($sql_query, $type = 'string') {
  global $dblink;
	$query = $dblink->prepare($sql_query);
  $query->execute();
	if ($type == 'count')
		return $query->rowCount();

	if ($query->rowCount()){
		$v = $query->fetchColumn();
		if ($type == 'string')
				return $v;
		if ($type == 'int')
				return (int)$v;
		if ($type == 'float')
				return (float)$v;
	}
	if ($type == 'string')
		return '';
	if ($type == 'int')
		return 0;
	if ($type == 'float')
		return 0;
}

function dbPerform($table, $data, $action = 'insert', $parameters = '') {
  global $dblink;
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into '.$table.' (';
      $query2 = '';
      foreach ($data as $column => $value) {
        $query  .= $column.', ';
        if ($value == 'NULL')  $query2 .= $value.', ';
        else  $query2 .= '\''.str_replace("'",'"',$value).'\', ';
      }
      $query = mb_substr($query, 0, -2).') values (';
      $query .= mb_substr($query2, 0, -2).')';
    }
    elseif ($action == 'update') {
      $query = 'update '.$table.' set ';
      foreach ($data as $column => $value) {
        if ($value == 'NULL')  $query .= $column.' = '.$value.', ';
        else  $query .= $column.' = \''.str_replace("'",'"',$value).'\', ';
      }
      $query = mb_substr($query, 0, -2).' where '.$parameters;
    }
    return $dblink->query($query);
}
?>