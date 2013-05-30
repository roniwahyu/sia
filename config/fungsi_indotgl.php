<?php
	function tgl_form($tgl)
	{
		$tgl 	= date('d/m/Y',strtotime($tgl));
		return $tgl;		 
	}
	
	function tgl_insert($tgl)
	{
		$tanggal	= substr($tgl,0,2);
		$bulan 		= substr($tgl,3,2);
		$tahun	 	= substr($tgl,6,4);
		$tgl		= $bulan.'/'.$tanggal.'/'.$tahun;
		$tgl 		= date('m/d/Y g:i:s A',strtotime($tgl));
		return $tgl;		 
	}

	function tgl_format($tgl){
			$tgl = date('Y-m-d',strtotime($tgl));
			return $tgl;		 
	}	

	function tgl_indo($tgl){
			$tgl 		= tgl_format($tgl);
			$tanggal 	= substr($tgl,8,2);
			$bulan 		= getBulan(substr($tgl,5,2));
			$tahun 		= substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	

	function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}
			} 
?>
