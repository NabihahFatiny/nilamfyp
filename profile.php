<?PHP
session_start();

// connect ke database
include("database.php");

// semak samada dah login ke belum, jika belum kembali ke page login
if( !verifyStudent($con) ) 
{
	header( "Location: index.php" );
	return false;
}
?>
<?PHP
// id_student dapat selepas login berjaya
$id_student = $_SESSION["id_student"];
$act		= (isset($_POST['act'])) ? trim($_POST['act']) : '';

// Dapat setelah hantar borang
$name		= (isset($_POST['name'])) ? trim($_POST['name']) : '';
$email		= (isset($_POST['email'])) ? trim($_POST['email']) : '';
$password	= (isset($_POST['password'])) ? trim($_POST['password']) : '';
$dob 		= (isset($_POST['dob'])) ? trim($_POST['dob']) : '';
$class 		= (isset($_POST['class'])) ? trim($_POST['class']) : '';
$address 	= (isset($_POST['address'])) ? trim($_POST['address']) : '';

// Jika arahan edit - tekan butang Save Changes
if($act == "edit")
{	
	// Arahan SQL standard utk update
	$SQL_update = " UPDATE
						`student`
					SET
						`name` = '$name',
						`email` = '$email',
						`password` = '$password',
						`dob` = '$dob',
						`class` = '$class',
						`address` = '$address'
					WHERE `id_student` =  '$id_student'
					";	
	
	// Runkan arahan SQL									
	$result = mysqli_query($con, $SQL_update);
	
	// -------- Upload Gambar  -----------------
	if(isset($_FILES['photo'])){
		 
		if($_FILES["photo"]["error"] == 4) {
				//means there is no file uploaded
		} else {

			$file_name = $_FILES['photo']['name'];
			$file_size = $_FILES['photo']['size'];
			$file_tmp = $_FILES['photo']['tmp_name'];
			$file_type = $_FILES['photo']['type'];
			
			$fileNameCmps = explode(".", $file_name);
			$file_ext = strtolower(end($fileNameCmps));

			$extensions= array("jpeg","jpg","png","gif");

			if(in_array($file_ext,$extensions)=== false){
				$errors="extension not allowed, please choose a JPEG, JPG, PNG or GIF file.";
			}

			if($file_size > 12097152) {
				$errors='File size must be smaller than 12 MB';
			}

			if(empty($errors)==true) {

				// image resize bfore uploaded
				$fileName = $_FILES['photo']['tmp_name']; 
				$sourceProperties = getimagesize($fileName);
				$resizeFileName = $id_student;
				$uploadPath = "photo/";
				$fileExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
				$uploadImageType = $sourceProperties[2];
				$sourceImageWidth = $sourceProperties[0];
				$sourceImageHeight = $sourceProperties[1];
				switch ($uploadImageType) {
					case IMAGETYPE_JPEG:
						$resourceType = imagecreatefromjpeg($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagejpeg($imageLayer,$uploadPath. $resizeFileName.'.'. $fileExt);
						break;

					case IMAGETYPE_GIF:
						$resourceType = imagecreatefromgif($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagegif($imageLayer,$uploadPath. $resizeFileName.'.'. $fileExt);
						break;

					case IMAGETYPE_PNG:
						$resourceType = imagecreatefrompng($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagepng($imageLayer,$uploadPath. $resizeFileName.'.'. $fileExt);
						break;

					default:
						break;
				}
				// image resize bfore uploaded
				
				$finalFileName = $resizeFileName.'.'. $fileExt;
				
				$query = "UPDATE `student` SET `photo`='$finalFileName' WHERE `id_student` =  '$id_student'";			
				$result = mysqli_query($con, $query) or die("Error in query: ".$query."<br />".mysqli_error($con));
			}else{
				print_r($errors);
			}  
		}
	}
	// -------- End Gambar -----------------
	
	// popup success
	print "<script>alert('Successfully Updated');</script>";
}

// dapatkan data asas student
$SQL_view 	= " SELECT * FROM `student` WHERE `email` =  '". $_SESSION["email"] ."'";
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
a { text-decoration : none; }
</style>
</head>
<body>

<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="main.php" class="w3-bar-item w3-border-bottom w3-large"><img src="photo/<?PHP echo $photo;?>" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
 
  <a href="main.php" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="profile.php" class="w3-bar-item w3-button w3-blue"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="summary.php" class="w3-bar-item w3-button"><i class="fa fa-book w3-margin-right"></i>MANAGE BOOK</a>
  <a href="challenge.php" class="w3-bar-item w3-button"><i class="fa fa-calendar w3-margin-right"></i>CHALLENGE</a>
  <a href="report.php" class="w3-bar-item w3-button"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
  <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out w3-margin-right"></i>LOGOUT</a>
</nav>



<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:320px;">
<i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>

<div class="w3-blue-gray w3-padding-large w3-large w3-hide-small">
<b>PROFILE</b>
</div>


<div class="w3-padding-32"></div>

<div class="w3-container w3-content w3-card" style="max-width:900px;">  
	<div class="w3-row">
		<div class="w3-col m4 w3-center">
		
		<div class="w3-white w3-padding w3-round">
			
				<div class="w3-section w3-center w3-dark" >
				<img class="w3-circle w3-image" src="photo/<?PHP echo $photo;?>" style="width:200px">
				</div>
				
				<div class="w3-container">
					<p>
					<span class="w3-xlarge"><b><?PHP echo $name;?></b></span><br>
					Student<br>
					</p> 
				</div>
			</div>
			
		</div>
		<div class="w3-col m8 ">
		
			<div class="w3-white w3-padding w3-round">

			<form action="" method="post" class="w3-margin" enctype = "multipart/form-data" >
			
			  <div class="w3-section" >
				<label >Photo </label>
				<?PHP if($data["photo"] =="") { ?>
				<div class="custom-file">
					<input type="file" class="custom-file-input" name="photo" id="photo" accept=".jpeg, .jpg,.png,.gif">
					
				</div>
				<small>  accept JPEG, JPG, PNG or GIF only </small>
				<?PHP } ?>
									
				<?PHP if($data["photo"] <>"") { ?>
				<div class="w3-input w3-border w3-round">
				<a class="w3-tag" target="_BLANK" href="photo/<?PHP echo $photo; ?>"><small>View</small></a>

				<a class="w3-tag w3-red" href="photo-del.php?id_student=<?PHP echo $id_student;?>"><small>Delete</small></a>
				</div>
				<?PHP } ?>
				
			</div>
			
			  <div class="w3-section" >
				<label>Full Name *</label>
				<input class="w3-input w3-border w3-round" type="text" name="name" value="<?PHP echo $data["name"]; ?>"  required>
			  </div>
			  <div class="w3-section" >
				<label>Email *</label>
				<input class="w3-input w3-border w3-round" type="text" name="email" value="<?PHP echo $data["email"]; ?>" required>
			  </div>
			  <div class="w3-section">
				<label>Password *</label>
				<input class="w3-input w3-border w3-round" type="password" name="password" value="<?PHP echo $data["password"]; ?>" required>
			  </div>
			  <div class="w3-section">
				<label>Date of Birth *</label>
				<input class="w3-input w3-border w3-round" type="date" name="dob" value="<?PHP echo $data["dob"]; ?>" required>
			  </div>
			  <div class="w3-section" >
				<label>Class Year </label>
				<input class="w3-input w3-border w3-round" type="text" name="class" value="<?PHP echo $data["class"]; ?>"  >
			  </div>
			  <div class="w3-section" >
				<label>Address </label>
				<textarea class="w3-input w3-border w3-round" type="text" name="address"><?PHP echo $data["address"]; ?></textarea>
			  </div>
			  
			  <div class="w3-padding"></div>
			  <input name="act" type="hidden" value="edit">
			  <button type="submit" class="w3-wide w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round"><b>UPDATE</b></button>
			</form> 				
					
					
					
					
					
				</div>

			</div>
		
		</div>
	</div>
</div>


<div class="w3-padding-32"></div>
     
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
