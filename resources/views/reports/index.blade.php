@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/weignments')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Reports</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route($report) }}">
	@csrf
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-lg-12">
                <div class="form-group">
					@php
						$value = '';
						if($report == 'shed-abstract-report'){
							$value = 'Shed Abstract Report';
						}elseif($report == 'shed-detail-report'){
							$value = 'Shed Detail Report';
						}elseif($report == 'slip-report'){
							$value = 'Slip Report';
						}
					@endphp
                    <label >{{$value}}</label>
					<input type="hidden" class="form-control" name="report" value="{{$report}}">
                    {{--<select class="form-control" required name="report" id="report">
                      <option value="">Select Report Type</option>
					  @can('shed-abstract-report')
					  <option value="shed-abstract-report">Shed Abstract Report</option>
					  @endcan
					  @can('shed-detail-report')
					  <option value="shed-detail-report">Shed Detail Report</option>
					  @endcan
					  @can('slip-report')
					  <option value="slip-report">Slip Report</option>
					  @endcan
                    </select>--}}
                  </div>
            </div>
            <div class="col-lg-4 non-slip-report">
                <div class="form-group">
					<input type="text" name="daterange" class="form-control" id="daterange" value="" placeholder="Please Select a Date Range">
                </div>
            </div>
            <div class="col-lg-4 non-slip-report">
                <div class="form-group">
                    <select class="form-control" name="shed" id="shed">
                      <option value="">Select Sheds</option>
					  @foreach($sheds as $shed)
						<option value="{{$shed->id}}">{{$shed->name}}</option>
					  @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-4 non-slip-report">
                <div class="form-group">
                    <select class="form-control" name="grade" id="grade">
                      <option value="">Select Grades</option>
					  @foreach($grades as $grade)
						<option value="{{$grade->id}}">{{$grade->name}}</option>
					  @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-8 non-slip-report">
                <div class="form-group">
                    <input type="text" name="farmer" class="form-control" id="farmer" placeholder="Enter farmer name">
                </div>
            </div>
			<div class="col-lg-2 text-center">
				<a href="#" class="btn bg-black text-white w-100" onclick="formReset()">Reset</a>
			</div>
			<div class="col-lg-2 text-center">
				<button tyoe="submit" class="btn bg-green text-white w-100">Apply</button>
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
		$('#daterange').val('');
	});
	
	function formReset(){
		$('#report').val('');
		$('#daterange').val('');
		$('#shed').val('');
		$('#grade').val('');
		$('#farmer').val('');
	}
</script>
@endsection
