<?php
function UploadImage($fupload_name){
  //direktori gambar
  $vdir_upload = "../../gambar_modul/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefrompng($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 64 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 64;
  $dst_height = ($dst_width/$src_width)*$src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width,$dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  imagepng($im,$vdir_upload . "small_" . $fupload_name);
    
  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
}
?>
