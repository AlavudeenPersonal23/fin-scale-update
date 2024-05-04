@extends('layouts.finscale')

@section('header')
    <div class="back-arrow"> 
        Dashboard
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-3">
		<div class="card shadow-lg  p-3 mb-4 bg-white rounded">
			<div class="row">
				<div class="col-lg-8 col-8">
					<div class="total-count">{{$sheds}}</div>
				</div>
				<div class="col-lg-4 col-4">
					<a href="{{url('/sheds')}}"><div class="total-icon-deshboard"><img src="./img/svg/total_shed.svg"></div></a>
				</div>
				<div class="col-lg-12">
					<div class="total-value-name">Total Route</div>
				</div>
			</div>
		</div>
    </div>
    <div class="col-lg-3">
        <div class="card shadow-lg  p-3 mb-4 bg-white rounded">
            <div class="row">
                <div class="col-lg-8 col-8">
                    <div class="total-count">{{$farmers}}</div>
                </div>
                <div class="col-lg-4 col-4">
                    <a href="{{url('/farmers')}}"><div class="total-icon-deshboard"><img src="./img/svg/total_farmers.svg"></div></a>
                </div>
                <div class="col-lg-12">
                    <div class="total-value-name">Total Employees</div>
                </div>
            </div>
        </div>
    </div>    <div class="col-lg-3">
        <div class="card shadow-lg  p-3 mb-4 bg-white rounded">
            <div class="row">
                <div class="col-lg-8 col-8">
                    <div class="total-count">{{$vehicles}}</div>
                </div>
                <div class="col-lg-4 col-4">
                    <a href="{{url('/vehicles')}}"><div class="total-icon-deshboard"><img src="./img/svg/vehicles-desh.svg"></div></a>
                </div>
                <div class="col-lg-12">
                    <div class="total-value-name">Total Vehicles</div>
                </div>
            </div>
        </div>
    </div>    <div class="col-lg-3">
        <div class="card shadow-lg  p-3 mb-4 bg-white rounded">
            <div class="row">
                <div class="col-lg-8 col-8">
                    <div class="total-count">{{$wastes}}</div>
                </div>
                <div class="col-lg-4 col-4">
                    <a href="{{url('/waste-types')}}"><div class="total-icon-deshboard"><img src="./img/svg/wastages-desh.svg"></div></a>
                </div>
                <div class="col-lg-12">
                    <div class="total-value-name">Total Wastages</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2" >
    <div class="col-lg-8">
		<div class="topnav">
		  <a>Total Weighments</a>
		  <div class="topnav-right tab" style="padding-top: 10px;">
			<button id="year-menu" onclick="switchChart('year')">This Year</button>
			<button id="month-menu" onclick="switchChart('month')">This Month</button>
			<button id="week-menu" onclick="switchChart('week')">This Week</button>
			<button id="today-menu" onclick="switchChart('today')">Today</button>
		  </div>
		</div>
        <div class="card shadow-lg  p-3 mb-5 bg-white rounded text-center" id="year"></div>
        <div class="card shadow-lg  p-3 mb-5 bg-white rounded text-center" id="month"></div>
        <div class="card shadow-lg  p-3 mb-5 bg-white rounded text-center" id="week"></div>
        <div class="card shadow-lg  p-3 mb-5 bg-white rounded text-center" id="today"></div>
    </div>
    <div class="col-lg-4">
		<div class="topnav">
		  <a>Total Users</a>
		</div>
        <div class="card shadow-lg  p-3 mb-5 bg-white rounded text-center" id="users"></div>
    </div>
</div>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){
		$('#year').hide();
		$('#month').hide();
		$('#week').hide();
		$('#today').show();
		$('#today-menu').addClass('active');
	});
	
	function switchChart(div){
		$('#year').hide();
		$('#month').hide();
		$('#week').hide();
		$('#today').hide();
		$('#year-menu').removeClass('active');
		$('#month-menu').removeClass('active');
		$('#week-menu').removeClass('active');
		$('#today-menu').removeClass('active');
		$('#'+div).show();
		$('#'+div+'-menu').addClass('active');
	}
	
	function drawTodayVisualization() {
	  // Create and populate the data table.
	  var data = google.visualization.arrayToDataTable([
		['Date', 'Weighments'],
		['{{Carbon\Carbon::now()->toDateString()}}',  {{$todayWeighments}}],
	  ]);

	  // Create and draw the visualization.
	  new google.visualization.ColumnChart(document.getElementById('today')).
		  draw(data,
			   {width:630, height:300,
				vAxis: {title: "Weighments"},
				hAxis: {title: "Date"},legend: { position: 'bottom', alignment: 'center' }}
		  );
	}

	function drawWeekVisualization() {
	  // Create and populate the data table.
	  var data = google.visualization.arrayToDataTable([
		['Date', 'Weighments'],
		@if(count($currentWeekWeignments) != 0)
			@foreach($currentWeekWeignments as $key => $currentWeekWeignment)
				['{{$key}}',  {{$currentWeekWeignment}}],
			@endforeach
		@else
			['No Weighment',0]
		@endif
	  ]);

	  // Create and draw the visualization.
	  new google.visualization.ColumnChart(document.getElementById('week')).
		  draw(data,
			   {width:630, height:300,
				vAxis: {title: "Weighments"},
				hAxis: {title: "Date"},legend: { position: 'bottom', alignment: 'center' }}
		  );
	}

	function drawMonthVisualization() {
	  // Create and populate the data table.
	  var data = google.visualization.arrayToDataTable([
		['Date', 'Weighments'],
		@if(count($currentMonthWeignments) != 0)
			@foreach($currentMonthWeignments as $key => $currentMonthWeignment)
				['{{$key}}',  {{$currentMonthWeignment}}],
			@endforeach
		@else
			['No Weighment',0]
		@endif
	  ]);

	  // Create and draw the visualization.
	  new google.visualization.ColumnChart(document.getElementById('month')).
		  draw(data,
			   {width:630, height:300,
				vAxis: {title: "Weighments"},
				hAxis: {title: "Date"},legend: { position: 'bottom', alignment: 'center' }}
		  );
	}

	function drawYearVisualization() {
	  // Create and populate the data table.
	  var data = google.visualization.arrayToDataTable([
		['Month', 'Weighments'],
		@if(count($currentYearWeignments) != 0)
			@foreach($currentYearWeignments as $key => $currentYearWeignment)
				['{{$key}}',  {{$currentYearWeignment}}],
			@endforeach
		@else
			['No Weighment',0]
		@endif
	  ]);

	  // Create and draw the visualization.
	  new google.visualization.ColumnChart(document.getElementById('year')).
		  draw(data,
			   {width:630, height:300,
				vAxis: {title: "Weighments"},
				hAxis: {title: "Month"},legend: { position: 'bottom', alignment: 'center' }}
		  );
	}

      function drawUserChart() {
        var data = google.visualization.arrayToDataTable([
          ['Roles', 'Users'],
          ['Admins',     {{$admin}}],
          ['Supervisors',     {{$supervisor}}]
        ]);

        var options = {
          title: 'Total Users',
		  width:300, height:300,
		  legend: { position: 'bottom', alignment: 'center' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('users'));
        chart.draw(data, options);
      }

	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawTodayVisualization);
	google.setOnLoadCallback(drawWeekVisualization);
	google.setOnLoadCallback(drawMonthVisualization);
	google.setOnLoadCallback(drawYearVisualization);
	google.setOnLoadCallback(drawUserChart);
</script>
@endsection