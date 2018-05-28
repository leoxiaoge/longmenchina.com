<?php
@session_start();
session_cache_expire(30);
if(!isset($_SESSION['Admin_UserName']) || $_SESSION['is_admin']=="")
{
	@session_destroy();
	echo"<script language='javascript'>top.document.location.href='../web_manage/index.php';</script>";
	exit();
}
?>
