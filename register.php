<?PHP
// connect ke database
include("database.php");

// Dapat jika hantar borang
$act 		= (isset($_POST['act'])) ? trim($_POST['act']) : '';
$name 		= (isset($_POST['name'])) ? trim($_POST['name']) : '';
$email 		= (isset($_POST['email'])) ? trim($_POST['email']) : '';
$password 	= (isset($_POST['password'])) ? trim($_POST['password']) : '';
$dob 		= (isset($_POST['dob'])) ? trim($_POST['dob']) : '';

// utk elakkan nama ada simbol atau tanda ''
$name	=	mysqli_real_escape_string($con, $name);

$found = 0;
$error = "";
$success = false;

// arahan jika daftar baru - tekan SUBMIT
if($act == "register")
{
	// Semak dulu emel dah wujud atau tidak
	$found 	= numRows($con, "SELECT * FROM `student` WHERE `email` = '$email' ");
	if($found) $error ="Email already registered";
}


// Jika arahan register DAN tiada error (emel belum wujud)
if(($act == "register") && (!$error))
{	
	// Arahan sq standard utk Insert data
	$SQL_insert = " 
	INSERT INTO `student`(`id_student`, `name`, `email`, `password`, `dob`) 
		VALUES (NULL, '$name', '$email', '$password', '$dob')";	

	$result = mysqli_query($con, $SQL_insert) or die("Error in query: ".$SQL_insert."<br />".mysqli_error($con));
	$success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reading Record Log System</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link href='https://fonts.googleapis.com/css?family=RobotoDraft' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><style>
html,body,h1,h2,h3,h4,h5 {font-family: "RobotoDraft", "Roboto", sans-serif}
.w3-bar-block .w3-bar-item {padding: 16px}
</style>
</head>
<body>

<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="index.php" class="w3-bar-item w3-border-bottom w3-large"><img src="images/logo.png" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
 
  <a id="myBtn" onclick="myFunc('Demo1')" href="javascript:void(0)" class="w3-bar-item w3-button"><i class="fa fa-unlock-alt w3-margin-right"></i>LOGIN<i class="fa fa-caret-down w3-margin-left"></i></a>
  <div id="Demo1" class="w3-hide w3-animate-left">
    <a href="index.php" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey w3-light-grey"  id="firstTab">
      <div class="w3-container">
        <span ><i class="fa fa-student w3-margin-right"></i>Student</span>
      </div>
    </a>
     <a href="login-pengurus.php" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" >
      <div class="w3-container">
        <span ><i class="fa fa-student-md w3-margin-right"></i>Teacher</span>
      </div>
    </a>
  </div>
</nav>



<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>




<div class="w3-padding-24"></div>
		
	
	
<div class="w3-container w3-padding-32" id="contact">
    <div class="w3-content w3-container w3-white w3-round w3-card" style="max-width:600px">
		<div class="w3-padding">
		
<?PHP if($success) { ?>
<div class="w3-panel w3-green w3-display-container w3-animate-zoom">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-large w3-display-topright">&times;</span>
  <h3>Success!</h3>
  <p>Your registration was successful! You may now <a href="index.php" class="w3-xlarge">Login.</a> </p>
</div>
<?PHP  } ?>

<?PHP if($error) { ?>
<div class="w3-panel w3-red w3-display-container w3-animate-zoom">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-large w3-display-topright">&times;</span>
  <h3>Error!</h3>
  <p><?PHP echo $error;?></p>
</div>
<?PHP  } ?>

<?PHP if(!$success) { ?>		
			<form action="" method="post">
			<div class="w3-xxlarge">Reading Record Log System</div>
			<hr>
			<h3>Student Registration</h3>
			  
			  <div class="w3-section" >
				<label>Name *</label>
				<input class="w3-input w3-border w3-round" type="text" name="name"  required>
			  </div>
			  
			  <div class="w3-section" >
				<label>Email *</label>
				<input class="w3-input w3-border w3-round" type="text" name="email"  required>
			  </div>
			  
			  <div class="w3-section">
				<label>Password *</label>
				<input class="w3-input w3-border w3-round" type="password" name="password" required>
			  </div>
			  
			  <div class="w3-section">
				<label>Data of Birth *</label>
				<input class="w3-input w3-border w3-round" type="date" name="dob" required>
			  </div>
			  
			  <div class="w3-section w3-padding-16">
				<input type="hidden" name="act" value="register">
				<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">SUBMIT</button>
			  </div>
			</form> 

<?PHP } ?>			
			<div class="w3-center">Already registered? <a href="index.php" class="w3-text-blue">Login here</a></div> 
			
		</div>
    </div>
</div>


     
</div>

<script>
var openInbox = document.getElementById("myBtn");
openInbox.click();

function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}

function myFunc(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show"; 
    x.previousElementSibling.className += " w3-blue";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-blue", "");
  }
}

</script>


</body>
</html> 
