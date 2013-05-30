<?php
	include "config/login.php";
	$link = login($_POST[username],$_POST[password]);
	header("location:$link");
?>