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
  <a href="{{ route('teacher.reports') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-file-text w3-margin-right"></i>REPORT</a>
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
<b>STUDENT REPORT</b>
</div>

<div class="w3-padding-16"></div>

<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1400px;">
	@if(session('success'))
	<div class="w3-panel w3-green w3-display-container w3-animate-zoom">
	  <span onclick="this.parentElement.style.display='none'"
	  class="w3-button w3-large w3-display-topright">&times;</span>
	  <h3>Success!</h3>
	  <p>{{ session('success') }}</p>
	</div>
	@endif

	<!-- Filter Section -->
	<div class="w3-white w3-padding w3-margin-bottom">
		<form method="GET" action="{{ route('teacher.reports') }}" class="w3-row">
			<div class="w3-col m4">
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

	<div class="w3-white w3-padding">
		<table class="w3-table w3-striped w3-bordered">
			<thead>
				<tr class="w3-light-grey">
					<th>#</th>
					<th>Student Name</th>
					<th>Class Year</th>
					<th>Rating</th>
					<th>Summary</th>
					<th>Date Finished</th>
					<th>Comment</th>
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
						<td>{{ $book->student->name }}</td>
						<td>{{ $book->student->class ?: '-' }}</td>
						<td>
							@if($rating != '-')
								@for($i = 0; $i < 5; $i++)
									@if($i < $rating)
										<span style="color: gold;">★</span>
									@else
										<span style="color: #ccc;">★</span>
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
							{{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}
						</td>
						<td>
							@if($summary)
								<div style="max-width: 300px;">
									@if($summary->comment)
										<div style="word-wrap: break-word; margin-bottom: 5px;">{{ Str::limit($summary->comment, 100) }}</div>
										@if(strlen($summary->comment) > 100)
											<button onclick="openCommentModal({{ $summary->id_summary }}, '{{ addslashes($summary->comment) }}')" class="w3-button w3-tiny w3-blue">View Full</button>
										@else
											<button onclick="openCommentModal({{ $summary->id_summary }}, '{{ addslashes($summary->comment) }}')" class="w3-button w3-tiny w3-grey">Edit</button>
										@endif
									@else
										<button onclick="openCommentModal({{ $summary->id_summary }}, '')" class="w3-button w3-tiny w3-blue">Add Comment</button>
									@endif
								</div>
							@else
								-
							@endif
						</td>
					</tr>
				@empty
				<tr>
					<td colspan="7" class="w3-center">No student reports found. Students need to submit book summaries and ratings first.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
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
					<p><strong>Student:</strong> {{ $book->student->name }}</p>
					<p><strong>Book:</strong> {{ $book->name }}</p>
					<p><strong>Summary:</strong></p>
					<div class="w3-padding w3-light-grey" style="white-space: pre-wrap;">{{ $summary->summary }}</div>
				</div>
			</div>
		</div>
	@endif
@endforeach

<!-- Comment Modal -->
<div id="commentModal" class="w3-modal">
	<div class="w3-modal-content w3-card-4" style="max-width:600px">
		<header class="w3-container w3-blue">
			<span onclick="document.getElementById('commentModal').style.display='none'"
			class="w3-button w3-display-topright">&times;</span>
			<h2>Add/Edit Comment</h2>
		</header>
		<div class="w3-container">
			<form method="POST" id="commentForm">
			@csrf
			@method('PUT')
				<div class="w3-section">
					<label>Comment</label>
					<textarea class="w3-input w3-border w3-round" name="comment" id="comment_text" rows="5" placeholder="Enter your comment on the student's summary..."></textarea>
				</div>
				<div class="w3-section">
					<button type="submit" class="w3-button w3-block w3-blue w3-round">Save Comment</button>
				</div>
			</form>
		</div>
	</div>
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

function openCommentModal(summaryId, comment) {
	document.getElementById('commentForm').action = '{{ url("teacher/summaries") }}/' + summaryId + '/comment';
	document.getElementById('comment_text').value = comment;
	document.getElementById('commentModal').style.display='block';
}
</script>

@endsection
