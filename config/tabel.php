<?php
	function tabel_jadwal($tgl,$no)
	{
		$hasil = check_tgl($tgl);
		if($hasil == 0)
		{
			$line ="<tr class='merah'>";
		}
		elseif($hasil == 1)
		{
			$line ="<tr class='hijau'>";
		}
		elseif($hasil == 2)
		{
			if($no % 2 == 0)
				$line = "<tr class='genap'>";
			else
				$line = "<tr class='ganjil'>";
		}
		return $line;
	}

	function tabel_antara($awal,$akhir,$no)
	{
		$hasil = tanggal_antara($awal,$akhir);
		if($hasil == 0)
		{
			$line ="<tr class='merah'>";
		}
		elseif($hasil == 1)
		{
			$line ="<tr class='hijau'>";
		}
		elseif($hasil == 2)
		{
			if($no % 2 == 0)
				$line = "<tr class='genap'>";
			else
				$line = "<tr class='ganjil'>";
		}
		return $line;
	}
	
	function tabel_normal($no)
	{
		if($no % 2 == 0)
			$line = "<tr class='genap'>";
		else
			$line = "<tr class='ganjil'>";
		return $line;
	}
	
	function tabel_hari($hari,$no)
	{
		if($hari == date("N"))
		{
			$line ="<tr class='hijau'>";
		}
		else
		{
			if($no % 2 == 0)
				$line = "<tr class='genap'>";
			else
				$line = "<tr class='ganjil'>";
		}
		return $line;		
	}
	function tabel_nilai($nilai)
	{
		if($nilai == "A")
		{
			$line="<tr class='nilai_A'>";
		}
		elseif($nilai == "B")
		{
			$line="<tr class='nilai_B'>";
		}
		elseif($nilai == "C")
		{
			$line="<tr class='nilai_C'>";
		}
		elseif($nilai == "D")
		{
			$line="<tr class='nilai_D'>";
		}
		elseif($nilai == "E")
		{
			$line="<tr class='nilai_E'>";
		}
		return $line;
	}
?>