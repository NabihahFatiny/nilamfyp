<?PHP
	/*	-----------------------------
		Developed by : BelajarPHP.com
		Date : 17 Jan 2023
		-----------------------------	*/
	
	/* https://nilamfyp.u-ji.com/ */
	
	// utk set default tarikh ikut masa Malaysia (kalau tak ikut masa hosting)
	date_default_timezone_set('Asia/Kuala_Lumpur');
	

	//localhost
	$dbHost = "localhost";	// Database host
	$dbName = "nilamfyp";	// Database name
	$dbUser = "root";		// Database user
	$dbPass = "";			// Database password

	
	// Connect ke databse
	$con = mysqli_connect($dbHost,$dbUser ,$dbPass,$dbName);
	
	//function utk sahkan Teacher dah login atau belum
	function verifyTeacher($con)
	{
		if ($_SESSION['email'] && $_SESSION['password'] ) 
		{
		  $result=mysqli_query($con,"SELECT  `email`, `password` FROM `teacher` WHERE `email`='$_SESSION[email]' AND `password`='$_SESSION[password]' " ) ;

          if( mysqli_num_rows( $result ) == 1 ) 
	  	  return true;
		}
		return false;
	}
	
	// Function ini utk semak Student dh login atau belum
	function verifyStudent($con)
	{
		if ($_SESSION['email'] && $_SESSION['password'] ) 
		{
		  $result=mysqli_query($con,"SELECT  `email`, `password` FROM `student` WHERE `email`='$_SESSION[email]' AND `password`='$_SESSION[password]' " ) ;

          if( mysqli_num_rows( $result ) == 1 ) 
	  	  return true;
		}
		return false;
	}

	// Universal utk semak kiraan data (dlm register.php & register-teacher.php)
	function numRows($con, $query) {
        $result  = mysqli_query($con, $query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }
	
	//function utk resize semua image yg upload fixed to 300x300pxl
	function resizeImage($resourceType,$image_width,$image_height) {
		$resizeWidth 	= 300;
		$resizeHeight 	= 300;
		$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
		imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
		return $imageLayer;
	}
?>