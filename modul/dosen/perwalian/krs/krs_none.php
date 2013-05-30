<?php
switch($_GET[act]){
  // Tampil jurusan Utama
	default:
	echo"
	<div class='content'>
	<h3>Perwalian &#187; K R S</h3>
	<br class='clear'/>
	<form action='$aksi_self' method='post'>
		<b>Angkatan</b>
		<select name=periode>";
		$q_periode=mssql_query("select periode_id from tm_periode where periode_id like '__________1' order by  periode_id desc");
		while($r_periode=mssql_fetch_array($q_periode))
		{
			if($r_periode[periode_id] == $periode)
				echo "<option value='$r_periode[periode_id]' selected='selected'>".substr($r_periode[periode_id],0,4)."</option>";
			else
				echo "<option value='$r_periode[periode_id]'>".substr($r_periode[periode_id],0,4)."</option>";
		}
		echo "</select>
		<input type='submit' value='Tampil'>
	</form>
	<br>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='40px' class='kiri'>no</th>
		<th width='150px'>kode kelas</th>
		<th>nama kelas</th>
		<th class='kanan' width='30px'>&nbsp;</th>
	</tr>";
	$no=1;
	$q_kelas=mssql_query("
						SELECT tm_kelas.kelas_id, 
							tm_kelas.kelas_nama
						FROM tm_kelas 
							INNER JOIN tt_dosen_kelas ON tm_kelas.kelas_id = tt_dosen_kelas.kelas_id
						WHERE (tt_dosen_kelas.pegawai_kode = '$_SESSION[pegawai_kode]') 
							AND (tm_kelas.periode_id = '$periode')
						");
	while($r_kelas=mssql_fetch_array($q_kelas))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'>$no</td>
			<td>$r_kelas[kelas_id]</td>
			<td>$r_kelas[kelas_nama]</td>
			<td><a href='$aksi_self&act=mahasiswa&kelas_id=$r_kelas[kelas_id]' title='Detail $r_kelas[kelas_nama]'><img src='template/images/detail.ico'></a></td>
		</tr>";
		$no++;
	}
	echo "</tbody>
	</table>
	</div>";
    break;

	case "mahasiswa":
	if(substr($periode,-1,1)==1)
	{
		$periode=substr($periode,0,8)."-2";
	}
	elseif(substr($periode,-1,1)==2)
	{
		$periode=(substr($periode,0,4)+1)."-".(substr($periode,5,4)+1)."-1";
	}
	echo"
	<div class='content'>
	<h3>Perwalian &#187; K R S &#187; Kelas $_GET[kelas_id]</h3>
	<br class='clear'/>
	<form action='$aksi?act=group' method='post' name='form_data' id='form_data'>
	<input type='hidden' name='periode' value='$periode'>
	<input type='hidden' name='kelas_id' value='$_GET[kelas_id]'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
	<tr align='center'>
		<th width='30px' class='kiri'><input type='checkbox' name='check_all' id='check_all'></th>
		<th width='30px'>no</th>
		<th width='60px'>nim</th>
		<th>nama</th>
		<th width='60px'>jenis kelamin</th>
		<th>no telepon</th>
		<th>email</th>
		<th>periode</th>
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
							(SELECT count(*) 
							FROM tt_nilai 
							WHERE tt_nilai.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim 
								AND tt_nilai.periode_id='$periode') AS nilai,
							(SELECT TOP(1) tt_krs.krs_approve
							FROM tt_krs 
							WHERE tt_krs.mahasiswa_nim=tm_mahasiswa.mahasiswa_nim 
								AND tt_krs.periode_id='$periode') AS krs
						FROM tm_mahasiswa 
							INNER JOIN tt_kelas_mahasiswa ON tm_mahasiswa.mahasiswa_nim = tt_kelas_mahasiswa.mahasiswa_nim 
						WHERE (tt_kelas_mahasiswa.kelas_id = '$_GET[kelas_id]')
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
			<td>$periode</td>
			<td align='center'>$sms</td>
			<td align='center'>";
			if($r_kelas[krs]=='Y')
			{
				if($r_kelas[nilai]==0)
				{
					echo"<a href='$aksi?act=ubahstatuskrs&nim=$r_kelas[mahasiswa_nim]&periode_id=$periode&kelas_id=$_GET[kelas_id]' onClick=\"return confirm('Apakah Anda ingin mengganti status $r_kelas[mahasiswa_nim] $r_kelas[mahasiswa_nama]?')\"><img src='template/images/icon_accept.png' title='Siap Approve' id='foto'></a>";
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
				echo "<a href='$aksi_self&act=detailmhs&kelas_id=$_GET[kelas_id]&nim=$r_kelas[mahasiswa_nim]&periode_id=$periode' title='Detail Mata Kuliah KRS $r_kelas[mahasiswa_nim] Periode $periode'><img src='template/images/detail.ico'></a>&nbsp;&nbsp;&nbsp;";
				if($r_kelas[krs]=='Y')
				{
					echo "<a href='$aksi?act=individu&nim=$r_kelas[mahasiswa_nim]&periode_id=$periode&kelas_id=$_GET[kelas_id]' title='Approve Mata Kuliah $r_kelas[mahasiswa_nim] Periode $periode' onClick=\"return confirm('Apakah Anda ingin Appprove mata kuliah $r_kelas[mahasiswa_nim] $r_kelas[mahasiswa_nama]?')\"><img src='template/images/icon_accept.png'></a>";
				}
			}
			elseif($r_kelas[nilai]>0)
			{
				echo "<a href='$aksi_self&act=detailnextsms&&nim=$r_kelas[mahasiswa_nim]&periode_id=$periode&kelas_id=$_GET[kelas_id]' title='Detail Mata Kuliah $r_kelas[mahasiswa_nim] Periode $periode'><img src='template/images/chart_bar.png'></a>&nbsp;&nbsp;
				<a href='$aksi?act=hapus&nim=$r_kelas[mahasiswa_nim]&periode_id=$periode&kelas_id=$_GET[kelas_id]' title='Hapus Mata Kuliah $r_kelas[mahasiswa_nim] Periode $periode' onClick=\"return confirm('Apakah Anda ingin menghapus mata kuliah $r_kelas[mahasiswa_nim] $r_kelas[mahasiswa_nama]?')\"><img src='template/images/icon_delete.png'></a>";
			}
			echo "</td>
		</tr>";
		$no++;
	}
	echo "<input type='hidden' name='jummhs' value='$no'>
	<tr><th colspan='12' align='center'><input type='submit' value='Approve'></th></tr>
	</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;	
	
	case "detailmhs":
	$periode=$_GET[periode_id];
	echo"
	<div class='content'>
	<h3>Perwalian &#187; K R S &#187; NIM $_GET[nim]</h3>
	<br class='clear'/>
	<form method='post' action='$aksi?kelas_id=$_GET[kelas_id]&act=hapuscheksms' name='form_data' id='form_data'>
	<input type='hidden' name='periode' value='$_GET[periode_id]'>
	<input type='hidden' name='nim' value='$_GET[nim]'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr align='center'>
			<th class='kiri' width='30px'><input type='checkbox' name='check_all' id='check_all'></th>
			<th width='30px'>no</th>
			<th width='150px'>kode mata kuliah</th>
			<th>nama mata kuliah</th>
			<th width='60px'>sks</th>
			<th width='70px'>jam</th>
			<th width='100px' class='kanan'>kelas</th>
		</tr>";
	$q_mhs=mssql_query("
						SELECT tm_matakuliah.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_sks, 
							tm_matakuliah.matakuliah_tipe, 
							tm_matakuliah.matakuliah_jam, 
							tt_krs.kelas_id, 
							tt_krs.periode_id
						FROM tm_matakuliah 
							INNER JOIN tt_krs ON tm_matakuliah.matakuliah_id = tt_krs.matakuliah_id
						WHERE (tt_krs.periode_id = '$periode') 
							AND (tt_krs.mahasiswa_nim = '$_GET[nim]')
						ORDER BY tm_matakuliah.matakuliah_id
					");
	$no=1;
	while($r_mhs=mssql_fetch_array($q_mhs))
	{
		if($no % 2 == 0)
			echo "<tr id='genap'>";
		else
			echo "<tr>";
		echo "<td align='center'><input type='checkbox' name='matakuliah_$no' value='$r_mhs[matakuliah_id]'></td>
			<td align='center'>$no</td>
			<td align='center'>$r_mhs[matakuliah_id]</td>
			<td>$r_mhs[matakuliah_nama]</td>
			<td align='center'>$r_mhs[matakuliah_sks]</td>
			<td align='center'>$r_mhs[matakuliah_jam]</td>
			<td align='center'>$r_mhs[kelas_id]</td>
		</tr>";
		$no++;
		$sks=$sks+$r_mhs[matakuliah_sks];
	}
	echo "<tr><td colspan='4'>Jumlah SKS</th><td align='center'>$sks</th><td colspan='3'>&nbsp;</th></tr>
	<tr>
		<input type='hidden' name='jummatkul' value='$no'>
		<th colspan='8' align='center'><input type='submit' value='Hapus'></th>
	</tr>
	</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;

	case "detailnextsms":
	$periode=$_GET[periode_id];
	echo"
	<div class='content'>
	<h3>Perwalian &#187; K R S &#187; Semester Berikutnya NIM $_GET[nim]</h3>
	<br class='clear'/>
	<form method='post' action='$aksi?kelas_id=$_GET[kelas_id]&act=hapuscheknextsms' name='form_data' id='form_data>
	<input type='hidden' name='periode' value='$periode'>
	<input type='hidden' name='nim' value='$_GET[nim]'>
	<table class='input' cellpadding='0' cellspacing='0' width='100%'>
	<tbody>
		<tr align='center'>
			<th class='kiri' width='30px'><input type='checkbox' name='check_all' id='check_all'></th>
			<th width='30px'>no</th>
			<th width='150px'>kode mata kuliah</th>
			<th>nama mata kuliah</th>
			<th width='60px'>sks</th>
			<th width='70px'>jam</th>
			<th width='100px' class='kanan'>kelas</th>
		</tr>";
	$q_mhs=mssql_query("
						SELECT tm_matakuliah.matakuliah_id, 
							tm_matakuliah.matakuliah_nama, 
							tm_matakuliah.matakuliah_sks, 
							tm_matakuliah.matakuliah_tipe, 
							tm_matakuliah.matakuliah_jam, 
							tt_nilai.kelas_id, 
							tt_nilai.periode_id
						FROM tm_matakuliah 
							INNER JOIN tt_nilai ON tm_matakuliah.matakuliah_id = tt_nilai.matakuliah_id
						WHERE (tt_nilai.periode_id = '$periode') 
							AND (tt_nilai.mahasiswa_nim = '$_GET[nim]')
						ORDER BY tm_matakuliah.matakuliah_id
					");
	$no=1;
	while($r_mhs=mssql_fetch_array($q_mhs))
	{
		$line =tabel_normal($no);
		echo "$line<td align='center'><input type='checkbox' name='matakuliah_$no' value='$r_mhs[matakuliah_id]'></td>
			<td align='center'>$no</td>
			<td align='center'>$r_mhs[matakuliah_id]</td>
			<td>$r_mhs[matakuliah_nama]</td>
			<td align='center'>$r_mhs[matakuliah_sks]</td>
			<td align='center'>$r_mhs[matakuliah_jam]</td>
			<td align='center'>$r_mhs[kelas_id]</td>
		</tr>";
		$no++;
		$sks=$sks+$r_mhs[matakuliah_sks];
	}
	echo "<tr><td colspan='4'>Jumlah SKS</th><td align='center'>$sks</th><td colspan='2'>&nbsp;</th></tr>
	<tr>
		<input type='hidden' name='jummatkul' value='$no'>
		<th colspan='8' align='center'><input type='submit' value='Hapus'></th>
	</tr>
	</tbody>
	</table>
	</form>
	<br><br>
	<a onclick='self.history.back()' title='Kembali'><img src='template/images/back.png'> Back</a>
	</div>";
	break;
}
?>
