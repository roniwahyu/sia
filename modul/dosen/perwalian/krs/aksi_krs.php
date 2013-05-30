<?php
	include "../../../../config/session.php";
	include "../../../../config/koneksi.php";
	if($_GET[act]=='group')
	{
		for($i=1;$i<=$_POST[jummhs]-1;$i++)
		{
			$nim=$_POST["nim_$i"];
			$q_mhs=mssql_query("SELECT matakuliah_id,
										semester_id,
										kelas_id
								FROM tt_krs
								WHERE mahasiswa_nim='$nim'
									AND periode_id='$_POST[periode]'
							");
			while($r_mhs=mssql_fetch_array($q_mhs))
			{
				mssql_query("INSERT INTO tt_nilai(mahasiswa_nim,
												matakuliah_id,
												semester_id,
												kelas_id,
												periode_id)
											VALUES('$nim',
												'$r_mhs[matakuliah_id]',
												'$r_mhs[semester_id]',
												'$r_mhs[kelas_id]',
												'$_POST[periode]')
							");
			}
		}
		header("location:../../../../media.php?departemen=dosen&menu=perwalian&modul=krs");
	}

	if($_GET[act]=='individu')
	{
		$q_mhs=mssql_query("SELECT matakuliah_id,
										semester_id,
										kelas_id
								FROM tt_krs
								WHERE mahasiswa_nim='$_GET[nim]'
									AND periode_id='$_GET[periode_id]'
							");
		while($r_mhs=mssql_fetch_array($q_mhs))
		{
			mssql_query("INSERT INTO tt_nilai(mahasiswa_nim,
												matakuliah_id,
												semester_id,
												kelas_id,
												periode_id)
											VALUES('$_GET[nim]',
												'$r_mhs[matakuliah_id]',
												'$r_mhs[semester_id]',
												'$r_mhs[kelas_id]',
												'$_GET[periode_id]')
						");
		}
	}

	elseif($_GET[act]=='ubahstatuskrs')
	{
		mssql_query("UPDATE tt_krs SET krs_approve ='N' WHERE mahasiswa_nim='$_GET[nim]' AND periode_id='$_GET[periode_id]'");
	}

	elseif($_GET[act]=='hapus')
	{
		mssql_query("DELETE FROM tt_nilai WHERE mahasiswa_nim='$_GET[nim]' AND periode_id='$_GET[periode_id]'");
		mssql_query("DELETE FROM tt_krs WHERE mahasiswa_nim='$_GET[nim]' AND periode_id='$_GET[periode_id]'");
	}
	
	elseif($_GET[act]=='hapuscheksms')
	{
		$nim=$_POST["nim"];
		$periode=$_POST["periode"];
		for($i=1;$i<=$_POST[jummatkul]-1;$i++)
		{
			$matakuliah_id=$_POST["matakuliah_$i"];
			mssql_query("DELETE FROM tt_krs WHERE mahasiswa_nim='$nim' AND periode_id='$periode' AND matakuliah_id='$matakuliah_id'");
		}
	}
	
	elseif($_GET[act]=='hapuscheknextsms')
	{
		$nim=$_POST["nim"];
		$periode=$_POST["periode"];
		for($i=1;$i<=$_POST[jummatkul]-1;$i++)
		{
			$matakuliah_id=$_POST["matakuliah_$i"];
			mssql_query("DELETE FROM tt_nilai WHERE mahasiswa_nim='$nim' AND periode_id='$periode' AND matakuliah_id='$matakuliah_id'");
			mssql_query("DELETE FROM tt_krs WHERE mahasiswa_nim='$nim' AND periode_id='$periode' AND matakuliah_id='$matakuliah_id'");
		}
	}

	header("location:../../../../media.php?departemen=dosen&menu=perwalian&modul=krs");
?>