<?php
include "../../../../config/session.php";
require('../../../../config/fpdf/fpdf.php');
include "../../../../config/koneksi.php";

class PDF extends FPDF
{
	//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','',9);
    //Page number
    $this->Cell(0,10,''.$this->PageNo().'',0,0,'C');
}
//Colored table
function FancyTable($header)
{
	$this->Ln(6);
	$this->SetX(120);
	$this->SetFont('Arial','B',11);
	$title3="JADWAL $_GET[periode]";
	$this->Write(7,$title3);
	$this->Ln(20);
	
	$this->SetFont('Arial','',9);
    //Colors, line width and bold font
    $this->SetFillColor(255);
    $this->SetTextColor(0);
    $this->SetDrawColor(0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    //Header
    $w=array(10,40,80,30,15,20,20,30,20);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    //Color and font restoration
    $this->SetFillColor(255);
    $this->SetTextColor(0);
    $this->SetDrawColor(0);
    $this->SetLineWidth(.3);
    $this->SetFont('','');
    //Data
    $fill=false;
	$q_jadwal=mssql_query("SELECT tt_jadwal.kelas_id, 
								tt_jadwal.matakuliah_id, 
								tm_jam.jam_waktu, 
								tm_hari.hari_nama, 
								tt_jadwal.ruang_id, 
								tm_matakuliah.matakuliah_nama, 
								tm_matakuliah.matakuliah_sks,
								tm_matakuliah.matakuliah_jam,
								tm_matakuliah.matakuliah_tipe,
								tm_hari.hari_urutan
							FROM tt_jadwal 
								INNER JOIN tm_matakuliah ON tt_jadwal.matakuliah_id = tm_matakuliah.matakuliah_id
								INNER JOIN tm_hari ON tt_jadwal.hari_id=tm_hari.hari_id
								INNER JOIN tm_jam ON tm_jam.jam_id=tt_jadwal.jam_id 
							WHERE (tt_jadwal.periode_id = '$_GET[periode]') 
								AND (tt_jadwal.pegawai_kode = '$_SESSION[pegawai_kode]') 
							ORDER BY tm_hari.hari_urutan, tm_jam.jam_id ASC"
						);
	$no=1;
	while($r_jadwal=mssql_fetch_array($q_jadwal))
	{
		$akhir=$r_jadwal[jam_waktu]+$r_jadwal[matakuliah_jam];
		if($akhir < 10)
		{
			$akhir="0$akhir.00";
		}
		else
		{
			$akhir="$akhir.00";
		}
		$this->Cell($w[0],6,$no,1,0,'L',$fill);
        $this->Cell($w[1],6,$r_jadwal[matakuliah_id],1,0,'C',$fill);
        $this->Cell($w[2],6,$r_jadwal[matakuliah_nama],1,0,'L',$fill);
		if($r_jadwal[matakuliah_tipe] == 'T')
	        $this->Cell($w[3],6,"Teori",1,0,'L',$fill);
		if($r_jadwal[matakuliah_tipe] == 'P')
	        $this->Cell($w[3],6,"Praktek",1,0,'L',$fill);
        $this->Cell($w[4],6,$r_jadwal[matakuliah_sks],1,0,'C',$fill);
        $this->Cell($w[5],6,$r_jadwal[kelas_id],1,0,'L',$fill);
        $this->Cell($w[6],6,$r_jadwal[hari_nama],1,0,'L',$fill);
        $this->Cell($w[7],6,$r_jadwal[jam_waktu]." - ".$akhir,1,0,'L',$fill);
        $this->Cell($w[8],6,$r_jadwal[ruang_id],1,0,'C',$fill);
		$this->Ln();
        $fill=!$fill;
		$no++;
    }
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf=new PDF();
$pdf->AliasNbPages();
//Column titles
$header=array('No.','Kode Mata Kuliah','Nama Matakuliah','Tipe','SKS','Kelas','Hari','Jam','Ruang');
$pdf->SetFont('Arial','',9);
$pdf->AddPage(L,A4);
$pdf->FancyTable($header);
$pdf->Output("Jadwal.pdf",D);
?>

