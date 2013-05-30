<?php
	//module
	$menu=mssql_query("select menu_pegawai_nama from ts_menu_pegawai where menu_pegawai_id='$_GET[menu]'");
	$mn=mssql_fetch_array($menu);
	$modul=mssql_query("select modul_pegawai_id,modul_pegawai_nama,modul_pegawai_urutan from ts_modul_pegawai where menu_pegawai_id='$_GET[menu]' and modul_pegawai_aktif='Y' order by modul_pegawai_urutan");
	echo"<div class='onecolumn''>
	<div class='header'>
		<span>$mn[menu_pegawai_nama]</span>";
	echo "</div>
	<br class='clear'/>
	<div class='tabber'>
	<ul id='cont_menu'>";
	while($md=mssql_fetch_array($modul))
	{
		echo "<li><a href='?departemen=$_GET[departemen]&menu=$_GET[menu]&modul=$md[modul_pegawai_id]'>$md[modul_pegawai_nama]</a> </li>";
	}
	echo"</ul>
	</div>";
	$aksi_self="?departemen=$_GET[departemen]&menu=$_GET[menu]&modul=$_GET[modul]";
	$aksi="modul/$_GET[departemen]/$_GET[menu]/$_GET[modul]/aksi_$_GET[modul].php";
	$pdf="modul/$_GET[departemen]/$_GET[menu]/$_GET[modul]/pdf_$_GET[modul].php";
	$xls="modul/$_GET[departemen]/$_GET[menu]/$_GET[modul]/xls_$_GET[modul].php";
	$print="modul/$_GET[departemen]/$_GET[menu]/$_GET[modul]/print_$_GET[modul].php";

	if($_POST[periode]!='')$periode=$_POST[periode];
	else $periode=$_SESSION[periode];

	include "modul/$_GET[departemen]/$_GET[menu]/$_GET[modul]/$_GET[modul].php";
?>