<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_indotgl.php";
	include "../../../../config/tabel.php";

	echo "<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>";
	$q_folder=mssql_query("SELECT * FROM tm_folder WHERE folder_aktif='Y'");
	while($r_folder=mssql_fetch_array($q_folder))
	{	
		echo "<tr><th colspan='4'><a title='$r_folder[folder_keterangan]'>$r_folder[folder_nama]</a></th></tr>";
		$no=1;
		if($_GET[dok]=="")
		{
			$q_file=mssql_query("SELECT * FROM tt_file WHERE folder_id='$r_folder[folder_id]' AND periode_id='$_GET[periode]'");
		}
		else
		{
			$q_file=mssql_query("SELECT * FROM tt_file WHERE folder_id='$r_folder[folder_id]' AND periode_id='$_GET[periode]'
			AND (file_nama LIKE '%$_GET[dok]%' OR file_keterangan LIKE '%$_GET[dok]%')");
		}
		while($r_file=mssql_fetch_array($q_file))
		{
			$tgl = tgl_indo($r_file[file_tanggal]);
			echo"<tr class='on'>
				<td width='40px'>$no</td>
				<td><a href='files/$r_file[file_tmp]' title='$r_file[file_keterangan]' target='_blank'>$r_file[file_nama]</a></td>
				<td>$r_file[file_keterangan]</td>
				<td>$tgl</td>
			</tr>";
			$no++;
		}
	}
	echo "</tbody>
	</table>
	<br class='clear'>
	<br class='clear'>";
?>