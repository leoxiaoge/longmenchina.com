<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$sql="select * from `{$tablepre}config`";
$result=$db->sql_query($sql);
while($row=$db->sql_fetchrow($result)){
 	$sys[]=$row;
}

if(isset($_POST['dosubmit']))
{
    foreach($_POST as $k=>$v)
    {
		$db->sql_query("UPDATE `{$tablepre}config` SET `varvalue`='$v' WHERE varname='$k'");
		//echo "UPDATE `{$tablepre}config` SET `varvalue`='$v' WHERE varname='$k'";
		$num=1;
     }
	 //exit($sql);
	if($num){
		AddLog("设置首页SEO优化",$_SESSION['Admin_UserName']);
		
		JsSucce("操作成功！","seo_index.php");
		exit();
 	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统参数设置</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li>基本信息</li>
    <li>管理页面</li>
    </ul>
    </div>
    
    <div class="formbody">
    
    
    <div id="usual1" class="usual"> 
    
    <div class="itab">
  	<ul> 
	<li><a href="#tab1" class="selected">首页优化</a></li> 
  	</ul>
    </div> 
	   <form action="" method="post" name="myform" id="myform" enctype="multipart/form-data">
		<div id="tab1" class="tabson">
		
		<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
			<ul class="forminfo">
				<li><label style="width:110px;">页面标题<b>*</b></label><input name="sys_seotitle" type="text" class="dfinput" value="<?=$sys[15]['varvalue']?>"  style="width:518px;"/></li>
				<li><label style="width:110px;">页面关键字<b>*</b></label><textarea name="sys_seokeywords" id="sys_seokeywords" style="width:520px;height:100px;" class="dfinput"><?=$sys[16]['varvalue']?></textarea></li>
				<li><label style="width:110px;">页面描述<b>*</b></label><textarea  name="sys_seodescription" id="sys_seodescription" style="width:520px;height:100px;" class="dfinput"><?=$sys[17]['varvalue']?></textarea></li>	 
			  
			</ul>
		</div> 
    
 		
      	<li><label style="width:110px;">&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>
 		</form>
	</div> 
    
    </div>


</body>

</html>
