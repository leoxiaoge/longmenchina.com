<?
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';
$filenames=basename($PHP_SELF,".php");

$path="../".$upload_picpath."/";

$pid=(int)$_GET['pid'];
$ty=(int)$_GET['ty'];
$tty=(int)$_GET['tty'];
$ttty=(int)$_GET['ttty'];

if($pid) $sqlkey.=" AND pid={$pid}";
if($ty) $sqlkey.=" AND ty={$ty}";
if($tty) $sqlkey.=" AND tty={$tty}";
if($ttty) $sqlkey.=" AND ttty={$ttty}";
 
$gourl=get_tourl($pid,$ty,$tty,$ttty,"{$filenames}");
$classname=get_toclassname($pid,$ty,$tty,$ttty);
$cname=get_cname($pid,$ty,$tty,$ttty);
$showtype=get_showtype($pid,$ty,$tty,$ttty);
 
$sql="select * FROM `{$tablepre}news` where isstate=1 ".$sqlkey." order by id desc";
 //echo $sql;
$result=$db->sql_query($sql);
$row=$db->sql_fetchrow($result);
$PageCount=$db->sql_numrows($result);
if($PageCount>0){
	$id=$row['id'];
}else{
	$content="内容编辑中...";
}

if (isset($_POST['dosubmit'])) {

	$delimg=$_POST['delimg'];
	$img_name=$_FILES['img1']['name'];
	$imgtype=end(explode(".", basename($img_name))); 
 	
 	$data = new_addslashes($_POST['info']);
	//$data = new_html_special_chars($data);
	//$data['title']=$cname;
  	$data['sendtime'] = $PHP_TIME;
  	$data['isstate'] = 1;
 	
	if($img_name){
		if (file_exists($path.$delimg)) @unlink($path.$delimg); 
		$imgnewname=date("YmdHis").mt_rand(10,99).".".$imgtype;
		uploadimg('img1',$path,$imgnewname);
		$data['img1']=$imgnewname;
	}
   	
	if($PageCount>0){
	
		$insertid=$db->update($data,"news"," id={$id}");
		
 		AddLog("编辑{$cname}栏目内容",$_SESSION['Admin_UserName']);
	}else{
  		
		$insertid=$db->insert($data,"news",true);
		
  		AddLog("添加{$cname}栏目内容",$_SESSION['Admin_UserName']);
	}
	//exit($sql);
	
 	JsSucce("操作成功！","{$gourl}");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>单页面编辑</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/select.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/jquery-1.6.2.min.js"></script>
<script language="javascript" src="js/checkform.js"></script>
<script charset="utf-8" src="myeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="myeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="myeditor/plugins/code/prettify.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor1 = K.create('.editor_id', {
		filterMode: false,//是否开启过滤模式
		cssPath : 'myeditor/plugins/code/prettify.css',
		uploadJson : 'myeditor/php/upload_json.php',
		fileManagerJson : 'myeditor/php/file_manager_json.php',
		allowFileManager : true,
 		afterBlur : function() {
		 this.sync();
		 K.ctrl(document, 13, function() {
		  K('form[name=formlist]')[0].submit();
		 });
		 K.ctrl(this.edit.doc, 13, function() {
		  K('form[name=formlist]')[0].submit();
		 });
		}
	});
	prettyPrint();
});
</script>

</head>

<body>

<div class="place">
	<span>位置：</span>
	<ul class="placeul">
		<li><a href="mains.php">首页</a></li>
		<?=$classname?>
		<li>管理页面</li>
	</ul>
</div>
    
<div class="formbody">

	<div id="usual1" class="usual"> 
	
		<div id="tab1" class="tabson">
		
			<div class="formtext">Hi，<b><?=$_SESSION['Admin_UserName']?></b>，欢迎您使用信息发布功能！</div>
		
			<ul class="forminfo">

				<form action="" method="post" name="formlist" enctype="multipart/form-data" onSubmit="return contents_check(this);">
					<input type="hidden" name="info[pid]" value="<?=$pid?>">
					<input type="hidden" name="info[ty]" value="<?=$ty?>">
					<input type="hidden" name="info[tty]" value="<?=$tty?>">
					<input type="hidden" name="info[ttty]" value="<?=$ttty?>">
					<input type="hidden" name="delimg" value="<?=$row['img1']?>" />

					<li><label>信息标题<b>*</b></label><input name="info[title]" type="text" class="dfinput" value="<?=$row['title']?>"  style="width:518px;"/></li>

					<? if($ty==1111){?>
					
					<li><label>视频简介<b>*</b></label><textarea name="info[introduce]" id="introduce" style="width:520px;height:100px;" class="dfinput"><?=$row['introduce']?></textarea></li>

					<li><label>更多链接<b>*</b></label><input name="info[linkurl]" type="text" class="dfinput" value="<?=$row['linkurl']?>"  style="width:518px;"/></li>

					<? }?>

					<li><label>信息内容<b>*</b></label><?=get_kindeditor(new_stripslashes(new_html_entity_decode($row['content'])))?></li>

					<? if($pid==1){?>
					<li><label>首页图片<b>*</b></label>
					<? 
					if ($row['img1']==""){
						echo "<input name=\"img1\" type=\"file\" id=\"img1\">";
					}else{
						echo"<input name=\"img1\" type=\"file\" id=\"img1\"> <a href=\"{$path}{$row['img1']}\" target=\"_blank\">查看图片</a>";
					}
					?> <font color="#FF0000">图片大小:<?=$System_Picsize?>K内,图片尺寸：<?=get_toimgsize($pid,$ty,$tty,$ttty)?>px</font></li>
					<? }?>

	
					<li><label>&nbsp;</label><input name="dosubmit" type="submit" class="btn" value="马上修改"/></li>

				</form>

			</ul>
		
		</div> 

	</div> 

</div>


</body>

</html>
