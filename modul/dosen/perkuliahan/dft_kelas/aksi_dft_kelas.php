<?php
	include "../../../../config/session.php";
	include "../../../../config/koneksi.php";
	include "../../../../config/fungsi_nilai.php";
	$tgl=date("n/j/Y g:i A");
	if($_GET[act]=='tambahabsen')
	{
		for($i=1;$i<=$_POST[jummhs]-1;$i++)
		{
			$nim=$_POST["nim_$i"];
			$keterangan=$_POST["keterangan_$i"];
			mssql_query("insert into tt_absensi_mahasiswa(absensi_mahasiswa_tgl,
															pegawai_kode,
															matakuliah_id,
															periode_id,
															jam_id,
															mahasiswa_nim,
															kelas_id,
															keterangan_absensi_id,
															absensi_mahasiswa_acc)
													values('$tgl',
															'$_SESSION[pegawai_kode]',
															'$_POST[matkul]',
															'$_POST[periode]',
															'$_POST[jam]',
															'$nim',
															'$_POST[kelas]',
															'$keterangan',
															'N')
						");
		}
		header("Location:../../../../media.php?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=absensi&periode=$_POST[periode]&kelas_id=$_POST[kelas]&matakuliah_id=$_POST[matkul]");
	}

	elseif($_GET[act]=='ubahabsen')
	{
		mssql_query("UPDATE tt_absensi_mahasiswa 
					SET keterangan_absensi_id='$_POST[keterangan_absensi_id]'
					WHERE absensi_mahasiswa_id='$_POST[absensi_mahasiswa_id]'
					");
		header("Location:../../../../media.php?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=detailabsensi&periode=$_POST[periode_id]&kelas_id=$_POST[kelas_id]&matakuliah_id=$_GET[matakuliah_id]");
	}
	
	
	elseif($_GET[act]=='tambahnilai')
	{
		$q_nilai=mssql_query("
						SELECT tt_nilai.nilai_uts, 
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
						WHERE (tt_nilai.periode_id = '$_POST[periode]') 
							AND (tt_nilai.matakuliah_id = '$_POST[matkul]') 
							AND (tt_nilai.kelas_id = '$_POST[kelas]')						
						");
		$i=1;
		while($r_nilai=mssql_fetch_array($q_nilai))
		{
			$nilai_rata_rata=round(
									(
										($r_nilai[nilai_uts]*$r_nilai[persen_uts])+
										($r_nilai[nilai_uas]*$r_nilai[persen_uas])+
										($r_nilai[nilai_1]*$r_nilai[persen_1])+
										($r_nilai[nilai_2]*$r_nilai[persen_2])+
										($r_nilai[nilai_3]*$r_nilai[persen_3])+
										($r_nilai[nilai_4]*$r_nilai[persen_4])+
										($r_nilai[nilai_5]*$r_nilai[persen_5])+
										($r_nilai[nilai_6]*$r_nilai[persen_6])+
										($r_nilai[nilai_7]*$r_nilai[persen_7])+
										($r_nilai[nilai_8]*$r_nilai[persen_8])+
										($r_nilai[nilai_9]*$r_nilai[persen_9])+
										($r_nilai[nilai_10]*$r_nilai[persen_10])
									)/100,2
								);
	
			$nim=$_POST["nim_$i"];
			$nilai=$_POST["nilai_$i"];
			
			$nilai_rata_rata=$nilai_rata_rata+round(($nilai*($r_nilai["persen".substr($_POST[kolomnilai],5)])/100),2);
			
			$nilai_tipe=nilai_tipe($nilai_rata_rata);
			
			mssql_query("update tt_nilai set $_POST[kolomnilai] 	= '$nilai',
											acc_$_POST[kolomnilai] 	= '0',
											nilai_rata_rata			= '$nilai_rata_rata',
											nilai_tipe_id			= '$nilai_tipe'
										where mahasiswa_nim='$nim'
											and matakuliah_id='$_POST[matkul]'
											and periode_id='$_POST[periode]'
						");
			$i++;
		}
header("Location:../../../../media.php?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=nilai&periode=$_POST[periode]&kelas_id=$_POST[kelas]&matakuliah_id=$_POST[matkul]");
	}
	
	elseif($_GET[act]=='editnilai')
	{
		$q_nilai=mssql_query("
						SELECT 	tt_prodi_nilai.nilai_uts AS persen_uts, 
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
						FROM tt_prodi_nilai 
						WHERE periode_id= '$_GET[periode]'
							AND matakuliah_id = '$_GET[matakuliah_id]'
						");
		$r_nilai=mssql_fetch_array($q_nilai);
			
		$nilai_rata_rata=round(
								(
									($_POST[nilai_uts]*$r_nilai[persen_uts])+
									($_POST[nilai_uas]*$r_nilai[persen_uas])+
									($_POST[nilai_1]*$r_nilai[persen_1])+
									($_POST[nilai_2]*$r_nilai[persen_2])+
									($_POST[nilai_3]*$r_nilai[persen_3])+
									($_POST[nilai_4]*$r_nilai[persen_4])+
									($_POST[nilai_5]*$r_nilai[persen_5])+
									($_POST[nilai_7]*$r_nilai[persen_7])+
									($_POST[nilai_8]*$r_nilai[persen_8])+
									($_POST[nilai_9]*$r_nilai[persen_9])+
									($_POST[nilai_10]*$r_nilai[persen_10])
								)/100,2
							);

		$nilai_tipe=nilai_tipe($nilai_rata_rata);
		mssql_query("UPDATE tt_nilai 
					SET nilai_uts	='$_POST[nilai_uts]',
						nilai_uas	='$_POST[nilai_uas]',
						nilai_1		='$_POST[nilai_1]',
						nilai_2		='$_POST[nilai_2]',
						nilai_3		='$_POST[nilai_3]',
						nilai_4		='$_POST[nilai_4]',
						nilai_5		='$_POST[nilai_5]',
						nilai_6		='$_POST[nilai_6]',
						nilai_7		='$_POST[nilai_7]',
						nilai_8		='$_POST[nilai_8]',
						nilai_9		='$_POST[nilai_9]',
						nilai_10	='$_POST[nilai_10]',
						nilai_rata_rata ='$nilai_rata_rata',
						nilai_tipe_id	='$nilai_tipe'
					WHERE nilai_id= '$_POST[nilai_id]'
					");
		header("Location:../../../../media.php?departemen=dosen&menu=perkuliahan&modul=dft_kelas&act=nilai&periode=$_GET[periode]&kelas_id=$_GET[kelas_id]&matakuliah_id=$_GET[matakuliah_id]");
		
	}
?>