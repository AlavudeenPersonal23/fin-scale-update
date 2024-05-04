@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/weignments')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Reports</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('get-new-report') }}">
	@csrf
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-lg-6 non-slip-report">
                <div class="form-group">
					<input type="text" name="daterange" class="form-control" id="daterange" value="" placeholder="Please Select a Date Range">
                </div>
            </div>
            <div class="col-lg-6 non-slip-report">
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
            <div class="col-lg-6 non-slip-report">
                <div class="form-group">
                    <input type="text" name="farmer" class="form-control" id="farmer" placeholder="Enter farmer name" value="{{$requestData->farmer}}">
                </div>
            </div>
			<div class="col-lg-2 text-center">
				<a href="#" class="btn bg-black text-white w-100" onclick="formReset()">Reset</a>
			</div>
			<div class="col-lg-2 text-center">
				<button type="submit" class="btn bg-green text-white w-100">Apply</button>
			</div>
			<div class="col-lg-2 text-center">
				<div class="dropdown">
					<button class="btn bg-blue text-white w-100 dropdown-toggle" type="button"
						id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Export
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="#" onclick="createExcel()">Excel</a>
					</div>
				</div>
			</div>
			</div>
			<div class="table-responsive-sm" id="tab">
				<table class="table" id="reportTable">
				@if(count($report_data) > 0)
					<tr>
						<th class="wt-75 p-2" scope="col">Member No</th>
						<th class="wt-75 p-2" scope="col">Net Kgs</th>
						<th class="wt-75 p-2" scope="col">Member Name</th>
						<th class="wt-75 p-2" scope="col">Gross</th>
						@foreach($wastes as $waste)
							<th class="wt-75 p-2" scope="col">{{$waste->name}}</th>
						@endforeach
						<th class="wt-75 p-2" scope="col">Bag</th>
						<th class="wt-75 p-2" scope="col">Rcpt No</th>
						<th class="wt-75 p-2" scope="col">Route</th>
					</tr>
					@foreach($report_data as $report)
					<tr>
						@foreach($report as $r)
							<td>{{$r}}</td>
						@endforeach
					</tr>
					@endforeach
				@else
					<tr>
						<td>No Data Found</td>
					</tr>
				@endif
				</table>
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
		$('#daterange').val('');
		$('#shed').val('');
		$('#farmer').val('');
	}
	
	function createExcel(){
		html_table_to_excel('xlsx');
	}

	@php
		$filename = 'shed_abstract_report';
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

	function html_table_to_excel(type)
    {
        var data = document.getElementById('tab');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, '{{$filename}}.' + type);
    }
</script>
@endsection
