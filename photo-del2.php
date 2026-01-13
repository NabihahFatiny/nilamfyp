<?php
// connect ke database
include("database.php");

// id_teacher yg dipilih pada borang sebelum ini
$id_teacher = (isset($_GET['id_teacher'])) ? trim($_GET['id_teacher']) : '';

// carian nama gambar
$dat	= mysqli_fetch_array(mysqli_query($con, "SELECT `photo` FROM `teacher` WHERE `id_teacher`= '$id_teacher'"));

// Hapuskan - delete gambar secara fizikal dari folder photo
unlink("photo/" .$dat['photo']);

// Kemaskini maklumat teacher - photo jadikan blank
$rst_d = mysqli_query( $con, "UPDATE `teacher` SET `photo`='' WHERE `id_teacher` = '$id_teacher' " );

// Arahan redirect ke profil
print "<script>self.location='a-profile.php';</script>";
?>