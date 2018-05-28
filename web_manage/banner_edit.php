<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$path="../".$upload_picpath."/";
$id= (int)$_GET['id'];
$sql="select * FROM `{$tablepre}news_cats` where id=".$id."";
$result=$db->sql_query($sql);
if($row=$db->sql_fetchrow($result)){
	$pid=$row['pid'];
}

if(isset($_POST['update']))
{
	$sqlvalues="";
	$fields=$_POST['fields'];
	
	$delimg=$_POST['delimg'];
	$img_name=$_FILES['img']['name'];
	$imgtype=end(explode(".", basename($img_name))); 
	
	
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
	// exit($sql);

	if($db->sql_query($sql)){
		
		AddLog("编辑栏目分类",$_SESSION['Admin_UserName']);
		
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
<title>栏目分类修改</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
    <li>Banner管理</li>
    <li>Banner修改</li>
	</ul>
    </div>
    
    <div class="formbody">
    
    
    <div id="usual1" class="usual"> 
    
    <div class="itab">
  	<ul> 
    <li><a href="#tab1" class="selected"><?=$row['catname']?></a></li> 
  	</ul>
    </div> 
    
  	<div id="tab1" class="tabson">
    
    <div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
    
		<ul class="forminfo">
			<form name="form1" method="post" action="" onSubmit="return checkcats(this)" enctype="multipart/form-data">
  			
 			<li><label>所属栏目<b>*</b></label><?=$row['catname']?></li>
 			
			<li><label>栏目广告<b>*</b></label><? 
	  if ($row['img1']==""){
	  	echo "<input name=\"img\" type=\"file\" id=\"img\" style=\"width:300px\">";
	  }else{
	  	echo"<input name=\"img\" type=\"file\" id=\"img\" style=\"width:300px\"> <a href=\"{$path}{$row['img1']}\" target=\"_blank\">查看图片</a>";
	  }
	  ?> 1920 * 270px</li>
			<li><label>&nbsp;</label><input name="update" type="submit" class="btn" value="马上修改"/></li>
			</form>
		</ul>
    
    </div> 
    
  	  
       
	</div> 
    
    </div>


</body>

</html>
