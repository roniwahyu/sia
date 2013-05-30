<?php
switch($_GET[act]){
  // Tampil jurusan Utama
	default:
	?>
	<script type="text/javascript">
        var htmlobjek;
        $(document).ready(function()
		{
        	$("#periode").change(function()
			{
            	var periode = $("#periode").val();
            	var kelas = $("#kelas").val();
            	$.ajax(
				{
                	url: "modul/dosen/perwalian/kls_bimbingan/kelas.php",
                	data: "periode="+periode,
                	cache: false,
                	success: function(msg)
					{
                    	$("#kelas").html(msg);
                	}
            	});

            	$.ajax(
				{
                	url: "modul/dosen/perwalian/kls_bimbingan/mahasiswa.php",
                	data: "periode="+periode+"&kelas="+kelas,
                	cache: false,
                	success: function(msg)
					{
                    	$("#mahasiswa").html(msg);
                	}
            	});

          	});

        	$("#kelas").change(function()
			{
            	var periode = $("#periode").val();
            	var kelas = $("#kelas").val();
            	$.ajax(
				{
                	url: "modul/dosen/perwalian/kls_bimbingan/mahasiswa.php",
                	data: "periode="+periode+"&kelas="+kelas,
                	cache: false,
                	success: function(msg)
					{
                    	$("#mahasiswa").html(msg);
                	}
            	});
          	});

        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Kelas Bimbingan</h3>
	<br class='clear'/>
	<b>Angkatan</b>
	<select name=periode id='periode'>";
		$q_periode=mssql_query("SELECT DISTINCT periode_id 
								FROM tm_kelas INNER 
									JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
								WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]')
								ORDER BY periode_id DESC 
							");
		echo "<option value='%'>SEMUA</option>";
		while($r_periode=mssql_fetch_array($q_periode))
		{
			$angkatan = substr($r_periode[periode_id],0,4);
			echo "<option value='$r_periode[periode_id]'>$angkatan</option>";
		}
	echo "</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Kelas</b>
	<select name='kelas' id='kelas'>
	<option value='%'>SEMUA</option>";
	$q_kelas=mssql_query("
						SELECT tm_kelas.kelas_id, 
							tm_kelas.kelas_nama
						FROM tm_kelas INNER 
							JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
						WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]') 
							AND (tm_kelas.periode_id like '%')							
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		echo "<option value='$r_kelas[kelas_id]'>$r_kelas[kelas_nama]</option>";
	}
	echo "</select>
	<br class='clear'>
	<br class='clear'>
	<div id='mahasiswa'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
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
								AND tm_kelas.periode_id LIKE '%'
							INNER JOIN tm_jurusan ON tm_mahasiswa.jurusan_id = tm_jurusan.jurusan_id 
							INNER JOIN tm_prodi ON tm_jurusan.jurusan_id = tm_prodi.jurusan_id 
								AND tm_mahasiswa.prodi_id = tm_prodi.prodi_id
							INNER JOIN tt_dosen_kelas ON tt_dosen_kelas.kelas_id=tt_kelas_mahasiswa.kelas_id
								AND tt_dosen_kelas.pegawai_kode='$_SESSION[pegawai_kode]' 
						WHERE (tt_kelas_mahasiswa.kelas_id LIKE '%')
							AND tm_kelas.periode_id LIKE '%'
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
			<td><a href='$aksi_self&act=detailmhs&nim=$r_kelas[mahasiswa_nim]' title='Detail Data $r_kelas[mahasiswa_nim]'><img src='template/images/detail.ico'></a> &nbsp;&nbsp;
				<a href='$aksi_self&act=detailnilai&nim=$r_kelas[mahasiswa_nim]' title='Kartu Hasil Studi $r_kelas[mahasiswa_nim]'><img src='template/images/chart_bar.png'></a>&nbsp;&nbsp;
				<a href='$aksi_self&act=historynilai&nim=$r_kelas[mahasiswa_nim]' title='History Nilai $r_kelas[mahasiswa_nim]'><img src='template/images/icon_calendar.png'></a>&nbsp;&nbsp;
				<a href='$aksi_self&act=ksm&nim=$r_kelas[mahasiswa_nim]' title='Kartu Studi Mahasiswa $r_kelas[mahasiswa_nim]'><img src='template/images/icon_pages.png'></a>&nbsp;&nbsp;
				<a href='$aksi_self&act=nilai&nim=$r_kelas[mahasiswa_nim]' title='Nilai $r_kelas[mahasiswa_nim] Periode $periode'><img src='template/images/icon_krs.png'></a>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>
	<br><br>
	</div>";
	break;	
	
	case "detailmhs":
	$q_mhs=mssql_query("
						SELECT tm_mahasiswa.*,
							tm_jurusan.jurusan_nama,
							tm_jurusan.jenjang_id,
							tm_prodi.prodi_nama,
							tp_propinsi.propinsi_nama,
							tp_kota.kota_nama				
						FROM tm_mahasiswa 
							INNER JOIN tp_propinsi ON tp_propinsi.propinsi_id = tm_mahasiswa.propinsi_id 
							INNER JOIN tp_kota ON tp_kota.kota_kode = tm_mahasiswa.kota_kode 
							INNER JOIN tm_jurusan ON tm_mahasiswa.jurusan_id = tm_jurusan.jurusan_id 
							INNER JOIN tm_prodi ON tm_jurusan.jurusan_id = tm_prodi.jurusan_id 
								AND tm_mahasiswa.prodi_id = tm_prodi.prodi_id
						WHERE (tm_mahasiswa.mahasiswa_nim = '$_GET[nim]')
					");
	$r_mhs=mssql_fetch_array($q_mhs);
	$tgl = tgl_indo($r_mhs[mahasiswa_tgl_lhr]);
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Kelas Bimbingan &#187; Detail NIM $_GET[nim]</h3>
	<br class='clear'/>";
	if($r_mhs[mahasiswa_foto] !='')
	{
	echo"<center><a id='photo_show' rel='slide' href='images/foto_mahasiswa/$r_mhs[mahasiswa_foto]' title='$r_mhs[mahasiswa_nama] ($r_mhs[mahasiswa_nim])'><img src='images/foto_mahasiswa/$r_mhs[mahasiswa_foto]' height='200px'></a></center>";
	}
	else
	{
	echo"<center><img src='images/foto_mahasiswa/no_photo.png' height='200px'></center>";
	}
	echo"<br>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr><th width='200px'>NIM</th><td>$r_mhs[mahasiswa_nim]</td></tr>
	<tr><th>Nama</th><td>$r_mhs[mahasiswa_nama]</td></tr>
	<tr><th>Jenis Kelamin</th><td>";
	if($r_mhs[jeniskelamin_id]=="L")
		echo "Laki-laki";
	if($r_mhs[jeniskelamin_id]=="P")
		echo "Perempuan";
	echo"</td></tr>
	<tr><th>Tempat, Tanggal Lahir</th><td>$r_mhs[mahasiswa_tmpt_lhr], $tgl</td></tr>
	<tr><th>Alamat</th><td>No. $r_mhs[mahasiswa_no] Rt. $r_mhs[mahasiswa_rt] Rw. $r_mhs[mahasiswa_rw] Ds. $r_mhs[mahasiswa_desa] Kec. $r_mhs[mahasiswa_kecamatan] $r_mhs[kota_nama], $r_mhs[propinsi_nama] $r_mhs[mahasiswa_kodepos]</td></tr>
	<tr><th>No. Telepon</th><td>$r_mhs[mahasiswa_telp]</td></tr>
	<tr><th>Email</th><td>$r_mhs[mahasiswa_email]</td></tr>
	<tr><th>Agama</th><td>$r_mhs[agama_kode]</td></tr>
	<tr><th>Semester</th><td>$r_mhs[semester_id]</td></tr>
	<tr><th>Program Studi</th><td>$r_mhs[prodi_nama]</td></tr>
	<tr><th>Jurusan</th><td>$r_mhs[jenjang_id] $r_mhs[jurusan_nama]</td></tr>
	<tr><th>Asal Sekolah</th><td>$r_mhs[mahasiswa_asal_sekolah]</td></tr>
	<tr><th>Jumlah Nilai</th><td>$r_mhs[mahasiswa_jml_nilai]</td></tr>
	<tr><th>Jumlah Pelajaran</th><td>$r_mhs[mahasiswa_jml_pelajaran]</td></tr>
	<tr><th>Jumlah Rata-rata</th><td>".round($r_mhs[mahasiswa_jml_nilai]/$r_mhs[mahasiswa_jml_pelajaran],2)."</td></tr>
	</tbody>
	</table>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;
	
	case "detailnilai":
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Kelas Bimbingan &#187; Kartu Hasil Studi Mahasiswa NIM $_GET[nim]</h3>
	<br class='clear'/>";
	$q_semester=mssql_query("SELECT  DISTINCT tt_kurikulum_matakuliah.semester_id
						FROM tm_mahasiswa 
							INNER JOIN tt_kurikulum_prodi ON tm_mahasiswa.prodi_id = tt_kurikulum_prodi.prodi_id 
							INNER JOIN tm_kurikulum ON tt_kurikulum_prodi.kurikulum_id = tm_kurikulum.kurikulum_id 
							INNER JOIN tt_kurikulum_matakuliah ON tm_kurikulum.kurikulum_id = tt_kurikulum_matakuliah.kurikulum_id 
							INNER JOIN tm_matakuliah ON tt_kurikulum_matakuliah.matakuliah_id = tm_matakuliah.matakuliah_id
						WHERE (tm_mahasiswa.mahasiswa_nim = '$_GET[nim]')
						ORDER BY tt_kurikulum_matakuliah.semester_id
						");
	while($r_semester=mssql_fetch_array($q_semester))
	{
		$q_nilai=mssql_query("
							SELECT tm_matakuliah.matakuliah_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks, 
								tm_matakuliah.matakuliah_tipe, 
								tm_matakuliah.matakuliah_jam, 
								(SELECT TOP (1) nilai_tipe_id
									FROM tt_nilai
									WHERE (matakuliah_id = tm_matakuliah.matakuliah_id) 
										AND (mahasiswa_nim = tm_mahasiswa.mahasiswa_nim)
										AND tt_nilai.nilai_disetujui='Y'
									ORDER BY nilai_id DESC) AS nilai,
                          		(SELECT nilai_tipe_konstanta
                            		FROM tm_nilai_tipe
                            		WHERE (nilai_tipe_id =(SELECT TOP (1) nilai_tipe_id
                                                         FROM tt_nilai AS tt_nilai_1
                                                         WHERE (matakuliah_id = tm_matakuliah.matakuliah_id) 
														 	AND (mahasiswa_nim = tm_mahasiswa.mahasiswa_nim)
                                                         ORDER BY nilai_id DESC))) AS konstanta
							FROM tm_mahasiswa 
								INNER JOIN tt_kurikulum_prodi ON tm_mahasiswa.prodi_id = tt_kurikulum_prodi.prodi_id 
								INNER JOIN tm_kurikulum ON tt_kurikulum_prodi.kurikulum_id = tm_kurikulum.kurikulum_id 
								INNER JOIN tt_kurikulum_matakuliah ON tm_kurikulum.kurikulum_id = tt_kurikulum_matakuliah.kurikulum_id
									AND  tt_kurikulum_matakuliah.semester_id='$r_semester[semester_id]'
								INNER JOIN tm_matakuliah ON tt_kurikulum_matakuliah.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tm_mahasiswa.mahasiswa_nim = '$_GET[nim]')
							ORDER BY tt_kurikulum_matakuliah.semester_id, 
								tm_matakuliah.matakuliah_id
							");
		echo "<b>Semester $r_semester[semester_id]</b>
		<table class='input' cellpadding='0' cellspacing='0' width='100%'>
		<tbody>
		<tr align='center'>
			<th width='30px' class='kiri'>no</th>
			<th width='130px'>kode mata kuliah</th>
			<th>nama mata kuliah</th>
			<th width='70px'>sks</th>
			<th width='70px'>tipe</th>
			<th width='70px'>jam</th>
			<th width='70px' class='kanan'>nilai</th>
		</tr>";
		$no=1;
		$sks=0;
		$jum=0;
		$ips=0;
		while($r_nilai=mssql_fetch_array($q_nilai))
		{
			$line =tabel_normal($no);
			echo "$line<td align='center'>$no</td>
				<td align='center'>$r_nilai[matakuliah_id]</td>
				<td>$r_nilai[matakuliah_nama]</td>
				<td align='center'>$r_nilai[matakuliah_sks]</td>
				<td align='center'>$r_nilai[matakuliah_tipe]</td>
				<td align='center'>$r_nilai[matakuliah_jam]</td>";
				if($r_nilai[nilai]!=NULL)
					echo "<td align='center'>$r_nilai[nilai]</td>";
				else
					echo "<td align='center'>NA</td>";
			echo "</tr>";
			$no++;
			if($r_nilai[nilai]!=NULL)
			{
				$sks=$sks+$r_nilai[matakuliah_sks];
				$jum=$jum+($r_nilai[matakuliah_sks]*$r_nilai[konstanta]);
			}
		}
		$ips=$jum/$sks;
		$sks_tot=$sks_tot+$sks;
		$jum_tot=$jum_tot+$jum;
		if($ips!=0)
			echo "<tr><th colspan='4' align='center'>IPS</th><th align='center'>$sks</th><th colspan='3' align='center'>".number_format($ips,2)."</th></tr>";
		else
			echo "<tr><th colspan='4' align='center'>IPS</th><th align='center'>NA</th><th colspan='3' align='center'>NA</th></tr>";
		echo "</tbody>
		</table>
		<br>";
	}
	$ipk=$jum_tot/$sks_tot;
	echo "<b>Index Prestasi Komulatif (IPK) : ".number_format($ipk,2)."
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$aksi?act=printtranskip&nim=$_GET[nim]' title='Cetak/Print'><img src='template/images/printer.png'> Print</a></b>
	</div>";
	break;

	case "historynilai":
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Kelas Bimbingan &#187; History Nilai NIM $_GET[nim]</h3>
	<br class='clear'/>";
	$q_periode=mssql_query("SELECT DISTINCT tt_nilai.periode_id
						FROM tt_nilai
							INNER JOIN tm_periode ON tm_periode.periode_id=tt_nilai.periode_id
						WHERE (tt_nilai.mahasiswa_nim = '$_GET[nim]')
							AND (tm_periode.periode_aktif = 'F')
						ORDER BY tt_nilai.periode_id DESC
						");
	while($r_periode=mssql_fetch_array($q_periode))
	{
		$q_nilai=mssql_query("
							SELECT tm_matakuliah.matakuliah_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks, 
								tm_matakuliah.matakuliah_tipe,
								tt_nilai.nilai_tipe_id
							FROM tm_matakuliah
								INNER JOIN tt_nilai ON tt_nilai.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_nilai.mahasiswa_nim = '$_GET[nim]')
								AND (tt_nilai.periode_id='$r_periode[periode_id]')
							ORDER BY tm_matakuliah.matakuliah_id
							");
		echo "<h3>Periode $r_periode[periode_id]</h3>
		<table class='input' cellpadding='0' cellspacing='0' width='100%'>
		<tbody>
		<tr align='center'>
			<th width='30px' class='kiri'>no</th>
			<th width='130px'>kode mata kuliah</th>
			<th>nama mata kuliah</th>
			<th width='70px'>sks</th>
			<th width='120px'>tipe</th>
			<th width='70px' class='kanan'>nilai</th>
		</tr>";
		$no=1;
		$sks=0;
		while($r_nilai=mssql_fetch_array($q_nilai))
		{
			$line =tabel_normal($no);
			echo "$line<td align='center'>$no</td>
				<td align='center'>$r_nilai[matakuliah_id]</td>
				<td>$r_nilai[matakuliah_nama]</td>
				<td align='center'>$r_nilai[matakuliah_sks]</td>";
				if($r_nilai[matakuliah_tipe] == 'T')
					echo "<td align='center'>Teori</td>";
				if($r_nilai[matakuliah_tipe] == 'P')
					echo "<td align='center'>Praktek</td>";
				echo "<td align='center'>$r_nilai[nilai_tipe_id]</td>
			</tr>";
			$sks=$sks+$r_nilai[matakuliah_sks];
			$no++;
		}
		echo "<tr align='center'><th colspan='3'>&nbsp;</th><th>$sks</th><th colspan='3'>&nbsp;</th>
		</tbody>
		</table>
		<br>";
	}
	echo "<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a></b>
	</div>";
	break;

	case "ksm":
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Kelas Bimbingan &#187; Kartu Studi Mahasiswa NIM $_GET[nim] Periode $periode</h3>
	<br class='clear'/>";
		$q_nilai=mssql_query("
							SELECT tm_matakuliah.matakuliah_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks, 
								tm_matakuliah.matakuliah_tipe,
								tt_nilai.kelas_id
							FROM tm_matakuliah
								INNER JOIN tt_nilai ON tt_nilai.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_nilai.mahasiswa_nim = '$_GET[nim]')
								AND (tt_nilai.periode_id='$periode')
							ORDER BY tm_matakuliah.matakuliah_id
							");
		echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
		<tbody>
		<tr align='center'>
			<th width='30px' class='kiri'>no</th>
			<th width='130px'>kode mata kuliah</th>
			<th>nama mata kuliah</th>
			<th width='70px'>sks</th>
			<th width='120px'>tipe</th>
			<th width='70px' class='kanan'>kelas</th>
		</tr>";
		$no=1;
		$sks=0;
		while($r_nilai=mssql_fetch_array($q_nilai))
		{
			$line =tabel_normal($no);
			echo "$line<td align='center'>$no</td>
				<td align='center'>$r_nilai[matakuliah_id]</td>
				<td>$r_nilai[matakuliah_nama]</td>
				<td align='center'>$r_nilai[matakuliah_sks]</td>";
				if($r_nilai[matakuliah_tipe] == 'T')
					echo "<td align='center'>Teori</td>";
				if($r_nilai[matakuliah_tipe] == 'P')
					echo "<td align='center'>Praktek</td>";
				echo "<td align='center'>$r_nilai[kelas_id]</td>
			</tr>";
			$sks=$sks+$r_nilai[matakuliah_sks];
			$no++;
		}
		echo "<tr align='center'><th colspan='3'>&nbsp;</th><th>$sks</th><th colspan='3'>&nbsp;</th>
		</tbody>
		</table>
		<br>";
	echo "<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a></b>
	</div>";
	break;

	case "nilai":
	echo"
	<div class='content'>
	<h3>Perwalian &#187; Kelas Bimbingan &#187; Nilai NIM $_GET[nim] Periode $periode</h3>
	<br class='clear'/>";
		$q_nilai=mssql_query("
							SELECT tm_matakuliah.matakuliah_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks, 
								tm_matakuliah.matakuliah_tipe,
								tt_nilai.*
							FROM tm_matakuliah
								INNER JOIN tt_nilai ON tt_nilai.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_nilai.mahasiswa_nim = '$_GET[nim]')
								AND (tt_nilai.periode_id='$periode')
							ORDER BY tm_matakuliah.matakuliah_id
							");
		echo "<table class='input' cellpadding='0' cellspacing='0' width='100%'>
		<tbody>
		<tr align='center'>
			<th class='kiri'>no</th>
			<th>kode mata kuliah</th>
			<th>nama mata kuliah</th>
			<th>sks</th>
			<th>tipe</th>
			<th>nilai uts</th>
			<th>nilai uas</th>
			<th>nilai 1</th>
			<th>nilai 2</th>
			<th>nilai 3</th>
			<th>nilai 4</th>
			<th>nilai 5</th>
			<th>nilai 6</th>
			<th>nilai 7</th>
			<th>nilai 8</th>
			<th>nilai 9</th>
			<th>nilai 10</th>
			<th>nilai rata-rata</th>
			<th class='kanan'>nilai akhir</th>
		</tr>";
		$no=1;
		while($r_nilai=mssql_fetch_array($q_nilai))
		{
			$line =tabel_normal($no);
			echo "$line<td align='center'>$no</td>
				<td align='center'>$r_nilai[matakuliah_id]</td>
				<td>$r_nilai[matakuliah_nama]</td>
				<td align='center'>$r_nilai[matakuliah_sks]</td>";
				if($r_nilai[matakuliah_tipe] == 'T')
					echo "<td align='center'>Teori</td>";
				if($r_nilai[matakuliah_tipe] == 'P')
					echo "<td align='center'>Praktek</td>";
				echo "<td align='center'>".$r_nilai[nilai_uts]."</td>
				<td align='center'>".$r_nilai[nilai_uas]."</td>
				<td align='center'>".$r_nilai[nilai_1]."</td>
				<td align='center'>".$r_nilai[nilai_2]."</td>
				<td align='center'>".$r_nilai[nilai_3]."</td>
				<td align='center'>".$r_nilai[nilai_4]."</td>
				<td align='center'>".$r_nilai[nilai_5]."</td>
				<td align='center'>".$r_nilai[nilai_6]."</td>
				<td align='center'>".$r_nilai[nilai_7]."</td>
				<td align='center'>".$r_nilai[nilai_8]."</td>
				<td align='center'>".$r_nilai[nilai_9]."</td>
				<td align='center'>".$r_nilai[nilai_10]."</td>
				<td align='center'>$r_nilai[nilai_rata_rata]</td>
				<td align='center'>$r_nilai[nilai_tipe_id]</td>
			</tr>";
			$no++;
		}
		echo "</tbody>
		</table>
		<br>";
	echo "<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a></b>
	</div>";
	break;
}
?>
