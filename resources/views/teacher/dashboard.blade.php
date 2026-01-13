@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . ($teacher->photo ?: 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>


  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('teacher.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('teacher.students') }}" class="w3-bar-item w3-button"><i class="fa fa-users w3-margin-right"></i>STUDENT LIST</a>
  <a href="{{ route('teacher.reports') }}" class="w3-bar-item w3-button"><i class="fa fa-file-text w3-margin-right"></i>REPORT</a>
  <a href="{{ route('teacher.progress.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-line-chart w3-margin-right"></i>PROGRESS DASHBOARD</a>
  <form method="POST" action="{{ route('teacher.logout') }}" style="display:inline">
    @csrf
    <button type="submit" class="w3-bar-item w3-button w3-red"><i class="fa fa-sign-out w3-margin-right"></i>LOGOUT</button>
  </form>
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
		<div class="w3-white w3-padding">
			<h3><b>Description</b></h3>
			<p>Reading Record Log also known as reading logs or journals, are an effective tool that teachers can use to help their students develop a stronger and more fulfilling reading life. Reading Record Log System is necessarily for students at school.</p>
			<p>The previous system has been improved to the online Reading Record Log System. This system has some objectives to achieve. Reading Record Log System can help the teacher in properly managing and carrying out their duties.</p>
			<p>The Reading Record Log System gives students to record their reading book wherever they at. Any school can use this system to practice and accomplish the targeted objective and help the library management to be organized.</p>
		</div>
	<!-- End Page Container -->
	</div>




</div>
<!-- container end -->

<div class="w3-padding-24"></div>



</div>

<script>
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}
</script>
@endsection
