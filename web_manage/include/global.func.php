<?php

//********************************************

//	作者：Whj

//	时间：2010-4-29

//	作用：

//********************************************

if(!defined('IN_COPY')) {

	exit('Access Denied');

}


//账户资金流水
function pay_logs($typeid,$uid,$_logcontent,$price) {
	global $PHP_TIME,$PHP_IP,$db,$tablepre;
	$db->sql_query("INSERT INTO `{$tablepre}pay_logs`(typeid,uid,content,price,ip,sendtime) VALUES ('".$typeid."','".$uid."','".$_logcontent."','".$price."','".$PHP_IP."','".$PHP_TIME."')");
 	//Return 1;
}


//数组排序
function array_sort($arr,$keys,$type='desc'){ 
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array; 
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
 
 

//获取关键字列表
function get_type_lists($key,$ptype){
	$key=ereg_replace(" 　"," ",trim($key));
	$key=ereg_replace("，"," ",trim($key));
	$key=ereg_replace(","," ",trim($key));
	$key=preg_replace('/ +/', ' ',trim($key));
	$keywordsarray=split(' ',$key);
	
	$str.="<SELECT name=\"info[ptype]\">";
	$str.="<OPTION value=\"\">请选择产品类型</OPTION>";
	for($i=0;$i<count($keywordsarray);$i++){
		$v=$keywordsarray[$i];
		if($ptype&&$ptype==$v){
			$c="selected";
		}else{
  			if ($i==0) $c="selected"; else $c="";
		}	
		$str.="<option value='".$v."' ".$c.">{$v}</option>";
 	}
	$str.="</SELECT>";
	return $str;
} 

function get_xianmu_list($newpid=0,$table="news",$where,$tyname="xmid"){
	global $db,$tablepre; 
	$sql= "SELECT * FROM `{$tablepre}{$table}` WHERE isstate=1 {$where} ORDER BY id ASC";
	//echo $sql;
  	$result=$db->sql_query($sql);
	$i=1;
  	while($bd=$db->sql_fetchrow($result)){

		//echo $newpids;
		$AdvQxs=explode(',',$newpid);
		//echo $AdvQxs;
		$cks="";
		foreach($AdvQxs as $mns)
		if(trim($mns)==$bd['id']) $cks="checked";
		
		$str.="<input type=\"checkbox\" name=\"{$tyname}[]\" id=\"{$tyname}[]\" {$cks} value=\"{$bd['id']}\"/>{$bd['title']}　";
	
	}

 	return $str;
	unset($sql,$result,$bd);
} 

//获取银行卡列表
function get_cats_list($newpid=0,$table="news",$where,$tyname="yid"){
	global $db,$tablepre; 
	
 	$sql="SELECT * FROM `{$tablepre}{$table}` where 1=1 {$where} ORDER BY id ASC";
	//echo $sql;
 	$result=$db->sql_query($sql);
	
	$str.="<select name=\"info[{$tyname}]\" style=\" width:200px;\" class=\"dfinput\" >";
	
	$str.="<OPTION value=\"0\">请选择</OPTION>";
	
	while($row=$db->sql_fetchrow($result)){

		if ($row['id']==(int)$newpid) $c="selected"; else $c="";

		$str.="<option value='".$row['id']."' ".$c.">{$row['title']}</option>";

	}
	
	$str.="</select> {$tyname}";
	return $str;
}

//获取省份列表
function get_city_list($newpid=0,$table="news",$where,$tyname="yid"){
	global $db,$tablepre; 
	
 	$sql="SELECT * FROM `{$tablepre}{$table}` where 1=1 {$where} ORDER BY id ASC";
	//echo $sql;
 	$result=$db->sql_query($sql);
	
	$str.="<select name=\"{$tyname}\" style=\" width:100px;\">";
	
	$str.="<OPTION value=\"0\">请选择</OPTION>";
	
	while($row=$db->sql_fetchrow($result)){

		if ($row['id']==(int)$newpid) $c="selected"; else $c="";

		$str.="<option value='".$row['id']."' ".$c.">{$row['catname']}</option>";

	}
	
	$str.="</select>";
	return $str;
}

//返回跳转地址
function get_tourl($pid,$ty,$tty,$ttty,$filename){
	if($ttty)
	$gourl="{$filename}.php?pid={$pid}&ty={$ty}&tty={$tty}&ttty={$ttty}";
	elseif($tty)
	$gourl="{$filename}.php?pid={$pid}&ty={$ty}&tty={$tty}";
	else
	$gourl="{$filename}.php?pid={$pid}&ty={$ty}";
	return $gourl;
}

//作用：平均值
if(!function_exists('get_sums')){
	function get_sums($orderid,$btypeid) {
		global $db,$tablepre; 
 		$sql= "SELECT jg,num FROM `{$tablepre}orders_items` where orderid='{$orderid}' AND btypeid={$btypeid} AND isstate=0";
		 //exit($sql);
    	$result=$db->sql_query($sql);
		$PageCount=$db->sql_numrows($result);
		while($row=$db->sql_fetchrow($result)){
			$tprice=$row['jg']*$row['num'];
			$tjine=$tjine+$tprice;
			$totalprice=$tjine;	
 		}
		if($PageCount) return $totalprice; else return 0;
	}
	unset($sql,$result,$str);
}
  

 //购物车+分页
function get_tempdata_list($pid,$orderid) {
	global $db,$upload_picpath,$tablepre,$srt; 
	if($uid) $sqlkey=" AND uid={$uid}";
 	
 	$sqls= "SELECT * FROM `{$tablepre}orders_items` WHERE pid={$pid} AND orderid='{$orderid}' order by id asc limit 13";
	//echo $sqls;
  	$result=$db->sql_query($sqls);
  					
	while($bd=$db->sql_fetchrow($result)){
		if(!empty($bd['img1'])) $Img=$bd['img1']; else $Img="nopic.jpg";
 		$sys_typeid=$bd['mtypeid'];
		$typeid=$bd['btypeid'];
 		if($sys_typeid==1||$sys_typeid==3)
			if($typeid==2) $cname="一起定";else $cname="购买";
		else
			if($typeid==2) $cname="按张数";elseif($typeid==3) $cname="按英尺";else $cname="长期订单系统";
		
		$tprice=$bd['jg']*$bd['num'];
	
	
		$str.="\n<tr>
					   <td>".get_catname($pid)."</td>
					   <td><img src=\"/{$upload_picpath}/{$Img}\" width=\"40\" height=\"40\" /> {$bd['title']}</td>
					   <td>{$cname}</td>
					   <td>￥{$bd['jg']}</td>
					   <td>{$bd['num']}</td>
					   <td>￥{$tprice}</td>
 				  </tr>";		
  	}		
  	return $str;
	unset($sql,$result,$bd);
}

//返回当前栏目名称
function get_cname($pid,$ty,$tty,$ttty){

	if($ttty)
	$cname=get_catname($ttty,"news_cats");
	elseif($tty)
	$cname=get_catname($tty,"news_cats");
	else
	$cname=get_catname($ty,"news_cats");
	return $cname;
}

//返回跳转当前栏目位置
function get_toclassname($pid,$ty,$tty,$ttty){

	if($ttty)
	$classname="<li>".get_catname($pid,"news_cats")."</li><li>".get_catname($ty,"news_cats")."</li><li>".get_catname($tty,"news_cats")."</li><li>".get_catname($ttty,"news_cats")."</li>";
	elseif($tty)
	$classname="<li>".get_catname($pid,"news_cats")."</li><li>".get_catname($ty,"news_cats")."</li><li>".get_catname($tty,"news_cats")."</li>";
	else
	$classname="<li>".get_catname($pid,"news_cats")."</li><li>".get_catname($ty,"news_cats")."</li>";
	return $classname;
}


//返回当前栏目图片尺寸
function get_toimgsize($pid,$ty,$tty,$ttty){
	$tttyimg=get_catname($ttty,"news_cats","imgsize");
	$ttyimg=get_catname($tty,"news_cats","imgsize");
	$tyimg=get_catname($ty,"news_cats","imgsize");
	$pidimg=get_catname($pid,"news_cats","imgsize");
	
	if($tttyimg)
		$imgsize=$tttyimg;
 	elseif($ttyimg)
		$imgsize=$ttyimg;
 	elseif($tyimg)
		$imgsize=$tyimg;
	else
		$imgsize=$pidimg;
	return $imgsize;
}



//返回当前栏目类型
function get_showtype($pid,$ty,$tty,$ttty){
	$tttyshowtype=get_catname($ttty,"news_cats","showtype");
	$ttyshowtype=get_catname($tty,"news_cats","showtype");
	$tyshowtype=get_catname($ty,"news_cats","showtype");
	$pidshowtype=get_catname($pid,"news_cats","showtype");
	
	if($tttyshowtype)
		$showtype=$tttyshowtype;
 	elseif($ttyshowtype)
		$showtype=$ttyshowtype;
 	elseif($tyshowtype)
		$showtype=$tyshowtype;
	else
		$showtype=$pidshowtype;
		
		
	return $showtype;
}


//返回当前栏目类型
function get_imgnum($pid,$ty,$tty,$ttty){
	$tttyimgnum=get_catname($ttty,"news_cats","imgnum");
	$ttyimgnum=get_catname($tty,"news_cats","imgnum");
	$tyimgnum=get_catname($ty,"news_cats","imgnum");
	$pidimgnum=get_catname($pid,"news_cats","imgnum");
	
	if($tttyimgnum)
		$imgnum=$tttyimgnum;
 	elseif($ttyimgnum)
		$imgnum=$ttyimgnum;
 	elseif($tyimgnum)
		$imgnum=$tyimgnum;
	else
		$imgnum=$pidimgnum;
		
	return $imgnum;
}



//根据条件获取内容
if(!function_exists('get_zd_name')){
	function get_zd_name($zd,$table='users',$where) {
	global $db,$tablepre; 
		$sql= "SELECT {$zd} FROM `{$tablepre}{$table}` WHERE 1=1 {$where}";
		 // echo $sql;
 		$result=$db->sql_query($sql);
		if($arr=$db->sql_fetchrow($result)){
			return $arr[$zd];
		} 
	}
	unset($sql,$result,$arr);
}


  
//获取默认值 
function get_zd_value($t,$zd,$strings,$zdz=""){
	$value = split(',',$strings);
	$str="<select onChange=\"select{$zd}(this.options[this.selectedIndex].value)\">
		  <option value=\"\">快速选择{$t}</option>";
	for($i=0;$i<count($value);$i++){
 		$v=$value[$i];
		if($v==$zdz) $s=" selected";else $s="";
		$str.="\n<option value=\"{$v}\"{$s}>{$v}</option>";
	}
	$str.="</select>";
	return $str;
}


//获取默认值 
function get_zd_value2($t,$zd,$strings,$zdz="lanmu"){
	$value = split(',',$strings);
	$str="<select onChange=\"select{$zd}(this.options[this.selectedIndex].value)\" name=\"{$zd}\">
		  <option value=\"\">快速选择{$t}</option>";
	for($i=0;$i<count($value);$i++){
 		$v=$value[$i];
		if($v==$zdz) $s=" selected";else $s="";
		$str.="\n<option value=\"{$v}\"{$s}>{$v}</option>";
	}
	$str.="</select>";
	return $str;
}
 

//作用：获取会员名称
if(!function_exists('get_zd_name')){
	function get_zd_name($pid,$zd='username',$table='users') {
	global $db,$tablepre; 
		$sql= "SELECT $zd FROM `{$tablepre}{$table}` WHERE id=".$pid;
		//echo $sql;
 		$result=$db->sql_query($sql);
		if($arr=$db->sql_fetchrow($result)){
			return $arr[$zd];
		}else{
			return "无";
		}
	}
	unset($sql,$result,$arr);
}



function strselected($str1,$str2){
	if ($str1==$str2){
		$str="selected=\"selected\"";
	}else{
		$str = "";
	}
	echo $str;
}	


//读取CSV指定行内容
function get_file_line($file_name, $line ){
	$n = 0;
	$handle = fopen($file_name,'r');
	if ($handle) {
		while (!feof($handle)) {
			++$n;
			$out = fgets($handle, 4096);
			if($line==$n) break;
		}
		fclose($handle);
	}
	if( $line==$n) return $out;
	return false;
}

//作用：开启ob缓存
if(!function_exists('get_ob_start')){
 	function get_ob_start($html_path,$html_filename,$html_time) {
 		if($html_filename=="index.html"){
			if(file_exists($html_path.$html_filename)&& filemtime($html_path.$html_filename)+$html_time*60>time()){
   				echo file_get_contents($html_path.$html_filename);
				exit();
			}
		}else{
 			if(file_exists($html_path.$html_filename)){
 				echo file_get_contents($html_path.$html_filename);
				exit();
			}
		}
		ob_start();
	}
}

// 分析内容，获取第1张图片
function get_firstpic($content) {
	$content = stripslashes($content);
	$str = @preg_match("/src\s*=\s*([\"']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\1/i", $content, $rs) ? $rs[2] : '';
	$tmp=str_replace("/uploadfile/upload/", "", $str);
	return $tmp;
}

//---------下载地址组合
function ReturnDownpath($downname,$downpath,$img,$down=0){
	$f_exp="::::::";
	$r_exp="\r\n";
	$returnstr="";
    $downurl=str_replace($f_exp,"",$downurl);
	$downurl=str_replace($r_exp,"",$downurl);
	//增加软件
	if(empty($down))
	{
		for($i=0;$i<count($downname);$i++)
		{
			//替换非法字符
			$name=str_replace($f_exp,"",$downname[$i]);
			$name=str_replace($r_exp,"",$downname[$i]);
			$path=str_replace($f_exp,"",$downpath[$i]);
			$path=str_replace($r_exp,"",$downpath[$i]);
			//批量更换权限
			if($add[doforuser])
			{
				if(empty($foruser))
				{
					$foruser=0;
			    }
				$fuser=$foruser;
		    }
			else
			{
				if(empty($downuser[$i]))
				{
					$fuser=0;
			    }
				else
				{
					$fuser=$downuser[$i];
				}
		    }
			//批量更新点数
			if($add[dodownfen])
			{
				if(empty($add[downfen]))
				{
					$add[downfen]=0;
				}
				$ffen=$add[downfen];
			}
			else
			{
				if(empty($fen[$i]))
				{
					$ffen=0;
				}
				else
				{
					$ffen=$fen[$i];
				}
			}
			$downqz=$thedownqz[$i];
			if($path&&$name)
			{$returnstr.=$name.$f_exp.$downurl.$path.$f_exp.$fuser.$f_exp.$ffen.$f_exp.$downqz.$r_exp;}
		}
	}
	//修改软件
	else
	{
		for($i=0;$i<count($downname);$i++)
		{
			//删除下载地址
			$del=0;
			for($j=0;$j<count($delpathid);$j++)
			{
				if($delpathid[$j]==$pathid[$i])
				{$del=1;}
			}
			if($del)
			{continue;}
			//替换非法字符
			$name=str_replace($f_exp,"",$downname[$i]);
			$name=str_replace($r_exp,"",$downname[$i]);
			$path=str_replace($f_exp,"",$downpath[$i]);
			$path=str_replace($r_exp,"",$downpath[$i]);
			//批量更换权限
			if($add[doforuser])
			{
				if(empty($foruser))
				{
					$foruser=0;
			    }
				$fuser=$foruser;
		    }
			else
			{
				if(empty($downuser[$i]))
				{
					$fuser=0;
			    }
				else
				{
					$fuser=$downuser[$i];
				}
		    }
			//批量更新点数
			if($add[dodownfen])
			{
				if(empty($add[downfen]))
				{
					$add[downfen]=0;
				}
				$ffen=$add[downfen];
			}
			else
			{
				if(empty($fen[$i]))
				{
					$ffen=0;
				}
				else
				{
					$ffen=$fen[$i];
				}
			}
			$downqz=$thedownqz[$i];
			if($path&&$name)
			{$returnstr.=$name.$f_exp.$downurl.$path.$f_exp.$fuser.$f_exp.$ffen.$f_exp.$downqz.$r_exp;}
		}
	}
	//去掉最后的字符
	$returnstr=substr($returnstr,0,strlen($returnstr)-2);
	return $returnstr;
}



//作用：结束ob缓存，并把内容写入文件中
if(!function_exists('get_ob_end')){
	function get_ob_end($html_path,$html_filename) {
		file_put_contents($html_path.$html_filename,ob_get_contents());
 	}
}



//获取方式
function opturl($str){
	return $_POST[$str] ? $_POST[$str] : $_GET[$str]; 
}

//过滤字符串
function Encode($fString){
	//$fString = str_replace("&gt;", ">", $fString);
	//$fString = str_replace("'", "’", $fString);
	//$fString = str_replace("&lt;", "<", $fString);
	//$fString = str_replace("&#39;", chr(39) ,$fString );
	//$fString = str_replace("", chr(13),$fString);
	//$fString = str_replace(chr(13), "<BR />", $fString);
	//$fString = str_replace(chr(10) & chr(10),"</P><P>",  $fString);
	//$fString = str_replace(chr(10), "<BR />", $fString);
	return $fString;
}

//获取图片状态
function getimg($path,$img){
 	if($img) $str="<a href=".$path.$img."  target='_blank'><img src='images/1.gif'  alt='有缩略图' border=0></a>"; else $str="<img src='images/0.gif'  alt='无缩略图'>";
	return $str;
}
	
//出生日期
function get_date_list($cid,$snum,$enum,$name){
	$str.="<select name=\"{$name}\">";
	for($i=$snum; $i<=$enum; $i++){
		if($cid==$i) $s=" selected"; else $s="";
  		$str.="<option value=\"{$i}\"{$s}>{$i}</option>";
	}
	$str.="</select>";
 	return $str;
}

	
	
//获取URL传的值 @filed 表单名称
function getformfield($field){
	$value = Encode(trim($_POST[$field]));
	return $value;
}

//密码加码方式
function cusmd5($str){
	global $pre_password;
	return md5(md5($pre_password.$str));
}

//获取类别的显示方式
function news_cat_type($pid,$table="news_cats") {
global $db,$tablepre; 
	$sql= "SELECT showtype FROM `{$tablepre}{$table}` WHERE isstate=1 AND id=".$pid;
	//exit($sql);
	$result=$db->sql_query($sql);
	if($bd=$db->sql_fetchrow($result)){
		$context=$bd['showtype'];
		return $context;
	}else{
		return " ";
	}
}


//作用：获取会员名称
if(!function_exists('get_username')){
	function get_username($pid) {
	global $db,$tablepre; 
		$sql= "SELECT username FROM `{$tablepre}users` WHERE id=".$pid;
 		$result=$db->sql_query($sql);
		if($arr=$db->sql_fetchrow($result)){
			return $arr['username'];
		}else{
			return "";
		}
	}
	unset($sql,$result,$arr);
}


/**  
 * 排序函数【数据库】  
 * @param string $tablename 表名  
 * @param array $wheresqlarr 条件数组  
 * @param int $sortnum 移动位数  
 * @param string $sortflag 移动标志，向上为up,向下为down  
 * @param int $sort 当前对象排序的序号  
 */  
function data_sort($sortflag="up",$sort,$table="news",$wheresqlarr,$sortnum=1,$silent=0)  
{  
	global $db,$tablepre; 
    $where = $comma = '';  
    if(empty($wheresqlarr)) {  
        $where = '1';  
    } elseif(is_array($wheresqlarr)) {  
        foreach ($wheresqlarr as $key => $value) {  
            $where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';  
            $comma = ' AND ';  
        }  
    } else {  
        $where = $wheresqlarr;  
    }  
    if($sortflag == "up"){  
        $maxsort = $sort;  
        //$minsort = $_OGLOBAL['db']->result_first("select min(`disorder`) from $tablename where `disorder` < $sort AND $where order by `disorder` desc limit $sortnum");  
		$result=$db->sql_query("select min(`disorder`) as minsort from `{$tablepre}{$table}`  where `disorder` < $sort AND $where order by `disorder` desc limit $sortnum");
		//exit("select min(`disorder`) as minsort from `{$tablepre}{$table}`  where `disorder` < $sort AND $where order by `disorder` desc limit $sortnum");
		$bd=$db->sql_fetchrow($result);
		$minsort=$bd['minsort'];
		
		
		$db->sql_query("UPDATE `{$tablepre}{$table}` SET `disorder` = `disorder` + 1  WHERE `disorder` < $maxsort AND `disorder` >= $minsort AND $where, $silent? SILENT:''");  
		//exit("UPDATE `{$tablepre}{$table}` SET `disorder` = `disorder` + 1  WHERE `disorder` < $maxsort AND `disorder` >= $minsort AND $where, $silent? SILENT:''");
        $db->sql_query("UPDATE `{$tablepre}{$table}` SET `disorder` = $minsort WHERE $where, $silent? SILENT:''");  
    } elseif($sortflag == "down") {  
        $minsort = $sort;  
       // $maxsort = $_OGLOBAL['db']->result_first("select max(`sort`) from $tablename where `disorder` > $sort AND $where order by `disorder` asc limit $sortnum"); 
		   
		$result=$db->sql_query("select max(`disorder`) as maxsort from `{$tablepre}{$table}` where `disorder` > $sort AND $where order by `disorder` asc limit $sortnum");
		$bd=$db->sql_fetchrow($result);
		$maxsort=$bd['maxsort'];
		    
        $db->sql_query("UPDATE `{$tablepre}{$table}` SET `disorder` = `disorder` - 1  WHERE `disorder` <= $maxsort AND `disorder` > $minsort AND $where, $silent? SILENT:''");  
        $db->sql_query("UPDATE `{$tablepre}{$table}` SET `disorder` = $maxsort WHERE $where, $silent? SILENT:''");  
    }  
} 

//获取栏目分类
function get_class_list($name="cats") {
global $db,$upload_picpath,$tablepre,$srt; 
$sql= "SELECT id,catname FROM `{$tablepre}news_cats` WHERE pid=0 AND isstate=1 AND showtype in(1) order by disorder Asc,id ASC";
//echo $sql;
$result=$db->sql_query($sql);
$str="<select name=\"{$name}\"><option selected value=\"0\">选择要移动/复制的目标栏目</option>";
while($bd=$db->sql_fetchrow($result)){
	$tys=$bd['id'];
	$str.="<optgroup label=\"{$bd['catname']}\"/>";
		$sql1= "SELECT pid,id,catname FROM `{$tablepre}news_cats` WHERE pid={$tys} AND isstate=1 AND showtype in(1,3) order by disorder Asc,id ASC";
		//echo $sql1;
		$result1=$db->sql_query($sql1);
		while($bd1=$db->sql_fetchrow($result1)){
			//if($SmalllId==$bd1['id']) $d="  style=\"color:#094;\"";else $d="";
			$str.="<option value=\"{$bd1['pid']}|{$bd1['id']}\">{$bd1['catname']}</option>\n";
		}
}
$str.="</select> ";
return $str;
unset($sql,$result,$bd);
}

//获取上一个id
function get_pre_id($pid,$ty,$id){
	global $db,$tablepre; 
	$sql= "select id from `{$tablepre}news` where pid={$pid} and ty={$ty} and id>{$id} order by disorder desc,id desc";
	echo $sql."<br>";
	$result=$db->sql_query($sql);
	if($arr=$db->sql_fetchrow($result)){
		$str=$arr['id'];
	}else{
		$str=0;
	}
	return $str;
	unset($sql,$result,$arr);
}
	

//作用：获取类别名称
if(!function_exists('get_catname')){
	function get_catname($pid,$table="news_cats",$zd="catname") {
	global $db,$tablepre; 
		$sql= "SELECT {$zd} FROM `{$tablepre}{$table}` WHERE id=".$pid;
		//echo $sql;
 		$result=$db->sql_query($sql);
		$arr=$db->sql_fetchrow($result);
		return $arr[$zd];
	}
	unset($sql,$result,$cachekey,$arr);
}
 


//获取分类名称
function get_type_cats_list($typeid,$cid,$zd="pid",$sname="产品分类",$table="prod_cats"){
	global $db,$tablepre; 
	
	if($typeid) $sqlkey=" AND typeid={$typeid}";
	$sql="SELECT pid,id,catname FROM `{$tablepre}{$table}` WHERE pid=0 AND isstate=1 {$sqlkey} ORDER BY disorder ASC, id ASC";
	//exit($sql);
 	$result=$db->sql_query($sql);
 	$str.="<SELECT name=\"info[{$zd}]\" id=\"{$zd}\" style=\"background-color: #0000FF; color:#FFFFFF\"> 
			<OPTION value=\"0\">-{$sname}-</OPTION>";
 	
	while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$cid) $c="selected"; else $c="";
 		$str.="<option value=\"{$row['id']}\" ".$c.">{$row['catname']}</option>";
	}
	
	$str.="</SELECT>";
 	 return $str;
}

 //作用：获取信息提示
if(!function_exists('get_infotip')){
	function get_infotip($key) {
		$str="<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
		$str.="<tr>";
		$str.="<td width=\"31%\" align=\"left\"><a href=\"#\" onClick=\"history.back()\">[返回列表]</a></td>";
		$str.="<td width=\"32%\" align=\"right\"><b>{$key}信息(带<font color=#ff0000>*</font>号的必须填写 )</b> </td>";
		$str.="<td width=\"37%\" style=\"color:#FF0000;font-weight:bold;\">&nbsp;当前编辑：".$_SESSION['Admin_UserName']."&nbsp;编号：".$_SESSION['Admin_UserID']."</td>";
		$str.="</tr>";
		$str.="</table>";
		return $str;
	}
}


function frm_out_put($arr,$nm,$tag,$value='',$js="",$firstNode=""){
/***********************************
	功能：从初始化到表单的生成
	参数
***********************************/
	$value=trim($value);
	switch($tag){
		case 'option':
			$str="<select name='".$nm."' id='".$nm."' ".$js." >\n";
			if($firstNode){
				$str.="\t<option value='99'>".$firstNode."</option>\n";

			}
			foreach ($arr as $k => $v) {
				if($value==$k&&$value!=''){
					$str .= "\t<$tag value='$k' selected>$v</$tag>\n";
				}else{
					$str .= "\t<$tag value='$k' $defaultCk>$v</$tag>\n";
				}
			}
			$str.="</select>";
			break;

		case 'radio':
			$i=0;
			foreach ($arr as $k => $v) {
				if($value==$k&&$value!=''){
					$str .= "\n<input type='".$tag."' name='".$nm."' id='".$nm.$k."' class='radio' checked='checked' value='".$k."' ".$js."> ".$v."</input>\n ";
				}else{
					if($i==0 && $value==''){$defaultCk = 'checked';$i++;}
					$str .= "\n<input type='".$tag."' name='".$nm."' id='".$nm.$k."' class='radio' value='".$k."' ".$js." $defaultCk> ".$v."</input>\n ";
					$defaultCk='';
				}
			}
			break;

		case 'checkbox':
			if($value)
				$arrValue = explode(',',$value);
			else
				$arrValue = array();
			$checkTag=0;
			foreach ($arr as $k => $v) {
				for($i=0;$i<count($arrValue);$i++){
					if($arrValue[$i]==$k&&$value!=''){
						$str .= "\n<span><input type='".$tag."' name='".$nm."[]' value='".$k."' checked='checked' ".$js.">".$v."</input></span>\n";
						$checkTag++;
					}
				}
				if($checkTag==0){
					$str .= "\n<span><input type='".$tag."' name='".$nm."[]' value='".$k."' ".$js.">".$v."</input></span>\n";
				}
				$checkTag=0;
			}
			break;
	}
	return $str;
}

//判断单选和多选框选中
function get_frm_checked($v1,$v2){
 	if($v1==$v2) $str="checked";;
	return $str;
}	

//获取信息的列表
function get_news_list($pid=1,$ty=6,$pagesize=15) {
	global $db,$tablepre,$srt; 
	$page=(int)$_GET['page']==0?1:(int)$_GET['page'];
 	if($pid) $sqlkey.=" AND pid={$pid}";
	if($ty) $sqlkey.=" AND ty={$ty}";
	
	$sql= "SELECT disorder FROM `{$tablepre}news` WHERE isstate=1 ".$sqlkey." order by disorder desc,id desc";
 	$pagestr=page_list($sql,$page,$pagesize);
	$sql.=" limit ".(($page-1)*$pagesize).",$pagesize";	
	//echo $sql;
	$result=$db->sql_query($sql);
	while($row=$db->sql_fetchrow($result)){
		$arr[]=$row;
 	}
	return $arr;
	unset($sql,$arr,$bd);
}

//获取信息的列表
function get_frm_list($tag,$oldpid,$newpid=0,$formname="pid",$table="other_cats",$firstNode="无(属一级分类)",$js=''){
	global $db,$tablepre; 
	
 	if ($oldpid) $sql3.=" AND typeid={$oldpid}";
 	
 	$sql="SELECT id,catname FROM `{$tablepre}{$table}` WHERE isstate=1 and pid=0 ".$sql3." ORDER BY disorder ASC, id ASC";
	//echo $sql;
	$result=$db->sql_query($sql);
	while($row=$db->sql_fetchrow($result)){
		$arr[]=$row;
	}
	
	switch($tag){
		case 'option':
		$str.="<select name={$formname} {$js}>";
			if($firstNode){
				$str.="\t<option value=\"0\">".$firstNode."</option>\n";
			}		
			foreach ($arr as $k => $v) {
				if ($v['id']==(int)$newpid) $c="selected"; else $c="";
				$str.="\t<option value='".$v['id']."' ".$c.">".$v['catname']."</option>\n";
			}
			$str.="</select>";
			break;

		case 'radio':
			$i=0;
			foreach ($arr as $k => $v) {
				if($newpid==$k&&$newpid!=''||$newpid==$v['id']){
					$str .= "\n<input type='".$tag."' name='".$formname."' id='".$formname.$v['id']."' class=\"radio\" checked=\"checked\" value='".$v['id']."' ".$js."> <label for='".$formname.$v['id']."'>".$v['catname']."</label></input>\n ";
				}else{
					if($i==0 && $newpid==''){$defaultCk = 'checked';$i++;}
					$str .= "\n<input type='".$tag."' name='".$formname."' id='".$formname.$v['id']."' class=\"radio\" value='".$v['id']."' ".$js." $defaultCk> <label for='".$formname.$k."'>".$v['catname']."</label></input>\n ";
					$defaultCk='';
				}
			}
		break;

		case 'checkbox':
			if($newpid)
				$arrValue = explode(',',$newpid);
			else
				$arrValue = array();
			$checkTag=0;
			foreach ($arr as $k => $v) {
				for($i=0;$i<count($arrValue);$i++){
					if($arrValue[$i]==$k+1&&$newpid!=''||$v['id']==$arrValue[$i]){
						$str .= "\n<input type='".$tag."' name='".$formname."[]' value='".$v['id']."' checked=\"checked\" {$js}>".$v['catname']."</input>\n";
						$checkTag++;
					}
				}
				if($checkTag==0){
					$str .= "\n<input type='".$tag."' name='".$formname."[]' value='".$v['id']."' {$js}>".$v['catname']."</input>\n";
				}
				$checkTag=0;
			}
		break;
	}
	return $str;
}


/**

* 加密，分别能加密和解密。通过传入$operation = 'ENCODE'|'DECODE' 来实现。

*/
function authcode($string,$operation='ENCODE') {

	if ($operation=='ENCODE'){

		$OutTxt = "";

		for ($x=0;$x<strlen($string);$x++) {

			$nr = ord($string[$x]);

			if ($nr < 128) {

				$nr += 128;

			}

			elseif ($nr > 127) {

				$nr -= 128;

			}

			$nr = 255 - $nr;

			$OutTxt .= sprintf("%02x", $nr);

		}

		return $OutTxt;

	}else{

		$OutTxt = "";

		for ($x=0;$x<(strlen($string)/2);$x++) {

			$nr = hexdec($string[$x * 2] . $string[($x * 2) + 1]);

			$nr = 255 - $nr;

			if ($nr < 128) {

				$nr += 128;

			}

			elseif ($nr > 127) {

				$nr -= 128;

			}

			$OutTxt .= chr($nr);

		}

		return $OutTxt;

	}

}



/**

* 字符串分割

*/

function cutstr($string, $length, $dot='...') {

	global $charset;



	if(strlen($string) <= $length) {

		return $string;

	}



	$string = str_replace(array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;'), array('&', '"', '\'', '<', '>'), $string);

	$string = str_replace('&nbsp;', ' ', $string);



	$strcut = '';

	if(strtolower($charset) == 'utf-8') {



		$n = $tn = $noc = 0;

		while($n < strlen($string)) {



			$t = ord($string[$n]);

			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {

				$tn = 1; $n++; $noc++;

			} elseif(194 <= $t && $t <= 223) {

				$tn = 2; $n += 2; $noc += 2;

			} elseif(224 <= $t && $t < 239) {

				$tn = 3; $n += 3; $noc += 2;

			} elseif(240 <= $t && $t <= 247) {

				$tn = 4; $n += 4; $noc += 2;

			} elseif(248 <= $t && $t <= 251) {

				$tn = 5; $n += 5; $noc += 2;

			} elseif($t == 252 || $t == 253) {

				$tn = 6; $n += 6; $noc += 2;

			} else {

				$n++;

			}



			if($noc >= $length) {

				break;

			}



		}

		if($noc > $length) {

			$n -= $tn;

		}



		$strcut = substr($string, 0, $n);



	} else {

		for($i = 0; $i < $length - strlen($dot) - 1; $i++) {

			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];

		}

	}



	$strcut = str_replace(array('&', '"', '\'', '<', '>'), array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;'), $strcut);



	return $strcut.$dot;

}

function cutstr_html($string, $sublen)    
 {
  $string = strip_tags($string);
  $string = preg_replace ('/\n/is', '', $string);
  $string = preg_replace ('/ |　/is', '', $string);
  $string = preg_replace ('/&nbsp;/is', '', $string);
   
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
  if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
  else $string = join('', array_slice($t_string[0], 0, $sublen));
   
  return $string;
 }
/**

* 清除HTML格式字符串分割

*/

function substrs($content,$length) {

	if($length && strlen($content)>$length){

		$num=0;

		for($i=0;$i<$length-3;$i++) {

			if(ord($content[$i])>127){

				$num++;

			}

		}

		$num%2==1 ? $content=substr($content,0,$length-4):$content=substr($content,0,$length-3);

		$content.='..';

	}

	$content=strip_tags($content);

	return $content;

}



/**

* 检查、创建目录

*/

function ChkPath($upfile) {

	$UpFile=split('/',$upfile);

	for ($i=0;$i<count($UpFile);$i++){

		if ($i>0){

			$FileL="/";

		}

		$FilePath=$FilePath.$FileL.$UpFile[$i];

		if ( !is_dir(ROOT_PATH.$FilePath) ){

			@mkdir(ROOT_PATH.$FilePath,0777)||

			die("建立 ".ROOT_PATH.$FilePath." 文件夹失败！");

		}

	}

	Return 1;

}



/**

* 系统路径目录建立 目录以时间命名

*/

function mkdatepath($upfile) {

	ChkPath($upfile);

	if ( !is_dir(ROOT_PATH.$upfile."/".date('ym')) ){

		@mkdir(ROOT_PATH.$upfile."/".date('ym'),0777)||

		die("建立 ".date('ym')." 文件夹失败！");

	}

	Return 1;

}



/**

* 检测并建立目录

*/

function mkpath($filepath){

	$sFilePath=split('/',$filepath);

	$tFilePath='';

	for ($i=0;$i<count($sFilePath);$i++){

		$tFilePath.=$sFilePath[$i].'/';

		if ( !is_dir(WEB_ROOT.'./'.$tFilePath) ){

			@mkdir(WEB_ROOT.'./'.$tFilePath,0777)||

			die('建立 '.$sFilePath[$i].' 文件夹失败！');

		}

	}

}



/**

* 用来过滤字串的，考虑到是不是打开了magic_quotes_gpc.

*/

function daddslashes($string, $force = 0) {

	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	if(!MAGIC_QUOTES_GPC || $force) {

		if(is_array($string)) {

			foreach($string as $key => $val) {

				$string[$key] = daddslashes($val, $force);

			}

		}else{

			$string = addslashes($string);

		}

	}

	return $string;

}



/**

* 检查一个日期是否合法

*/

function datecheck($ymd, $sep='-') {

	if(!empty($ymd)) {

		list($year, $month, $day) = explode($sep, $ymd);

		return checkdate($month, $day, $year);

	}else{

		return FALSE;

	}

}



/**

* 用来计算程序运行时间的,底部的 debug info

*/

function debuginfo() {

	if($GLOBALS['debug']) {

		global $sys_starttime, $debuginfo;

		$mtime = explode(' ', microtime());

		$debuginfo = number_format(($mtime[1] + $mtime[0] - $sys_starttime), 6);

		return TRUE;

	}else{

		return FALSE;

	}

}



/**

* 强制退出

*/

function dexit($message = '') {

	echo $message;

	exit();

}



/**        

* 过滤HTML代码

*/

function dhtmlspecialchars($string) {

	if(is_array($string)) {

		foreach($string as $key => $val) {

			$string[$key] = dhtmlspecialchars($val);

		}

	}else{

		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',

		str_replace(array('&', '"', '\'', '<', '>'), array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;'), $string));

	}

	return $string;

}



/**

* 判断是不是文件上传了

*/

function disuploadedfile($file) {

	return function_exists('is_uploaded_file') && (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));

}



/**

* 清除 Cookie

*/

function clearcookies($cvar,$cpre='',$cpath='/',$cdomain='') {

	global $cookiepre, $cookiedomain, $cookiepath, $PHP_TIME, $_SERVER;

	$cpre ? $cpre : $cookiepre;

	$cpath ? $cpath : $cookiepath;

	$cdomain ? $cdomain : $cookiedomain;

	$ctime = $PHP_TIME - 86400 * 365;

	$S=$_SERVER['SERVER_PORT'] == 443 ? 1 : 0;

	setCookie($cpre.$cvar,'',$ctime,$cpath,$cdomain,$S);

}



/**

* 保存 Cookie

*/

function destcookie($cvar,$cvalue,$cpre='',$cpath='/',$cdomain='',$ctime='F') {

	global $cookiepre, $cookiedomain, $cookiepath, $PHP_TIME, $_SERVER;

	$cpre ? $cpre : $cookiepre;

	$cpath ? $cpath : $cookiepath;

	$cdomain ? $cdomain : $cookiedomain;

	$ctime = $ctime=='F' ? $PHP_TIME+1800 : ($ctime=='' && $ctime==0 ? $PHP_TIME-1800 : $ctime);

	$S=$_SERVER['SERVER_PORT'] == 443 ? 1 : 0;

	setCookie($cpre.$cvar,$cvalue,$ctime,$cpath,$cdomain,$S);

}



/**

* 设置header

*/

function dheader($string, $replace = true, $http_response_code = 0) {

	$string = str_replace(array("\r", "\n"), array('', ''), $string);

	header($string, $replace, $http_response_code);

	if(preg_match('/^\s*location:/is', $string)) {

		exit();

	}

}



/**

* 判断访问者是不是robot

*/

function getrobot() {

	if(!defined('IS_ROBOT')) {

		$kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';

		$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';

		if(preg_match("/($kw_browsers)/", $_SERVER['HTTP_USER_AGENT'])) {

			define('IS_ROBOT', FALSE);

		} elseif(preg_match("/($kw_spiders)/", $_SERVER['HTTP_USER_AGENT'])) {

			define('IS_ROBOT', TRUE);

		} else {

			define('IS_ROBOT', FALSE);

		}

	}

	return IS_ROBOT;

}



/**

* 得到当前日期

*/

function get_date($timestamp,$timeformat=''){

	global $db_datefm,$db_timedf,$_datefm,$_timedf;

	$date_show=$timeformat ? $timeformat : ($_datefm ? $_datefm : $db_datefm);

	if($_timedf){

		$offset = $_timedf=='111' ? 0 : $_timedf;

	}else{

		$offset = $db_timedf=='111' ? 0 : $db_timedf;

	}

	return gmdate($date_show,$timestamp+$offset*3600);

}



/**

* 得到一个文件的扩展名

*/

function fileext($filename) {

	return trim(substr(strrchr($filename, '.'), 1, 10));

}



/**

* JS 动作集

* JsSucce 确认跳转、JsError 错误返回、JsClose关闭窗口、JsGourl Url跳转

*/

function JsSucce($msg,$BackURL = NULL) {

	echo("<SCRIPT LANGUAGE=\"JavaScript\"><!--\n");

	echo("alert('".$msg."');");

	if($BackURL != NULL) {

		echo("self.location='".$BackURL."';");

	}

	echo("\n//--></SCRIPT>");

	Return 1;

}



function JsError($msg) {

	echo("<SCRIPT LANGUAGE=\"JavaScript\"><!--\n");

	echo("alert('".$msg."');");

	echo("history.back(-1);");

	echo("\n//--></SCRIPT>");

	Return 1;

}



function JsClose($msg) {

	echo("<SCRIPT LANGUAGE=\"JavaScript\"><!--\n");

	echo("alert('".$msg."');");

	echo("history.back(-1);");

	echo("window.close();");

	echo("\n//--></SCRIPT>");

	Return 1;

}



function JsGourl($URL,$Tag = "self") {

	echo("<SCRIPT LANGUAGE=\"JavaScript\"><!--\n");


	echo($Tag.".location='".$URL."';");

	echo("\n//--></SCRIPT>");

	Return 1;

}



/**

* 检查一个email的合法性

*/

function isemail($email) {

	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);

}

//分页
function page_list($pagesql,$page=1,$pagesize=10){
	$page=(int)$page<1?1:$page;
	$result=mysql_query($pagesql);
	@$numrows=mysql_num_rows($result);
 	$url=basename($HTTP_SERVER_VARS['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'];
 	$url=preg_replace('/[&]?page=[\w]*[&]?/i','',$url);
 	$pages=$numrows%$pagesize==0?$numrows/$pagesize:(int)($numrows/$pagesize)+1;
	if($pages==0 or $pages=="") return;		
	$pagestr='';
	if($page>$pages){
		echo "<script>location.href=\"$url&page=$pages\"</script>";
		return;
	}
	$first=1;
	$prev=$page-1;
	$next=$page+1;
	$last=$pages;
 	$total_pages = $pages;
	$on_page = $page;
	$num=$numrows;
    $pagestr=$pagestr."<div class=\"fy\">\n";
	 if ($page==1){
		$pagestr=$pagestr."<a href=\"javascript:void()\">首页</a>\n";
		$pagestr=$pagestr."<a href=\"javascript:void()\">上一页</a>\n";
     }
     if ($page>1){
		$pagestr=$pagestr."<a href='$url&page=".$first."'>首页</a>";
		$pagestr=$pagestr."<a href='$url&page=".$prev."'>上一页</a>\n";
     }
	
	if ($on_page<5) { $i_num=1;$i_max=9;} else{ $i_num=$on_page-4;$i_max=$on_page+5;}
 	for($i=$i_num;$i<$i_max;$i++){
		
  		$page_nums=($i-1)*$pagesize;
 		if ($page_nums<$num){
			if ($i==$on_page){
				$str="<a href=\"javascript:void()\" id=\"active1\">{$i}</a>\n";
			}else{
				$str="<a href=\"{$url}&page={$i}\">{$i}</a>\n";
			}
			$pagestr=$pagestr."".$str."";
		}
  	}
	
	if ($page==$pages){
		$pagestr=$pagestr."<a href=\"javascript:void()\">下一页</a>\n";
		$pagestr=$pagestr."<a href=\"javascript:void()\">末页</a>\n";
     }
	if ($page<$pages){
		$pagestr=$pagestr."<a href='$url&page=".$next."'>下一页</a>\n";
		$pagestr=$pagestr."<a href='$url&page=".$last."'>末页</a>";
	}
	
	$pagestr=$pagestr."<select class=\"fl\" name=select onchange=\"self.location.href=this.options[this.selectedIndex].value\" style=\"background-color:#0000FF; color:#FFFFFF\">\n";
 	for($i=1;$i<=$pages;$i++){
		if($i==$page) $s="selected";else $s="";
		$pagestr=$pagestr."<option value=\"{$url}&page={$i}\"{$s}>{$i}</option>\n";
	}
	$pagestr=$pagestr."</select>\n";
	
	
	
	$pagestr=$pagestr."</div>";
	return $pagestr;
}	


/**

* 分页函数

*/
function PageList($BaseUrl, $NumItems, $PerPage, $StartPageItem, $Couns = 0, $AddPrevnextText = TRUE){

	$total_pages = ceil($NumItems/$PerPage);

	$on_page = floor($StartPageItem / $PerPage) + 1;



	if ( $total_pages < 2 ){

		return '';

	}else{

		if (!$Couns){

			$page_string='<div id="info">';

			$page_string.="<SCRIPT LANGUAGE='JavaScript'>function Goto(){var StartPage = (GotoLink.StartPage.value -1) * ".$PerPage.";self.location='".$BaseUrl."&StartPage=' + StartPage;}</SCRIPT>";

			$page_string.='<form method="GET" action="" name="GotoLink">';

			$page_string.='共 <font class="fh">'.$NumItems.'</font> 条　第 <font class="fh">'.$on_page.'</font> 页 / 共 <font class="fh">'.$total_pages.'</font> 页';

			$page_string.='　转到 <input type="text" name="StartPage" value="'.$on_page.'" class="in"> 页 <input type="button" name="goTo" onclick="Goto();" value="GO" class="go"></form>';

			$page_string.="</div>";

		}



		$page_string.='<div id="page">';



		//定义“首页”和“上一页”连接

		if ($on_page > 1){

			$page_string.='<a href="'.$BaseUrl.'">[首页]</a>　<a href="'.$BaseUrl.'&StartPage='.(($on_page-2 )*$PerPage).'">[上一页]</a>　';

		}else{

			$page_string.='<span disabled>[首页]　[上一页]</span>　';

		}



		//定义页码连接

		if ($on_page<7) { $i_num=1;$i_max=14;} else{ $i_num=$on_page-6;$i_max=$on_page+7;}

		for($i=$i_num;$i<$i_max;$i++){

			$page_nums=($i-1)*$PerPage;

			if ($page_nums<$NumItems){

				if ($i==$on_page){

					$page_string.=$i." ";

				}else{

					$page_string.='<a href="'.$BaseUrl.'&StartPage='.$page_nums.'">['.$i.']</a> ';

				}

			}

		}



		//定义“尾页”和“下一页”连接

		if ($on_page < $total_pages){

			$page_string.='<a href="'.$BaseUrl.'&StartPage='.($on_page * $PerPage).'">[下一页]</a>　<a href="'.$BaseUrl.'&StartPage='.(($total_pages-1)* $PerPage).'">[尾页]</a>';

		}else{

			$page_string.='<span disabled>[下一页]　[尾页]<span>';

		}



		$page_string.='</div>';

		return $page_string;

	}

}



/**

* 查找$haystack是不是在$needle中存在

*/

function strexists($haystack, $needle) {

	return !(strpos($haystack, $needle) === FALSE);

}


//增加日志文件

function AddLog($_logcontent,$_logname) {

	global $PHP_TIME,$PHP_IP,$db,$tablepre;
	$db->sql_query("INSERT INTO `{$tablepre}logs`(username,content,ip,sendtime) VALUES ('".$_logname."','".$_logcontent."','".$PHP_IP."','".$PHP_TIME."')");
	
	//Return 1;

}


//身份验证

function CheckLogin() {

	global $REQUEST_URI,$UserInfo,$CONFIG,$db;



	if (empty($UserInfo[USERNAME]) || empty($UserInfo[USERBRANCH]) || empty($UserInfo[PRIVID])) {

		JsGoURL("/".$CONFIG['mywebdir']."/","top");

		Return 0;

	}



	//用户权限判断

	$REQUEST_URI = substr($REQUEST_URI,strlen("/".$CONFIG['mywebdir']));

	$sql="SELECT Id FROM `{$tablepre}menu` WHERE Url='$REQUEST_URI'";

	$result=$db->sql_query($sql);

	$bd=$db->sql_fetchrow($result);



	//判断Id是否存在权限列表中

	$aPRIVID=split(',',$UserInfo['PRIVID']);
	/*
	if (!(in_array($bd['Id'],$aPRIVID)) && $bd['Id']!=""){

		JsError("您没有权限操作此项目！");

		Return 0;

	}
	*/



	Return 1;

}



//获取用户信息

function GetUserInfo() {

	global $UserInfo;

	Return $UserInfo;

}



//保存用户信息

function SetUserInfo($UserInfo) {

	global $PHP_TIME;

	setCookie("UserInfo[Id]",$UserInfo[Id],$PHP_TIME+12*3600,'/');							//用户ID
	
	setCookie("UserInfo[USERNAME]",$UserInfo[USERNAME],$PHP_TIME+12*3600,'/');			//用户帐号

	setCookie("UserInfo[CNAME]",$UserInfo[CNAME],$PHP_TIME+12*3600,'/');				//用户姓名

	setCookie("UserInfo[LASTTIME]",$UserInfo[LASTTIME],$PHP_TIME+12*3600,'/');			//登录时间

	setCookie("UserInfo[USERBRANCH]",$UserInfo[USERBRANCH],$PHP_TIME+12*3600,'/');		//用户所在部门

	setCookie("UserInfo[USERPRIV]",$UserInfo[USERPRIV],$PHP_TIME+12*3600,'/');			//用户角色

	setCookie("UserInfo[PRIVID]",$UserInfo[PRIVID],$PHP_TIME+12*3600,'/');				//用户角色ID

	Return 1;

}



//用户注销

function UserLogout() {

	global $UserInfo,$PHP_TIME,$db,$tablepre;

	$sql = "UPDATE {$tablepre}user SET Online=0 WHERE Username='".$UserInfo[USERNAME]."'";

	$db->sql_query($sql);



	$ctime = $PHP_TIME - 86400 * 365;


	setcookie("UserInfo[Id]",'',$ctime,'/');					//用户Id

	setcookie("UserInfo[CNAME]",'',$ctime,'/');					//用户姓名

	setcookie("UserInfo[LASTTIME]",'',$ctime,'/');				//登录时间

	setcookie("UserInfo[USERBRANCH]",'',$ctime,'/');			//用户所在部门

	setcookie("UserInfo[USERPRIV]",'',$ctime,'/');				//用户角色

	setcookie("UserInfo[PRIVID]",'',$ctime,'/');				//用户角色ID

	Return 1;

}

//图片上传
function uploadimg($obj,$path,$name){
	global $System_Pictype,$System_Picsize;
	$picsAllowExt  = $System_Pictype;    							//允许上传图片类型
	$picmax_thumbs_size=$System_Picsize;							//允许上传图片大小
	$picaExt = explode("|",$picsAllowExt);							//图片文件
	$uppic=$_FILES[$obj]['name'];									//文件名称
	$thumbs_type=strtolower(substr($uppic,strrpos($uppic,".")+1));	//上传类型
	$thumbs_file=$_FILES[$obj]['tmp_name'];							//临时文件
	$thumbs_size=$_FILES[$obj]['size'];								//文件大小
	$imageinfo = getimagesize($thumbs_file); 
	
	
	$upfile=$path.$name;
	if(in_array($thumbs_type,$picaExt)&&$thumbs_size>intval($picmax_thumbs_size)*1024){
		JsError("图片上传大小超过上限:".ceil($picmax_thumbs_size/1024)."M！");
		exit();
	}
	
	if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/bmp') { 
  	 	JsError("非法图像文件！");
     	exit();
    }
	
	if(!in_array($thumbs_type,$picaExt)){
		JsError("上传图片格式不对，请上传".$System_Pictype."格式的图片！");
		exit();
	}
	if (!is_writable($path)){
		//修改文件夹权限
		$oldumask = umask(0);
		mkdir($path,0777);
		umask($oldumask);
		JsError('请确保文件夹的存在或文件夹有写入权限!');
		exit();
	}
	if(!copy($thumbs_file,$upfile)){
		JsError('上传失败!');
		exit();
	};
}

//文件上传
function uploadfile($obj,$path,$name)
{
	global $System_Filetype,$System_Filesize;
	$filesAllowExt  = $System_Filetype;    							//文件后缀
	$filemax_thumbs_size= $System_Filesize;							//文件大小
	$fileaExt = explode("|",$filesAllowExt);						//图片文件
	$uppic=$_FILES[$obj]['name'];									//文件名称
	$thumbs_type=strtolower(substr($uppic,strrpos($uppic,".")+1));	//上传类型
	$thumbs_file=$_FILES[$obj]['tmp_name'];							//临时文件
	$thumbs_size=$_FILES[$obj]['size'];								//文件大小
	$imageinfo = getimagesize($thumbs_file); 
	
	
	$upfile=$path.$name;
	if(in_array($thumbs_type,$fileaExt)&&$thumbs_size>intval($filemax_thumbs_size)*1024){
			JsError("附件上传大小超过上限:".ceil($filemax_thumbs_size/1024)."M！");
		exit();
	}
	
	/*
	if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/bmp') { 
  	 	JsError("非法图像文件！");
     	exit();
    }*/ 
	
	if(!in_array($thumbs_type,$fileaExt)){
		JsError("上传附件格式不对，请上传".$System_Filetype."格式文件！");
		exit();
	}
	if (!is_writable($path)){
		//修改文件夹权限
		$oldumask = umask(0);
		mkdir($path,0777);
		umask($oldumask);
		JsError('请确保文件夹的存在或文件夹有写入权限!');
		exit();
	}
	if(!copy($thumbs_file,$upfile)){
		JsError('上传失败!');
		exit();
	};
}



//加图片水印

function imageWaterMark($groundImage,$waterPos=0,$waterImage=""){ 

	global $CONFIG;

	$waterImage = $waterImage == "" ? $CONFIG['waterimg'] : $waterImage;

	$isWaterImage = FALSE; 

	$formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。";

	

	//读取水印文件 

	if(!empty($waterImage) && file_exists($waterImage)){ 

		$isWaterImage = TRUE; 

		$water_info = getimagesize($waterImage); 

		$water_w   = $water_info[0];//取得水印图片的宽 

		$water_h   = $water_info[1];//取得水印图片的高 



		//取得水印图片的格式 

		switch($water_info[2]){ 

			case 1:$water_im = imagecreatefromgif($waterImage);break; 

			case 2:$water_im = imagecreatefromjpeg($waterImage);break; 

			case 3:$water_im = imagecreatefrompng($waterImage);break; 

			default:die($formatMsg); 

		} 

	} 



	//读取背景图片 

	if(!empty($groundImage) && file_exists($groundImage)){

		$ground_info = getimagesize($groundImage);

		$ground_w   = $ground_info[0];//取得背景图片的宽

		$ground_h   = $ground_info[1];//取得背景图片的高



		//取得背景图片的格式

		switch($ground_info[2]){

			case 1:$ground_im = imagecreatefromgif($groundImage);break;

			case 2:$ground_im = imagecreatefromjpeg($groundImage);break;

			case 3:$ground_im = imagecreatefrompng($groundImage);break;

			default:die($formatMsg);

		}



		$ground_ext=strtolower(strrchr($groundImage,"."));

		if(strstr($ground_ext,"jp")){ 



			//水印位置 

			$w = $water_w;

			$h = $water_h;

			

			//需要加水印的图片的长度或宽度比水印大时，开始加水印

			if( !($ground_w<$w) || !($ground_h<$h) ){



				switch($waterPos){ 

					case 0://随机 

						$posX = rand(0,($ground_w - $w)); 

						$posY = rand(0,($ground_h - $h)); 

						break; 

					case 1://1为顶端居左 

						$posX = 0; 

						$posY = 0; 

						break; 

					case 2://2为顶端居中 

						$posX = ($ground_w - $w) / 2; 

						$posY = 0; 

						break; 

					case 3://3为顶端居右 

						$posX = $ground_w - $w; 

						$posY = 0; 

						break; 

					case 4://4为中部居左 

						$posX = 0; 

						$posY = ($ground_h - $h) / 2; 

						break; 

					case 5://5为中部居中 

						$posX = ($ground_w - $w) / 2; 

						$posY = ($ground_h - $h) / 2; 

						break; 

					case 6://6为中部居右 

						$posX = $ground_w - $w; 

						$posY = ($ground_h - $h) / 2; 

						break; 

					case 7://7为底端居左 

						$posX = 0; 

						$posY = $ground_h - $h; 

						break; 

					case 8://8为底端居中 

						$posX = ($ground_w - $w) / 2; 

						$posY = $ground_h - $h; 

						break; 

					case 9://9为底端居右 

						$posX = $ground_w - $w; 

						$posY = $ground_h - $h; 

						break; 

					default://随机 

						$posX = rand(0,($ground_w - $w)); 

						$posY = rand(0,($ground_h - $h)); 

						break;   

				}



				//设定图像的溷色模式 

				imagealphablending($ground_im, true); 



				//加水印 

				imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);//拷贝水印到目标文件       



				//生成水印后的图片 

				@unlink($groundImage); 

				switch($ground_info[2])//取得背景图片的格式 

				{ 

					case 1:imagegif($ground_im,$groundImage);break; 

					case 2:imagejpeg($ground_im,$groundImage);break; 

					case 3:imagepng($ground_im,$groundImage);break; 

					default:die($errorMsg); 

				} 



				//释放内存 

				if(isset($water_info)) unset($water_info); 

				if(isset($water_im)) imagedestroy($water_im); 

				unset($ground_info); 

				imagedestroy($ground_im);

			}

		}

	}

}

//返回错误信息并返回上一页面
function MsgBox($Msg){
	echo "
	<!DOCTYPE html PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN>
	<html>
	<head>
	<meta http-equiv=Content-Type content=text/html; charset=utf-8>
	<title>信息提示</title>
	<style type=text/css>
	#redirect {MARGIN: 50px 25% 12px 25%}
	#h2 {font-size:12px;color:#fff;padding:4px 0px 2px 8px}
	#txt {font-size:12px;padding:10px}
	#txt a{color:#0066b9;text-decoration:underline}
	#txt a:hover {color:#b42000;text-decoration:underline}
	#input {font-size:12px}
	</style>
	</head>
	<body>
	<table cellpadding=0 cellspacing=1 bgcolor=#0066B9 width=370 align=center id=redirect>
	<tr>
	<td id=h2>信息提示</td>
	</tr>
	<tr>
	<td bgcolor=#EFEFEF id=txt height=50>{$Msg}</td>
	</tr>
	</table>
	</body>
	</html>";
}

//返回错误信息并返回上一页面
function ErrorHtml($Msg){
	echo "
	<!DOCTYPE html PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN>
	<html>
	<head>
	<meta http-equiv=Content-Type content=text/html; charset=utf-8>
	<title>".$sitename."</title>
	<style type=text/css>
	#redirect {MARGIN: 50px 25% 12px 25%}
	#h2 {font-size:12px;color:#fff;padding:4px 0px 2px 8px}
	#txt {font-size:12px;padding:10px}
	#txt a{color:#0066b9;text-decoration:underline}
	#txt a:hover {color:#b42000;text-decoration:underline}
	#input {font-size:12px}
	</style>
	</head>
	<body>
	<table cellpadding=0 cellspacing=1 bgcolor=#0066B9 width=370 align=center id=redirect>
	<tr>
	<td id=h2>信息提示</td>
	</tr>
	<tr>
	<td bgcolor=#EFEFEF id=txt height=50>{$Msg}</td>
	</tr>
	</table>
	</body>
	</html>";
}

//编辑器调用
function GetEwebeditor($Content,$id="content",$BasePath="myeditor",$ToolbarSet="whj220",$Width=550,$Height=350){
	$str="<INPUT type='hidden' name='{$id}' value='{$Content}' id='movie'>";
	$str.="<IFRAME ID='eWebEditor1' src='../{$BasePath}/ewebeditor.htm?id={$id}&style={$ToolbarSet}' frameborder='0' scrolling='no' width='{$Width}' height='{$Height}'></IFRAME>";
	echo $str;
}

//编辑器调用
function showfck($val= '',$name='content', $toolbarset = 'Basic', $width = '520px', $height = '350px'){ 
	
	require_once WEB_ROOT . '../fckeditor/fckeditor.php'; 
	
	$fck = new FCKeditor($name); 
	
	$fck->BasePath = '../fckeditor/'; 
	
	$fck->ToolbarSet = $toolbarset; 
	
	$fck->Value = $val; 
	
	$fck->Width = $width; 
	
	$fck->Height = $height; 
	
	$fck->Create(); 
	
}


//编辑器调用
function get_kindeditor($val= '',$nr="content",$width = '520', $height = '350'){ 
	
	$editor="<textarea class=\"editor_id\" name=\"info[{$nr}]\" style=\"width:{$width}px;height:{$height}px;\">{$val}</textarea>";
	return $editor;
}

//编辑器调用
function get_kindeditor2($val= '',$nr="content",$width = '520', $height = '350'){ 
	
	$editor="<textarea class=\"editor_id\" name=\"{$nr}\" style=\"width:{$width}px;height:{$height}px;\">{$val}</textarea>";
	return $editor;
}


//作用：数量统计
if(!function_exists('get_count')){
	function get_count($where,$table="news") {
		global $db,$tablepre; 
 		$sql= "SELECT count(*) as counts FROM `{$tablepre}{$table}` where 1=1 {$where}";
		  //echo $sql;
    	$result=$db->sql_query($sql);
		if($row=$db->sql_fetchrow($result)){
			$str=(int)$row['counts'];
		}
		return $str;
	}
	unset($sql,$result,$str);
}

//获取子类数量
function get_son_count($pid){
	global $db,$tablepre; 
	
 	$sql="SELECT id FROM `{$tablepre}news_cats` WHERE isstate=1 and pid={$pid}";
	//echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	$num=(int)$PageCount;
	return $num;
}



//获取信息的列表
function cat_list($pid,$ty){
	global $db,$tablepre; 
	
	if($ty) $sqlkey=" AND pid={$ty}";
	$sql="SELECT pid,id,catname FROM `{$tablepre}news_cats` WHERE isstate=1 {$sqlkey} ORDER BY disorder ASC, id ASC";
	//echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount>0){
		while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$newpid) $c="selected"; else $c="";
			$str.="<a href='news.php?pid={$pid}&ty={$row['pid']}&tty={$row['id']}' ".$c.">".$row['catname']."</a> ";
		}
	 }
	 return $str;
}


//获取信息的列表
function get_level_list($newpid=8,$tyname="typeid"){
	global $db,$tablepre; 
	
 	$sql="SELECT id,title,price FROM `{$tablepre}level` WHERE isstate=1  ORDER BY id ASC";
	 //echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount>0){
		$str.="<SELECT name=\"{$tyname}\"  style=\"color:#FFFFFF; background-color:#0000FF\">";
		$str.="<OPTION value=\"0\">请选择客户等级</OPTION>";
		while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$newpid) $c="selected"; else $c="";
			$str.="<option value='".$row['id']."' ".$c.">".$row['title']."</option>";
		}
		$str.="</SELECT>";
	 }
	 return $str;
}



//获取信息的列表
function get_type_list($typeid,$cid,$tyname="sid"){
	global $db,$tablepre; 
	
 	$sql="SELECT * FROM `{$tablepre}prod_cats` WHERE typeid={$typeid} AND isstate=1 ORDER BY disorder ASC, id ASC";
	// echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount>0){
		$str.="<SELECT name=\"{$tyname}\">";
		$str.="<OPTION value=\"0\">请选择</OPTION>";
		while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$cid) $c="selected"; else $c="";
			$str.="<option value='".$row['id']."' ".$c.">".$row['catname']."</option>";
		}
		$str.="</SELECT>";
	 }
	 return $str;
}

 

//获取信息的列表
function dw_list($oldpid,$newpid=8,$table="news",$tyname="sid"){
	global $db,$tablepre; 
	
	if($oldpid) $sqlkey=" AND pid={$oldpid}";
	$sql="SELECT id,title FROM `{$tablepre}{$table}` WHERE isstate=1 {$sqlkey} ORDER BY disorder ASC, id ASC";
	 //echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount>0){
		$str.="<SELECT name=\"{$tyname}\">";
		$str.="<OPTION value=\"0\">请选择</OPTION>";
		while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$newpid) $c="selected"; else $c="";
			$str.="<option value='".$row['id']."' ".$c.">".$row['title']."</option>";
		}
		$str.="</SELECT>";
	 }
	 return $str;
}

 

//获取信息的列表
function dw_type_list($typeid,$newpid=0,$table="type_cats",$tyname="pid",$tname="请选择分类"){
	global $db,$tablepre; 
	
	if($typeid) $sqlkey=" AND typeid={$typeid}";
	$sql="SELECT id,catname FROM `{$tablepre}{$table}` WHERE isstate=1 {$sqlkey} ORDER BY disorder ASC, id ASC";
	 //echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount>0){
		$str.="<SELECT name=\"{$tyname}\"  style=\"color:#FFFFFF; background-color:#0000FF\">";
		$str.="<OPTION value=\"0\">{$tname}</OPTION>";
		while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$newpid) $c="selected"; else $c="";
			$str.="<option value='".$row['id']."' ".$c.">".$row['catname']."</option>";
		}
		$str.="</SELECT>";
	 }
	 return $str;
}

function optionlist2($table,$nm,$formname,$sname,$TypeId,$oldpid,$oldty,$oldtty){
	global $db,$tablepre; 
	if ($TypeId) $sqlkey=" AND typeid={$TypeId}";
?>
	   <select name="<?=$nm?>" style="color:#FFFFFF; background-color:#0000FF">
	   	<option selected="selected" value="0|0|0"><?=$sname?></option>
		<?
		$sqlstr1="SELECT * FROM `{$tablepre}{$table}` WHERE pid=0 ".$sqlkey." ORDER BY disorder ASC,id ASC";
		//echo $sqlstr1;
		$resultstr1 = mysql_query($sqlstr1);
		while($rsstr=mysql_fetch_array($resultstr1)){
		?>
		<option value='<?=$rsstr['id']?>|0|0'><?=$rsstr['catname']?></option>
		<?
		$sqlstr2="SELECT * FROM `{$tablepre}{$table}` WHERE pid=".$rsstr['id']." ".$sqlkey." ORDER BY disorder ASC,id ASC";
		$resultstr2 = mysql_query($sqlstr2);
		while($rsstr2=mysql_fetch_array($resultstr2)){
		 ?>
		<option value='<?=$rsstr['id']?>|<?=$rsstr2['id']?>|0'>┝<?=$rsstr2['catname']?></option>
		<?
		$sqlstr3="SELECT * FROM `{$tablepre}{$table}` WHERE pid=".$rsstr2['id']."  ".$sqlkey." ORDER BY disorder ASC,id ASC";
		$resultstr3 = mysql_query($sqlstr3);
		while($rsstr3=mysql_fetch_array($resultstr3)){
		 ?>
		<option value='<?=$rsstr['id']?>|<?=$rsstr2['id']?>|<?=$rsstr3['id']?>'>└└<?=$rsstr3['catname']?></option>
		<?
					}
				}
			}
		?>      
		</select>
<? 
if ($oldpid and $oldty and $oldtty) {
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|{$oldty}|{$oldtty}\"</script>";
}elseif ($oldpid and $oldty){
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|{$oldty}|0\"</script>";
}elseif ($oldpid) {
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|0|0\"</script>";
}else{
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"0|0|0\"</script>";
 }
}
 
function optionlist($table,$nm,$formname,$sname,$TypeId,$oldpid,$oldty){
	global $db,$tablepre; 
	if ($TypeId) $sqlkey=" AND typeid={$TypeId}";
?>
	   <select name="<?=$nm?>" class="dfinput">
	   	<option selected="selected" value="0|0"><?=$sname?></option>
		<?
		$sqlstr1="SELECT * FROM `{$tablepre}{$table}` WHERE pid=0 AND typeid=1 ".$sqlkey." ORDER BY disorder ASC,id ASC";
		//echo $sqlstr1;
		$resultstr1 = mysql_query($sqlstr1);
		while($rsstr1=mysql_fetch_array($resultstr1)){
		?>
		<option value='<?=$rsstr1['id']?>|0'><?=$rsstr1['catname']?></option>
		<?
		$sqlstr2="SELECT * FROM `{$tablepre}{$table}` WHERE pid=".$rsstr1['id']." ".$sqlkey." ORDER BY disorder ASC,id ASC";
		$resultstr2 = mysql_query($sqlstr2);
		while($rsstr2=mysql_fetch_array($resultstr2)){
		 ?>
		<option value='<?=$rsstr1['id']?>|<?=$rsstr2['id']?>'>┝<?=$rsstr2['catname']?></option>
		<?
				}
			}
		?>      
		</select>
<?  
	if ($oldpid and $oldty){
		echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|{$oldty}\"</script>";
	}elseif ($oldpid) {
		echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|0\"</script>";
	}else{
		echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"0|0\"</script>";
	}
}





//--------2015年更新-----------

/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
	$encoding = 'utf-8';
	if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
	if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,$encoding);
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}

function new_html_entity_decode($string) {
	$encoding = 'utf-8';
	if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
	return html_entity_decode($string,ENT_QUOTES,$encoding);
}

function new_htmlentities($string) {
	$encoding = 'utf-8';
	if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
	return htmlentities($string,ENT_QUOTES,$encoding);
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	return $string;
}

/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2); 

	for ($i = 0; $i < sizeof($parm); $i++) { 
		$pattern = '/'; 
		for ($j = 0; $j < strlen($parm[$i]); $j++) { 
			if ($j > 0) { 
				$pattern .= '('; 
				$pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
				$pattern .= '|(&#0([9][10][13]);?)?'; 
				$pattern .= ')?'; 
			}
			$pattern .= $parm[$i][$j]; 
		}
		$pattern .= '/i';
		$string = preg_replace($pattern, ' ', $string); 
	}
	return $string;
}

/**
 * 过滤ASCII码从0-28的控制字符
 * @return String
 */
function trim_unsafe_control_chars($str) {
	$rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
	return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
}

/**
 * 格式化文本域内容
 *
 * @param $string 文本域内容
 * @return string
 */
function trim_textarea($string) {
	$string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
	return $string;
}

/**
 * 将文本格式成适合js输出的字符串
 * @param string $string 需要处理的字符串
 * @param intval $isjs 是否执行字符串格式化，默认为执行
 * @return string 处理后的字符串
 */
function format_js($string, $isjs = 1) {
	$string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
	return $isjs ? 'document.write("'.$string.'");' : $string;
}

/**
 * 转义 javascript 代码标记
 *
 * @param $str
 * @return mixed
 */
 function trim_script($str) {
	if(is_array($str)){
		foreach ($str as $key => $val){
			$str[$key] = trim_script($val);
		}
 	}else{
 		$str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
		$str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
		$str = str_replace ( 'javascript:', 'javascript：', $str );
 	}
	return $str;
}
/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length, $dot = '...') {
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8') {
		$length = intval($length-strlen($dot)-$length/3);
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		$strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
	} else {
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		$current_str = '';
		$search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
		$replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
		$search_flip = array_flip($search_arr);
		for ($i = 0; $i < $maxi; $i++) {
			$current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			if (in_array($current_str, $search_arr)) {
				$key = $search_flip[$current_str];
				$current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
			}
			$strcut .= $current_str;
		}
	}
	return $strcut.$dot;
}



/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip() {
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

function get_cost_time() {
	$microtime = microtime ( TRUE );
	return $microtime - SYS_START_TIME;
}
/**
 * 程序执行时间
 *
 * @return	int	单位ms
 */
function execute_time() {
	$stime = explode ( ' ', SYS_START_TIME );
	$etime = explode ( ' ', microtime () );
	return number_format ( ($etime [1] + $etime [0] - $stime [1] - $stime [0]), 6 );
}

/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
	if($data == '') return array();
	@eval("\$array = $data;");
	return $array;
}
/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}

/**
* 转换字节数为其他单位
*
*
* @param	string	$filesize	字节大小
* @return	string	返回大小
*/
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}
/**
* 字符串加密、解密函数
*
*
* @param	string	$txt		字符串
* @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
* @param	string	$key		密钥：数字、字母、下划线
* @param	string	$expiry		过期时间
* @return	string
*/
function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
	$key_length = 4;
	$key = md5($key != '' ? $key : pc_base::load_config('system', 'auth_key'));
	$fixedkey = md5($key);
	$egiskeys = md5(substr($fixedkey, 16, 16));
	$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
	$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));

	$i = 0; $result = '';
	$string_length = strlen($string);
	for ($i = 0; $i < $string_length; $i++){
		$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
	}
	if($operation == 'ENCODE') {
		return $runtokey . str_replace('=', '', base64_encode($result));
	} else {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	}
}
?>