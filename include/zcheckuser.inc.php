<?php
if(empty($_COOKIE['sys_zuid'])){ 
	echo"<script language='javascript'>top.document.location.href='zlogin.php?uri={$PHP_URL}';</script>";
	exit();
}
?>
