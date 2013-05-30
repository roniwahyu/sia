<?php
include "config/old_session.php";
error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SIAKAD Politeknik Negeri Malang</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<link rel="shortcut icon" href="icon_logo.png" />

<link href="template/css/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="logo">
	<img src="template/images/logo.png" alt="logopng"/> <!--//  Logo on upper corner -->
</div>
<div class="box">
	<div class="welcome" id="welcometitle"><!--//  Welcome message --> Welcome to SIAKAD Politeknik Negeri Malang</div>
  <div id="fields">
  <?php
  if($_GET[page]=='login' || $_GET[page]=='')
  {
  ?>
	<form action="cek_login" method="POST">   
    <table width="333">
      <tr>
        <td width="79" height="35"><span class="login">USERNAME</span></td>
        <td width="244" height="35"><label>
          <input name="username" type="text" class="fields" id="username" size="30" />  <!--//  Username field  -->
        </label></td>
      </tr>
      
      
      <tr>
        <td height="35"><span class="login">PASSWORD</span></td>
        <td height="35"><input name="password" type="password" class="fields" id="password" size="30" /></td> <!--//  Password field -->
      </tr>
      
      
      <tr>
        <td height="65">&nbsp;</td>
        <td height="65" valign="middle"><label>
          <input name="button" type="submit" class="button" id="button" value="LOGIN" />
          <!--//  login button -->
        </label></td>
      </tr>
    </table>
    </form>
    <?php
  	}
	elseif($_GET[page]=='forgot_password')
	{
	?>
	<form action="forgot_password.php" method="POST">   
    <table width="333">
      <tr>
        <td width="79" height="35"><span class="login">USERNAME</span></td>
        <td width="244" height="35"><label>
          <input name="username" type="text" class="fields" id="username" size="30" />  <!--//  Username field  -->
        </label></td>
      </tr>
      
      
      <tr>
        <td height="35"><span class="login">EMAIL</span></td>
        <td height="35"><input name="email" type="text" class="fields" id="email" size="30" /></td> <!--//  Email field -->
      </tr>
      
      
      <tr>
        <td height="65">&nbsp;</td>
        <td height="65" valign="middle"><label>
          <input name="button" type="submit" class="button" id="button" value="SUBMIT" />
          <!--//  login button -->
        </label></td>
      </tr>
    </table>
    </form>
    <?php
	}
	?>
  </div>
  
  <?php
  if($_GET[page]=='login' || $_GET[page]=='')
  {
  ?>  
  <div class="login" id="lostpassword"><a href="forgot_password">Forgot Password?</a></div> <!--//  lost password part -->
  <?php
  }
  elseif($_GET[page]=='forgot_password')
  {
  ?>
  <div class="login" id="lostpassword"><a href="login">Login Again?</a></div> <!--//  lost password part -->
  <?php
  }
  ?>
  
  <div class="copyright" id="copyright">Copyright &copy; Politeknik Negeri Malang 2011.</div>
</div>


</body>
</html>
