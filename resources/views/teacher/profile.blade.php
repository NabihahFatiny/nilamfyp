@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . ($teacher->photo ?: 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('teacher.profile') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-user w3-margin-right"></i>PROFILE</a>
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

<div class="w3-blue-gray w3-padding-large w3-large w3-hide-small">
<b>PROFILE</b>
</div>

<div class="w3-padding-32"></div>

<div class="w3-container w3-content w3-card" style="max-width:900px;">
	<div class="w3-row">
		<div class="w3-col m4 w3-center">
			<div class="w3-white w3-padding w3-round">
				<div class="w3-section w3-center w3-dark">
					<img class="w3-circle w3-image" src="{{ asset('photo/' . ($teacher->photo ?: 'avatar.png')) }}" style="width:200px">
				</div>
				<div class="w3-container">
					<p>
						<span class="w3-xlarge"><b>{{ $teacher->name }}</b></span><br>
						Teacher<br>
					</p>
				</div>
			</div>
		</div>
		<div class="w3-col m8">
			<div class="w3-white w3-padding w3-round">
				<form action="{{ route('teacher.profile.update') }}" method="post" class="w3-margin" enctype="multipart/form-data">
				@csrf

				@if(session('success'))
				<div class="w3-panel w3-green w3-display-container w3-animate-zoom">
				  <span onclick="this.parentElement.style.display='none'"
				  class="w3-button w3-large w3-display-topright">&times;</span>
				  <h3>Success!</h3>
				  <p>{{ session('success') }}</p>
				</div>
				@endif

				  <div class="w3-section">
					<label>Photo</label>
					@if(!$teacher->photo)
					<div class="custom-file">
						<input type="file" class="custom-file-input" name="photo" id="photo" accept=".jpeg, .jpg,.png,.gif">
					</div>
					<small>accept JPEG, JPG, PNG or GIF only</small>
					@else
					<div class="w3-input w3-border w3-round">
						<a class="w3-tag" target="_BLANK" href="{{ asset('photo/' . $teacher->photo) }}"><small>View</small></a>
						<a class="w3-tag w3-red" href="{{ route('teacher.photo.delete', $teacher->id_teacher) }}"><small>Delete</small></a>
					</div>
					@endif
				  </div>

				  <div class="w3-section">
					<label>Full Name *</label>
					<input class="w3-input w3-border w3-round" type="text" name="name" value="{{ $teacher->name }}" required>
				  </div>

				  <div class="w3-section">
					<label>Email *</label>
					<input class="w3-input w3-border w3-round" type="email" name="email" value="{{ $teacher->email }}" required>
				  </div>

				  <div class="w3-section">
					<label>Password *</label>
					<input class="w3-input w3-border w3-round" type="password" name="password" value="{{ $teacher->password }}" required>
				  </div>

				  <div class="w3-section">
					<label>Address</label>
					<textarea class="w3-input w3-border w3-round" name="address" rows="3">{{ $teacher->address }}</textarea>
				  </div>

				  <div class="w3-padding"></div>
				  <button type="submit" class="w3-wide w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round"><b>UPDATE</b></button>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="w3-padding-32"></div>

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
