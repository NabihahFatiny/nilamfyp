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
// dapat jika submit form
$act 		= (isset($_REQUEST['act'])) ? trim($_REQUEST['act']) : '';	
$id_student = (isset($_REQUEST['id_student'])) ? trim($_REQUEST['id_student']) : '';	

// jika tekan delete
if($act == "del")
{
	$SQL_delete = " DELETE FROM `student` WHERE `id_student` =  '$id_student' ";
	$result = mysqli_query($con, $SQL_delete);
	
	print "<script>self.location='a-student.php';</script>";
}

// database data peribadi teacher yg login
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
  <a href="main.php" class="w3-bar-item w3-border-bottom w3-large"><img src="photo/<?PHP echo $photo;?>" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
 

  <a href="a-main.php" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="a-profile.php" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="a-student.php" class="w3-bar-item w3-button w3-blue"><i class="fa fa-users w3-margin-right"></i>STUDENT LIST</a>
  <a href="a-report.php" class="w3-bar-item w3-button"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
  <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out w3-margin-right"></i>LOGOUT</a>
</nav>



<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>

<div class="w3-blue-gray w3-padding-large w3-large w3-hide-smallx">
<b>STUDENT LIST</b>
</div>

<div class="w3-padding-32"></div>
	
<div class="w3-container">

	<!-- Page Container -->
	<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1000px;">    
	  <!-- The Grid -->
	  <div class="w3-row w3-white w3-padding">
	  
		<div class="w3-row">
		<table class="w3-table w3-table-all">
			<tr>
				<th>#</th>
				<th>Photo</th>
				<th>Name</th>
				<th>D.O.B</th>
				<th>Class</th>
				<th></th>
			<tr>
			<?PHP
			$bil = 0;
			$SQL_list = "SELECT * FROM `student` ";
			$result = mysqli_query($con, $SQL_list) ;
			while ( $data_student	= mysqli_fetch_array($result) )
			{
				$bil++;
				$photo2		= $data_student["photo"];
				if(!$photo2) $photo2 = "avatar.png"
			?>			
			<tr>
				<td><?PHP echo $bil ;?></td>
				<td><img class="w3-circle" src="photo/<?PHP echo $photo2;?>" style="height:55px"></td>
				<td><?PHP echo $data_student["name"] ;?></td>
				<td><?PHP echo $data_student["dob"] ;?></td>
				<td><?PHP echo $data_student["class"] ;?></td>
				<td class="w3-right">
				<a title="Hapus" onclick="return confirm('Anda Pasti ?');" href="?act=del&id_student=<?PHP echo $data_student["id_student"]; ?>" class="w3-button w3-red w3-round">Del</a>
				</td>
			<tr>			
			<?PHP } ?>
		</table>
		</div>

		
	  <!-- End Grid -->
	  </div>
	  
	<!-- End Page Container -->
	</div>
	
	
	

</div>
<!-- container end -->

<div id="add01" class="w3-modal" >
    <div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px">
      <header class="w3-container "> 
        <span onclick="document.getElementById('add01').style.display='none'" 
        class="w3-button w3-large w3-circle w3-display-topright "><i class="fa fa-fw fa-times"></i></span>
      </header>
	  
	  
      <div class="w3-container w3-padding">
		
		<form action="" method="post">
			<div class="w3-padding">
			<b class="w3-large">Add Challenge</b>
			<hr>
			  <div class="w3-section " >
				<label>Date Finished *</label>
				<input class="w3-input w3-border w3-round" type="date" name="date_finished" value="" required>
			  </div>
			  
			  <div class="w3-section " >
				<label>Title *</label>
				<input class="w3-input w3-border w3-round" type="text" name="title" value="" required>
			  </div>
			  
			  <div class="w3-section " >
				<label>Rating *</label>
				<input class="w3-input w3-border w3-round" type="text" name="rating" value="" required>
			  </div>
			  
			  <hr class="w3-clear">
			  
			  <div class="w3-section" >
				<input name="act" type="hidden" value="add">
				<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Submit</button>
			  </div>
			</div>  
		</form> 
         
      </div>


    </div>
</div>

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
