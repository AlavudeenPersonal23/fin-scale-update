@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/sheds')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Reset Password</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('password-change') }}" onsubmit="return mySubmitFunction(event)">
  @csrf
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationCustom01">New Password</label>
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter New Password" name="password" required>
                @error('password')
                  <div class="invalid-feedback">
                      Enter The Password
                  </div>
                @enderror
            </div>
            <div class="col-md-12 mb-3">
                <label for="validationCustom01">Confirm Password</label>
                <input type="password" id="confirm-password" class="form-control @error('confirm-password') is-invalid @enderror" placeholder="Confirm Password" name="confirm-password" required>
                @error('confirm-password')
                  <div class="invalid-feedback">
                      Enter The Password
                  </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-footer-fixed position-sticky ">
        <button class="btn bg-green text-white w-15" type="submit">Reset</button>
        <a href="{{url('/dashboard')}}" class="btn bg-black text-white w-15" >Cancel</a>
    </div>
</form>
@endsection
@section('scripts')
<script>
	function mySubmitFunction(e){
		if( $('#password').val() != $('#confirm-password').val() ){
			alert ('Password and Confirm Password does not match');
			e.preventDefault();
			return false;
		}else{
			return true;
		}
	}
</script>
@endsection
