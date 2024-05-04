@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/vehicles')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Create Vehicle</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('vehicle-save') }}">
  @csrf
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationCustom01">Vehicle Number</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Vehicle Number" name="name" required>
                @error('name')
                  <div class="invalid-feedback">
                      Enter The Vehicle Number
                  </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-footer-fixed position-sticky ">
        <button class="btn bg-green text-white w-15" type="submit">Create</button>
        <a href="{{url('/vehicles')}}"><button type="button" class="btn bg-black text-white w-15" >Cancel</button></a>
    </div>
</form>
@endsection
