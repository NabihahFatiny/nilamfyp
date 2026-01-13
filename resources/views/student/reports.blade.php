@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.src='{{ asset('photo/avatar.png') }}'"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('student.manage.book') }}" class="w3-bar-item w3-button"><i class="fa fa-book w3-margin-right"></i>MANAGE BOOK SUMMARY</a>
  <a href="{{ route('student.challenges.manage') }}" class="w3-bar-item w3-button"><i class="fa fa-star w3-margin-right"></i>BOOK RATING</a>
  <a href="{{ route('student.reports') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-print w3-margin-right"></i>REPORT</a>
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
<b>STUDENT REPORT</b>
</div>

<div class="w3-padding-32"></div>

<div class="w3-container">
	<!-- Page Container -->
	<div class="w3-container w3-content" style="max-width:1200px;">
		<!-- Guidance Section -->
		<div class="w3-container w3-light-grey w3-round w3-padding w3-card" style="margin-bottom: 20px;">
			<p class="w3-text-dark-grey" style="margin: 0 0 12px 0; line-height: 1.6;">
				This page shows only your completed books that have been rated. It displays your submitted book records along with your book rating and summary. It also displays the teacher's feedback once your summary has been reviewed. If the teacher has not reviewed your submission yet, the Teacher Comment column will show "No comment yet". Please check this page regularly to view your evaluation results and feedback.
			</p>
		</div>

	<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1200px; margin-top: 0;">
		<div class="w3-white w3-padding">
			<table class="w3-table w3-striped w3-bordered">
				<thead>
					<tr class="w3-light-grey">
						<th>#</th>
						<th>Book Name</th>
						<th>Rating</th>
						<th>Summary</th>
						<th>Teacher Comment</th>
					</tr>
				</thead>
				<tbody>
					@forelse($books as $index => $book)
						@php
							$summary = $book->summaries->first();
							$challenge = $book->challenge;
							$rating = $challenge ? $challenge->rating : '-';
						@endphp
						<tr>
							<td>{{ $index + 1 }}</td>
							<td>{{ $book->name }}</td>
							<td>
								@if($rating != '-')
									@for($i = 0; $i < 5; $i++)
										@if($i < $rating)
											<span style="color: gold; font-size: 20px;">★</span>
										@else
											<span style="color: #ccc; font-size: 20px;">★</span>
										@endif
									@endfor
									<span>({{ $rating }}/5)</span>
								@else
									-
								@endif
							</td>
							<td>
								@if($summary)
									<div style="max-width: 300px; word-wrap: break-word;">
										{{ Str::limit($summary->summary, 100) }}
										@if(strlen($summary->summary) > 100)
											<button onclick="document.getElementById('summaryModal{{ $summary->id_summary }}').style.display='block'" class="w3-button w3-tiny w3-blue">View Full</button>
										@endif
									</div>
								@else
									-
								@endif
							</td>
							<td>
								@if($summary && $summary->comment)
									<div style="max-width: 300px; word-wrap: break-word; padding: 10px; background-color: #f0f0f0; border-left: 4px solid #2196F3;">
										{{ $summary->comment }}
									</div>
								@else
									<span style="color: #999;">No comment yet</span>
								@endif
							</td>
						</tr>
					@empty
					<tr>
						<td colspan="5" class="w3-center w3-padding-32">
							<p class="w3-large w3-text-grey">No reports found.</p>
							<p class="w3-text-grey">Only completed books with ratings are shown here. Please finish reading your books and submit ratings to see them in this report.</p>
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="w3-padding-24"></div>

</div>

<!-- Summary View Modal -->
@foreach($books as $book)
	@if($book->summaries->first())
		@php $summary = $book->summaries->first(); @endphp
		<div id="summaryModal{{ $summary->id_summary }}" class="w3-modal">
			<div class="w3-modal-content w3-card-4" style="max-width:700px">
				<header class="w3-container w3-blue">
					<span onclick="document.getElementById('summaryModal{{ $summary->id_summary }}').style.display='none'"
					class="w3-button w3-display-topright">&times;</span>
					<h2>Full Summary</h2>
				</header>
				<div class="w3-container w3-padding-16">
					<p><strong>Book:</strong> {{ $book->name }}</p>
					<p><strong>Summary:</strong></p>
					<div class="w3-padding w3-light-grey" style="white-space: pre-wrap;">{{ $summary->summary }}</div>
					@if($summary->comment)
						<div class="w3-padding-16"></div>
						<p><strong>Teacher Comment:</strong></p>
						<div class="w3-padding w3-light-blue" style="white-space: pre-wrap; border-left: 4px solid #2196F3;">{{ $summary->comment }}</div>
					@endif
				</div>
			</div>
		</div>
	@endif
@endforeach

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
