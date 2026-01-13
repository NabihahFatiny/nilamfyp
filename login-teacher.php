<?PHP
session_start();
// connect ke database
include("database.php");

$email 		= (isset($_POST['email'])) ? trim($_POST['email']) : '';
$password 	= (isset($_POST['password'])) ? trim($_POST['password']) : '';

$act 		= (isset($_POST['act'])) ? trim($_POST['act']) : '';

$error = "";

// jika arahan login Teacher (tekan LOGIN)
if($act == "login")
{	
	$SQL_login 	= " SELECT * FROM `teacher` WHERE `email` = '$email' AND `password` = '$password'  ";

	$result = mysqli_query($con, $SQL_login);
	$data	= mysqli_fetch_array($result);

	$valid = mysqli_num_rows($result);

	if($valid > 0)
	{
		// jika login sah, buka a-main.php
		$_SESSION["password"] 	= $password;
		$_SESSION["email"] 		= $email;
		$_SESSION["id_teacher"] = $data["id_teacher"];
		header("Location:a-main.php");
	}else{
		// jika tak sah kembali kepada borang login
		$error = "Invalid";
		header( "refresh:1;url=index.php" );
	}
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
    <a href="index.php" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey"  id="firstTab">
      <div class="w3-container">
        <span ><i class="fa fa-user w3-margin-right"></i>Student</span>
      </div>
    </a>
     <a href="login-teacher.php" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey w3-light-grey" >
      <div class="w3-container">
        <span ><i class="fa fa-user-md w3-margin-right"></i>Teacher</span>
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
		
<?PHP if($error) { ?>
		<div class="w3-padding-32" id="contact">
			<div class="w3-red w3-round w3-card" style="max-width:600px">
				<div class="w3-padding">
				<div class="w3-large">Error! Invalid login</div>
				Please try again...
				</div>
			</div>
		</div>	
<?PHP } ?>  
		
			<form action="" method="post">
			<div class="w3-xxlarge">Reading Record Log System</div>
			<hr>
			<h3>Login Teacher</h3>
			  <div class="w3-section" >
				<label>Email *</label>
				<input class="w3-input w3-border w3-round" type="text" name="email"  required>
			  </div>
			  <div class="w3-section">
				<label>Password *</label>
				<input class="w3-input w3-border w3-round" type="password" name="password" required>
			  </div>
			  <div class="w3-section w3-padding-16">
				<input type="hidden" name="act" value="login">
				<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">LOGIN</button>
			  </div>
			</form> 
			<div class="w3-center">Dont have account yet? <a href="register-teacher.php" class="w3-text-blue">Sign up here</a></div> 	
			
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
