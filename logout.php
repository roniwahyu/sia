<?php
	include "config/session.php";
	session_unregister(periode);
	session_unregister(pegawai_kode);
	session_unregister(pegawai_password);
	session_unregister(comid);
	session_unregister(userid);
	session_destroy();
	header("Location:index.php");
?>
