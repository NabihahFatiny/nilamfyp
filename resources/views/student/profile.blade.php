@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.src='{{ asset('photo/avatar.png') }}'"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>


  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('student.manage.book') }}" class="w3-bar-item w3-button"><i class="fa fa-book w3-margin-right"></i>MANAGE BOOK SUMMARY</a>
  <a href="{{ route('student.challenges.manage') }}" class="w3-bar-item w3-button"><i class="fa fa-star w3-margin-right"></i>BOOK RATING</a>
  <a href="{{ route('student.reports') }}" class="w3-bar-item w3-button"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
  <a href="{{ route('student.progress.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-line-chart w3-margin-right"></i>PROGRESS DASHBOARD</a>
  <form method="POST" action="{{ route('student.logout') }}" style="display:inline">
    @csrf
    <button type="submit" class="w3-bar-item w3-button w3-red"><i class="fa fa-sign-out w3-margin-right"></i>LOGOUT</button>
  </form>
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
				<img class="w3-circle w3-image" src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" style="width:200px" onerror="this.src='{{ asset('photo/avatar.png') }}'">
				</div>

				<div class="w3-container">
					<p>
					<span class="w3-xlarge"><b>{{ $student->name }}</b></span><br>
					Student<br>
					</p>
				</div>
			</div>

		</div>
		<div class="w3-col m8 ">

			<div class="w3-white w3-padding w3-round">

			<form action="{{ route('student.profile.update') }}" method="post" class="w3-margin" enctype="multipart/form-data" >
			@csrf

			  <div class="w3-section" >
				<label >Photo </label>
				@if(!$student->photo)
				<div class="custom-file">
					<input type="file" class="custom-file-input" name="photo" id="photo" accept=".jpeg, .jpg,.png,.gif">

				</div>
				<small>  accept JPEG, JPG, PNG or GIF only </small>
				@else
				<div class="w3-input w3-border w3-round">
				<a class="w3-tag" target="_BLANK" href="{{ asset('photo/' . $student->photo) }}"><small>View</small></a>

				<a class="w3-tag w3-red" href="{{ route('student.photo.delete', $student->id_student) }}"><small>Delete</small></a>
				</div>
				@endif

			</div>

			  <div class="w3-section" >
				<label>Full Name *</label>
				<input class="w3-input w3-border w3-round" type="text" name="name" value="{{ $student->name }}"  required>
			  </div>
			  <div class="w3-section" >
				<label>Email *</label>
				<input class="w3-input w3-border w3-round" type="text" name="email" value="{{ $student->email }}" required>
			  </div>
			  <div class="w3-section">
				<label>Password *</label>
				<input class="w3-input w3-border w3-round" type="password" name="password" value="{{ $student->password }}" required>
			  </div>
			  <div class="w3-section">
				<label>Date of Birth *</label>
				<input class="w3-input w3-border w3-round" type="date" name="dob" value="{{ $student->dob }}" required>
			  </div>
			  <div class="w3-section" >
				<label>Class Year </label>
				<select class="w3-select w3-border w3-round" name="class">
					<option value="">Select Class Year</option>
					<option value="Year 1" {{ $student->class == 'Year 1' ? 'selected' : '' }}>Year 1</option>
					<option value="Year 2" {{ $student->class == 'Year 2' ? 'selected' : '' }}>Year 2</option>
					<option value="Year 3" {{ $student->class == 'Year 3' ? 'selected' : '' }}>Year 3</option>
					<option value="Year 4" {{ $student->class == 'Year 4' ? 'selected' : '' }}>Year 4</option>
					<option value="Year 5" {{ $student->class == 'Year 5' ? 'selected' : '' }}>Year 5</option>
					<option value="Year 6" {{ $student->class == 'Year 6' ? 'selected' : '' }}>Year 6</option>
				</select>
			  </div>
			  <div class="w3-section" >
				<label>Address </label>
				<textarea class="w3-input w3-border w3-round" type="text" name="address">{{ $student->address }}</textarea>
			  </div>

			  <div class="w3-padding"></div>
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

@endsection