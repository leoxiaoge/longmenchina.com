<?php
if(!defined('IN_COPY')) {
	exit('Access Denied');
}

date_default_timezone_set('Asia/Shanghai');
 
$smtp_server = "smtp.126.com";
$smtp_user = "whj220@126.com";
$smtp_pwd = "acegbd220";				//您登录smtp服务器的密码
$smtp_sender = "whj220@126.com";		//发件人,一般要与您登录smtp服务器的用户名($smtpuser)相同,否则可能会因为smtp服务器的设置导致发送失败
$smtp_port = 25;
$smtp_mailtype = "HTML";				//邮件的类型，可选值是 TXT 或 HTML ,TXT 表示是纯文本的邮件,HTML 表示是 html格式的邮件
$smtp = new smtp($smtp_server,$smtp_port,true,$smtp_user,$smtp_pwd,$smtp_sender); 
//$smtp->debug = true; //是否开启调试,只在测试程序时使用，正式使用时请将此行注释
$smtp_subject = "【我要艺术网】邮箱验证";
$smtp_body = "<style>
.qmbox p{
	font-family:Arial, Helvetica, sans-serif, \"微软雅黑\", \"宋体\";
	font-size:14px;
	line-height:24px;
	color:#969696;
	text-align:left;
	vertical-align:middle;
	width:560px;
	word-break:break-word;
}
.qmbox p a{
	font-family:Arial, Helvetica, sans-serif, \"微软雅黑\", \"宋体\";
	font-size:14px;
	line-height:24px;
	color:#969696;
	text-decoration:underline;
	text-align:left;
	vertical-align:middle;
	width:560px;
	word-break:break-word;
}
.qmbox p a:hover{
	color:#fff;
	background-color:#da4453
}
.qmbox b{
	font-weight:bold;
	color:#333;
}
.qmbox .Prompt{
	font-size:12px;
}
.qmbox .Copyright{
	font-size:12px;
	vertical-align:text-top;
	margin-top:10px;
}
</style>



<table id=\"__01\" width=\"620\" height=\"580\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	<tr>
		<td rowspan=\"5\">
			<img src=\"http://www.51art.com/resource/images/edm_01.gif\" width=\"30\" height=\"580\"  /></td>
		<td>
			<img src=\"http://www.51art.com/resource/images/edm_02.gif\" width=\"560\" height=\"81\"  /></td>
		<td rowspan=\"5\">
			<img src=\"http://www.51art.com/resource/images/edm_03.gif\" width=\"30\" height=\"580\"  /></td>
	</tr>
	<tr>
		<td width=\"560\" height=\"107\" background=\"http://www.51art.com/resource/images/edm_04.gif\">
			<p><b>尊敬的  {$email}，您好！</b><br  />
			您已在我要艺术网设置了本邮箱，请点击此链接完成验证</p>
		</td>
	</tr>
	<tr>
		<td width=\"560\" height=\"98\" background=\"http://www.51art.com/resource/images/edm_05.gif\">
			<p><a href=\"http://www.51art.net/huiyuan/addEmailStatus.php?email=".urlencode($email)."\">http://www.51art.net/huiyuan/addEmailStatus.php?email=".urlencode($email)."
</a></p>
		</td>
	</tr>
	<tr>
		<td width=\"560\" height=\"211\" background=\"http://www.51art.com/resource//images/edm_06.gif\">
			<p>如果点击以上链接无效，请将此链接复制到浏览器地址栏中访问<br  />
			请将 “发&#x4EF6;的邮箱地址” 加入您的邮&#x4EF6;联系人名单</p><br  />
			<p class=\"Prompt\">友情提示：<br  />
			验证邮箱为了确保您能收到验证、找回密码等邮&#x4EF6;，<br  />
			如非本人操作，请您忽略此邮&#x4EF6;，给您带来不便请谅解。</p>
		</td>
	</tr>
	<tr>
		<td width=\"560\" height=\"83\" background=\"http://www.51art.com/resource/images/edm_07.gif\" valign=\"top\">
			<p class=\"Copyright\">Copyright&#xA9; 2014 www.51art.com All Rights Reserved<br  />
			我要艺术网版权所有</p>
		</td>
	</tr>
</table>";
//echo  ($smtp_body);
?>