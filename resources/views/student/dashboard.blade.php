@extends('layouts.app')

@section('content')
<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-border-bottom w3-large">
    @if(!empty($student->photo) && file_exists(public_path('photo/' . $student->photo)))
      <img src="{{ asset('photo/' . $student->photo) }}" class="w3-circle w3-padding" style="width:100%;" onerror="this.onerror=null; this.src='{{ asset('photo/avatar.png') }}'">
    @else
      <img src="{{ asset('photo/avatar.png') }}" class="w3-circle w3-padding" style="width:100%;" alt="Default Avatar">
    @endif
  </a>
  <a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu"
  class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>

  <a href="{{ route('student.dashboard') }}" class="w3-bar-item w3-button w3-blue"><i class="fa fa-home w3-margin-right"></i>HOME</a>
  <a href="{{ route('student.profile') }}" class="w3-bar-item w3-button"><i class="fa fa-user-circle w3-margin-right"></i>PROFILE</a>
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
<b>BOOK LIST</b>
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

	<!-- Welcome Section -->
	<div class="w3-container w3-card w3-white w3-round-large w3-padding-24" style="margin-top: 20px;">
		<div class="w3-center" style="margin-bottom: 20px;">
			<h2 class="w3-text-blue" style="margin: 0;"><i class="fa fa-book w3-margin-right"></i>Reading Record Log System (NILAM)</h2>
		</div>
		<div class="w3-container" style="max-width: 900px; margin: 0 auto;">
			<p class="w3-text-dark-grey" style="font-size: 16px; line-height: 1.8; text-align: justify; margin: 0;">
				Reading Record Log System (NILAM-Nadi Ilmu Amalan Membaca) is a digital platform to help students record and monitor their NILAM reading activities. Students can add books they have read, write book summaries, and submit ratings. Teachers will review student submissions and provide evaluation and feedback. This system helps track students' reading progress in an organised and systematic way.
			</p>
		</div>
	</div>

	<!-- Bar Chart Section -->
	<div class="w3-container w3-card w3-white w3-round-large w3-padding-24" style="margin-top: 20px;">
		<h3 class="w3-text-blue" style="margin-bottom: 20px;"><i class="fa fa-bar-chart w3-margin-right"></i>Books Read per Month</h3>
		<div style="position: relative; height: 400px;">
			<canvas id="booksChart"></canvas>
		</div>
	</div>
</div>

<div class="w3-padding-24"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}

// Bar Chart for Books Read per Month
const ctx = document.getElementById('booksChart').getContext('2d');
const booksChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($monthlyLabels),
        datasets: [{
            label: 'Books Read',
            data: @json($monthlyData),
            backgroundColor: 'rgba(33, 150, 243, 0.6)',
            borderColor: 'rgba(33, 150, 243, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: false
            }
        }
    }
});
</script>
@endsection
