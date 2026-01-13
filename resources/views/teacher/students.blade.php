@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . ($teacher->photo ?: 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('teacher.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('teacher.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('teacher.students') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-users w3-margin-right"></i>STUDENT LIST</a>
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
<b>STUDENT LIST</b>
</div>

<div class="w3-padding-16"></div>

<div class="w3-container w3-content" style="max-width:1200px;">
	@if(session('success'))
	<div class="w3-panel w3-green w3-display-container w3-animate-zoom">
	  <span onclick="this.parentElement.style.display='none'"
	  class="w3-button w3-large w3-display-topright">&times;</span>
	  <h3>Success!</h3>
	  <p>{{ session('success') }}</p>
	</div>
	@endif

	<!-- Description Section -->
	<div class="w3-container w3-light-grey w3-round w3-padding w3-card" style="margin-bottom: 20px;">
		<p class="w3-text-dark-grey" style="margin: 0; line-height: 1.6;">
			This page helps teachers monitor student participation in NILAM reading activities. Teachers can view the student list by class and generate a PDF report to support evaluation and reporting.
		</p>
	</div>

<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1200px; margin-top: 0;">
	<!-- Filter and PDF Export Section -->
	<div class="w3-white w3-padding w3-margin-bottom">
		<div class="w3-row">
			<div class="w3-col m6">
				<form method="GET" action="{{ route('teacher.students') }}" class="w3-row">
					<div class="w3-col m8">
						<label><strong>Filter by Class Year:</strong></label>
						<select name="class_year" class="w3-select w3-border w3-round" onchange="this.form.submit()" style="padding: 8px;">
							<option value="">All Classes</option>
							<option value="Year 1" {{ (isset($classFilter) && $classFilter == 'Year 1') ? 'selected' : '' }}>Year 1</option>
							<option value="Year 2" {{ (isset($classFilter) && $classFilter == 'Year 2') ? 'selected' : '' }}>Year 2</option>
							<option value="Year 3" {{ (isset($classFilter) && $classFilter == 'Year 3') ? 'selected' : '' }}>Year 3</option>
							<option value="Year 4" {{ (isset($classFilter) && $classFilter == 'Year 4') ? 'selected' : '' }}>Year 4</option>
							<option value="Year 5" {{ (isset($classFilter) && $classFilter == 'Year 5') ? 'selected' : '' }}>Year 5</option>
							<option value="Year 6" {{ (isset($classFilter) && $classFilter == 'Year 6') ? 'selected' : '' }}>Year 6</option>
						</select>
					</div>
				</form>
			</div>
			<div class="w3-col m6 w3-right-align">
				<a href="{{ route('teacher.students.pdf', ['class_year' => $classFilter ?? '']) }}" class="w3-button w3-blue w3-round" target="_blank">
					<i class="fa fa-file-pdf-o w3-margin-right"></i>Generate PDF Report
				</a>
			</div>
		</div>
	</div>

	<div class="w3-white w3-padding">
		<table class="w3-table w3-striped w3-bordered">
			<thead>
				<tr class="w3-light-grey">
					<th>#</th>
					<th>Photo</th>
					<th>Name</th>
					<th>Email</th>
					<th>Student</th>
					<th>Class</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@forelse($students as $index => $student)
				<tr>
					<td>{{ $index + 1 }}</td>
					<td>
						<img src="{{ asset('photo/' . ($student->photo ?: 'avatar.png')) }}" class="w3-circle" style="width:50px;height:50px;">
					</td>
					<td>{{ $student->name }}</td>
					<td>{{ $student->email }}</td>
					<td>{{ $student->name }}</td>
					<td>{{ $student->class ?: '-' }}</td>
					<td>
						<form method="POST" action="{{ route('teacher.students.delete', $student->id_student) }}" style="display:inline">
							@csrf
							@method('DELETE')
							<button type="submit" class="w3-button w3-small w3-red" onclick="return confirm('Are you sure you want to delete this student?')">Del</button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="7" class="w3-center">No students found.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
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
