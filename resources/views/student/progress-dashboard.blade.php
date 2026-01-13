@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.onerror=null; this.src='{{ asset('photo/avatar.png') }}'"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('student.manage.book') }}" class="w3-bar-item w3-button"><i class="fa fa-book w3-margin-right"></i>MANAGE BOOK SUMMARY</a>
  <a href="{{ route('student.challenges.manage') }}" class="w3-bar-item w3-button"><i class="fa fa-star w3-margin-right"></i>BOOK RATING</a>
  <a href="{{ route('student.reports') }}" class="w3-bar-item w3-button"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
  <a href="{{ route('student.progress.dashboard') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-line-chart w3-margin-right"></i>PROGRESS DASHBOARD</a>
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
					<h2 class="w3-text-blue">{{ $totalBooks }}</h2>
					<p>Total Books</p>
				</div>
			</div>
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-green">{{ $finishedBooks }}</h2>
					<p>Finished Books</p>
				</div>
			</div>
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-orange">{{ $booksWithRatings }}</h2>
					<p>Books Rated</p>
				</div>
			</div>
			<div class="w3-col m3 w3-margin-bottom">
				<div class="w3-card w3-white w3-padding-16 w3-center">
					<h2 class="w3-text-purple">{{ $booksWithSummaries }}</h2>
					<p>Books with Summary</p>
				</div>
			</div>
		</div>

		<!-- Description Section -->
		<div class="w3-container w3-light-grey w3-round w3-padding w3-card" style="margin-top: 20px; margin-bottom: 20px;">
			<p class="w3-text-dark-grey" style="margin: 0; line-height: 1.6;">
				This dashboard shows your reading progress and statistics. Track your total books, finished books, ratings submitted, and summaries written to monitor your NILAM reading activities.
			</p>
		</div>

		<!-- Recent Books Table -->
		<div class="w3-container w3-card w3-white w3-padding-16" style="margin-top: 20px;">
			<h3>Recent Books</h3>
			<table class="w3-table w3-striped w3-bordered">
				<thead>
					<tr class="w3-light-grey">
						<th>#</th>
						<th>Book Name</th>
						<th>Date Reading</th>
						<th>Date Finished</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@forelse($books->take(10) as $index => $book)
					<tr>
						<td>{{ $index + 1 }}</td>
						<td>{{ $book->name }}</td>
						<td>{{ $book->date_reading ? date('Y-m-d', strtotime($book->date_reading)) : '-' }}</td>
						<td>{{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</td>
						<td>
							@if($book->date_finished && $book->challenge && $book->challenge->rating !== null)
								<span class="w3-tag w3-green">Finished</span>
							@else
								<span class="w3-tag w3-orange">Reading</span>
							@endif
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="5" class="w3-center">No books found.</td>
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
