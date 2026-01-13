<?PHP
session_start();

// connect ke database
include("database.php");

// semak samada dah login ke belum, jika belum kembali ke page login
if( !verifyTeacher($con) ) 
{
	header( "Location: index.php" );
	return false;
}
?>
<?PHP
// dapatkan data asas teacher
$SQL_view 	= " SELECT * FROM `teacher` WHERE `email` =  '". $_SESSION["email"] ."'";
$result 	= mysqli_query($con, $SQL_view);
$data		= mysqli_fetch_array($result);
$name		= $data["name"];
$photo		= $data["photo"];
if(!$photo) $photo = "avatar.png"
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
  <a href="a-main.php" class="w3-bar-item w3-border-bottom w3-large"><img src="photo/<?PHP echo $photo;?>" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
 

  <a href="a-main.php" class="w3-bar-item w3-button w3-blue"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="a-profile.php" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="a-student.php" class="w3-bar-item w3-button"><i class="fa fa-users w3-margin-right"></i>STUDENT LIST</a>
  <a href="a-report.php" class="w3-bar-item w3-button"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
  <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out w3-margin-right"></i>LOGOUT</a>
</nav>



<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>

<div class="w3-blue-gray w3-padding-large w3-large w3-hide-smallx">
<b>DASHBOARD</b>
</div>

<div class="w3-padding-16"></div>

<div class="w3-xxlarge w3-center w3-padding-16 w3-text-indigo">READING RECORD LOG SYSTEM</div>

	
<div class="w3-padding-16"></div>
	
<div class="w3-container">

	<!-- Page Container -->
	<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1000px;">    
	  <!-- The Grid -->
	  <div class="w3-row w3-white w3-padding">
		<h4>Description</h4>
		<p>
		Lorem ipsum dolor sit amet. Non eligendi odio non ipsum consectetur non dicta molestiae quo repellat voluptates eos ducimus animi. Quo corrupti maiores sit aspernatur asperiores et sapiente dolores qui molestiae corporis est unde nihil hic voluptatem quae non aperiam voluptatibus. Cum iste laborum quo illum earum non accusantium eaque hic soluta beatae sit dolores eius At veritatis aliquid?
		</p><p>
		Qui consequuntur suscipit eos illum adipisci quo error nihil. Ut dolores aperiam non expedita cumque ex voluptatibus atque qui corporis dolor ut veritatis soluta. Est quibusdam dicta At fuga recusandae qui alias odio.
		</p><p>
		Qui minus animi qui illo provident ad soluta beatae 33 earum quis ut possimus internos nam eligendi eligendi vel incidunt veritatis. Quo sint autem ut odio accusantium cum tempore nesciunt 33 eaque voluptatum non voluptatibus debitis vel quia assumenda et voluptatem atque. Aut voluptatem debitis in aliquid vero qui quia exercitationem vel aliquam quisquam. Non nihil sint aut nemo facilis qui assumenda laudantium est voluptas rerum qui animi eveniet id recusandae eius et voluptatum earum.
		</p>
		
	  <!-- End Grid -->
	  </div>
	  
	<!-- End Page Container -->
	</div>
	
	
	

</div>
<!-- container end -->

<div class="w3-padding-24"></div>

     
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
    x.previousElementSibling.className += " w3-red";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-red", "");
  }
}

</script>

</body>
</html> 
