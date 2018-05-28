<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
</head>


<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
    </ul>
    </div>
    
    <div class="mainindex">
     
    <div class="welinfo"><b><?=$_SESSION['Admin_UserName']?>，欢迎使用信息管理系统</b><? if ($_SESSION['Admin_BigMyMenu']=="super"){?><a href="manager.php">帐号设置</a><? }?></div>
      
    <div class="xline"></div>
    
    <ul class="iconlist">
	<table width='100%' height="351" border=1 cellpadding=1 cellspacing=0 bordercolorlight=#c0c0c0 bordercolordark=#FFFFFF>
                  <tr>
                    <td height="27" colspan=2 align=center bgcolor=#ffffff class=red_3><strong>服务器的有关参数</strong></td>
                  </tr>
                  <tr>
                    <td width="26%" height="23">&nbsp;服务器名：</td>
                    <td width="74%" height="23">&nbsp; <?=$_SERVER["SERVER_NAME"]?>                    </td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;服务器IP：</td>
                    <td height="23">&nbsp; <?=$_SERVER["LOCAL_ADDR"]?>                    </td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;服务器端口：</td>
                    <td width="74%" height="23">&nbsp;
                      <?=$_SERVER["SERVER_PORT"]?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;服务器时间：</td>
                    <td height="23">&nbsp; <?=date("Y年m月d日H点i分s秒")?>                    </td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;PHP版本：</td>
                    <td height="23">&nbsp;
                      <?=PHP_VERSION?>                    </td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;WEB服务器版本：</td>
                    <td height="23">&nbsp;
                        <?=$_SERVER["SERVER_SOFTWARE"]?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;服务器操作系统：</td>
                    <td height="23">&nbsp;
                      <?=PHP_OS?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;脚本超时时间：</td>
                    <td height="23">&nbsp;
                      <?=get_cfg_var("max_execution_time")?> 秒                    </td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;站点物理路径：</td>
                    <td height="23">&nbsp;
                        
                      <?=realpath("../")?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;脚本上传文件大小限制：</td>
                    <td height="23">&nbsp;
                      <?=get_cfg_var("upload_max_filesize")?get_cfg_var("upload_max_filesize"):"不允许上传附件"?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;POST提交内容限制：</td>
                    <td height="23">&nbsp;
                      <?=get_cfg_var("post_max_size")?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;服务器语种：</td>
                    <td height="23">&nbsp;
                      <?=getenv("HTTP_ACCEPT_LANGUAGE")?></td>
                  </tr>
                  <tr>
                    <td height="23">&nbsp;脚本运行时可占最大内存</td>
                    <td height="23">&nbsp;
                      <?=get_cfg_var("memory_limit")?get_cfg_var("memory_limit"):"无"?></td>
                  </tr>
                  <tr>
                    <td height="23" colspan="2">&nbsp;                    </td>
                  </tr>
      </table>
    </ul>
 
</body>

</html>
