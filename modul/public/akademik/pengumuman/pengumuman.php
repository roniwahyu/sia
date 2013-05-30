<?php
	echo"
	<div class='content'>
	<h3>Akademik &#187; Pengumuman</h3>
	<br class='clear'/>
	<div id='content_inner_data'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>";
	$pengumuman=mssql_query("
							SELECT tt_pengumuman.pengumuman_judul, 
								tt_pengumuman.pengumuman_isi, 
								tt_pengumuman.pengumuman_tanggal_awal, 
								tt_pengumuman.pengumuman_tanggal_akhir,
								tt_pengumuman.pengumuman_tipe_id 
							FROM ts_departemen 
								INNER JOIN tt_pegawai_departemen ON tt_pegawai_departemen.departemen_id = ts_departemen.departemen_id
								INNER JOIN tt_pengumuman ON tt_pengumuman.pengumuman_tipe_id = ts_departemen.departemen_id
							WHERE (tt_pegawai_departemen.pegawai_kode = '$_SESSION[pegawai_kode]')
							ORDER BY tt_pengumuman.pengumuman_id DESC");
	$no=1;
	while($p=mssql_fetch_array($pengumuman))
	{
		$tgl = tanggal_antara($p[pengumuman_tanggal_awal],$p[pengumuman_tanggal_akhir]);
		if($tgl == 2 || $tgl == 1)
		{
			$departemen = ucfirst($p[pengumuman_tipe_id]);
			$line =tabel_normal($no);
			echo "$line<td>
			<h4>$p[pengumuman_judul] <i id='text_merah'>($departemen)</i></h4>
			<p>$p[pengumuman_isi]</p><br>
			</td>
			</tr>";
			$no++;
		}
	}
	echo "</tbody>
	</table>
	</div>
	</div>";
?>