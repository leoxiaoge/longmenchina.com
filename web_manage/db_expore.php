<?php
require './include/dbconn.php';
//获取表的名称
function list_tables($database)
{
    $rs = mysql_list_tables($database);
    $tables = array();
    while ($row = mysql_fetch_row($rs)) {
        $tables[] = $row[0];
    }
    mysql_free_result($rs);
    return $tables;
}
//导出数据库
function dump_table($table, $fp = null)
{
    $need_close = false;
    if (is_null($fp)) {
        $fp = fopen($table . '.sql', 'w');
        $need_close = true;
    }
	$a=mysql_query("show create table `{$table}`");
	$row=mysql_fetch_assoc($a);fwrite($fp,$row['Create Table'].';');//导出表结构
    $rs = mysql_query("SELECT * FROM `{$table}`");
    while ($row = mysql_fetch_row($rs)) {
        fwrite($fp, get_insert_sql($table, $row));
    }
    mysql_free_result($rs);
    if ($need_close) {
        fclose($fp);
    }
}
//导出表数据
function get_insert_sql($table, $row)
{
    $sql = "INSERT INTO `{$table}` VALUES (";
    $values = array();
    foreach ($row as $value) {
        $values[] = "'" . mysql_real_escape_string($value) . "'";
    }
    $sql .= implode(', ', $values) . ");";
    return $sql;
}
///////************************///////下面正式开始//////***************************///////
$database=$dbname;//数据库名

//设置日期为备份文件名
date_default_timezone_set('PRC'); 
$t_name = date("Ymd_His");

$options=array(
    'hostname' => $dbhost,//ip地址
    'charset' => 'utf8',//编码
    'filename' => "".$database.$t_name.'.sql',//文件名
    'username' => $dbuser,
    'password' => $dbpw
);
mysql_connect($options['hostname'],$options['username'],$options['password'])or die("不能连接数据库!");
mysql_select_db($database) or die("数据库名称错误!");
mysql_query("SET NAMES '{$options['charset']}'");
$tables = list_tables($database);
$filename = sprintf($options['filename'],$database);
$fp = fopen($filename, 'w');
foreach ($tables as $table) {
    dump_table($table, $fp);
}
fclose($fp);
//下载sql文件
$file_name=$options['filename'];
header("Content-type:application/octet-stream");
header("Content-Disposition:attachment;filename=".$file_name);
readfile($file_name);
//删除服务器上的sql文件
unlink($file_name);
exit("备份成功,请到根目录的uploadfile/dbakup文件夹下取备份！");
?>
