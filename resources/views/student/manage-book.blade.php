@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.src='{{ asset('photo/avatar.png') }}'"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('student.manage.book') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-book w3-margin-right"></i>MANAGE BOOK SUMMARY</a>
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

<div class="w3-padding-24"></div>

<div class="w3-container w3-padding-32">
    <div class="w3-content w3-container w3-white w3-round w3-card" style="max-width:1200px">
		<div class="w3-padding">

@if(session('success'))
<div class="w3-panel w3-green w3-display-container w3-animate-zoom">
  <span onclick="this.parentElement.style.display='none'"
  class="w3-button w3-large w3-display-topright">&times;</span>
  <h3>Success!</h3>
  <p>{{ session('success') }}</p>
</div>
@endif

		<div class="w3-xxlarge w3-text-blue" style="margin-bottom: 12px;">READING RECORD LOG SYSTEM</div>
		<div class="w3-container w3-light-grey w3-round w3-padding" style="margin-bottom: 20px;">
			<p class="w3-text-dark-grey" style="margin: 0 0 12px 0; line-height: 1.6;">
				Record the books you have read and submit your book summary for teacher evaluation. After submitting, you may edit or delete your record if needed.
			</p>
			<div class="w3-text-dark-grey" style="margin-top: 12px;">
				<strong>Follow these steps:</strong>
				<ol style="margin: 8px 0 0 0; padding-left: 20px; line-height: 1.8;">
					<li>Click <strong>+ Add</strong> to insert book information</li>
					<li>Write your summary</li>
					<li>Submit for teacher evaluation of your progress reading</li>
				</ol>
			</div>
		</div>
		<hr>

		<div class="w3-container w3-padding-16">
			<a onclick="document.getElementById('addBook').style.display='block'" class="w3-right w3-button w3-blue w3-margin-bottom w3-round">
				<i class="fa fa-fw fa-lg fa-plus"></i> Add
			</a>

			<table class="w3-table w3-table-all w3-striped w3-bordered">
				<thead>
					<tr class="w3-light-grey">
						<th>#</th>
						<th>Book Name</th>
						<th>Date Reading</th>
						<th>Date Finished</th>
						<th>Book Author</th>
						<th>Book Edition</th>
						<th>Summary</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($books as $book)
						@php
							$summary = $book->summaries->first();
						@endphp
						<tr>
							<td>{{ $loop->iteration }}</td>
							<td>{{ $book->name }}</td>
							<td>{{ $book->date_reading ? date('Y-m-d', strtotime($book->date_reading)) : '-' }}</td>
							<td>{{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</td>
							<td>{{ $book->author }}</td>
							<td>{{ $book->edition }}</td>
							<td>
								@if($summary)
									<div style="max-width: 250px; word-wrap: break-word;">
										{{ Str::limit($summary->summary, 50) }}
										@if(strlen($summary->summary) > 50)
											<span style="color: #999;">...</span>
											<button onclick="document.getElementById('summaryModal{{ $summary->id_summary }}').style.display='block'" class="w3-button w3-tiny w3-blue w3-round" style="margin-left: 5px;">View Full</button>
										@endif
									</div>
								@else
									<span style="color: #999;">No summary</span>
								@endif
							</td>
							<td class="w3-right">
								<a href="#" onclick="document.getElementById('edit{{ $book->id_book }}').style.display='block'" class="w3-button w3-dark-grey w3-round w3-small">Edit</a>
								<form method="POST" action="{{ route('student.books.delete', $book->id_book) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
									@csrf
									@method('DELETE')
									<button type="submit" class="w3-button w3-red w3-round w3-small">Del</button>
								</form>
							</td>
						</tr>

						<!-- Edit Book Modal -->
						<div id="edit{{ $book->id_book }}" class="w3-modal" style="z-index:10;">
							<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px;">
								<header class="w3-container w3-blue">
									<span onclick="document.getElementById('edit{{ $book->id_book }}').style.display='none'"
									class="w3-button w3-large w3-circle w3-display-topright w3-white"><i class="fa fa-fw fa-times"></i></span>
									<h2>Update Book</h2>
								</header>
								<div class="w3-padding w3-margin">
									<form method="POST" action="{{ route('student.books.update', $book->id_book) }}">
										@csrf
										@method('PUT')
										<div class="w3-section">
											<label>Book Name *</label>
											<input class="w3-input w3-border w3-round" type="text" name="name" value="{{ $book->name }}" required>
										</div>
										<div class="w3-section">
											<label>Date Reading *</label>
											<input class="w3-input w3-border w3-round" type="date" name="date_reading" value="{{ $book->date_reading }}" required>
										</div>
										<div class="w3-section">
											<label>Date Finished</label>
											<input class="w3-input w3-border w3-round" type="date" name="date_finished" value="{{ $book->date_finished }}">
											<small class="w3-text-grey">Leave empty if book is not finished yet</small>
										</div>
										<div class="w3-section">
											<label>Book Author *</label>
											<input class="w3-input w3-border w3-round" type="text" name="author" value="{{ $book->author }}" required>
										</div>
										<div class="w3-section">
											<label>Book Edition *</label>
											<input class="w3-input w3-border w3-round" type="text" name="edition" value="{{ $book->edition }}" required>
										</div>
										<div class="w3-section">
											<label>Summary</label>
											<textarea class="w3-input w3-border w3-round" name="summary" rows="5" placeholder="Write a summary of this book (optional)">{{ $summary ? $summary->summary : '' }}</textarea>
											<small class="w3-text-grey">You can add or edit the summary</small>
										</div>
										<hr class="w3-clear">
										<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Save Changes</button>
									</form>
								</div>
							</div>
						</div>
					@empty
						<tr>
							<td colspan="8" class="w3-center w3-padding-32">No books added yet. Click "Add" to add a book you're currently reading.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		<!-- Add Book Modal -->
		<div id="addBook" class="w3-modal">
			<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px">
				<header class="w3-container w3-blue">
					<span onclick="document.getElementById('addBook').style.display='none'"
					class="w3-button w3-large w3-circle w3-display-topright w3-white"><i class="fa fa-fw fa-times"></i></span>
					<h2>Add Book</h2>
				</header>
				<div class="w3-container w3-padding">
					<form action="{{ route('student.books.store') }}" method="post">
						@csrf
						<div class="w3-padding">
							<div class="w3-section">
								<label>Book Name *</label>
								<input class="w3-input w3-border w3-round" type="text" name="name" required>
							</div>
							<div class="w3-section">
								<label>Date Reading *</label>
								<input class="w3-input w3-border w3-round" type="date" name="date_reading" required>
							</div>
							<div class="w3-section">
								<label>Date Finished</label>
								<input class="w3-input w3-border w3-round" type="date" name="date_finished">
								<small class="w3-text-grey">Leave empty if book is not finished yet</small>
							</div>
							<div class="w3-section">
								<label>Book Author *</label>
								<input class="w3-input w3-border w3-round" type="text" name="author" required>
							</div>
							<div class="w3-section">
								<label>Book Edition *</label>
								<input class="w3-input w3-border w3-round" type="text" name="edition" required>
							</div>
							<div class="w3-section">
								<label>Summary</label>
								<textarea class="w3-input w3-border w3-round" name="summary" rows="5" placeholder="Write a summary of this book (optional)"></textarea>
								<small class="w3-text-grey">You can add or edit the summary later</small>
							</div>
							<hr class="w3-clear">
							<div class="w3-section">
								<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Rating Prompt Modal -->
		@if(session('show_rating_prompt'))
		<div id="ratingPrompt" class="w3-modal" style="display:block; z-index:20;">
			<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:500px;">
				<header class="w3-container w3-blue">
					<span onclick="document.getElementById('ratingPrompt').style.display='none'"
					class="w3-button w3-large w3-circle w3-display-topright w3-white"><i class="fa fa-fw fa-times"></i></span>
					<h2><i class="fa fa-star w3-margin-right"></i>Rate Your Book</h2>
				</header>
				<div class="w3-container w3-padding-24">
					<p class="w3-large w3-text-dark-grey" style="margin-bottom: 24px;">
						<strong>Do you want to rate this book now?</strong>
					</p>
					<p class="w3-text-grey" style="margin-bottom: 24px;">
						You can rate your book now or do it later from the Book Rating page.
					</p>
					<div class="w3-section" style="display: flex; gap: 12px; justify-content: flex-end;">
						<button onclick="document.getElementById('ratingPrompt').style.display='none'" 
							class="w3-button w3-light-grey w3-round w3-padding-large" style="min-width: 100px;">
							Later
						</button>
						<a href="{{ route('student.challenges.manage') }}" 
							class="w3-button w3-blue w3-round w3-padding-large" style="min-width: 100px;">
							Rate Now
						</a>
					</div>
				</div>
			</div>
		</div>
		@endif

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
						</div>
					</div>
				</div>
			@endif
		@endforeach

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

// Close modals when clicking outside
window.onclick = function(event) {
  var ratingPrompt = document.getElementById('ratingPrompt');
  if (ratingPrompt && event.target == ratingPrompt) {
    ratingPrompt.style.display = "none";
  }
  
  @foreach($books as $book)
    @if($book->summaries->first())
      @php $summary = $book->summaries->first(); @endphp
      var modal{{ $summary->id_summary }} = document.getElementById('summaryModal{{ $summary->id_summary }}');
      if (modal{{ $summary->id_summary }} && event.target == modal{{ $summary->id_summary }}) {
        modal{{ $summary->id_summary }}.style.display = "none";
      }
    @endif
  @endforeach
}

</script>
@endsection
