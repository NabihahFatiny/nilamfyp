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
$id_report 	= (isset($_REQUEST['id_report'])) ? trim($_REQUEST['id_report']) : '';	

$id_student 	= (isset($_POST['id_student'])) ? trim($_POST['id_student']) : '';
$reading_level 	= (isset($_POST['reading_level'])) ? trim($_POST['reading_level']) : '';	

// jika arahan add (tekan butang Submit)
if($act == "add")
{	
	$SQL_insert = " INSERT INTO `report`(`id_student`, `reading_level`) 
								VALUES ('$id_student', '$reading_level')";	
										
	$result = mysqli_query($con, $SQL_insert);
}

// jika arahan edit (tekan butang Save Changes)
if($act == "edit")
{	
	$SQL_update = " 
		UPDATE `report` SET 
			`id_student` = '$id_student',
			`reading_level` = '$reading_level'
		WHERE 
			`id_report` = $id_report";	
										
	$result = mysqli_query($con, $SQL_update);
	
	//print "<script>self.location='a-main.php';</script>";
}


// jika tekan delete
if($act == "del")
{
	$SQL_delete = " DELETE FROM `report` WHERE `id_report` =  '$id_report' ";
	$result = mysqli_query($con, $SQL_delete);
	
	print "<script>self.location='a-report.php';</script>";
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
  <a href="a-student.php" class="w3-bar-item w3-button"><i class="fa fa-users w3-margin-right"></i>STUDENT LIST</a>
  <a href="a-report.php" class="w3-bar-item w3-button w3-blue"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
  <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out w3-margin-right"></i>LOGOUT</a>
</nav>



<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>

<div class="w3-blue-gray w3-padding-large w3-large w3-hide-smallx">
<b>STUDENT REPORT</b>
</div>

<div class="w3-padding-32"></div>
	
<div class="w3-container">

	<!-- Page Container -->
	<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1000px;">    
	  <!-- The Grid -->
	  <div class="w3-row w3-white w3-padding">
	  
	  <a onclick="document.getElementById('add01').style.display='block'; " class=" w3-right w3-button w3-blue w3-margin-bottom w3-round "><i class="fa fa-fw fa-lg fa-plus"></i> Add</a>
	  
		<div class="w3-row">
		<table class="w3-table w3-table-all">
			<tr>
				<th>#</th>
				<th>Student Name</th>
				<th>Class Year</th>
				<th>Rating</th>
				<th></th>
			<tr>
			<?PHP
			$bil = 0;
			$SQL_list = "SELECT * FROM `report`,`student` WHERE report.id_student = student.id_student ";
			$result = mysqli_query($con, $SQL_list) ;
			while ( $data_report = mysqli_fetch_array($result) )
			{
				$bil++;
			?>			
			<tr>
				<td><?PHP echo $bil ;?></td>
				<td><?PHP echo $data_report["name"] ;?></td>
				<td><?PHP echo $data_report["class"] ;?></td>
				<td><?PHP echo $data_report["reading_level"] ;?></td>
				<td class="w3-right">
				<a href="#" onclick="document.getElementById('idEdit<?PHP echo $bil;?>').style.display='block'" class="w3-button w3-dark-grey w3-round">Edit</a>
				
				<a title="Hapus" onclick="return confirm('Anda Pasti ?');" href="?act=del&id_report=<?PHP echo $data_report["id_report"]; ?>" class="w3-button w3-red w3-round">Del</a>
				</td>
			<tr>
<div id="idEdit<?PHP echo $bil; ?>" class="w3-modal" style="z-index:10;">
	<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px;">
	  <header class="w3-container "> 
		<span onclick="document.getElementById('idEdit<?PHP echo $bil; ?>').style.display='none'" 
		class="w3-button w3-large w3-circle w3-display-topright"><i class="fa fa-fw fa-times"></i></span>	
	  </header>
		<div class="w3-padding w3-margin">
			<b class="w3-large">Update</b>
			<hr>
		<form method="post" action="" > 
			  
			  <div class="w3-section " >
				<label>Student *</label>
				<select class="w3-select w3-border w3-round w3-padding" name="id_student" value="" required>
					<option value="">- Select Student -</option>
				<?PHP 
					$rst = mysqli_query($con, "SELECT * FROM `student`");
					while( $dat = mysqli_fetch_array($rst) ) { 
				?>	
					<option value="<?PHP echo $dat["id_student"];?>" <?PHP if($dat["id_student"] == $data_report["id_student"]) echo "selected";?>><?PHP echo $dat["name"] . " ( " . $dat["class"] . " ) ";?></option>
				<?PHP } ?>
				</select>
			  </div>
			  
			  <div class="w3-section " >
				<label>Reading Level *</label>
				<select class="w3-select w3-border w3-round" name="reading_level" value="" required>
					<option value="">- Select Level -</option>
					<option value="Level 1" <?PHP if($data_report["reading_level"] == "Level 1") echo "selected";?>>Level 1</option>
					<option value="Level 2" <?PHP if($data_report["reading_level"] == "Level 2") echo "selected";?>>Level 2</option>
					<option value="Level 3" <?PHP if($data_report["reading_level"] == "Level 3") echo "selected";?>>Level 3</option>
				</select>
			  </div>
			  

			<hr class="w3-clear">
			<input type="hidden" name="id_report" value="<?PHP echo $data_report["id_report"];?>" >
			<input type="hidden" name="act" value="edit" >
			<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Save Changes</button>

		</form>
		</div>
	</div>
</div>				
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
			<b class="w3-large">Add Report</b>
			<hr>
			  
			   <div class="w3-section " >
				<label>Student *</label>
				<select class="w3-select w3-border w3-round w3-padding" name="id_student" value="" required>
					<option value="">- Select Student -</option>
				<?PHP 
					$rst = mysqli_query($con, "SELECT * FROM `student`");
					while( $dat = mysqli_fetch_array($rst) ) { 
				?>	
					<option value="<?PHP echo $dat["id_student"];?>"><?PHP echo $dat["name"] . " ( " . $dat["class"] . " ) ";?></option>
				<?PHP } ?>
				</select>
			  </div>
			  
			  <div class="w3-section " >
				<label>Reading Level *</label>
				<select class="w3-select w3-border w3-round w3-padding" name="reading_level" value="" required>
					<option value="">- Select Level -</option>
					<option value="Level 1">Level 1</option>
					<option value="Level 2">Level 2</option>
					<option value="Level 3">Level 3</option>
				</select>
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
