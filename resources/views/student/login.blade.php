@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('home') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('images/logo.png') }}" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a id="myBtn" onclick="myFunc('Demo1')" href="javascript:void(0)" class="w3-bar-item w3-button"><i class="fa fa-unlock-alt w3-margin-right"></i>LOGIN<i class="fa fa-caret-down w3-margin-left"></i></a>
  <div id="Demo1" class="w3-hide w3-animate-left">
    <a href="{{ route('student.login') }}" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey w3-light-grey"  id="firstTab">
      <div class="w3-container">
        <span ><i class="fa fa-user w3-margin-right"></i>Student</span>
      </div>
    </a>
     <a href="{{ route('teacher.login') }}" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" >
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

@if($errors->any() || session('error'))
		<div class="w3-padding-32" id="contact">
			<div class="w3-red w3-round w3-card" style="max-width:600px">
				<div class="w3-padding">
				<div class="w3-large">Error! {{ $errors->first('login') ?? session('error') ?? 'Invalid login' }}</div>
				Please try again...
				</div>
			</div>
		</div>
@endif

			<form action="{{ route('student.login.post') }}" method="post">
			@csrf
			<div class="w3-xxlarge">Reading Record Log System</div>
			<hr>
			<h3>Login Student</h3>
			  <div class="w3-section" >
				<label>Email *</label>
				<input class="w3-input w3-border w3-round" type="email" name="email" value="{{ old('email') }}" required>
			  </div>
			  <div class="w3-section">
				<label>Password *</label>
				<input class="w3-input w3-border w3-round" type="password" name="password" required>
			  </div>
			  <div class="w3-section w3-padding-16">
				<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">LOGIN</button>
			  </div>
			</form>
			<div class="w3-center">Dont have account yet? <a href="{{ route('student.register') }}" class="w3-text-blue">Sign up here</a></div>

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
@endsection
