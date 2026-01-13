@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . ($teacher->photo ?: 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('teacher.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('teacher.students') }}" class="w3-bar-item w3-button"><i class="fa fa-users w3-margin-right"></i>STUDENT LIST</a>
  <a href="{{ route('teacher.reports') }}" class="w3-bar-item w3-button"><i class="fa fa-file-text w3-margin-right"></i>REPORT</a>
  <a href="{{ route('teacher.progress.dashboard') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-line-chart w3-margin-right"></i>PROGRESS DASHBOARD</a>
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
<b>PROGRESS DASHBOARD</b>
</div>

<div class="w3-padding-32"></div>

<div class="w3-container">
	<!-- Page Container -->
	<div class="w3-container w3-content" style="max-width:1200px;">
		<!-- Statistics Cards -->
		<div class="w3-row-padding">
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-blue">{{ $totalStudents }}</h2>
					<p>Total Students</p>
				</div>
			</div>
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-green">{{ $totalBooks }}</h2>
					<p>Total Books</p>
				</div>
			</div>
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-orange">{{ $finishedBooks }}</h2>
					<p>Finished Books</p>
				</div>
			</div>
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-purple">{{ $booksWithRatings }}</h2>
					<p>Books Rated</p>
				</div>
			</div>
		</div>

		<div class="w3-row-padding" style="margin-top: 20px;">
			<div class="w3-col m6 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-teal">{{ $booksWithSummaries }}</h2>
					<p>Books with Summary</p>
				</div>
			</div>
			<div class="w3-col m6 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-red">{{ $booksByClass->sum('count') }}</h2>
					<p>Students by Class</p>
				</div>
			</div>
		</div>

		<!-- Description Section -->
		<div class="w3-container w3-light-grey w3-round w3-padding w3-card" style="margin-top: 20px; margin-bottom: 20px;">
			<p class="w3-text-dark-grey" style="margin: 0; line-height: 1.6;">
				This dashboard provides an overview of student participation and progress in NILAM reading activities. Monitor total students, books submitted, completion rates, and engagement metrics to track the effectiveness of the reading program.
			</p>
		</div>

		<!-- Students by Class Table -->
		<div class="w3-container w3-card w3-white w3-padding-16" style="margin-top: 20px;">
			<h3>Students by Class</h3>
			<table class="w3-table w3-striped w3-bordered">
				<thead>
					<tr class="w3-light-grey">
						<th>Class Year</th>
						<th>Number of Students</th>
					</tr>
				</thead>
				<tbody>
					@forelse($booksByClass as $class)
					<tr>
						<td>{{ $class->class }}</td>
						<td>{{ $class->count }}</td>
					</tr>
					@empty
					<tr>
						<td colspan="2" class="w3-center">No class data available.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>

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
