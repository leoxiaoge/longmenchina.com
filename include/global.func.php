<?php
//********************************************
//	作者：Whj
//	时间：2010-5-1
//	作用：
//********************************************


if(!defined('IN_COPY')) {
	exit('Access Denied');
}

//增加日志文件


function Add_logs($uid,$_logcontent,$users='file_logs') {

	global $PHP_TIME,$PHP_IP,$db,$tablepre;
	$db->sql_query("INSERT INTO `{$tablepre}{$users}`(uid,content,ip,sendtime) VALUES ('".$uid."','".$_logcontent."','".$PHP_IP."','".$PHP_TIME."')");
	
	//Return 1;
}


//编辑器调用
function get_kindeditor($val= '',$nr="content",$width = '667', $height = '350'){ 
	
	$editor="<textarea class=\"editor_id\" name=\"info[{$nr}]\" style=\"width:{$width}px;height:{$height}px;\">{$val}</textarea>";
	return $editor;
}

//账户资金流水
function pay_logs($typeid,$uid,$_logcontent,$price) {
	global $PHP_TIME,$PHP_IP,$db,$tablepre;
	$db->sql_query("INSERT INTO `{$tablepre}pay_logs`(typeid,uid,content,price,ip,sendtime) VALUES ('".$typeid."','".$uid."','".$_logcontent."','".$price."','".$PHP_IP."','".$PHP_TIME."')");
 	//Return 1;
}
//会员操作日志
function users_logs($uid,$title,$_logcontent) {
	global $PHP_TIME,$PHP_IP,$db,$tablepre;
	$db->sql_query("INSERT INTO `{$tablepre}users_logs`(uid,title,content,ip,sendtime) VALUES ('".$uid."','".$title."','".$_logcontent."','".$PHP_IP."','".$PHP_TIME."')");
	//Return 1;
}

 
function format_date($time){
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}


//获取信息的列表
function dw_type_list($typeid,$newpid=0,$tyname="pid",$tipname="请选择分类",$table="type_cats"){
	global $db,$tablepre; 
	
	if($typeid) $sqlkey=" AND typeid={$typeid}";
	$sql="SELECT id,catname FROM `{$tablepre}{$table}` WHERE isstate=1 {$sqlkey} ORDER BY disorder ASC, id ASC";
	 //echo $sql;
 	$result=$db->sql_query($sql);
	$PageCount=$db->sql_numrows($result);
	if($PageCount>0){
		$str.="<SELECT name=\"{$tyname}\" class=\"select\">";
		$str.="<OPTION value=\"0\">{$tipname}</OPTION>";
		while($row=$db->sql_fetchrow($result)){
			if ($row['id']==(int)$newpid) $c="selected"; else $c="";
			$str.="<option value='".$row['id']."' ".$c.">".$row['catname']."</option>";
		}
		$str.="</SELECT>";
	 }
	 return $str;
}

//获取银行卡列表
function get_yhk_list($newpid=0,$table="news",$tyname="yid"){
	global $db,$tablepre; 
	
 	$sql="SELECT * FROM `{$tablepre}{$table}` where 1=1 and pid=1 and ty=3 ORDER BY id ASC";
	//echo $sql;
 	$result=$db->sql_query($sql);
	
	$str.="<select name=\"{$tyname}\" class=\"text rd5\">";
	
	$str.="<OPTION value=\"0\">选择银行卡</OPTION>";
	
	while($row=$db->sql_fetchrow($result)){
		if ($row['id']==(int)$newpid) $c="selected"; else $c="";
		$str.="<option value='".$row['id']."' ".$c.">{$row['title']}</option>";
	}
	
	$str.="</select>";
	return $str;
}
//获取银行卡列表
function get_yhk_list2($uid=0,$newpid=0,$table="bank",$tyname="bank"){
	global $db,$tablepre; 
	
 	$sql="SELECT * FROM `{$tablepre}{$table}` where 1=1 and uid={$uid} ORDER BY id ASC";
	//echo $sql;
 	$result=$db->sql_query($sql);
	
	$str.="<select name=\"{$tyname}\" class=\"text rd5\">";
	
	$str.="<OPTION value=\"0\">选择银行卡</OPTION>";
	
	while($row=$db->sql_fetchrow($result)){
		if ($row['id']==(int)$newpid) $c="selected"; else $c="";
		$title=get_zd_name("title","news"," and id={$row['title']} ");
		
		$str.="<option value='".$row['id']."' ".$c.">{$title}({$row['kahao']})</option>";
	}
	
	$str.="</select>";
	return $str;
}

/**
  * @param string $string 原文或者密文
  * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
  * @param string $key 密钥
   * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
   * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
   *
     * @example
     *
     *  $a = authcode('abc', 'ENCODE', 'key');
     *  $b = authcode($a, 'DECODE', 'key');  // $b(abc)
     *
     *  $a = authcode('abc', 'ENCODE', 'key', 3600);
     *  $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
     */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600) {

        $ckey_length = 4;   
        // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥

        $key = md5($key ? $key : 'default_key'); //这里可以填写默认key值
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }
	
//返回拼音
function ReturnPinyinFun($hz){
	include_once('epinyin.php');
	//编码
	include_once('doiconv.php');
	$iconv=new Chinese('');
	$char='UTF8';
	$targetchar='GB2312';
	$hz=$iconv->Convert($char,$targetchar,$hz);
	return c($hz);
}
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
    if($code == 'UTF-8')
    {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);

        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen));
        return join('', array_slice($t_string[0], $start, $sublen));
    }
    else
    {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';

        for($i=0; $i< $strlen; $i++)
        {
            if($i>=$start && $i< ($start+$sublen))
            {
                if(ord(substr($string, $i, 1))>129)
                {
                    $tmpstr.= substr($string, $i, 2);
                }
                else
                {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        //if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
        return $tmpstr;
    }
}

/**
 * 商品历史浏览记录
 * $data 商品记录信息
 */
function get_history($data){
    if(!$data || !is_array($data))
    {
        return false;
    }
     
    //判断cookie类里面是否有浏览记录
    if($_COOKIE["history"])
    {
        $history = unserialize($_COOKIE["history"]);
        array_unshift($history, $data); //在浏览记录顶部加入
 
        /* 去除重复记录 */
        $rows = array();
        foreach ($history as $v)
        {
            if(in_array($v, $rows))
            {
                continue;
            }
            $rows[] = $v;
        }
 
        /* 如果记录数量多余5则去除 */
        while (count($rows) > 5)
        {
            array_pop($rows); //弹出
        }
 
        setcookie('history',serialize($rows),time() + 3600 * 24 * 30,'/');
    }
    else
    {
        $history = serialize(array($data));
 
        setcookie('history',$history,time() + 3600 * 24 * 30,'/');
    }
	return $_COOKIE["history"];
}
//操作的url
function opturl($str){
	return $_POST[$str] ? $_POST[$str] : $_GET[$str]; 
}

//字符串转换
function str_code($str){
	$str=stripslashes(urlencode(base64_encode($str)));
	return $str;
}

//判断单选和多选框选中
function get_frm_checked($v1,$v2){
 	if($v1==$v2) $str="checked";;
	return $str;
}	


//过滤字符串
function Encode($fString){

	$fString = str_replace("&gt;", ">", $fString);
	$fString = str_replace("'", "’", $fString);
	$fString = str_replace("&lt;", "<", $fString);
	$fString = str_replace("&#39;", chr(39) ,$fString );
	$fString = str_replace("", chr(13),$fString);
	$fString = str_replace(chr(13), "<BR />", $fString);
	$fString = str_replace(chr(10) & chr(10),"</P><P>",  $fString);
	$fString = str_replace(chr(10), "<BR />", $fString);
	return $fString;

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


//处理str
function showstr($str,$strname="没填"){
	if($str) $str=$str;else $str=$strname;
	echo $str;
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
//获取QQ列表
function get_qq_list22($str){
	$qq = split(',',$str);
	for($i=0;$i<count($qq);$i++){
	
		$title=split('=',$qq[$i]);
	
		$qqlist.="\n<li>
						<span class=\"fl\" style=\"padding-left:25px;\"><img src=\"img/qq.jpg\" width=\"23\" height=\"23\" /></span><a href=\"http://wpa.qq.com/msgrd?v=3&uin=".$title[0]."&site=qq&menu=yes\" style=\"line-height:23px; color:#005da2; padding-left:4px;\" class=\"fl\" target=\"_blank\">{$title[1]}</a>
						<div class=\"clear\"></div>
					</li>";
	}
	return $qqlist;
}


// 
function get_count_list($count){
	for($i=1;$i<=$count;$i++){
		$str.="<option value=\"{$i}\">{$i}</option>";
	}
	return $str;
}

//获取QQ列表
function get_qq_list($str){
	$qq = split(',',$str);
	for($i=0;$i<count($qq);$i++){
  		$qqlists.="\n<li><a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?v=3&uin=".$qq[$i]."&site=qq&menu=yes\"><img border=\"0\" src=\"http://wpa.qq.com/pa?p=2:".$qq[$i].":51\" alt=\"我们竭诚为您服务！\" title=\"我们竭诚为您服务！\"/></a></li>";
 	}
	return $qqlists;
}


//获取QQ列表
function get_qq_list2($str){
	$qq = split(',',$str);
	for($i=0;$i<count($qq);$i++){
		$qqlist.="<a href=\"http://wpa.qq.com/msgrd?v=3&uin=".$qq[$i]."&site=qq&menu=yes\" target=\"_blank\"><img src=\"img/qq.jpg\" />";
	}
	return $qqlist;
}

//获取关键字列表
function get_hotsearch_list($str){
	$qq = split(',',$str);
	for($i=0;$i<count($qq);$i++){
		$q=$qq[$i];
		$qqlist.="<a href=\"category.php?q=".urlencode($q)."\">{$q}</a>";
	}
	return $qqlist;
}

//编辑器调用
function GetEwebeditor($Content,$BasePath="myeditor",$ToolbarSet="mini",$Width=550,$Height=350){
	$str="<INPUT type=\"hidden\" name=\"content\" value='{$Content}'>";
	$str.="<IFRAME ID=\"eWebEditor1\" src=\"../{$BasePath}/ewebeditor.htm?id=content&style={$ToolbarSet}\" frameborder=\"0\" scrolling=\"no\" width=\"{$Width}\" height=\"{$Height}\"></IFRAME>";
	echo $str;
}


//获取图片状态
function getimg($path,$img){
 	if($img) $str="<a href=".$path.$img."  target='_blank'><img src='images/1.gif'  alt='有缩略图' border=0></a>"; else $str="<img src='images/0.gif'  alt='无缩略图'>";
	return $str;
}

//分页内容
function PageContent($BaseUrl,$Content,$ContentPage) {
	if(!empty($Content)) {
		//获取内容以-CONTENTPAGE-分隔
		$ContextTxt = split('-CONTENTPAGE-',$Content);
		$TxtNum		= count($ContextTxt);
		//判断是否存在分隔符，并显示
		if ($TxtNum<2){
			$OutTxt = $ContextTxt[0];
		}else{
			$Page="<div class=\"pageWarp\"><div class=\"page1\">分页：";
			for($i=0;$i<$TxtNum;$i++){
				$NowPage=($ContentPage);
				if ($i==$NowPage){
					$Page.="<b>".($i+1)."</b> ";
				}else{
					$Page.="<a href='".$BaseUrl."&ContentPage=".($i)."' class=\"skin2\">".($i+1)."</a> ";
				}
			}
			$Page.="</div></div>";
			
			$OutTxt = $ContextTxt[$ContentPage].$Page;
		}

		Return $OutTxt;
	}else{
		Return 0;
	}
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


function getred($key,$str){
	$str_array=split(' ',$key);
	for($i=0; $i<count($str_array); $i++){
		if(strpos($str,$str_array[$i],count($str_array))){
			$newkey=$str_array[$i];
		}
	}
	return $newkey;
}

function get_choose($str1,$str2){
	if ($str1==$str2){
		$str=" checked=\"checked\"";
	}else{
		$str = "";
	}
	echo $str;
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
				$str.="\t<option value=''>".$firstNode."</option>\n";
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
					$str .= "\n<input type='".$tag."' name='".$nm."' id='".$nm.$k."' class='radio' checked='checked' value='".$k."' ".$js.">".$v."</input>\n ";
				}else{
					if($i==0 && $value==''){$defaultCk = 'checked';$i++;}
					$str .= "\n<input type='".$tag."' name='".$nm."' id='".$nm.$k."' class='radio' value='".$k."' ".$js." $defaultCk>".$v."</input>\n ";
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
						$str .= "<input type='".$tag."' name='".$nm."[]' value='".$k."' checked='checked' ".$js.">".$v."</input>\n";
						$checkTag++;
					}
				}
				if($checkTag==0){
					$str .= "<input type='".$tag."' name='".$nm."[]' value='".$k."' ".$js.">".$v."</input>\n";
				}
				$checkTag=0;
			}
			break;
	}
	return $str;
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



/**
* 清除HTML格式字符串分割
*/
function substrs($content,$length) {
	if($length && strlen($content)>$length){
		$content=cutstr($content,$length);
 	}
	$content=strip_tags($content);
	return $content;
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
* 清除 Cookie
*/
function clearcookies($cvar,$cpre='',$cpath='/',$cdomain='') {
	global $cookiepre, $cookiedomain, $cookiepath, $PHP_TIME, $_SERVER;
	if (empty($cpre)){ $cpre=$cookiepre;}
	if (empty($cpath)){ $cpath=$cookiepath;}
	if (empty($cdomain)){ $cdomain=$cookiedomain;}
	$ctime = $PHP_TIME - 86400 * 365;
	$S=$_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	setCookie($cpre.$cvar,'',$ctime,$cpath,$cdomain,$S);
}

/**
* 保存 Cookie
*/
function destcookie($cvar,$cvalue,$cpre='',$cpath='/',$cdomain='',$ctime='F') {
	global $cookiepre, $cookiedomain, $cookiepath, $PHP_TIME, $_SERVER;
	if (empty($cpre)){ $cpre=$cookiepre;}
	if (empty($cpath)){ $cpath=$cookiepath;}
	if (empty($cdomain)){ $cdomain=$cookiedomain;}
	$ctime = $ctime=='F' ? $PHP_TIME+1800 : ($ctime=='' && $ctime==0 ? $PHP_TIME-1800 : $ctime);
	$S=$_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	setCookie($cpre.$cvar,$cvalue,$ctime,$cpath,$cdomain,$S);
}


function destcookie_2($cvar,$cvalue,$cpre='',$cpath='/',$cdomain='',$ctime='F') {
	global $cookiepre, $cookiedomain, $cookiepath, $PHP_TIME, $_SERVER;
	if (empty($cpre)){ $cpre=$cookiepre;}
	if (empty($cpath)){ $cpath=$cookiepath;}
	if (empty($cdomain)){ $cdomain=$cookiedomain;}
	$ctime = $ctime=='F' ? $PHP_TIME+1800 : ($ctime=='' && $ctime==0 ? $PHP_TIME-10 : $ctime);
	$S=$_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	setCookie($cpre.$cvar,$cvalue,$ctime,$cpath,$cdomain,$S);
}

//设置COOKIE
if ( ! function_exists('set_cookie')){
	function set_cookie($cvar,$cvalue,$cpath='/',$cdomain='',$ctime='F')
	{
		global $cookiedomain, $cookiepath, $PHP_TIME, $_SERVER;
		if (empty($cpath)){ $cpath=$cookiepath;}
		if (empty($cdomain)){ $cdomain=$cookiedomain;}
		$ctime = $ctime=='F' ? time()+1800 : ($ctime=='' && $ctime==0 ? time()-1800 : $ctime);
		$S=$_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
		setCookie($cvar,$cvalue,$ctime,$cpath,$cdomain,$S);
	}
}

//获取COOKIE
if ( ! function_exists('get_cookie')){
	function get_cookie($index = '')
	{	
     	return @$_COOKIE[$index];
 	}
}

//删除COOKIE
if ( ! function_exists('delete_cookie'))
{
	function delete_cookie($cvar,$cvalue,$cpath='/',$cdomain='')
	{
		set_cookie($cookiepre.$cvar,'',$cpath,$cdomain,'');
	}
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
		echo("parent.location='".$BackURL."';");
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
	echo("window.opener= null;window.open('','_self');window.close();");
	echo("\n//--></SCRIPT>");
	Return 1;
}

function JsGourl($URL,$Tag = "parent") {
	echo("<SCRIPT LANGUAGE=\"JavaScript\"><!--\n");
	echo("top.location='".$URL."';");
	echo("\n//--></SCRIPT>");
	Return 1;
}

function JsTip($msg,$url,$id="update"){
	$str="<div class=\"successTip\" id=\{$id}SampleTip\">
			<h6>
				提示<span id=\"closeX\">x</span>
			</h6>
			<p style=\"text-indent: 90px;\">{$msg}</p>
			<div class=\"btn fix\">
				<input type=\"button\" value=\"确定\" onclick=\"window.location.href ='{$url}'\">
				<input type=\"button\" value=\"关闭\" class=\"closeBtn\" />
			</div>
		</div>";
	echo $str;
}
 
//会员页分页
function users_page_list($pagesql,$page=1,$pagesize=10){
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
    $pagestr=$pagestr."<div class=\"page\"><ul>\n";
	
	 if ($page==1){
		$pagestr=$pagestr."<li><a href=\"javascript:void()\">上一页</a></li>\n";
     }
     if ($page>1){
		$pagestr=$pagestr."<li><a href='$url&page=".$prev."'>上一页</a></li>\n";
     }
	
	
	if ($page==$pages){
		$pagestr=$pagestr."<li><a href=\"javascript:void()\">下一页</a></li>\n";
     }
	if ($page<$pages){
		$pagestr=$pagestr."<li><a href='$url&page=".$next."'>下一页</a></li>\n";
	}
	$pagestr=$pagestr."</ul></div> ";
	return $pagestr;
}	

function page_list($pagesql,$page=1,$pagesize=10){
	$page=(int)$page<1?1:$page;
	$result=mysql_query($pagesql);
	@$numrows=mysql_num_rows($result);
 	$url=basename($HTTP_SERVER_VARS['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'];
 	$url=preg_replace('/[&]?page=[\w]*[&]?/i','',$url);
	//if($gurl) $url=$gurl.$url; else $url="list.php".$url;
 	
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
    $pagestr=$pagestr."<div class=\"page\">
		<div class=\"w html5_w90 cle\">\n";
	
	 if ($page==1){
 		$pagestr=$pagestr."<a href=\"javascript:void()\" class=\"prve\"><img src=\"img/img15.jpg\" /></a>\n";
 		//$pagestr=$pagestr."<a href=\"javascript:void()\" class=\"prev point\">上一页</a>\n";
     }
	 
     if ($page>1){
 		$pagestr=$pagestr."<a href=\"{$url}&page={$prev}\" class=\"prve\"><img src=\"img/img15.jpg\" /></a>\n";
		//$pagestr=$pagestr."<a href=\"{$url}&page={$first}\" class=\"prev point\">上一页</a>\n";
     }
 	
	if ($on_page<5) { $i_num=1;$i_max=9;} else{ $i_num=$on_page-4;$i_max=$on_page+5;}
 	for($i=$i_num;$i<$i_max;$i++){
		
  		$page_nums=($i-1)*$pagesize;
 		if ($page_nums<$num){
			if ($i==$on_page){
				$str="<a href=\"javascript:void()\" class=\"on\">{$i}</a>\n";
			}else{
				$str="<a href=\"{$url}&page={$i}\">{$i}</a>\n";
			}
			$pagestr=$pagestr."".$str."";
		}
  	}
    	
	if ($page==$pages){
		//$pagestr=$pagestr."<a href=\"javascript:void()\" class=\"next point\">下一页</a>\n";
		$pagestr=$pagestr."<a href=\"javascript:void()\" class=\"next\"><img src=\"img/img16.jpg\" /></a>\n";
     }
	if ($page<$pages){
		//$pagestr=$pagestr."<a href=\"{$url}&page={$next}\" class=\"next point\">下一页</a>\n";
		$pagestr=$pagestr."<a href=\"{$url}&page={$last}\" class=\"next\"><img src=\"img/img16.jpg\" /></a>\n";
	}
	$pagestr=$pagestr."<span class=\"fr info\">共{$page}/{$pages}页</span></div>";
	return $pagestr;
}
/**
* 生成随机数
*/
function random1($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}


/**
* 过滤.., \n, \r 用的
*/
function wipespecial($str) {
	return str_replace(array('..', "\n", "\r"), array('', '', ''), $str);
}


//分页内容
function ViewContent($BaseUrl,$Content,$ContentPage) {
	if(!empty($Content)) {
		//获取内容以-CONTENTPAGE-分隔
		$ContextTxt = split('-CONTENTPAGE-',$Content);
		$TxtNum		= count($ContextTxt);
		//判断是否存在分隔符，并显示
		if ($TxtNum<2){
			$OutTxt = $ContextTxt[0];
		}else{
			$Page="<div class=digg>分页：";
			for($i=0;$i<$TxtNum;$i++){
				$NowPage=($ContentPage);
				if ($i==$NowPage){
					$Page.="<span class=current>".($i+1)."</span>";
				}else{
					$Page.="<a href='".$BaseUrl."_".($i).".html'>".($i+1)."</a>";
				}
			}
			$Page.="</div>";
			
			$OutTxt = $ContextTxt[$ContentPage].$Page;
		}

		Return $OutTxt;
	}else{
		Return 0;
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

function optionlist2($table,$nm,$formname,$sname,$TypeId,$oldpid,$oldty,$oldtty){
	global $db,$tablepre; 
	if ($TypeId) $sqlkey=" AND pid={$TypeId}";
?>
	<select name="<?=$nm?>">
		<option selected="selected" value="0|0|0"><?=$sname?></option>
		<?
		$sqlstr1="SELECT * FROM `{$tablepre}{$table}` WHERE isstate=1 ".$sqlkey." ORDER BY disorder ASC,id ASC";
		//echo $sqlstr1;
		$resultstr1 = mysql_query($sqlstr1);
		while($rsstr=mysql_fetch_array($resultstr1)){
		?>
		<option value='<?=$rsstr['id']?>|0'><?=$rsstr['catname']?></option>
		<?
		$sqlstr2="SELECT * FROM `{$tablepre}{$table}` WHERE pid=".$rsstr['id']." ORDER BY disorder ASC,id ASC";
		//echo $sqlstr2;
		$resultstr2 = mysql_query($sqlstr2);
		while($rsstr2=mysql_fetch_array($resultstr2)){
		?>
		<option value='<?=$rsstr['id']?>|<?=$rsstr2['id']?>'>┣━<?=$rsstr2['catname']?></option>
		<?
			}
		}
		?>      
		</select>
<? 
if ($oldpid and $oldty and $oldtty) {
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|{$oldty}\"</script>";
}elseif ($oldpid and $oldty){
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|{$oldty}\"</script>";
}elseif ($oldpid) {
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"{$oldpid}|0\"</script>";
}else{
echo"<script language=\"JavaScript\">document.{$formname}.{$nm}.value=\"0|0|0\"</script>";
 }
}

//返回错误信息并返回上一页面
function ErrorHtml ($Txt) {
	global $baseurl; 
	$error_html='<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<title>404出错页面！</title>

				<style type="text/css">
				<!--
				body,td,th {font-size: 14px;color: #168700;font-family: Verdana;}
				body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;}
				A:link,A:visited {text-decoration:underline;color:#0012FF;word-break:break-all;}
				-->
				</style></head>

				<body>
				<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td><table width="650" border="0" align="center" cellpadding="10" cellspacing="1" bgcolor="#D7F0CE">
					  <tr>
						<td height="280" bgcolor="#F5FDF2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td width="47%" align="center"><img src="/images/404.gif"></td>
							<td width="53%"><strong>'.$Txt.'<br>
							  请重新登录访问：<a href="'.$baseurl.'" target="_blank">'.$baseurl.'</a></strong></td>
						  </tr>
						</table></td>
					  </tr>
					</table></td>
				  </tr>
				</table>
				</body>
				</html>';
	//Return require("/include/error.php");
	echo $error_html;
	dexit();
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
	//if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
	if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,$encoding);
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}

function new_html_entity_decode($string) {
	$encoding = 'utf-8';
	//if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
	return html_entity_decode($string,ENT_QUOTES,$encoding);
}

function new_htmlentities($string) {
	$encoding = 'utf-8';
	//if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
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