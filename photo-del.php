<?php
// connect ke database
include("database.php");

// id_student yg dipilih pada borang sebelum ini
$id_student = (isset($_GET['id_student'])) ? trim($_GET['id_student']) : '';

// carian nama gambar
$dat	= mysqli_fetch_array(mysqli_query($con, "SELECT `photo` FROM `student` WHERE `id_student`= '$id_student'"));

// Hapuskan - delete gambar secara fizikal dari folder photo
unlink("photo/" .$dat['photo']);

// Kemaskini maklumat stundent - photo jadikan blank
$rst_d = mysqli_query( $con, "UPDATE `student` SET `photo`='' WHERE `id_student` = '$id_student' " );

// Arahan redirect ke profil
print "<script>self.location='profile.php';</script>";
?>