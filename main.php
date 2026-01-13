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
// dapatkan apa maklumat yg dikeyin pada borang
$act 		= (isset($_REQUEST['act'])) ? trim($_REQUEST['act']) : '';	
$id_book 	= (isset($_REQUEST['id_book'])) ? trim($_REQUEST['id_book']) : '';	

$name 		= (isset($_POST['name'])) ? trim($_POST['name']) : '';	
$author 	= (isset($_POST['author'])) ? trim($_POST['author']) : '';
$edition 	= (isset($_POST['edition'])) ? trim($_POST['edition']) : '';

// sessi dapat ketika login sukses
$id_student = $_SESSION["id_student"];

// arahan Add jika tekan butang Submit
if($act == "add")
{	
	$SQL_insert = " INSERT INTO `book`(`id_student`, `name`, `author`, `edition`) 
								VALUES ('$id_student', '$name', '$author', '$edition')";	
										
	$result = mysqli_query($con, $SQL_insert);
}

// arahan selepas tekan butang Save Changes
if($act == "edit")
{	
	$SQL_update = " 
		UPDATE `book` SET 
			`name` = '$name',
			`author` = '$author',
			`edition` = '$edition'
		WHERE 
			`id_book` = $id_book";	
										
	$result = mysqli_query($con, $SQL_update);
}

// Arahan jika tekan Delet
if($act == "del")
{
	$SQL_delete = " DELETE FROM `book` WHERE `id_book` =  '$id_book' ";
	$result = mysqli_query($con, $SQL_delete);
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
</style>
</head>
<body>

<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="main.php" class="w3-bar-item w3-border-bottom w3-large"><img src="photo/<?PHP echo $photo;?>" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
 

  <a href="main.php" class="w3-bar-item w3-button w3-blue"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="profile.php" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
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

<div class="w3-blue-gray w3-padding-large w3-large w3-hide-smallx">
<b>DASHBOARD</b>
</div>

<div class="w3-padding-16"></div>

<div class="w3-xxlarge w3-center w3-padding-16 w3-text-indigo">READING RECORD LOG SYSTEM</div>

<!-- Page Container1 -->
<div class="w3-container w3-content w3-card" style="max-width:1000px;">    
  <!-- The Grid -->
  <div class="w3-row w3-white">
	<div class="w3-row">
	<?PHP
	$set1 = rand(1, 2);
	$set2 = rand(3, 4);
	$set3 = 5;
	
	$numbers = array($set1, $set2, $set3);
	shuffle($numbers);

	foreach ($numbers as $number) {
	?>
	  <div class="w3-col s4 w3-center"><img src="images/<?PHP echo $number;?>.jpg"></div>
	<?PHP } ?>
	</div>
  </div>
</div>
	
<div class="w3-padding-16"></div>
	
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
				<th>Book Name</th>
				<th>Book Author</th>
				<th>Book Edition</th>
				<th></th>
			<tr>
			<?PHP
			$bil = 0;
			$SQL_list = "SELECT * FROM `book` WHERE `id_student` = $id_student";
			$result = mysqli_query($con, $SQL_list) ;
			while ( $data	= mysqli_fetch_array($result) )
			{
				$bil++;
			?>			
			<tr>
				<td><?PHP echo $bil ;?></td>
				<td><?PHP echo $data["name"] ;?></td>
				<td><?PHP echo $data["author"] ;?></td>
				<td><?PHP echo $data["edition"] ;?></td>
				<td class="w3-right">
				<a href="#" onclick="document.getElementById('idEdit<?PHP echo $bil;?>').style.display='block'" class="w3-button w3-dark-grey w3-round">Edit</a>
				
				<a title="Hapus" onclick="return confirm('Anda Pasti ?');" href="?act=del&id_book=<?PHP echo $data["id_book"]; ?>" class="w3-button w3-red w3-round">Del</a>
				</td>
			<tr>
<div id="idEdit<?PHP echo $bil; ?>" class="w3-modal" style="z-index:10;">
	<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:500px;">
	  <header class="w3-container "> 
		<span onclick="document.getElementById('idEdit<?PHP echo $bil; ?>').style.display='none'" 
		class="w3-button w3-large w3-circle w3-display-topright"><i class="fa fa-fw fa-times"></i></span>	
	  </header>
		<div class="w3-padding w3-margin">
			<b class="w3-large">Update</b>
			<hr>
		<form method="post" action="" > 
			  <div class="w3-section " >
				<label>Book Name *</label>
				<input class="w3-input w3-border w3-round" type="text" name="name" value="<?PHP echo $data["name"]; ?>" required>
			  </div>
			  
			  <div class="w3-section " >
				<label>Book Author *</label>
				<input class="w3-input w3-border w3-round" type="text" name="author" value="<?PHP echo $data["author"]; ?>" required>
			  </div>
			  
			  <div class="w3-section " >
				<label>Book Edition *</label>
				<input class="w3-input w3-border w3-round" type="text" name="edition" value="<?PHP echo $data["edition"]; ?>" required>
			  </div>
			  

			<hr class="w3-clear">
			<input type="hidden" name="id_book" value="<?PHP echo $data["id_book"];?>" >
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
    <div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:500px">
      <header class="w3-container "> 
        <span onclick="document.getElementById('add01').style.display='none'" 
        class="w3-button w3-large w3-circle w3-display-topright "><i class="fa fa-fw fa-times"></i></span>
      </header>
	  
	  
      <div class="w3-container w3-padding">
		
		<form action="" method="post">
			<div class="w3-padding">
			<b class="w3-large">Add Book</b>
			<hr>
			  <div class="w3-section " >
				<label>Book Name *</label>
				<input class="w3-input w3-border w3-round" type="text" name="name" value="" required>
			  </div>
			  
			  <div class="w3-section " >
				<label>Book Author *</label>
				<input class="w3-input w3-border w3-round" type="text" name="author" value="" required>
			  </div>
			  
			  <div class="w3-section " >
				<label>Book Edition *</label>
				<input class="w3-input w3-border w3-round" type="text" name="edition" value="" required>
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
