<?php
switch($_GET[act]){
  // Tampil jurusan Utama
	default:
	?>
	<script type="text/javascript">
        var htmlobjek;
        $(document).ready(function(){
          $("#periode").change(function(){
            var periode = $("#periode").val();
            $.ajax({
                url: "modul/dosen/perkuliahan/dft_kelas/kelas.php",
                data: "periode="+periode,
                cache: false,
                success: function(msg){
                    $("#kelas").html(msg);
                }
            });
          });
        });
    </script>
    <?php
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas</h3>
	<br class='clear'/>
	<b>Tahun Ajaran</b>
	<select name=periode id='periode'>";
	$q_periode=mssql_query("select periode_id from tm_periode where periode_aktif<> 'N' order by  periode_id desc");
	while($r_periode=mssql_fetch_array($q_periode))
	{
		if($r_periode[periode_id] == $_SESSION[periode])
			echo "<option value='$r_periode[periode_id]' selected='selected'>$r_periode[periode_id]</option>";
		else
			echo "<option value='$r_periode[periode_id]'>$r_periode[periode_id]</option>";
	}
	echo "</select>
	<br class='clear'>
	<br class='clear'>
	<div id='kelas'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='130px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th width='100px'>kode kelas</th>
		<th>sks</th>
		<th class='kanan' width='90px'>&nbsp;</th>
	</tr>";
	$q_jadwal=mssql_query("SELECT distinct tt_jadwal.kelas_id,
								tt_jadwal.matakuliah_id,
								tm_matakuliah.matakuliah_nama,
								tm_matakuliah.matakuliah_sks
							FROM tt_jadwal
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id
							WHERE (tt_jadwal.periode_id = '$periode') 
								AND (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]')
							ORDER BY tt_jadwal.matakuliah_id,
								tt_jadwal.kelas_id
							");
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_jadwal[matakuliah_id]</td>
			<td>$r_jadwal[matakuliah_nama]</td>
			<td>$r_jadwal[kelas_id]</td>
			<td align='center'>$r_jadwal[matakuliah_sks]</td>
			<td><a href='$aksi_self&act=absensi&periode=$periode&kelas_id=$r_jadwal[kelas_id]&matakuliah_id=$r_jadwal[matakuliah_id]' title='Absensi $r_jadwal[kelas_id] $r_jadwal[matakuliah_nama]'><img src='template/images/detail.ico'></a>
			&nbsp;&nbsp;
			<a href='$aksi_self&act=mahasiswa&periode=$periode&kelas_id=$r_jadwal[kelas_id]&matakuliah_id=$r_jadwal[matakuliah_id]' title='Mahasiswa $r_jadwal[kelas_id] $r_jadwal[matakuliah_nama]'><img src='template/images/group.png'></a>
			&nbsp;&nbsp;
			<a href='$aksi_self&act=nilai&periode=$periode&kelas_id=$r_jadwal[kelas_id]&matakuliah_id=$r_jadwal[matakuliah_id]' title='Nilai $r_jadwal[kelas_id] $r_jadwal[matakuliah_nama]'><img src='template/images/chart_bar.png'></a>
			</td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>
	<br><br>
	</div>";
	break;
	
	case "absensi";
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Absensi</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='170px'>tanggal</th>
		<th width='100px'>jam</th>
		<th width='150px'>kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th class='kanan' width='30px'>&nbsp;</th>
	</tr>";
	$q_absen=mssql_query("
						SELECT DISTINCT tt_absensi_mahasiswa.absensi_mahasiswa_tgl, 
							tm_matakuliah.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_jam, 
							tm_jam.jam_waktu
						FROM tt_absensi_mahasiswa 
							INNER JOIN tm_matakuliah ON tt_absensi_mahasiswa.matakuliah_id = tm_matakuliah.matakuliah_id 
							INNER JOIN tm_jam ON tt_absensi_mahasiswa.jam_id = tm_jam.jam_id
						WHERE(tt_absensi_mahasiswa.periode_id = '$_GET[periode]') 
							AND (tt_absensi_mahasiswa.matakuliah_id = '$_GET[matakuliah_id]') 
							AND (tt_absensi_mahasiswa.kelas_id = '$_GET[kelas_id]') 
							");
	$no=1;
	while($r_absen=mssql_fetch_array($q_absen))
	{
		$akhir=$r_absen[jam_waktu]+$r_absen[matakuliah_jam];
		if($akhir < 10)
		{
			$akhir="0$akhir.00";
		}
		else
		{
			$akhir="$akhir.00";
		}

		$line =tabel_normal($no);
		$tanggal=tgl_indo($r_absen[absensi_mahasiswa_tgl]);
		echo "$line<td align='center'>$no</td>
			<td>$tanggal</td>
			<td>$r_absen[jam_waktu] - $akhir</td>
			<td align='center'>$r_absen[matakuliah_id]</td>
			<td>$r_absen[matakuliah_nama]</td>
			<td><a href='$aksi_self&act=detailabsensi&periode=$_GET[periode]&kelas_id=$_GET[kelas_id]&matakuliah_id=$_GET[matakuliah_id]&tgl=$r_absen[absensi_mahasiswa_tgl]' title='Detai Absensi $_GET[kelas_id] $r_absen[matakuliah_nama]'><img src='template/images/detail.ico'></a></td>
	</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>&nbsp;&nbsp;&nbsp;
	<a href='$aksi_self&act=tambahabsensi&periode=$_GET[periode]&kelas_id=$_GET[kelas_id]&matakuliah_id=$_GET[matakuliah_id]' title='Tambah Absensi'><img src='template/images/add.png'> <b>Tambah</b></a>
	</div>";
	break;

	case "detailabsensi";
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Absensi &#187; Detail Absensi</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>no</th>
		<th >tanggal</th>
		<th>jam</th>
		<th >kode mata kuliah</th>
		<th>nama mata kuliah</th>
		<th>nim</th>
		<th>nama mahasiswa</th>
		<th >keterangan</th>
		<th class='kanan' >&nbsp;</th>
	</tr>";
	$q_absen=mssql_query("
						SELECT tt_absensi_mahasiswa.absensi_mahasiswa_tgl,
							tt_absensi_mahasiswa.absensi_mahasiswa_id, 
							tt_absensi_mahasiswa.absensi_mahasiswa_acc, 
							tm_keterangan_absensi.keterangan_absensi_nama, 
							tm_mahasiswa.mahasiswa_nim, 
							tm_mahasiswa.mahasiswa_nama, 
                      		tm_matakuliah.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_jam, 
							tm_jam.jam_waktu
						FROM tt_absensi_mahasiswa 
							INNER JOIN tm_mahasiswa ON tt_absensi_mahasiswa.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim 
							INNER JOIN tm_matakuliah ON tt_absensi_mahasiswa.matakuliah_id = tm_matakuliah.matakuliah_id 
							INNER JOIN tm_jam ON tt_absensi_mahasiswa.jam_id = tm_jam.jam_id
							INNER JOIN tm_keterangan_absensi ON tm_keterangan_absensi.keterangan_absensi_id=tt_absensi_mahasiswa.keterangan_absensi_id
						WHERE (tt_absensi_mahasiswa.periode_id = '$_GET[periode]') 
							AND (tt_absensi_mahasiswa.matakuliah_id = '$_GET[matakuliah_id]') 
							AND (tt_absensi_mahasiswa.kelas_id = '$_GET[kelas_id]')
							AND (tt_absensi_mahasiswa.absensi_mahasiswa_tgl = '$_GET[tgl]')
						");
	$no=1;
	while($r_absen=mssql_fetch_array($q_absen))
	{
		$akhir=$r_absen[jam_waktu]+$r_absen[matakuliah_jam];
		if($akhir < 10)
		{
			$akhir="0$akhir.00";
		}
		else
		{
			$akhir="$akhir.00";
		}

		$tanggal=tgl_indo($r_absen[absensi_mahasiswa_tgl]);
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td>$tanggal</td>
			<td>$r_absen[jam_waktu] - $akhir</td>
			<td align='center'>$r_absen[matakuliah_id]</td>
			<td>$r_absen[matakuliah_nama]</td>
			<td align='center'>$r_absen[mahasiswa_nim]</td>
			<td>$r_absen[mahasiswa_nama]</td>
			<td>$r_absen[keterangan_absensi_nama]</td>
			<td>";
			if($r_absen[absensi_mahasiswa_acc]!="Y")
				echo "<a href='$aksi_self&act=ubahabsensi&id=$r_absen[absensi_mahasiswa_id]' title='Ubah Absensi $r_absen[mahasiswa_nim]'><img src='template/images/icon_edit.png'></a>";
			echo "</td>
	</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	<br><br>
	<a href='$aksi_self' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>
	</div>";
	break;

	case "ubahabsensi";
	$q_uabsensi=mssql_query("
							SELECT tt_absensi_mahasiswa.*,
								tm_mahasiswa.mahasiswa_nama,
								tm_matakuliah.matakuliah_nama,
								tm_jam.jam_waktu
							FROM tt_absensi_mahasiswa 
								INNER JOIN tm_mahasiswa ON tt_absensi_mahasiswa.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim
								INNER JOIN tm_matakuliah ON tt_absensi_mahasiswa.matakuliah_id=tm_matakuliah.matakuliah_id
								INNER JOIN tm_jam ON tt_absensi_mahasiswa.jam_id=tm_jam.jam_id
							WHERE tt_absensi_mahasiswa.absensi_mahasiswa_id='$_GET[id]'");
	$r_uabsensi=mssql_fetch_array($q_uabsensi);
	$tanggal=tgl_form($r_uabsensi[absensi_mahasiswa_tgl]);
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Absensi &#187; Detail Absensi &#187; Ubah Absensi</h3>
	<br class='clear'/>
	<form method='post' action='$aksi?act=ubahabsen'>
	<input type='hidden' name='absensi_mahaiswa_id' value='$_GET[id]'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr>
		<th width='200px'>NIM</th>
		<td><input type='text' value='$r_uabsensi[mahasiswa_nim]' disabled='disabled' size='10'></td>
	</tr>
	<tr>
		<th width='200px'>Nama</th>
		<td><input type='text' value='$r_uabsensi[mahasiswa_nama]' disabled='disabled' size='40'></td>
	</tr>
	<tr>
		<th width='200px'>Periode</th>
		<td><input type='text' name='periode_id' value='$r_uabsensi[periode_id]' readonly='readonly' size='10'></td>
	</tr>
	<tr>
		<th width='200px'>Kelas</th>
		<td><input type='text' name='kelas_id' value='$r_uabsensi[kelas_id]' readonly='readonly' size='10'></td>
	</tr>
	<tr>
		<th width='200px'>Mata Kuliah</th>
		<td><input type='text' name='matakuliah_id' value='$r_uabsensi[matakuliah_id]' readonly='readonly' size='10'></td>
	</tr>
	<tr>
		<th width='200px'></th>
		<td><input type='text' value='$r_uabsensi[matakuliah_nama]' disabled='disabled' size='40'></td>
	</tr>
	<tr>
		<th width='200px'>Tanggal</th>
		<td><input type='text' value='$tanggal' disabled='disabled' size='20'></td>
	</tr>
	<tr>
		<th width='200px'>Jam</th>
		<td><input type='text' value='$r_uabsensi[jam_waktu]' disabled='disabled' size='10'></td>
	</tr>
	<tr>
		<th width='200px'>Keterangan</th>
		<td>";
		if($r_uabsensi[absensi_mahasiswa_acc]=='N')
		{
			echo "<select name='keterangan_absensi_id'>";
		}
		else
		{
			echo "<select name='keterangan_absensi_id' disabled='disabled'>";
		}
			$q_keterangan=mssql_query("select * from tm_keterangan_absensi");
			while($r_keterangan=mssql_fetch_array($q_keterangan))
			{
				if($r_keterangan[keterangan_absensi_id]=='$r_uabsensi[keterangan_absensi_id]')
					echo "<option value='$r_keterangan[keterangan_absensi_id]' selected='selected'>$r_keterangan[keterangan_absensi_nama]</option>'";
				else
					echo "<option value='$r_keterangan[keterangan_absensi_id]'>$r_keterangan[keterangan_absensi_nama]</option>'";
			}
		echo "</select>
		</td>
	</tr>";
	if($r_uabsensi[absensi_mahasiswa_acc]=='N')
	{
	echo"</tr>
		<th>&nbsp;</th>
		<td><input type='submit' value='Ubah'></td>
	</tr>";
	}
	echo"</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>
	</div>";
	break;

	case "tambahabsensi";
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Absensi &#187; Tambah Absensi</h3>
	<br class='clear'/>
	<form action='$aksi?act=tambahabsen' method='post'>
	<table class='input' cellpadding='0' cellspacing='0' width='60%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>periode</th>
		<th>kode mata kuliah</th>
		<th>kode kelas</th>
		<th class='kanan'>jam masuk</th>
	</tr>
	<tr align='center'>
		<td><input type='hidden' name='periode' value='$_GET[periode]' readonly='readonly'>$_GET[periode]</td>
		<td><input type='hidden' name='matkul' value='$_GET[matakuliah_id]' readonly='readonly'>$_GET[matakuliah_id]</td>
		<td><input type='hidden' name='kelas' value='$_GET[kelas_id]' readonly='readonly'>$_GET[kelas_id]</td>
		<td><select name='jam'>";
		$q_jam=mssql_query("select * from tm_jam");
		while($r_jam=mssql_fetch_array($q_jam))
		{
			echo "<option value='$r_jam[jam_id]'>$r_jam[jam_waktu]</option>";
		}
		echo "</select></td>
	</tbody>
	</table>
	<br class='clear'/>
	<table class='data' cellpadding='0' cellspacing='0' width='60%'>
	<tbody>
	<tr align='center'>
		<th width='40px'>NO</th>
		<th width='150px'>NIM</th>
		<th>NAMA MAHASISWA</th>
		<th width='120px'>KETERANGAN</th>
	</tr>";
	$q_absen=mssql_query("
						SELECT tm_mahasiswa.mahasiswa_nim, 
							tm_mahasiswa.mahasiswa_nama
						FROM tt_nilai 
							INNER JOIN tm_mahasiswa ON tt_nilai.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
						WHERE (tt_nilai.periode_id = '$_GET[periode]') 
							AND (tt_nilai.matakuliah_id = '$_GET[matakuliah_id]') 
							AND (tt_nilai.kelas_id = '$_GET[kelas_id]')						");
	$no=1;
	while($r_absen=mssql_fetch_array($q_absen))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'><input type='hidden' name='nim_$no' value='$r_absen[mahasiswa_nim]'>$r_absen[mahasiswa_nim]</td>
			<td>$r_absen[mahasiswa_nama]</td>
			<td align='center'>
				<select name='keterangan_$no'>";
				$q_keterangan=mssql_query("select * from tm_keterangan_absensi");
				while($r_keterangan=mssql_fetch_array($q_keterangan))
				{
					if($r_keterangan[keterangan_absensi_id]=='masuk')
						echo "<option value='$r_keterangan[keterangan_absensi_id]' selected='selected'>$r_keterangan[keterangan_absensi_nama]</option>'";
					else
						echo "<option value='$r_keterangan[keterangan_absensi_id]'>$r_keterangan[keterangan_absensi_nama]</option>'";
				}
				echo "</select>
			</td>
	</tr>";
		$no++;
	}
	echo "<input type='hidden' name='jummhs' value='$no'>
	<tr align='center'><th colspan='4'><input type='submit' value='Submit'></th>
	</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>
	</div>";
	break;

	case "mahasiswa";
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Rekap Absensi Mahasiswa</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='120px'>nim</th>
		<th>nama mahasiswa</th>";
		$q_keterangan=mssql_query("select * from tm_keterangan_absensi");
		while($r_keterangan=mssql_fetch_array($q_keterangan))
		{
			echo "<th>$r_keterangan[keterangan_absensi_nama]</th>";
			$query=$query."(select count(*) 
							from tt_absensi_mahasiswa
							where tt_absensi_mahasiswa.periode_id='$_GET[periode]'
								and tt_absensi_mahasiswa.matakuliah_id='$_GET[matakuliah_id]'
								and tt_absensi_mahasiswa.kelas_id='$_GET[kelas_id]'
								and tt_absensi_mahasiswa.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim
								and tt_absensi_mahasiswa.keterangan_absensi_id='$r_keterangan[keterangan_absensi_id]') as $r_keterangan[keterangan_absensi_id],";
		}
		echo "<th class='kanan' width='60px'>presentasi</th>
	</tr>";
	$q_absen=mssql_query("
							select tm_mahasiswa.mahasiswa_nim,
								$query
								tm_mahasiswa.mahasiswa_nama
							from tt_nilai
								INNER JOIN tm_mahasiswa ON tt_nilai.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim
							where tt_nilai.periode_id='$_GET[periode]'
								and tt_nilai.matakuliah_id='$_GET[matakuliah_id]'
								and tt_nilai.kelas_id='$_GET[kelas_id]'
						");
	$no=1;
	while($r_absen=mssql_fetch_array($q_absen))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_absen[mahasiswa_nim]</td>
			<td>$r_absen[mahasiswa_nama]</td>";
			$q_keterangan=mssql_query("select * from tm_keterangan_absensi");
			while($r_keterangan=mssql_fetch_array($q_keterangan))
			{
				$nama=$r_keterangan[keterangan_absensi_id];
				echo "<td>$r_absen[$nama]</td>";
				$jum=$jum+$r_absen[$nama];
				$alfa=$r_absen[alfa];
			}
			$presentase=($jum-$alfa)/$jum*100;
			$pre=number_format($presentase,2,',','.');
			echo "<td align='right'>$pre %</td>
	</tr>";
		$jum=0;
		$alfa=0;
		$presentase=0;
		$no++;
	}
	echo "</tbody>
	</table>
	<br><br>
	<a href='$aksi_self' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>
	</div>";
	break;

	case "nilai";
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Nilai</h3>
	<br class='clear'/>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='100px'>nim</th>
		<th width='200px'>nama mahasiswa</th>
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
		<th>nilai akhir</th>
		<th class='kanan'>&nbsp;</th>
	</tr>";
	$q_nilai=mssql_query("
						SELECT tm_mahasiswa.mahasiswa_nim, 
							tm_mahasiswa.mahasiswa_nama, 
							tt_nilai.nilai_id,  
							tt_nilai.nilai_uts, 
							tt_nilai.nilai_uas, 
							tt_nilai.nilai_1, 
							tt_nilai.nilai_2, 
							tt_nilai.nilai_3, 
							tt_nilai.nilai_4, 
							tt_nilai.nilai_5, 
							tt_nilai.nilai_6, 
							tt_nilai.nilai_7, 
							tt_nilai.nilai_8, 
							tt_nilai.nilai_9, 
							tt_nilai.nilai_10,
							tt_nilai.nilai_rata_rata,
							tt_nilai.nilai_tipe_id 
						FROM tt_nilai 
							INNER JOIN tm_mahasiswa ON tt_nilai.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
						WHERE (tt_nilai.periode_id = '$_GET[periode]') 
							AND (tt_nilai.matakuliah_id = '$_GET[matakuliah_id]') 
							AND (tt_nilai.kelas_id = '$_GET[kelas_id]')						
						");
	$no=1;
	while($r_nilai=mssql_fetch_array($q_nilai))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'>$r_nilai[mahasiswa_nim]</td>
			<td>$r_nilai[mahasiswa_nama]</td>
			<td align='right'>$r_nilai[nilai_uts]</td>
			<td align='right'>$r_nilai[nilai_uas]</td>
			<td align='right'>$r_nilai[nilai_1]</td>
			<td align='right'>$r_nilai[nilai_2]</td>
			<td align='right'>$r_nilai[nilai_3]</td>
			<td align='right'>$r_nilai[nilai_4]</td>
			<td align='right'>$r_nilai[nilai_5]</td>
			<td align='right'>$r_nilai[nilai_6]</td>
			<td align='right'>$r_nilai[nilai_7]</td>
			<td align='right'>$r_nilai[nilai_8]</td>
			<td align='right'>$r_nilai[nilai_9]</td>
			<td align='right'>$r_nilai[nilai_10]</td>
			<td align='right'>$r_nilai[nilai_rata_rata]</td>
			<td align='center'><b>$r_nilai[nilai_tipe_id]</b></td>
			<td align='center'><a href='$aksi_self&act=editnilai&periode=$periode&kelas_id=$_GET[kelas_id]&matakuliah_id=$_GET[matakuliah_id]&id=$r_nilai[nilai_id]' title='Ubah Nilai $r_nilai[mahasiswa_nim]$r_nilai[mahasiswa_nama]'><img src='template/images/icon_edit.png'></a></td>
	</tr>";
		$no++;
	}
	$jumdata=$no-1;
	echo "</tbody>
	</table>
	<br><br>
	<a href='$aksi_self' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>";
	if($_GET[periode] == $_SESSION[periode])
	{
		echo"&nbsp;&nbsp;&nbsp;<a href='$aksi_self&act=tambahnilai&periode=$_GET[periode]&kelas_id=$_GET[kelas_id]&matakuliah_id=$_GET[matakuliah_id]&jumdata=$jumdata' title='Tambah Nilai'><img src='template/images/add.png'> <b>Tambah</b></a>";
	}
	echo"</div>";
	break;

	case "tambahnilai";
	echo "<script language='javascript'>
	function validasi(form_data){";
	for($i=1;$i<=$_GET[jumdata];$i++)
	{
		echo"if (form_data.nilai_$i.value > 100){
			alert('Nilai Mahasiswa yang anda masukkan lebih dari 100');
			form_data.nilai_$i.focus();
			return (false);
		  }
			
		  if (form_data.nilai_$i.value < 0){
			alert('Nilai Mahasiswa yang anda masukkan kurang dari 0');
			form_data.nilai_$i.focus();
			return (false);
		  }
		  
		  if (form_data.nilai_$i.value == ''){
			alert('Anda belum memasukkan Nilai Mahasiswa');
			form_data.nilai_$i.focus();
			return (false);
		  }";
	}
	
	echo "return (true);
    }
    </script>";
    ?>
	<script language=Javascript>
    <!--
    function isNumberKey(evt)
    {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    
    return false;
    return true;
    }
    //-->
    </script>

    <?php
		$q_nilai=mssql_query("
						SELECT TOP(1) tt_nilai.nilai_uts, 
							tt_nilai.nilai_uas, 
							tt_nilai.nilai_1, 
							tt_nilai.nilai_2, 
							tt_nilai.nilai_3, 
							tt_nilai.nilai_4, 
							tt_nilai.nilai_5, 
							tt_nilai.nilai_6, 
							tt_nilai.nilai_7, 
							tt_nilai.nilai_8, 
							tt_nilai.nilai_9, 
							tt_nilai.nilai_10,
							tt_prodi_nilai.nilai_uts AS persen_uts, 
							tt_prodi_nilai.nilai_uas AS persen_uas, 
							tt_prodi_nilai.nilai_1 AS persen_1, 
							tt_prodi_nilai.nilai_2 AS persen_2, 
							tt_prodi_nilai.nilai_3 AS persen_3, 
							tt_prodi_nilai.nilai_4 AS persen_4, 
							tt_prodi_nilai.nilai_5 AS persen_5, 
							tt_prodi_nilai.nilai_6 AS persen_6, 
							tt_prodi_nilai.nilai_7 AS persen_7, 
							tt_prodi_nilai.nilai_8 AS persen_8, 
							tt_prodi_nilai.nilai_9 AS persen_9, 
							tt_prodi_nilai.nilai_10 AS persen_10 
						FROM tt_nilai 
							INNER JOIN tt_prodi_nilai ON tt_nilai.periode_id= tt_prodi_nilai.periode_id
								AND tt_nilai.matakuliah_id= tt_prodi_nilai.matakuliah_id
						WHERE (tt_nilai.periode_id = '$_GET[periode]') 
							AND (tt_nilai.matakuliah_id = '$_GET[matakuliah_id]') 
							AND (tt_nilai.kelas_id = '$_GET[kelas_id]')						
						");
	$r_nilai=mssql_fetch_array($q_nilai);
	echo"
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Nilai &#187; Tambah Nilai</h3>
	<br class='clear'/>
	<form action='$aksi?act=tambahnilai' method='post' id='form_data' onSubmit='return validasi(this)'>
	<table class='input' cellpadding='0' cellspacing='0' width='60%'>
	<tbody>
	<tr align='center'>
		<th class='kiri'>periode</th>
		<th>kode mata kuliah</th>
		<th>kode kelas</th>
		<th class='kanan'>Nilai Tipe</th>
	</tr>
	<tr align='center'>
		<td><input type='hidden' name='periode' value='$_GET[periode]' readonly='readonly'>$_GET[periode]</td>
		<td><input type='hidden' name='matkul' value='$_GET[matakuliah_id]' readonly='readonly'>$_GET[matakuliah_id]</td>
		<td><input type='hidden' name='kelas' value='$_GET[kelas_id]' readonly='readonly'>$_GET[kelas_id]</td>
		<td><select name='kolomnilai'>";
		$tipe_nilai=0;
		if($r_nilai[persen_uts] != "")
		{		
			if($r_nilai[nilai_uts] == "0.00" || $r_nilai[nilai_uts] == "")
			{
				echo "<option value='nilai_uts'>Nilai UTS</option>";
				$tipe_nilai++;
			}
		}
		if($r_nilai[persen_uas] != "")
		{		
			if($r_nilai[nilai_uas] == "0.00" || $r_nilai[nilai_uas] == "")
			{
				echo "<option value='nilai_uas'>Nilai UAS</option>";
				$tipe_nilai++;
			}
		}
		for($i=1;$i<=10;$i++)
		{
			if($r_nilai[persen_."$i"] != "")
			{		
				if($r_nilai[nilai_."$i"] == "0.00" || $r_nilai[nilai_."$i"] == "")
				{
					echo "<option value='nilai_$i'>Nilai $i</option>";
					$tipe_nilai++;
				}
			}
		}
		echo "</select></td>
	</tbody>
	</table>
	<br class='clear'/>
	<table class='data' cellpadding='0' cellspacing='0' width='60%'>
	<tbody>
	<tr align='center'>
		<th width='40px'>NO</th>
		<th width='120px'>NIM</th>
		<th>NAMA MAHASISWA</th>
		<th width='120px'>NILAI</th>
	</tr>";
	$q_absen=mssql_query("
							SELECT tm_mahasiswa.mahasiswa_nim, 
								tm_mahasiswa.mahasiswa_nama
							FROM tt_nilai 
								INNER JOIN tm_mahasiswa ON tt_nilai.mahasiswa_nim = tm_mahasiswa.mahasiswa_nim
							WHERE (tt_nilai.periode_id = '$_GET[periode]') 
								AND (tt_nilai.matakuliah_id = '$_GET[matakuliah_id]') 
								AND (tt_nilai.kelas_id = '$_GET[kelas_id]')
						");
	$no=1;
	while($r_absen=mssql_fetch_array($q_absen))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td align='center'><input type='hidden' name='nim_$no' value='$r_absen[mahasiswa_nim]'>$r_absen[mahasiswa_nim]</td>
			<td>$r_absen[mahasiswa_nama]</td>
			<td align='center'><input type='text' name='nilai_$no' size='4' maxlength='6' value='00.00' onkeypress='return isNumberKey(event)'></td>
	</tr>";
		$no++;
	}
	echo "<input type='hidden' name='jummhs' value='$no'>";
	if($tipe_nilai>0)
	{
		echo "<tr align='center'><th colspan='4'><input type='submit' value='Submit'></th>";
	}
	echo "</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>
	</div>";
	break;
	
	case "editnilai":
	echo "<script language='javascript'>
	function validasi(form_data){";
		echo"if (form_data.nilai_uts.value > 100){
			alert('Nilai Mahasiswa yang anda masukkan lebih dari 100');
			form_data.nilai_uts.focus();
			return (false);
		  }
			
		  if (form_data.nilai_uts.value < 0){
			alert('Nilai Mahasiswa yang anda masukkan kurang dari 0');
			form_data.nilai_$i.focus();
			return (false);
		  }
		  
		  if (form_data.nilai_uts.value == ''){
			alert('Anda belum memasukkan Nilai Mahasiswa');
			form_data.nilai_uts.focus();
			return (false);
		  }";

		echo"if (form_data.nilai_uas.value > 100){
			alert('Nilai Mahasiswa yang anda masukkan lebih dari 100');
			form_data.nilai_$i.focus();
			return (false);
		  }
			
		  if (form_data.nilai_uas.value < 0){
			alert('Nilai Mahasiswa yang anda masukkan kurang dari 0');
			form_data.nilai_$i.focus();
			return (false);
		  }
		  
		  if (form_data.nilai_uas.value == ''){
			alert('Anda belum memasukkan Nilai Mahasiswa');
			form_data.nilai_uas.focus();
			return (false);
		  }";
		  
	for($i=1;$i<=10;$i++)
	{
		echo"if (form_data.nilai_$i.value > 100){
			alert('Nilai Mahasiswa yang anda masukkan lebih dari 100');
			form_data.nilai_$i.focus();
			return (false);
		  }
			
		  if (form_data.nilai_$i.value < 0){
			alert('Nilai Mahasiswa yang anda masukkan kurang dari 0');
			form_data.nilai_$i.focus();
			return (false);
		  }
		  
		  if (form_data.nilai_$i.value == ''){
			alert('Anda belum memasukkan Nilai Mahasiswa');
			form_data.nilai_$i.focus();
			return (false);
		  }";
	}
	
	echo "return (true);
    }
    </script>";
    ?>
	<script language=Javascript>
    <!--
    function isNumberKey(evt)
    {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    
    return false;
    return true;
    }
    //-->
    </script>

    <?php
		$q_nilai=mssql_query("
						SELECT tm_mahasiswa.mahasiswa_nim,
							tm_mahasiswa.mahasiswa_nama, 
							tt_nilai.nilai_uts, 
							tt_nilai.nilai_uas, 
							tt_nilai.nilai_1, 
							tt_nilai.nilai_2, 
							tt_nilai.nilai_3, 
							tt_nilai.nilai_4, 
							tt_nilai.nilai_5, 
							tt_nilai.nilai_6, 
							tt_nilai.nilai_7, 
							tt_nilai.nilai_8, 
							tt_nilai.nilai_9, 
							tt_nilai.nilai_10,
							tt_nilai.acc_nilai_uts, 
							tt_nilai.acc_nilai_uas, 
							tt_nilai.acc_nilai_1, 
							tt_nilai.acc_nilai_2, 
							tt_nilai.acc_nilai_3, 
							tt_nilai.acc_nilai_4, 
							tt_nilai.acc_nilai_5, 
							tt_nilai.acc_nilai_6, 
							tt_nilai.acc_nilai_7, 
							tt_nilai.acc_nilai_8, 
							tt_nilai.acc_nilai_9, 
							tt_nilai.acc_nilai_10,
							tt_prodi_nilai.nilai_uts AS persen_uts, 
							tt_prodi_nilai.nilai_uas AS persen_uas, 
							tt_prodi_nilai.nilai_1 AS persen_1, 
							tt_prodi_nilai.nilai_2 AS persen_2, 
							tt_prodi_nilai.nilai_3 AS persen_3, 
							tt_prodi_nilai.nilai_4 AS persen_4, 
							tt_prodi_nilai.nilai_5 AS persen_5, 
							tt_prodi_nilai.nilai_6 AS persen_6, 
							tt_prodi_nilai.nilai_7 AS persen_7, 
							tt_prodi_nilai.nilai_8 AS persen_8, 
							tt_prodi_nilai.nilai_9 AS persen_9, 
							tt_prodi_nilai.nilai_10 AS persen_10 
						FROM tt_nilai 
							INNER JOIN tm_mahasiswa ON tm_mahasiswa.mahasiswa_nim=tt_nilai.mahasiswa_nim
							INNER JOIN tt_prodi_nilai ON tt_nilai.periode_id= tt_prodi_nilai.periode_id
								AND tt_nilai.matakuliah_id= tt_prodi_nilai.matakuliah_id
						WHERE (tt_nilai.nilai_id = '$_GET[id]') 
						");
	$r_nilai=mssql_fetch_array($q_nilai);
	echo "
	<div class='content'>
	<h3>Perkuliahan &#187; Daftar Kelas &#187; Nilai &#187; Edit Nilai</h3>
	<br class='clear'/>
	<form action='$aksi?act=editnilai&periode=$_GET[periode]&kelas_id=$_GET[kelas_id]&matakuliah_id=$_GET[matakuliah_id]' method='post' id='form_data' onSubmit='return validasi(this)'>
	<input type='hidden' name='nilai_id' value='$_GET[id]'>
	<table class='data' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr>
		<th width='200px'>NIM</th>
		<td><input type='text' value='$r_nilai[mahasiswa_nim]' disabled='disabled' size='10'></td>
	</tr>
	<tr>
		<th>Nama</th>
		<td><input type='text' value='$r_nilai[mahasiswa_nama]' disabled='disabled' size='40'></td>
	</tr>
	<tr>
		<th>Nilai UTS</th>
		<td>";
		if($r_nilai[persen_uts] != "")
		{		
			if($r_nilai[acc_nilai_uts] == "" || $r_nilai[acc_nilai_uts] == "0")
				echo "<input type='text' name='nilai_uts' size='4' maxlength='6' value='$r_nilai[nilai_uts]' onkeypress='return isNumberKey(event)'> <b id='text_hijau'> Nilai Belum ACC</b>";
			else
				echo "<input type='text' name='nilai_uts' size='4' maxlength='6' value='$r_nilai[nilai_uts]' onkeypress='return isNumberKey(event)' readonly='readonly'>  <b id='text_kuning'> Nilai Telah ACC</b>";
		}
		else
		{
			echo "<input type='text' name='nilai_uts' size='4' maxlength='6' onkeypress='return isNumberKey(event)' readonly='readonly' value='00.00'> <b id='text_merah'> Tidak Ada persentase Nilai</b>";
		}
	echo"<td>
	</tr>
	<tr>
		<th>Nilai UAS</th>
		<td>";
		if($r_nilai[persen_uas] != "")
		{		
			if($r_nilai[acc_nilai_uas] == "" || $r_nilai[acc_nilai_uas] == "0")
				echo "<input type='text' name='nilai_uas' size='4' maxlength='6' value='$r_nilai[nilai_uas]' onkeypress='return isNumberKey(event)'> <b id='text_hijau'> Nilai Belum ACC</b>";
			else
				echo "<input type='text' name='nilai_uas' size='4' maxlength='6' value='$r_nilai[nilai_uas]' onkeypress='return isNumberKey(event)' readonly='readonly'>  <b id='text_kuning'> Nilai Telah ACC</b>";
		}
		else
		{
			echo "<input type='text' name='nilai_uas' size='4' maxlength='6' onkeypress='return isNumberKey(event)' readonly='readonly' value='00.00'> <b id='text_merah'> Tidak Ada persentase Nilai</b>";
		}
	echo"<td>
	</tr>";
	for($i=1;$i<=10;$i++)
	{
	echo "<tr>
		<th>Nilai $i</th>
		<td>";
		if($r_nilai[persen_."$i"] != "")
		{		
			$nilai=$r_nilai[nilai_."$i"];
			if($r_nilai[acc_nilai_."$i"] == "" || $r_nilai[acc_nilai_."$i"] == "0")
				echo "<input type='text' name='nilai_$i' size='4' maxlength='6' value='$nilai' onkeypress='return isNumberKey(event)'>  <b id='text_hijau'> Nilai Belum ACC</b>";
			else
				echo "<input type='text' name='nilai_$i' size='4' maxlength='6' value='$nilai' onkeypress='return isNumberKey(event)' readonly='readonly'>  <b id='text_kuning'> Nilai Telah ACC</b>";
		}
		else
		{
			echo "<input type='text' name='nilai_$i' size='4' maxlength='6' onkeypress='return isNumberKey(event)' readonly='readonly' value='00.00'> <b id='text_merah'> Tidak Ada persentase Nilai</b>";
		}
	echo"<td>
	</tr>";
	}
	echo"
	<tr>
		<th>&nbsp;</th>
		<td><input type='submit' value='Submit'></td>
	</tr>
	</tbody>
	</table>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> <b>Back</b></a>
	</form>
	</div>";
	break;
}
?>
