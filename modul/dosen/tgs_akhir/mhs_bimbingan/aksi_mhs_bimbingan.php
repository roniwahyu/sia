<?php
	include "../../../../config/session.php";
	include "../../../../config/koneksi.php";
	if($_GET[act]=='setuju')
	{		
		mssql_query("UPDATE tt_tugas_akhir SET judul_diajukan='Y' 
					WHERE periode_id='$_GET[periode]'
						AND mahasiswa_nim='$_GET[mahasiswa_nim]'");
						
		header("location:../../../../media.php?departemen=dosen&menu=tgs_akhir&modul=mhs_bimbingan");		
	}
?>