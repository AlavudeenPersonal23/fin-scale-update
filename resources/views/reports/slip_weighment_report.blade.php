<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Slip Report</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/finscale.css') }}" >
</head>
<body style="width: 384px !important">
test
	@foreach($report_data as $report)
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<div class="card shadow-lg  p-3 mb-4 bg-white rounded">
					<div class="row">
						<div class="col-md-12 text-center"><strong>{{$report['company']}}</strong></div>
						<div class="col-md-6">
							{{$report['company']}}<br/>
							{{$report['shed']}}<br/>
							{{$report['farmer']}}<br/>
							{{$report['vehicle']}}<br/>
						</div>
						<div class="col-md-6 text-right">
							{{$report['date']}}<br/>
							{{$report['time']}}<br/>
							{{$report['bag_count']}}<br/>
						</div>
						<div class="col-md-12">
							<hr style="margin: 0px !important;margin-bottom: 1px !important;color: black !important;">
							{{$report['gross_weight']}}
							<hr style="margin: 0px !important;margin-top: 1px !important;color: black !important;">
						</div>
						<div class="col-md-6">
							Gross Weight
						</div>
						<div class="col-md-6 text-right">
							{{$report['gross_weight']}}
						</div>
						<div class="col-md-12">
							Deduction
						</div>
						@foreach($report['waste'] as $key => $waste)
							<div class="col-md-6">
								{{$key}}
							</div>
							<div class="col-md-6 text-right">
								{{$waste}}
							</div>
						@endforeach
						<div class="col-md-6">
							Total Deduction
						</div>
						<div class="col-md-6 text-right">
							{{$report['deduction']}}
						</div>
						<div class="col-md-12">
							<hr style="margin: 0px !important;margin-bottom: 1px !important;color: black !important;">
						</div>
						<div class="col-md-6">
							Net Weight
						</div>
						<div class="col-md-6 text-right">
							{{$report['net_weight']}}
						</div>
						<div class="col-md-12">
							<hr style="margin: 0px !important;margin-top: 1px !important;color: black !important;">
						</div>
						<div class="col-md-12">
							Grade
						</div>
						@foreach($report['grade'] as $key => $grade)
							<div class="col-md-6">
								{{$key}}
							</div>
							<div class="col-md-6 text-right">
								{{$grade}}
							</div>
						@endforeach
						<div class="col-md-12 text-center">
							<hr style="margin: 0px !important;margin-top: 1px !important;color: black !important;">
							{{$report['net_weight']}} <br>
							<strong>Thank you</strong>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4"></div>
		</div>
	@endforeach
</body>
</html>