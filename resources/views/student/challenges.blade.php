@extends('layouts.app')

@section('content')
<style>
.star {
  transition: all 0.2s ease;
  display: inline-block;
  user-select: none;
}

.star:hover {
  transform: scale(1.15);
}

.star-rating {
  padding: 10px 0;
  display: flex;
  align-items: center;
}

.rating-text {
  font-size: 18px;
  color: #333;
}
</style>
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large"><img src="{{ asset('photo/' . (!empty($student->photo) ? $student->photo : 'avatar.png')) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.src='{{ asset('photo/avatar.png') }}'"></a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>


  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
  <a href="{{ route('student.manage.book') }}" class="w3-bar-item w3-button"><i class="fa fa-book w3-margin-right"></i>MANAGE BOOK SUMMARY</a>
  <a href="{{ route('student.challenges.manage') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-star w3-margin-right"></i>BOOK RATING</a>
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
<b>BOOK RATING</b>
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
	<div class="w3-container w3-content" style="max-width:1000px;">
		<!-- Guidance Section -->
		<div class="w3-container w3-light-grey w3-round w3-padding w3-card" style="margin-bottom: 20px;">
			<p class="w3-text-dark-grey" style="margin: 0 0 12px 0; line-height: 1.6;">
				This page allows you to rate the books you have finished reading. Only finished books will appear here.
			</p>
			<div class="w3-text-dark-grey" style="margin-top: 12px;">
				<strong>Please follow these steps:</strong>
				<ol style="margin: 8px 0 0 0; padding-left: 20px; line-height: 1.8;">
					<li>If you have not finished reading a book yet, please go to the <strong>Manage Book Summary</strong> page and add your book summary / finished details.</li>
					<li>Once completed, click <strong>Add Rating</strong> and submit your rating about the book.</li>
				</ol>
			</div>
		</div>

	<div class="w3-container w3-content w3-card w3-padding-16" style="max-width:1000px; margin-top: 0;">
	  <!-- The Grid -->
	  <div class="w3-row w3-white w3-padding">

		<div class="w3-row">
		<table class="w3-table w3-table-all">
			<tr>
				<th>#</th>
				<th>Date Finished</th>
				<th>Book Name</th>
				<th>Rating</th>
				<th></th>
			</tr>
			@forelse($books as $book)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</td>
				<td>{{ $book->name }}</td>
				<td>
					@if($book->challenge && $book->challenge->rating !== null)
						@for($i = 1; $i <= 5; $i++)
							@if($i <= $book->challenge->rating)
								<span class="w3-text-yellow" style="font-size: 20px;">★</span>
							@else
								<span class="w3-text-grey" style="font-size: 20px;">☆</span>
							@endif
						@endfor
						<span class="w3-text-grey">({{ $book->challenge->rating }}/5)</span>
					@else
						<span class="w3-text-grey">No rating yet</span>
					@endif
				</td>
				<td class="w3-right">
					@if($book->challenge)
						<a href="#" onclick="document.getElementById('edit{{ $book->id_book }}').style.display='block'" class="w3-button w3-dark-grey w3-round">Edit</a>
					@else
						<a href="#" onclick="document.getElementById('add{{ $book->id_book }}').style.display='block'" class="w3-button w3-blue w3-round">Add Rating</a>
					@endif
				</td>
			</tr>

			<!-- Add Rating Modal -->
			<div id="add{{ $book->id_book }}" class="w3-modal" style="z-index:10;">
				<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px;">
					<header class="w3-container">
						<span onclick="document.getElementById('add{{ $book->id_book }}').style.display='none'"
						class="w3-button w3-large w3-circle w3-display-topright"><i class="fa fa-fw fa-times"></i></span>
					</header>
					<div class="w3-padding w3-margin">
						<b class="w3-large">Add Rating</b>
						<hr>
						<p><strong>Book Name:</strong> {{ $book->name }}</p>
						<p><strong>Date Finished:</strong> {{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</p>
						<hr>
						<form method="POST" action="{{ route('student.challenges.store') }}">
							@csrf
							<input type="hidden" name="id_book" value="{{ $book->id_book }}">
							<div class="w3-section">
								<label>Rating * (Click stars to rate)</label>
								<div class="star-rating" data-rating="5" data-book-id="{{ $book->id_book }}" data-type="add">
									@for($i = 1; $i <= 5; $i++)
										<span class="star" data-value="{{ $i }}" style="font-size: 35px; color: #ffd700; cursor: pointer; margin-right: 5px;">★</span>
									@endfor
									<span class="rating-text" style="margin-left: 10px; font-weight: bold;">5 / 5</span>
								</div>
								<input type="hidden" name="rating" id="rating-input-add{{ $book->id_book }}" value="5" required>
							</div>
							<hr class="w3-clear">
							<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round">Submit</button>
						</form>
					</div>
				</div>
			</div>

			<!-- Edit Rating Modal -->
			@if($book->challenge)
			<div id="edit{{ $book->id_book }}" class="w3-modal" style="z-index:10;">
				<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px;">
					<header class="w3-container">
						<span onclick="document.getElementById('edit{{ $book->id_book }}').style.display='none'"
						class="w3-button w3-large w3-circle w3-display-topright"><i class="fa fa-fw fa-times"></i></span>
					</header>
					<div class="w3-padding w3-margin">
						<b class="w3-large">Update Rating</b>
						<hr>
						<p><strong>Book Name:</strong> {{ $book->name }}</p>
						<p><strong>Date Finished:</strong> {{ $book->date_finished ? date('Y-m-d', strtotime($book->date_finished)) : '-' }}</p>
						<hr>
						<form method="POST" action="{{ route('student.challenges.update', $book->challenge->id_challenge) }}">
							@csrf
							@method('PUT')
							<div class="w3-section">
								<label>Rating * (Click stars to rate)</label>
								<div class="star-rating" data-rating="{{ $book->challenge->rating }}" data-book-id="{{ $book->id_book }}" data-type="edit">
									@for($i = 1; $i <= 5; $i++)
										<span class="star" data-value="{{ $i }}" style="font-size: 35px; color: {{ $i <= $book->challenge->rating ? '#ffd700' : '#ccc' }}; cursor: pointer; margin-right: 5px;">★</span>
									@endfor
									<span class="rating-text" style="margin-left: 10px; font-weight: bold;">{{ $book->challenge->rating }} / 5</span>
								</div>
								<input type="hidden" name="rating" id="rating-input-edit{{ $book->id_book }}" value="{{ $book->challenge->rating }}" required>
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
				<td colspan="5" class="w3-center w3-padding-32">No finished books yet. Go to <a href="{{ route('student.dashboard') }}">Homepage</a> to add books and mark them as finished.</td>
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

// Star Rating Functionality
document.addEventListener('DOMContentLoaded', function() {
  // Initialize all star ratings
  document.querySelectorAll('.star-rating').forEach(function(ratingContainer) {
    var bookId = ratingContainer.getAttribute('data-book-id');
    var type = ratingContainer.getAttribute('data-type');
    var currentRating = parseInt(ratingContainer.getAttribute('data-rating')) || 0;
    var stars = ratingContainer.querySelectorAll('.star');
    var ratingText = ratingContainer.querySelector('.rating-text');
    var hiddenInput = document.getElementById('rating-input-' + type + bookId);
    
    // Set initial state
    updateStars(stars, currentRating, ratingText, hiddenInput);
    
    // Add click handlers to each star
    stars.forEach(function(star, index) {
      var starValue = parseInt(star.getAttribute('data-value'));
      
      star.addEventListener('click', function() {
        currentRating = starValue;
        ratingContainer.setAttribute('data-rating', currentRating);
        updateStars(stars, currentRating, ratingText, hiddenInput);
      });
      
      // Add hover effect
      star.addEventListener('mouseenter', function() {
        highlightStars(stars, starValue);
      });
    });
    
    // Reset on mouse leave
    ratingContainer.addEventListener('mouseleave', function() {
      updateStars(stars, currentRating, ratingText, hiddenInput);
    });
  });
  
  function updateStars(stars, rating, ratingText, hiddenInput) {
    stars.forEach(function(star, index) {
      var starValue = index + 1;
      if (starValue <= rating) {
        star.style.color = '#ffd700'; // Gold for filled stars
        star.style.textShadow = '0 0 3px rgba(255, 215, 0, 0.5)';
      } else {
        star.style.color = '#ccc'; // Grey for empty stars
        star.style.textShadow = 'none';
      }
    });
    
    if (ratingText) {
      ratingText.textContent = rating + ' / 5';
    }
    
    if (hiddenInput) {
      hiddenInput.value = rating;
    }
  }
  
  function highlightStars(stars, hoverValue) {
    stars.forEach(function(star, index) {
      var starValue = index + 1;
      if (starValue <= hoverValue) {
        star.style.color = '#ffd700';
        star.style.textShadow = '0 0 5px rgba(255, 215, 0, 0.8)';
        star.style.transform = 'scale(1.1)';
      } else {
        star.style.color = '#ccc';
        star.style.textShadow = 'none';
        star.style.transform = 'scale(1)';
      }
    });
  }
});

</script>

@endsection