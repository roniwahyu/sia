<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/tabel.php";

	echo "$_GET[aksi_self]<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='30px' class='kiri'><input type='checkbox' name='check_all' id='check_all'></th>
		<th width='30px'>no</th>
		<th width='60px'>nim</th>
		<th>nama</th>
		<th width='60px'>jenis kelamin</th>
		<th>no telepon</th>
		<th>email</th>
		<th width='60px'>semester</th>
		<th width='60px'>approve</th>
		<th class='kanan' width='60px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_kelas=mssql_query("
						SELECT tm_mahasiswa.mahasiswa_nim, 
							tm_mahasiswa.mahasiswa_nama, 
							tm_mahasiswa.jeniskelamin_id, 
							tm_mahasiswa.mahasiswa_telp, 
							tm_mahasiswa.mahasiswa_email, 
							tm_mahasiswa.semester_id,
							tt_kelas_mahasiswa.kelas_id, 
							(SELECT count(*) 
							FROM tt_nilai 
							WHERE tt_nilai.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim 
								AND tt_nilai.periode_id='$_GET[nextperiode]') AS nilai,
							(SELECT TOP(1) tt_krs.krs_approve
							FROM tt_krs 
							WHERE tt_krs.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim 
								AND tt_krs.periode_id LIKE '$_GET[nextperiode]') AS krs
						FROM tm_mahasiswa 
							INNER JOIN tt_kelas_mahasiswa ON tm_mahasiswa.mahasiswa_nim = tt_kelas_mahasiswa.mahasiswa_nim 
							INNER JOIN tm_kelas ON tm_kelas.kelas_id=tt_kelas_mahasiswa.kelas_id
								AND tm_kelas.periode_id like '$_GET[periode]'
						WHERE (tt_kelas_mahasiswa.kelas_id LIKE '$_GET[kelas]')
						ORDER BY tm_kelas.periode_id DESC
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		
		$sms=1+$r_kelas[semester_id];
		$line =tabel_normal($no);
		echo "$line<td align='center'>";
			if($r_kelas[nilai]==0)
			{
				echo "<input type='checkbox' name=nim_$no value='$r_kelas[mahasiswa_nim]'>";
			}
			else
			{
				echo "<input type='checkbox' name=nim_$no value='$r_kelas[mahasiswa_nim]' disabled='disabled'>";
			}
			echo "</td>
			<td align='center'>$no</td>
			<td>$r_kelas[mahasiswa_nim]</td>
			<td>$r_kelas[mahasiswa_nama]</td>
			<td align='center'>$r_kelas[jeniskelamin_id]</td>
			<td>$r_kelas[mahasiswa_telp]</td>
			<td>$r_kelas[mahasiswa_email]</td>
			<td align='center'>$sms</td>
			<td align='center'>";
			if($r_kelas[krs]=='Y')
			{
				if($r_kelas[nilai]==0)
				{
					echo"<a href='modul/dosen/perwalian/krs/aksi_krs.php?act=ubahstatuskrs&nim=$r_kelas[mahasiswa_nim]&periode_id=$_GET[nextperiode]&kelas_id=$r_kelas[kelas_id]' onClick=\"return confirm('Apakah Anda ingin mengganti status $r_kelas[mahasiswa_nim] $r_kelas[mahasiswa_nama]?')\"><img src='template/images/icon_accept.png' title='Siap Approve' id='foto'></a>";
				}
				elseif($r_kelas[nilai]>0)
				{
					echo"<img src='template/images/icon_accept.png' title='Siap Approve' id='foto'>";
				}
			}
			else
			{
				echo"<img src='template/images/cancel.png' title='Belum Siap Approve' id='foto'>";
			}
			echo "</td>
			<td>";
			if($r_kelas[nilai]==0)
			{
				echo "<a href='?departemen=dosen&menu=perwalian&modul=krs&act=detailmhs&kelas_id=$r_kelas[kelas_id]&nim=$r_kelas[mahasiswa_nim]&periode_id=$_GET[nextperiode]' title='Detail Mata Kuliah KRS $r_kelas[mahasiswa_nim] Periode $_GET[nextperiode]'><img src='template/images/detail.ico'></a>&nbsp;&nbsp;&nbsp;";
				if($r_kelas[krs]=='Y')
				{
					echo "<a href='modul/dosen/perwalian/krs/aksi_krs.php?act=individu&nim=$r_kelas[mahasiswa_nim]&periode_id=$_GET[nextperiode]&kelas_id=$r_kelas[kelas_id]' title='Approve Mata Kuliah $r_kelas[mahasiswa_nim] Periode $_GET[nextperiode]' onClick=\"return confirm('Apakah Anda ingin Appprove mata kuliah $r_kelas[mahasiswa_nim] $r_kelas[mahasiswa_nama]?')\"><img src='template/images/icon_accept.png'></a>";
				}
			}
			elseif($r_kelas[nilai]>0)
			{
				echo "<a href='?departemen=dosen&menu=perwalian&modul=krs&act=detailnextsms&&nim=$r_kelas[mahasiswa_nim]&periode_id=$_GET[nextperiode]&kelas_id=$r_kelas[kelas_id]' title='Detail Mata Kuliah $r_kelas[mahasiswa_nim] Periode $_GET[nextperiode]'><img src='template/images/chart_bar.png'></a>&nbsp;&nbsp;
				<a href='modul/dosen/perwalian/krs/aksi_krs.php?act=hapus&nim=$r_kelas[mahasiswa_nim]&periode_id=$_GET[nextperiode]&kelas_id=$r_kelas[kelas_id]' title='Hapus Mata Kuliah $r_kelas[mahasiswa_nim] Periode $_GET[nextperiode]' onClick=\"return confirm('Apakah Anda ingin menghapus mata kuliah $r_kelas[mahasiswa_nim] $r_kelas[mahasiswa_nama]?')\"><img src='template/images/icon_delete.png'></a>";
			}
			echo "</td>
		</tr>";
		$no++;
	}
	echo "<input type='hidden' name='jummhs' value='$no'>
	<tr><th colspan='12' align='center'><input type='submit' value='Approve'></th></tr>
	</tbody>
	</table>";
?>
