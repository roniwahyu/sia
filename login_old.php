<?php
include "config/old_session.php";
error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Politeknik Negeri Malang</title>
<link href="template/login/styles.css"  webstripperwas="template/login/styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body{background:#333333 url(template/login/images/loginbg.jpg) repeat-x;}
</style>
<style type="text/css">
img, div, h1, ul, input, select, textarea, span, a { behavior: url(iepngfix.txt) }
</style> 
<script type="text/javascript" src="template/login/iepngfix_tilebg.js"  webstripperwas="template/login/iepngfix_tilebg.js" ></script>
</head>

<body>
<div id="loginWrap">
 <div id="loginMain">
  <div id="loginLogin"></div>
  <div id="loginhead"></div>
  <div id="loginform">
	<form action="cek_login.php" method="POST">
   <label><input name="username" type="text" value="username" onfocus="if(this.value=='username')this.value=''" onblur="if(this.value=='')this.value='username'" /></label>
  <label><input name="password" type="password" value="password" onfocus="if(this.value=='password')this.value=''" onblur="if(this.value=='')this.value='password'" /></label>
	<input type="submit" value="LOGIN" id="submit" />
    </form>
 </div>
</div>
</div>
</body>
</html>
