<?php
/********************************************
/	
/	数据库类型选择。
 ********************************************/


require_once(WEB_ROOT . './include/mysql4.php');
 
// Make the database connection.
$db = new sql_db($dbhost, $dbuser, $dbpw, $dbname, $dbconnect);
if(!$db->db_connect_id){
   die( "不能链接到数据库！");
}
$dbms = $dbhost = $dbuser = $dbpw = $dbname = $dbconnect = NULL;
?>