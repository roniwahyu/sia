<?php
require_once "config/session.php";
error_reporting(0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<?php 
	require_once "config/koneksi.php"; 
	require_once "config/library.php"; 
	require_once "config/fungsi_indotgl.php"; 
	require_once "config/fungsi_tanggal.php"; 
	require_once "config/tabel.php"; 
?>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
 
<!-- Website Title --> 
<title>SIAKAD Politeknik Negeri Malang</title>

<!-- Meta data for SEO -->
<meta name="description" content="Sistem Informasi Akademik (SIAKAD) Politeknik Negeri Malang">
<meta name="keywords" content="SIAKAD Politeknik Negeri Malang">

<link rel="shortcut icon" href="icon_logo.png" />

<!-- Template stylesheet -->
<link href="template/css/default/screen.css" rel="stylesheet" type="text/css" media="all">
<link href="template/css/default/datepicker.css" rel="stylesheet" type="text/css" media="all">
<!--<link href="template/css/default/tab.css" rel="stylesheet" type="text/css" media="all">-->
<link href="template/css/tipsy.css" rel="stylesheet" type="text/css" media="all">
<link href="template/js/visualize/visualize.css" rel="stylesheet" type="text/css" media="all">
<link href="template/js/jwysiwyg/jquery.wysiwyg.css" rel="stylesheet" type="text/css" media="all">
<link href="template/js/fancybox/jquery.fancybox-1.3.0.css" rel="stylesheet" type="text/css" media="all">
<link href="template/css/tipsy.css" rel="stylesheet" type="text/css" media="all">

<!--[if IE]>
	<link href="css/ie.css" rel="stylesheet" type="text/css" media="all">
	<script type="text/javascript" src="js/excanvas.js"></script>
<![endif]-->

<!-- Jquery and plugins -->
<script type="text/javascript" src="template/js/jquery.js"></script>
<script type="text/javascript" src="template/js/jquery-ui.js"></script>
<script type="text/javascript" src="template/js/jquery.img.preload.js"></script>
<script type="text/javascript" src="template/js/hint.js"></script>
<script type="text/javascript" src="template/js/visualize/jquery.visualize.js"></script>
<script type="text/javascript" src="template/js/jwysiwyg/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="template/js/fancybox/jquery.fancybox-1.3.0.js"></script>
<script type="text/javascript" src="template/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="template/js/custom_blue.js"></script>
<!--<script type="text/javascript" src="template/js/tabber.js"></script>-->
<script type="text/javascript">
var xmlhttp = createRequestObject();

function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == 'Microsoft Internet Explorer'){
        ro = new ActiveXObject('Microsoft.XMLHTTP');
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

</script>
</head>
<body>
<div class="content_wrapper">

	<!-- Begin header -->
	<div id="header">
		<div id="logo"><img src="template/images/logo_siakad.png" alt="logopng"/></div>
		<div id="account_info">
			<img src="template/images/icon_online.png" alt="Online" class="mid_align"/>
			Selamat Datang <?php 
							$profil=mssql_query("select * from tm_pegawai where pegawai_kode='$_SESSION[pegawai_kode]'");
							$p=mssql_fetch_array($profil);
							echo"<a href='?departemen=public&menu=akademik&modul=pengumuman'>$p[pegawai_nama]</a> ($p[pegawai_kode])";
							?> | <a href="logout">Logout</a>
		</div>
	</div>
	<!-- End header -->
	<div id="menu">
    	<ul id="nav">
			<?php $menu=mssql_query("
									SELECT DISTINCT ts_departemen.departemen_urutan, 
										ts_departemen.departemen_id, 
										ts_departemen.departemen_nama,
									   (SELECT departemen_id
											FROM tt_pegawai_departemen AS PD
											WHERE (departemen_id = ts_departemen.departemen_id) 
												AND (pegawai_kode = '$_SESSION[pegawai_kode]')) AS link,
									  	(SELECT TOP (1) menu_pegawai_id
											FROM ts_menu_pegawai
											WHERE (departemen_id = ts_departemen.departemen_id)
											ORDER BY menu_pegawai_urutan) AS menu,
									  	(SELECT TOP (1) modul_pegawai_id
											FROM ts_modul_pegawai
											WHERE (menu_pegawai_id =
												(SELECT     TOP (1) menu_pegawai_id
													FROM ts_menu_pegawai AS ts_menu_pegawai_1
													WHERE (departemen_id = ts_departemen.departemen_id)
													ORDER BY menu_pegawai_urutan))
			                            	ORDER BY modul_pegawai_urutan) AS modul
									FROM ts_departemen CROSS JOIN tt_pegawai_departemen
									ORDER BY ts_departemen.departemen_urutan
									");
				  while($m=mssql_fetch_array($menu))
				  {
					  if($m[link]!='')
					  {
						  echo"<li><a href='media.php?departemen=$m[link]&menu=$m[menu]&modul=$m[modul]'>$m[departemen_nama]</a></li>";
					  }
					  else
					  {
						  echo"<li><a>$m[departemen_nama]</a></li>";
					  }
				  }
			?>
        </ul>
    </div>
	
	<!-- Begin left panel -->
	<a href="javascript:;" id="show_menu">&raquo;</a>	
	<div id="left_menu">
		<a href="javascript:;" id="hide_menu">&laquo;</a>
		<ul id="main_menu">
			<?php $menu=mssql_query("
									SELECT departemen_id,
										menu_pegawai_id,
										menu_pegawai_nama,
										menu_pegawai_gambar,
										(select modul_pegawai_id
										FROM ts_modul_pegawai
										WHERE ts_modul_pegawai.menu_pegawai_id=ts_menu_pegawai.menu_pegawai_id
										AND modul_pegawai_urutan=1) AS modul_pegawai_id
									FROM ts_menu_pegawai 
									WHERE departemen_id='$_GET[departemen]'
									AND menu_pegawai_aktif='Y'
									ORDER BY menu_pegawai_urutan					
									");
				  while($m=mssql_fetch_array($menu))
				  {
				  echo"<li><a href='?departemen=$m[departemen_id]&menu=$m[menu_pegawai_id]&modul=$m[modul_pegawai_id]'><img src='template/images/$m[menu_pegawai_gambar]' alt='$m[menu_pegawai_nama]'/>$m[menu_pegawai_nama]</a>
				  	   </li>";
				  }
			?>
		</ul>
		<br class="clear"/>
		
		<!-- Begin left panel calendar -->
		<div id="calendar"></div>
		<!-- End left panel calendar -->
		
	</div>
	<!-- End left panel -->
	
	
	<!-- Begin content -->
	<div id="content">
		<div class="inner">
		<?php require_once('content.php'); ?>
            <br class="clear"/>
			
			
			<!-- Begin one column tab content window -->
			<!-- End one column tab content window -->
            <!-- Begin three column window -->
            <!-- End three column window -->
            <!-- Begin one column wysiwyg window -->
            <!-- End one column wysiwyg window -->
	</div>
		
		<br class="clear"/><br class="clear"/>
		
		
		<!-- Begin footer -->
		<div id="footer">
			&copy; Copyright 2011 by SIA-KOP
		</div>
		<!-- End footer -->
		
		
	</div>
<!-- End content --></div>
</body>
</html>
