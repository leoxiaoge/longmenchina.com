<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$gourl="payment.php";
$classname="<li>配送方式</li>";
 
if(isset($_POST['add'])){
 	$sqlvalues="";
	$fields=$_POST['fields'];

	$fields['title']=getformfield("title");
	$fields['ftitle']=getformfield("ftitle");
	$fields['price']=getformfield("price");
 	$fields['disorder']=(int)getformfield("disorder");
 	$fields['sendtime']=strtotime(getformfield("sendtime"));
	$fields['isstate']=1;
   
	while(list ($key,$val) =each ($fields)){
		$sqlvalues.=",$key='$val'";
	}
	$sqlvalues=substr($sqlvalues,1);
	$sql="INSERT INTO `{$tablepre}payment` SET ".$sqlvalues;
	
	 //exit($sql);
	
	if($db->sql_query($sql)){
		AddLog("添加邮费内容",$_SESSION['Admin_UserName']);
		JsSucce("操作成功！","{$gourl}");
	}else{
		JsError("操作失败！");
	}
	exit();
}
if($tty) $zid=$tty;else $zid=$ty;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>配送方式发布页面</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/checkform.js"></script>
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="mains.php">首页</a></li>
	<?=$classname?>
    <li>信息添加</li>
	</ul>
    </div>
    
    <div class="formbody">
    
    
    <div id="usual1" class="usual"> 
    
    <div class="itab">
  	<ul> 
    <li><a href="#tab1" class="selected">配送方式</a></li> 
  	</ul>
    </div> 
    
  	<div id="tab1" class="tabson">
    
    <div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
    
		<ul class="forminfo">
		  <form action="" method="post" name="formlist" onSubmit="return news_check(this);">
 			<li><label>名称<b>*</b></label><input name="title" type="text" class="dfinput" value=""/></li>
 			<li><label>说明<b>*</b></label><input name="ftitle" type="text" class="dfinput" value=""/></li>
 			<li><label>邮费<b>*</b></label><input name="price" type="text" class="dfinput" value=""/> 元</li>
			<li><label>创建时间<b>*</b></label><input name="sendtime" type="text" class="dfinput" value="<?=$PHP_DATE?>"/></li>
 			<li><label>信息排序<b>*</b></label><input name="disorder" type="text" class="dfinput" value="0" style="width:100px;"/> 注：数字越大越排在前</li>			
			<li><label>&nbsp;</label><input name="add" type="submit" class="btn" value="马上发布"/></li>
		  </form>
		</ul>
    
    </div> 
    
  	  
       
	</div> 
    
    </div>


</body>

</html>
