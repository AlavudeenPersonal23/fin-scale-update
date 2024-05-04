@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/reports')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Reports</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route($requestData->report) }}">
	@csrf
    <div class="container-fluid mb-4">
        <div class="form-row">
            <div class="col-lg-12">
                <div class="form-group">
					@php
						$value = '';
						if($requestData->report == 'shed-abstract-report'){
							$value = 'Shed Abstract Report';
						}elseif($requestData->report == 'shed-detail-report'){
							$value = 'Shed Detail Report';
						}elseif($requestData->report == 'slip-report'){
							$value = 'Slip Report';
						}
					@endphp
                    <label >{{$value}}</label>
					<input type="hidden" class="form-control" name="report" value="{{$requestData->report}}">
                    {{--<select class="form-control" required name="report" id="report">
                      <option value="">Select Report Type</option>
					  @can('shed-abstract-report')
					  <option value="shed-abstract-report"
					   {{($requestData->report == 'shed-abstract-report') ? 'selected': ''}}>Shed Abstract Report</option>
					  @endcan
					  @can('shed-detail-report')
					  <option value="shed-detail-report"
					   {{($requestData->report == 'shed-detail-report') ? 'selected': ''}}
					  >Shed Detail Report</option>
					  @endcan
					  @can('slip-report')
					  <option value="slip-report"
					  {{($requestData->report == 'slip-report') ? 'selected': ''}}
					  >Slip Report</option>
					  @endcan
                    </select>--}}
                  </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
					<input type="text" name="daterange" class="form-control" id="daterange" value="{{$requestData->daterange}}" placeholder="Please Select a Date Range">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <select class="form-control" name="shed" id="shed">
                      <option value="">Select Sheds</option>
					  @foreach($sheds as $shed)
						<option value="{{$shed->id}}"
						 {{($requestData->shed == $shed->id) ? 'selected': ''}}
						>{{$shed->name}}</option>
					  @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <select class="form-control" name="grade" id="grade">
                      <option value="">Select Grades</option>
					  @foreach($grades as $grade)
						<option value="{{$grade->id}}"
						 {{($requestData->grade == $grade->id) ? 'selected': ''}}
						>{{$grade->name}}</option>
					  @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <input type="text" name="farmer" class="form-control" id="farmer" placeholder="Enter farmer name" value="{{$requestData->farmer}}">
                </div>
            </div>
			<div class="col-lg-2 text-center">
				<a class="btn bg-black text-white w-100" onclick="formReset()">Reset</a>
			</div>
			<div class="col-lg-2 text-center">
				<button tyoe="submit" class="btn bg-green text-white w-100">Apply</button>
			</div>
			<div class="col-lg-2 text-center">
				<a onclick="exportPdf()" class="btn bg-info text-white w-100">Export</a>
			</div>
		</div>
	</div>
	<div class="body flex-grow-1 px-3">
		<div class="container-lg" id="print_report">
		<div class="row">
			@foreach($report_data as $report)
				<div class="col-md-4">
					<div class="card shadow-lg  p-3 mb-4 bg-white rounded">
						<div class="row">
							<div class="col-md-12 text-center"><strong>FinScale</strong></div>
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
			@endforeach
		</div>
		</div>
	</div>
</form>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){
		$('#daterange').daterangepicker({
			locale: {
			  format: 'DD/MM/YYYY'
			}
		});
		@if(!property_exists($requestData,'daterange') || empty($requestData->daterange))
			$('#daterange').val('');
		@endif
	});
	
	function formReset(){
		$('#report').val('');
		$('#daterange').val('');
		$('#shed').val('');
		$('#grade').val('');
		$('#farmer').val('');
	}
	
	@php
		$filename = 'slip_report';
		if(property_exists($requestData,'shed') && !empty($requestData->shed)){
			$tempshed = $sheds->where('id',$requestData->shed)->value('name');
			$filename = $filename.'_'.str_replace(' ','_',$tempshed);
		}
		if(property_exists($requestData,'daterange') && !empty($requestData->daterange)){
			$daterange = str_replace(' ','_',$requestData->daterange);
			$daterange = str_replace('-','to',$daterange);
			$filename = $filename.'_'.$daterange;
		}
	@endphp

	function exportPdf(){
		var doc = new jsPDF("p", "px", [250, 150]);
		@foreach($report_data as $report)
		doc.setFontSize(8);
		doc.setFontType('bold');
		doc.text("FinScale",70, 10, 'center');
		doc.setFontType('normal');
		doc.text("{{$report['company']}}",10, 20);
		doc.text("{{$report['shed']}}",10,30);
		doc.text("{{$report['farmer']}}",10, 40);
		doc.text("{{$report['vehicle']}}",10, 50);
		doc.text("{{$report['date']}}",140, 20, 'right');
		doc.text("{{$report['time']}}",140,30, 'right');
		doc.text("{{$report['bag_count']}}",140, 40, 'right');
		doc.line(10, 55, 140, 55);
		doc.text("{{$report['gross_weight']}}",10, 67.5);
		doc.line(10, 75, 140, 75);
		doc.text("Gross Weight",10, 85);
		doc.text("{{$report['gross_weight']}}",140, 85, 'right');
		doc.text("Deduction",10, 95);
			@php
				$hegight = 105;
			@endphp
			@foreach($report['waste'] as $key => $waste)
				doc.text("{{$key}}",10, {{$hegight}});
				doc.text("{{$waste}}",140, {{$hegight}}, 'right');
			@php
				$hegight += 10;
			@endphp
			@endforeach
		doc.line(10, {{$hegight}}, 140, {{$hegight}});
		@php
			$hegight += 12.5;
		@endphp
		doc.text("Net Weight",10, {{$hegight}});
		doc.text("{{$report['net_weight']}}",140, {{$hegight}}, 'right');
		@php
			$hegight += 7.5;
		@endphp
		doc.line(10, {{$hegight}}, 140, {{$hegight}});
		@php
			$hegight += 10;
		@endphp
		doc.text("Grade",10, {{$hegight}});
		@php
			$hegight += 10;
		@endphp
			@foreach($report['grade'] as $key => $waste)
				doc.text("{{$key}}",10, {{$hegight}});
				doc.text("{{$waste}}",140, {{$hegight}}, 'right');
			@php
				$hegight += 10;
			@endphp
			@endforeach
		doc.line(10, {{$hegight}}, 140, {{$hegight}});
		@php
			$hegight += 10;
		@endphp
		doc.text("Thank you !!!",70, {{$hegight}}, 'center');
		@if(!$loop->last)
			doc.addPage();
		@endif
		@endforeach
		doc.save('{{$filename}}.pdf');
	};
</script>
@endsection
