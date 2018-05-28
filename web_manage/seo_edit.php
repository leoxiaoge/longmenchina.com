<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$id= (int)$_GET['id'];
$sql="select * FROM `{$tablepre}news_cats` where id=".$id."";
//echo $sql;
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){

}
$path="../".$upload_picpath."/";
if(isset($_POST['update']))
{
	$sqlvalues="";
	$fields=$_POST['fields'];
	
	$delimg=$_POST['delimg'];
	$img_name=$_FILES['img']['name'];
	$imgtype=end(explode(".", basename($img_name))); 
	
	$fields['seotitle']=trim($_POST['seotitle']);
	$fields['seokeywords']=trim($_POST['seokeywords']);
	$fields['seodescription']=trim($_POST['seodescription']);

	if($img_name){
		if (file_exists($path.$delimg)) @unlink($path.$delimg); 
		$imgnewname=date("YmdHis").mt_rand(10,99).".".$imgtype;
		uploadimg('img',$path,$imgnewname);
		$fields['img1']=$imgnewname;
	}

	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);
	$sql="update `{$tablepre}news_cats` SET ".$sqlvalues." where id=".$id."";
	//exit($sql);
	if($db->sql_query($sql)){
	
		AddLog("设置栏目SEO优化",$_SESSION['Admin_UserName']);
		
		JsSucce("操作成功！","seo.php");
	}else{
		JsError("操作失败！");
	}
	exit();
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
				<li><a href="#tab1" class="selected"><?=$row['catname']?></a></li> 
			</ul>
		</div>
		
		<form action="" method="post" name="myform" id="myform" enctype="multipart/form-data">
		
			<div id="tab1" class="tabson">
			
				<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
				
				<ul class="forminfo">
				
					<li><label style="width:110px;">页面标题<b>*</b></label><input name="seotitle" type="text" class="dfinput" value="<?=$row['seotitle']?>"  style="width:518px;"/></li>
					<li><label style="width:110px;">页面关键字<b>*</b></label><textarea id="seokeywords" name="seokeywords" style="width:520px;height:100px;" class="dfinput"><?=$row['seokeywords']?></textarea></li>
					<li><label style="width:110px;">页面描述<b>*</b></label><textarea id="seodescription" name="seodescription" style="width:520px;height:100px;" class="dfinput"><?=$row['seodescription']?></textarea></li>	 
					
					<li><label>栏目图片<b>*</b></label><? 
					if ($row['img1']==""){
						echo "<input name=\"img\" type=\"file\" id=\"img\" style=\"width:300px\">";
					}else{
						echo"<input name=\"img\" type=\"file\" id=\"img\" style=\"width:300px\"> <a href=\"{$path}{$row['img1']}\" target=\"_blank\">查看图片</a>";
					}
					?> 580*310px</li>
					
				</ul>
				
			</div>
		
			<li><label style="width:110px;">&nbsp;</label><input name="update" type="submit" class="btn" value="马上修改"/></li>
		
		</form>
	
	</div>

</div>


</body>

</html>
