<?php
//********************************************//	作者：Whj//	时间：2012-2-06//	作用：网站配置//********************************************// 以下变量请根据空间商提供的账号参数修改,如有疑问,请联系服务器提供商
$dbms = 'mysql4';					// 数据库类型,此项不可修改
$dbhost = 'localhost';				// 数据库服务器
$dbuser = 'root';					// 数据库用户名//yanhuang520
$dbpw = 'Luo126001aa';					// 数据库密码
$dbname = 'db_lmw';				// 数据库名

$dbconnect = '0';					// 数据库持久连接 0=关闭, 1=打开

$mysql_mylink = mysql_connect($dbhost, $dbuser, $dbpw); 

mysql_query("SET NAMES 'utf8'");

$tablepre = 'dy_';			// 表 前缀

$charset='utf-8';					//设置字符集

$CONFIG[charset]='utf-8';			//设置字符集
	

// 网站投入使用后不能修改的变量
$SYSTEM['baseurl']				= 'http://'.$_SERVER['SERVER_NAME'];

$baseurl						= 'http://'.$_SERVER['SERVER_NAME'];

$upload_picpath					= 'uploadfile/upload';		//新闻图片

$SYSTEM['pagesize']				= '18';						//默认分页数,如果后台不设置的情况下

$SYSTEM['link_img']				= 'no';						//图片友情链接

$SYSTEM['ad_img']				= 'yes';					//图片友情链接

$indeximg						= 'no';						//开启公司简介图片
 
$indexabout						= 'no';						//开启首页公司简介

$indexcontact					= 'no';						//首页联系方式

$indexqq						= 'no';						//首页QQ

$SYSTEM['linkurl']				= 'no';					    //外部链接
 
$CONFIG['timezone']				= 'Etc/GMT-8';  		    //时区设置（只对php 5.1以上版本有效），Etc/GMT-8 实际表示的是 GMT+8 
		
$System_Webmode=				"html";					    //生成文件后缀	

$webarr=array(
	"showtype" => array ("1"=>"新闻列表","2"=>"单一内容", "3"=>"图片列表", "4"=>"友情链接", "5"=>"广告列表", "6"=>"产品模块", "7"=>"留言模块", "8"=>"招聘模块", "9"=>"下载模块"),
  	"ispay" => array ("0"=>"未支付","1"=>"已支付"),
 	"isstock" => array ("1"=>"未处理","2"=>"已发货","3"=>"完成订单","4"=>"管理员取消","5"=>"用户取消"),
	"iscats" => array ("0"=>"关闭","1"=>"开放"),
	"zid" => array ("1"=>"支付宝","2"=>"微信支付"),
	"isstate" => array ("99"=>"全部","0"=>"未审核","1"=>"已审核"),
 	);
?>