<?php
require './include/common.inc.php';
require WEB_ROOT.'./include/chkuser.inc.php';

$action = $_GET['action'];
if ($action=='export') { //导出XLS
 	$str = "手机号码\t姓名\t出生年月\t购买次数\t产品编号\t购买时间\t售后保养时间\t保养次数\t购买记录\t注册时间\t来自\t\n";
	
	$sql="SELECT * FROM `{$tablepre}vips`";
	//echo $sql;
	$result=$db->sql_query($sql);
    //$str = $str);
    while($row=$db->sql_fetchrow($result)){	
        $phone = $row['phone'];
		$realname = $row['realname'];
		$rqtime = $row['rqtime'];
		$gmcs = $row['gmcs'];
		$cpbh = $row['cpbh'];
		$gmsj = $row['gmsj'];
		$shbysj = $row['shbysj'];
		$bycs = $row['bycs'];
		$gmjl = $row['gmjl'];
		$sendtime = date('Y-m-d H:i',$row['sendtime']);
		
		if($row['uid'])
			$uid=get_zd_name("realname","manager"," and id={$row['uid']} ");
		else
			$uid="网站平台";

    	$str .= $phone."\t".$realname."\t".$rqtime."\t".$gmcs."\t".$cpbh."\t".$gmsj."\t".$shbysj."\t".$bycs."\t".$gmjl."\t".$sendtime."\t".$uid."\t\n";
		
		 
    }
	
	$str=iconv("UTF-8", "GBK", $str);
    $filename = date('YmdHis').'.xls';
    exportExcel($filename,$str);
}


function exportExcel($filename,$content){
 	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/vnd.ms-execl");
	header("Content-Type: application/force-download");
	header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Transfer-Encoding: binary");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $content;
}
 
?>
