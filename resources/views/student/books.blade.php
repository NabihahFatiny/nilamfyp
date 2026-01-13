@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.src='{{ asset('photo/avatar.png') }}'"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>


  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('student.books.manage') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-file-text w3-margin-right"></i>BOOK SUMMARY</a>
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
<b>BOOK SUMMARY</b>
</div>



<div class="w3-padding-32"></div>

<div class="w3-container">

@if(session('success'))
<div class="w3-panel w3-green w3-display-container w3-animate-zoom" style="max-width:1000px; margin: 0 auto 16px; position: relative;">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-large w3-display-topright" style="position: absolute; top: 0; right: 0;">&times;</span>
  <div style="padding-right: 40px;">
    <h3 style="margin-top: 0;">Success!</h3>
    <p style="margin-bottom: 0; font-size: 16px;">{{ session('success') }}</p>
  </div>
</div>
@endif

	<!-- Page Container -->
	<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1000px;">
	  <!-- The Grid -->
	  <div class="w3-row w3-white w3-padding">

		<div class="w3-row">
		<table class="w3-table w3-table-all">
			<tr>
				<th>#</th>
				<th>Date Reading</th>
				<th>Date Finished</th>
				<th>Book Name</th>
				<th>Summary</th>
				<th></th>
			</tr>
			@forelse($books as $book)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $book->date_reading ? date('Y-m-d', strtotime($book->date_reading)) : '-' }}</td>
				<td>{{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</td>
				<td>{{ $book->name }}</td>
				<td>
					@if($book->summary)
						{{ Str::limit($book->summary->summary, 50) }}
					@else
						<span class="w3-text-grey">No summary yet</span>
					@endif
				</td>
				<td class="w3-right">
					@if($book->summary)
						<a href="#" onclick="document.getElementById('edit{{ $book->id_book }}').style.display='block'" class="w3-button w3-dark-grey w3-round">Edit</a>
					@else
						<a href="#" onclick="document.getElementById('add{{ $book->id_book }}').style.display='block'" class="w3-button w3-blue w3-round">Add Summary</a>
					@endif
				</td>
			</tr>

			<!-- Add Summary Modal -->
			<div id="add{{ $book->id_book }}" class="w3-modal" style="z-index:10;">
				<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px;">
					<header class="w3-container">
						<span onclick="document.getElementById('add{{ $book->id_book }}').style.display='none'"
						class="w3-button w3-large w3-circle w3-display-topright"><i class="fa fa-fw fa-times"></i></span>
					</header>
					<div class="w3-padding w3-margin">
						<b class="w3-large">Add Summary</b>
						<hr>
						<p><strong>Book:</strong> {{ $book->name }}</p>
						<p><strong>Date Reading:</strong> {{ $book->date_reading ? date('Y-m-d', strtotime($book->date_reading)) : '-' }}</p>
						<p><strong>Date Finished:</strong> {{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</p>
						<hr>
						<form method="POST" action="{{ route('student.summaries.store') }}">
							@csrf
							<input type="hidden" name="id_book" value="{{ $book->id_book }}">
							<div class="w3-section">
								<label>Summary *</label>
								<textarea class="w3-input w3-border w3-round" name="summary" rows="6" required placeholder="Write your summary here..."></textarea>
							</div>
							<hr class="w3-clear">
							<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Submit</button>
						</form>
					</div>
				</div>
			</div>

			<!-- Edit Summary Modal -->
			@if($book->summary)
			<div id="edit{{ $book->id_book }}" class="w3-modal" style="z-index:10;">
				<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px;">
					<header class="w3-container">
						<span onclick="document.getElementById('edit{{ $book->id_book }}').style.display='none'"
						class="w3-button w3-large w3-circle w3-display-topright"><i class="fa fa-fw fa-times"></i></span>
					</header>
					<div class="w3-padding w3-margin">
						<b class="w3-large">Update Summary</b>
						<hr>
						<p><strong>Book:</strong> {{ $book->name }}</p>
						<p><strong>Date Reading:</strong> {{ $book->date_reading ? date('Y-m-d', strtotime($book->date_reading)) : '-' }}</p>
						<p><strong>Date Finished:</strong> {{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</p>
						<hr>
						<form method="POST" action="{{ route('student.summaries.update', $book->summary->id_summary) }}">
							@csrf
							@method('PUT')
							<div class="w3-section">
								<label>Summary *</label>
								<textarea class="w3-input w3-border w3-round" name="summary" rows="6" required>{{ $book->summary->summary }}</textarea>
							</div>
							<hr class="w3-clear">
							<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Save Changes</button>
						</form>
					</div>
				</div>
			</div>
			@endif
			@empty
			<tr>
				<td colspan="6" class="w3-center w3-padding-32">No books added yet. Go to <a href="{{ route('student.dashboard') }}">Homepage</a> to add books you're currently reading.</td>
			</tr>
			@endforelse
		</table>
		</div>



	  <!-- End Grid -->
	  </div>

	<!-- End Page Container -->
	</div>




</div>
<!-- container end -->


<div class="w3-padding-24"></div>



</div>


<script>
var openInbox = document.getElementById("myBtn");
if (openInbox) {
  openInbox.click();
}

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

// Auto-scroll to success message if it exists
window.addEventListener('load', function() {
  var successMsg = document.querySelector('.w3-panel.w3-green');
  if (successMsg) {
    successMsg.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
});

</script>

@endsection