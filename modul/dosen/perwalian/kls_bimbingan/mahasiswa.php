<?php
	include "../../../../config/new_session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/tabel.php";

	echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th>nim</th>
		<th>nama</th>
		<th>jenis kelamin</th>
		<th>no telepon</th>
		<th>email</th>
		<th>alamat</th>
		<th class='kanan'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_kelas=mssql_query("
						SELECT tm_mahasiswa.mahasiswa_nim, 
							tm_mahasiswa.mahasiswa_nama, 
							tm_mahasiswa.jeniskelamin_id, 
							tp_kota.kota_nama, 
							tp_propinsi.propinsi_nama, 
							tm_mahasiswa.mahasiswa_telp, 
							tm_mahasiswa.mahasiswa_email
						FROM tm_mahasiswa 
							INNER JOIN tp_propinsi ON tp_propinsi.propinsi_id = tm_mahasiswa.propinsi_id 
							INNER JOIN tp_kota ON tp_kota.kota_kode = tm_mahasiswa.kota_kode 
							INNER JOIN tt_kelas_mahasiswa ON tm_mahasiswa.mahasiswa_nim = tt_kelas_mahasiswa.mahasiswa_nim
							INNER JOIN tm_kelas ON tm_kelas.kelas_id = tt_kelas_mahasiswa.kelas_id 
								AND tm_kelas.periode_id LIKE '$_GET[periode]'
							INNER JOIN tm_jurusan ON tm_mahasiswa.jurusan_id = tm_jurusan.jurusan_id 
							INNER JOIN tm_prodi ON tm_jurusan.jurusan_id = tm_prodi.jurusan_id 
								AND tm_mahasiswa.prodi_id = tm_prodi.prodi_id
							INNER JOIN tt_dosen_kelas ON tt_dosen_kelas.kelas_id=tt_kelas_mahasiswa.kelas_id
								AND tt_dosen_kelas.pegawai_kode='$_SESSION[pegawai_kode]' 
						WHERE (tt_kelas_mahasiswa.kelas_id LIKE '$_GET[kelas]')
							AND tm_kelas.periode_id LIKE '$_GET[periode]'
						ORDER BY tm_kelas.periode_id DESC
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td>$r_kelas[mahasiswa_nim]</td>
			<td>$r_kelas[mahasiswa_nama]</td>
			<td align='center'>$r_kelas[jeniskelamin_id]</td>
			<td>$r_kelas[mahasiswa_telp]</td>
			<td>$r_kelas[mahasiswa_email]</td>
			<td>$r_kelas[kota_nama], $r_kelas[propinsi_nama]</td>
			<td><a href='?departemen=dosen&menu=perwalian&modul=kls_bimbingan&act=detailmhs&nim=$r_kelas[mahasiswa_nim]' title='Detail Data $r_kelas[mahasiswa_nim]'><img src='template/images/detail.ico'></a> &nbsp;&nbsp;
				<a href='?departemen=dosen&menu=perwalian&modul=kls_bimbingan&act=detailnilai&nim=$r_kelas[mahasiswa_nim]' title='Detail Nilai $r_kelas[mahasiswa_nim]'><img src='template/images/chart_bar.png'></a>&nbsp;&nbsp;
				<a href='?departemen=dosen&menu=perwalian&modul=kls_bimbingan&act=historynilai&nim=$r_kelas[mahasiswa_nim]' title='History Nilai $r_kelas[mahasiswa_nim]'><img src='template/images/icon_calendar.png'></a>&nbsp;&nbsp;
				<a href='?departemen=dosen&menu=perwalian&modul=kls_bimbingan&act=ksm&nim=$r_kelas[mahasiswa_nim]' title='Kartu Studi Mahasiswa $r_kelas[mahasiswa_nim]'><img src='template/images/icon_pages.png'></a>&nbsp;&nbsp;
				<a href='?departemen=dosen&menu=perwalian&modul=kls_bimbingan&act=nilai&nim=$r_kelas[mahasiswa_nim]' title='Nilai $r_kelas[mahasiswa_nim] Periode $periode'><img src='template/images/icon_krs.png'></a>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>";
?>