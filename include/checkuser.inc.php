<?php
if(empty($_COOKIE['sys_uid'])){ 
	echo"<script language='javascript'>top.document.location.href='login.php?uri={$PHP_URL}';</script>";
	exit();
}
?>
