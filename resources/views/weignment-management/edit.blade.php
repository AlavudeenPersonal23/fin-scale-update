@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/weignments')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Edit Weighment</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('weignment-update') }}">
  @csrf
  <input type="hidden" name="id" value="{{$weighment->id}}">
	<div class="empty">
		<div class="tab-list">
			<ul >
				<li class="tab-list-item weighment-active" onclick="toggleTabs('basic-detail')" id="basic-detail-button">Basic Details</li>
				<li class="tab-list-item" onclick="toggleTabs('weighment-detail')" id="weighment-detail-button">Weighment Details</li>
			</ul>
		</div>
		<!--Tab form1-->
		<div id="basic-detail">
			<div class="form-row">
				{{--<div class="col-lg-6 col-md-6 col-sm-12 col-12">
					<div class="form-group">
						<label>Branch Name</label>
						<select class="form-control" required name="company">
							<option value="">Select Branch</option>
							@foreach($companies as $company)
							@php
								$selected = '';
								if($company->id == $weighment->company){
									$selected = 'selected';
								}
							@endphp
							<option value="{{$company->id}}" {{$selected}}>{{$company->name}}</option>
							@endforeach
						</select>
					</div>
				</div>--}}
				<div class="col-lg-6 col-md-6 col-sm-12 col-12 ">
					<div class="form-group">
						<label>Route</label>
						<select class="form-control" required name="shed" id="shed">
							<option value="">Select Route</option>
							@foreach($sheds as $shed)
							@php
								$selected = '';
								if($shed->id == $weighment->shed){
									$selected = 'selected';
								}
							@endphp
							<option value="{{$shed->id}}" {{$selected}}>{{$shed->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-12 ">
					<label> Weighment Date</label>
					<input type="datetime-local" class="form-control" placeholder="Enter Weighment Date" name="weignment_date" value={{\Carbon\Carbon::parse($weighment->weignment_date)->format('Y-m-d').'T'.\Carbon\Carbon::parse($weighment->weignment_date)->format('h:m')}}>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-12 ">
					<div class="form-group">
						<label>Employee</label>
						<select class="form-control" required name="farmer" id="farmer">
							<option value="">Select Employee</option>
							@foreach($farmers as $farmer)
							@php
								$selected = '';
								if($farmer->id == $weighment->farmer){
									$selected = 'selected';
								}
							@endphp
							<option value="{{$farmer->id}}" {{$selected}}>{{$farmer->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>
		<!--tabform 1 end-->

		<!--tabfrom 2-->
		<div id="weighment-detail" style="overflow:scroll">
			<div class="form-row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
					<label> Gross Weight(kg)</label>
					<input type="text" value="{{$weighment->gross_weight}}" class="form-control" placeholder="Enter Gross Weight" name="gross_weight" id="gross_weight" onchange="calculateWeight()">

				</div>
			</div>
			<div class="col-12 sub-title-tab">Total Wastage</div>
			<div class="total-wastage-section" data-tab-select="add-wastage">
				<div class="form-row">
					<div class="col-lg-12" id="weighment-wastage">
						@if(count($weighmentwastes) > 0)
							@foreach($weighmentwastes as $key => $weighmentwaste)
								<div class="row" id="weighment-wastage{{$key}}">
									<div class="col-lg-5 col-md-5 col-sm-5 col-12">
										<label>Wastage Name</label>
										<select type="text" class="form-control" name="waste[{{$key}}]" id="waste{{$key}}">
											<option value="">Select Wastage Name</option>
											@foreach($wastes as $waste)
												@php
													$selected = '';
													if($waste->id == $weighmentwaste->waste){
														$selected = 'selected';
													}
												@endphp
											<option value="{{$waste->id}}" {{$selected}}>{{$waste->name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-12">
										<label>Wastage Qty(kg)</label>
										<input type="number" value="{{$weighmentwaste->weight}}" id="waste-value{{$key}}" class="form-control weight-value" placeholder="Enter Wastage Qty" name="waste_weight[{{$key}}]" step="any" onchange="calculateWeight()">
									</div>
								</div>
							@endforeach
						@else
							<div class="row" id="weighment-wastage0">
								<div class="col-lg-5 col-md-5 col-sm-5 col-12">
									<label>Wastage Name</label>
									<select type="text" class="form-control" name="waste[0]" id="waste0">
										<option value="">Select Wastage Name</option>
										@foreach($wastes as $waste)
										<option value="{{$waste->id}}">{{$waste->name}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5 col-12">
									<label>Wastage Qty(kg)</label>
									<input type="number" id="waste-value0" class="form-control weight-value" placeholder="Enter Wastage Qty" name="waste_weight[0]" step="any" onchange="calculateWeight()">
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
			<div class="col-12 add-wastage green-text p-0" data-tabbutton="add-wastage" onclick="addWastage()">+ Add Wastage</div>
			{{--<div class="col-12 sub-title-tab">Total Grade</div>
			<div class="total-wastage-section" data-tab-select="add-grade">
				<div class="form-row">
					<div class="col-lg-12" id="weighment-grade">
					@if(count($weighmentgrades) > 0)
						@foreach($weighmentgrades as $key => $weighmentgrade)
							<div class="row" id="weighment-grade{{$key}}">
								<div class="col-lg-5 col-md-5 col-sm-5 col-12 ">
									<label>Grade Name</label>
									<select type="text" class="form-control" name="grade[{{$key}}]" id="grade{{$key}}">
										<option value="">Select Grade</option>
										@foreach($grades as $grade)
											@php
												$selected = '';
												if($grade->id == $weighmentgrade->grade){
													$selected = 'selected';
												}
											@endphp
										<option value="{{$grade->id}}" {{$selected}}>{{$grade->name}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5 col-12 ">
									<label>Value</label>
									<input type="number" value="{{$weighmentgrade->weight}}"  id="grade-value{{$key}}" class="form-control grade-value" placeholder="Enter Grade Qty" name="grade_weight[{{$key}}]" step="any" onchange="calculateGrade()">
								</div>
							</div>
						@endforeach
					@else
						<div class="row" id="weighment-grade0">
							<div class="col-lg-5 col-md-5 col-sm-5 col-12 ">
								<label>Grade Name</label>
								<select type="text" class="form-control" name="grade[0]" id="grade0">
									<option value="">Select Grade</option>
									@foreach($grades as $grade)
									<option value="{{$grade->id}}">{{$grade->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-5 col-12 ">
								<label>Value</label>
								<input type="number" id="grade-value0" class="form-control grade-value" placeholder="Enter Grade Qty" name="grade_weight[0]" step="any" onchange="calculateGrade()">
							</div>
						</div>
					@endif
					</div>
				</div>
			</div>
			<div class="col-12 add-wastage green-text p-0" data-tabbutton="add-grade" onclick="addGrade()">+ Add Grade</div>--}}
			<div class="row" style="margin-bottom: 75px;">
				<div class="col-lg-6">
					<div class="bg-gray-box gross-font-size">
						<div class="row mb-3 ">
							<div class="col-8">Gross Weight</div>
							<div class="col-4" id="gross-weight-display"></div>
						</div>
						<div class="row mb-3">
							<div class="col-8">Total Wastage</div>
							<div class="col-4" id="waste-weight-display"></div>
						</div>
						<div class="row mb-3">
							<div class="col-8">Net Weight</div>
							<div class="col-4" id="net-weight-display"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-6" id="error-box">
					<div class="bg-red-box">
						<h4>Unable to update due to: </h4>
						<ul id="gross-weight-error-span"></ul>
						<ul id="net-weight-error-span"></ul>
					</div>
				</div>
			</div>
		</div>

		<!--tab from 2 end-->
	</div>
    <div class="form-footer-fixed position-sticky basic-detail-button-section">
        <a href="{{url('/weignments')}}"><button class="btn bg-black text-white w-15" type="button">Cancel</button></a>
        <button class="btn bg-green text-white w-15" id="next-button" type="button">Next</button>
    </div>
    <div class="form-footer-fixed position-sticky weighment-detail-button-section">
        <button class="btn bg-black text-white w-15" id="prev-button" type="button">Previous</button>
        <button class="btn bg-green text-white w-15" type="submit">Update</button>
    </div>
</form>
@endsection
@section('scripts')
<script>
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
	@if(count($weighmentwastes) > 0)
		var wasteCounter = Number({{count($weighmentwastes)}})-1;
	@else
		var wasteCounter = Number(0);
	@endif
	@if(count($weighmentgrades) > 0)
		var gradeCounter = Number({{count($weighmentgrades)}})-1;
	@else
		var gradeCounter = Number(0);
	@endif
	$(document).ready(function(){
	  $('#basic-detail').show();
	  $('.basic-detail-button-section').show();
	  $('#weighment-detail').hide();
	  $('.weighment-detail-button-section').hide();
		if($('#gross_weight').val() && $('#gross_weight').val() != '' && $('#gross_weight').val() != 0 ){
			$(':input[type="submit"]').prop('disabled', false);
			$('#gross-weight-error-span').empty();
			$('#error-box').hide();
		}else{
			$(':input[type="submit"]').prop('disabled', true);
			$('#error-box').show();
			$('#gross-weight-error-span').empty();
			$('#gross-weight-error-span').append('<li>Gross weight should not be empty or zero</li>');
		}
	  calculateWeight();
	  //calculateGrade();
	  $("#farmer").select2();
	});
	
	$('#shed').change(function() {
		$.ajax({
           type:'POST',
           url:"{{ route('shed-farmer-search') }}",
           data:{shed : $('#shed').val()},
           success:function(data){
			   var $el = $("#farmer");
			   $el.empty();
			   $el.val();
			   $.each(data, function(value,key) {
				  $el.append($("<option></option>")
					 .attr("value", key.id).text(key.name));
				});
				
           }
        });
	});

	$('#gross_weight').change(function() {
		if($('#gross_weight').val() && $('#gross_weight').val() != '' && $('#gross_weight').val() != 0 ){
			$(':input[type="submit"]').prop('disabled', false);
			$('#gross-weight-error-span').empty();
			$('#error-box').hide();
		}else{
			alert('Gross Weight cannot be empty or 0');
			$(':input[type="submit"]').prop('disabled', true);
			$('#error-box').show();
			$('#gross-weight-error-span').empty();
			$('#gross-weight-error-span').append('<li>Gross weight should not be empty or zero</li>');
		}
	});
	$("#next-button").click(function(){
		toggleTabs('weighment-detail');
	});

	$("#prev-button").click(function(){
		toggleTabs('basic-detail');
	});

	function toggleTabs(tab){
	  $('#basic-detail').hide();
	  $('#weighment-detail').hide();
	  $('#basic-detail-button').removeClass('weighment-active');
	  $('#weighment-detail-button').removeClass('weighment-active');
	  $('.basic-detail-button-section').hide();
	  $('.weighment-detail-button-section').hide();
	  $('#'+tab).show();
	  $('#'+tab+'-button').addClass('weighment-active');
	  $('.'+tab+'-button-section').show();
	}
	
	function addWastage(){
		var oldVal = wasteCounter;
		wasteCounter++;
		var html = '<div class="row" id="weighment-wastage'+wasteCounter+'">'+$('#weighment-wastage0').html()+'<div class="col-lg-1 col-md-2 col-sm-1" style="padding-top: 34px;"><a onclick=\'removeWaste("'+wasteCounter+'")\'><img src="./../../img/svg/cross.svg" alt="logo"></a></div>';
		html = replaceAll(html, 0, wasteCounter);
		html = replaceAll(html, 'col-lg-6 col-md-6 col-sm-6', 'col-lg-5 col-md-5 col-sm-5');
		$('#weighment-wastage').append(html);
		$('#waste'+wasteCounter).val('');
		$('#waste-value'+wasteCounter).val('');
		calculateWeight();
	}
	
	function addGrade() {
		var gross = $('#gross_weight').val();
		var waste = Number(0);
		$('input[type="number"].weight-value').each(function () {
			waste = Number(waste)+Number($(this).val());
		});
		var grade = Number(0);
		$('input[type="number"].grade-value').each(function () {
			grade = Number(grade)+Number($(this).val());
		});
		var net = gross - waste;
		var balance = net - grade;
		var oldVal = gradeCounter;
		gradeCounter++;
		var html = '<div class="row" id="weighment-grade'+gradeCounter+'">'+$('#weighment-grade0').html()+'<div class="col-lg-1 col-md-2 col-sm-1" style="padding-top: 34px;"><a onclick=\'removeGrade('+gradeCounter+')\'><img src="./../../img/svg/cross.svg" alt="logo"></a></div>';
		html = replaceAll(html, 0, gradeCounter);
		html = replaceAll(html, 'col-lg-6 col-md-6 col-sm-6', 'col-lg-5 col-md-5 col-sm-5');
		$('#weighment-grade').append(html);
		$('#grade-value'+gradeCounter).val(balance);
		calculateGrade();
	}
	
	function removeWaste(id){
		$("#weighment-wastage"+id).remove();
		wasteCounter--;
		calculateWeight();
	}

	function removeGrade(id){
		$("#weighment-grade"+id).remove();
		gradeCounter--;
		calculateGrade();
	}
	
	function calculateWeight(){
		var gross = $('#gross_weight').val();
		$('#gross-weight-display').empty();
		$('#gross-weight-display').html(gross+' kg');
		var waste = Number(0);
		$('input[type="number"].weight-value').each(function () {
			waste = Number(waste)+Number($(this).val());
		});
		waste = waste.toFixed(3);
		$('#waste-weight-display').empty();
		$('#waste-weight-display').html('- '+waste+' kg');
		var net = gross - waste;
		net = net.toFixed(3);
		$('#net-weight-display').empty();
		$('#net-weight-display').html(net+' kg');
		//$('#grade-value0').val(net);
	}
	
	function calculateGrade(){
		var gross = $('#gross_weight').val();
		var waste = Number(0);
		$('input[type="number"].weight-value').each(function () {
			waste = Number(waste)+Number($(this).val());
		});
		waste = waste.toFixed(3);
		var grade = Number(0);
		$('input[type="number"].grade-value').each(function () {
			grade = Number(grade)+Number($(this).val());
		});
		grade = grade.toFixed(3);
		var net = gross - waste;
		net = net.toFixed(3);
		if(grade > net){
			alert('Grade Should not be greater than '+String(net));
			$(':input[type="submit"]').prop('disabled', true);
			$('#error-box').show();
			$('#net-weight-error-span').empty();
			$('#net-weight-error-span').append('Grade Should not be greater than '+String(net));
		}else if(grade < net){
			alert('Grade Should not be lesser than '+String(net));
			$(':input[type="submit"]').prop('disabled', true);
			$('#error-box').show();
			$('#net-weight-error-span').empty();
			$('#net-weight-error-span').append('Grade Should not be lesser than '+String(net));
		} else{
			$(':input[type="submit"]').prop('disabled', false);
			$('#net-weight-error-span').empty();
			$('#error-box').hide();
		}
	}
	
	function escapeRegExp(string){
		return String(string).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	}
		
	/* Define functin to find and replace specified term with replacement string */
	function replaceAll(str, term, replacement) {
		return String(str).replace(new RegExp(escapeRegExp(term), 'g'), replacement);
	}
</script>
@endsection
