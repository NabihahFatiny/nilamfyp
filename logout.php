<?PHP
	// jika tekan logot hapuskan semua session - clear memory
	session_start();
	
	session_destroy();
	
	// kembali ke page login
	header("Location:index.php");
?>