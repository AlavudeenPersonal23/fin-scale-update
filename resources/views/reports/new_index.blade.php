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
                      <option value="">Select Route</option>
					  @foreach($sheds as $shed)
						<option value="{{$shed->id}}">{{$shed->name}}</option>
					  @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-8 non-slip-report">
                <div class="form-group">
                    <input type="text" name="farmer" class="form-control" id="farmer" placeholder="Enter Employee">
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
