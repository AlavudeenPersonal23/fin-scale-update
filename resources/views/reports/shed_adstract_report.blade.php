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
					<input type="text" name="daterange" class="form-control" id="daterange" 
					value="{{$requestData->daterange}}"  placeholder="Please Select a Date Range">
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
                      <option value="">All Grade</option>
					  @php
						$selectedGrade = '';
					  @endphp
					  @foreach($grades as $grade)
						  @php
							  if($requestData->grade == $grade->id){
								$selectedGrade = $grade->name;
							  }
						  @endphp
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
				<div class="dropdown">
					<button class="btn bg-blue text-white w-100 dropdown-toggle" type="button"
						id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Export
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="#" onclick="createPDF()">PDF</a>
						<a class="dropdown-item" href="#" onclick="createExcel()">Excel</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="body flex-grow-1 px-3">
		<div class="container-lg">
			<div class="table-responsive-sm" id="tab">
				<table class="table" id="reportTable">
				@if(count($report_data) > 0)
					@foreach($report_data as $shed => $shedData)
						@foreach($shedData as $date => $dateData)
							<tr>
								<th class="wt-500 p-2 bg-black " colspan="2" scope="col"  style="background:#1D1D1D; color: #fff;     font-weight: normal;">Shed Name: {{$shed}}</th>
								<th class="wt-75 p-2" colspan="{{$selectedGrade ? 1:3}}" scope="col" style="background:#707070 ;color: #fff;    font-weight: normal;">Date: {{$date}}</th>
							</tr>
							<tr>
								<th class="wt-75 p-2" scope="col">SL.NO</th>
								<th class="wt-500 p-2" scope="col">{{strtoupper('Farmar Name')}}</th>
								@if($selectedGrade)
									<th class="wt-500 p-2" scope="col">{{$selectedGrade}}</th>
								@else
									<th class="wt-100 p-2" scope="col">A</th>
									<th class="wt-75 p-2" scope="col">B</th>
									<th class="wt-75 p-2" scope="col">B+</th>
								@endif
							</tr>
							@foreach($dateData as $key => $data)
								<tr>
									<td scope="row">{{$key+1}}</td>
									<td> {{$data['farmer']}} </td>
									@if($selectedGrade)
										<td> {{$data[$selectedGrade]}} </td>
									@else
										<td> {{$data['A']}} </td>
										<td> {{$data['B']}} </td>
										<td> {{$data['B+']}} </td>
									@endif
								</tr>
							@endforeach
							@if(!$loop->last)
								<tr>
									<td colspan="{{$selectedGrade ? 3:5}}"></td>
								</tr>
							@endif
						@endforeach
					@endforeach
					<tr>
						<th style="background:#707070 ;color: #fff;    font-weight: normal;"></th>
						<th class="text-right" style="background:#707070 ;color: #fff;    font-weight: normal;">Grand Total</th>
						@if($selectedGrade)
							<th style="background:#707070 ;color: #fff;    font-weight: normal;"> 
								{{array_key_exists($selectedGrade,$total) ? $total[$selectedGrade] : 0}} </th>
						@else
							<th style="background:#707070 ;color: #fff;    font-weight: normal;"> 
								{{array_key_exists('A',$total) ? $total['A'] : 0}} </th>
							<th style="background:#707070 ;color: #fff;    font-weight: normal;">
								{{array_key_exists('B',$total) ? $total['B'] : 0}} </th>
							<th style="background:#707070 ;color: #fff;    font-weight: normal;"> 
								{{array_key_exists('B+',$total) ? $total['B+'] : 0}} </th>
						@endif
					</tr>
				@else
					<tr>
						<td>No Data Found</td>
					</tr>
				@endif
				</table>
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
</script>
<script>
    function createPDF() {
        var sTable = document.getElementById('tab').innerHTML;

        var style = "<style>";
        style = style + "table {width: 100%;font: 17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px;text-align: center;}";
        style = style + "</style>";

        // CREATE A WINDOW OBJECT.
        var win = window.open('', '', 'height=700,width=700');

        win.document.write('<html><head>');
        win.document.write('<title>Profile</title>');   // <title> FOR PDF HEADER.
        win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
        win.document.write('</head>');
        win.document.write('<body>');
        win.document.write(sTable);         // THE TABLE CONTENTS INSIDE THE BODY TAG.
        win.document.write('</body></html>');

        win.document.close(); 	// CLOSE THE CURRENT WINDOW.

        win.print();    // PRINT THE CONTENTS.
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
