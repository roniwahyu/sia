<?php
include "../config/koneksi.php"; 

$sql=mssql_query("SELECT * FROM tp_kota WHERE propinsi_id='$_GET[kode]'");
while($r=mssql_fetch_array($sql))
{
	echo"<option value='$r[kota_kode]'>$r[kota_nama]</option>";
}

?>
