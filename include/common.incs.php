<?php
//********************************************
//	作者：Whj
//	时间：2010-5-1
//	作用：
//********************************************

//定义PHP环境
error_reporting(0);
set_magic_quotes_runtime(0);

//设置开始的时间
//$mtime[0] 为微秒 $mtime[1]为UNIX时间戳 
$mtime = explode(' ', microtime());
$sys_starttime = $mtime[1] + $mtime[0];

//销毁一些不用的服务器变量
unset($_REQUEST, $HTTP_ENV_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_POST_FILES, $HTTP_COOKIE_VARS);

//版权定义
define('IN_COPY', TRUE);
define('WEB_ROOT', substr(dirname(__FILE__), 0, -7));		//定义WEB的路径,,,,,磁盘绝对路径! 
define('COPY_CN','#');
define('COPY_EN',' Information Industry Co.,Ltd.');

//通用性
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}

//引入 配置文件 语言文件 全局函数库!
require_once WEB_ROOT.'./config.inc.php';
require_once WEB_ROOT.'./include/global.func.php';

header('Content-Type:text/html;charset='.$CONFIG[charset].'');

//如果浏览者是一个robot
define('ISROBOT', getrobot());
if(defined('NOROBOT') && ISROBOT) {
	exit(header("HTTP/1.1 403 Forbidden"));
}

//过滤提交的变量用的，提高安全性的用法
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
isset($_REQUEST['GLOBALS']) && exit('Access Error');
foreach(array('_COOKIE', '_POST', '_GET') as $_request) {
	foreach($$_request as $_key => $_value) {
		$_key{0} != '_' && $$_key = daddslashes($_value);
	}
}
(!MAGIC_QUOTES_GPC) && $_FILES = daddslashes($_FILES);
   
//设置时区
if(function_exists('date_default_timezone_set')) date_default_timezone_set($CONFIG['timezone']);

//获取客户端IP
if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
	$PHP_IP = getenv('HTTP_CLIENT_IP');
}elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
	$PHP_IP = getenv('HTTP_X_FORWARDED_FOR');
}elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
	$PHP_IP = getenv('REMOTE_ADDR');
}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
	$PHP_IP = $_SERVER['REMOTE_ADDR'];
}
preg_match("/[\d\.]{7,15}/", $PHP_IP, $ipmatches);
$PHP_IP = $ipmatches[0] ? $ipmatches[0] : 'unknown';

//获得当前时间
$PHP_TIME = time();
$PHP_DATE = date('Y-m-d H:i:s');


//获得自己脚本的文件名  如: /test.php 
$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
//获得查询字符串 也就是 xxx.php?4982394  问号后面的内容
$PHP_QUERYSTRING = $_SERVER['QUERY_STRING'];
//获得服务器的域名  不包括 http://
$PHP_DOMAIN = $_SERVER['SERVER_NAME'];
//链接到当前页面的前一页面的 URL 地址。不是所有的用户代理（浏览器）都会设置这个变量，而且有的还可以手工修改 HTTP_REFERER。因此，这个变量不总是正确真实的。
$PHP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
//获得http端口
$PHP_SCHEME = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
$PHP_PORT = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
//得到完整的PHPCMS的URL地址
$PHP_SITEURL = $PHP_SCHEME.$PHP_DOMAIN.$PHP_PORT.WEB_PATH;
//得到当前网页的URL地址
$PHP_URL = $PHP_SCHEME.$PHP_DOMAIN.$PHP_PORT.$PHP_SELF.($PHP_QUERYSTRING ? '?'.$PHP_QUERYSTRING : '');
$PHP_FILE = $PHP_SELF.($PHP_QUERYSTRING ? '?'.$PHP_QUERYSTRING : '');

$RrPHP_FILE=$_SERVER['HTTP_REFERER'];


unset($CONFIG['timezone'], $CONFIG['cachedir']);

//检查gzip是不是打开了，打开就用ob_gzhandler，没有就用ob_start
if($gzipcompress && function_exists('ob_gzhandler')) {
	ob_start('ob_gzhandler');
} else {
	$gzipcompress = 0;
	ob_start();
}
@session_start();

//平衡负载*
if(!empty($loadctrl) && substr(PHP_OS, 0, 3) != 'WIN') {
	if($fp = @fopen('/proc/loadavg', 'r')) {
		list($loadaverage) = explode(' ', fread($fp, 6));
		fclose($fp);
		if($loadaverage > $loadctrl) {
			header("HTTP/1.0 503 Service Unavailable");
			include WEB_ROOT.'./include/serverbusy.htm';
			exit();
		}
	}
}

//设置一个 $web_auth_key，md5加密
$web_auth_key = md5($_SERVER['HTTP_USER_AGENT']);

//打开数据连接
require_once WEB_ROOT.'./include/db.inc.php';

//每页显示的信息数
$PageSize=10;

//定义翻页初始值
$StartPage=$_GET['StartPage'];
if(empty($StartPage)) $StartPage=0;

//内容翻页页码
$ContentPage=$_GET['ContentPage'];
if(empty($ContentPage)) $ContentPage=0;

//打开公用信息
require_once WEB_ROOT.'./include/function.php';

//读取网站基本配置信息
$sqls="select * from `{$tablepre}config` order by id asc";
$results=$db->sql_query($sqls);
while($bds=$db->sql_fetchrow($results)){
	$sys[]=$bds;
}

  


$System_copyright=$sys[18]['varvalue'];
$System_contact=$sys[19]['varvalue'];
$System_img1=$sys[20]['varvalue'];
$System_img2=$sys[21]['varvalue'];
$System_img3=$sys[22]['varvalue'];
$System_hotsearch=$sys[23]['varvalue'];
$System_phone=$sys[24]['varvalue'];
$System_qq=$sys[25]['varvalue'];
$System_email=$sys[26]['varvalue'];

$System_Showinfo=$sys[12]['varvalue'];
$System_sitename=$sys[13]['varvalue'];
$System_siteurl=$sys[14]['varvalue'];
$System_seotitle=$sys[15]['varvalue'];
$System_seokeywords=$sys[16]['varvalue'];
$System_seodescription=$sys[17]['varvalue'];
$System_visits=$sys[4]['varvalue'];
$System_share=$sys[5]['varvalue'];
$System_kefu=$sys[6]['varvalue'];
$System_Filetype=$sys[0]['varvalue'];
$System_Filesize=$sys[1]['varvalue'];
$System_Pictype=$sys[2]['varvalue'];
$System_Picsize=$sys[3]['varvalue'];
$srt=$sys[7]['varvalue'];
$System_Isstate=$sys[9]['varvalue'];
$System_isqq=$sys[10]['varvalue'];
$System_ismenu=$sys[11]['varvalue'];
 
$sqlq="select * from `{$tablepre}config` where typeid=4";
$resultq=$db->sql_query($sqlq);
while($bdq=$db->sql_fetchrow($resultq)){
	$tag[]=$bdq;
}
unset($sqlq,$resultq,$bds,$bdq);
 
//$System_qqcount=get_count("qq");
?>