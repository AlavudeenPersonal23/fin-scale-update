@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/sheds')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Edit Route</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('shed-update') }}">
  @csrf
  <input type="hidden" name="id" value="{{$shed->id}}">
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationCustom01">Route Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Route Name" name="name" required value="{{$shed->name}}">
                @error('name')
                  <div class="invalid-feedback">
                      Enter The Route Name
                  </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-footer-fixed position-sticky ">
        <button class="btn bg-green text-white w-15" type="submit">Update</button>
        <a href="{{url('/sheds')}}"><button type="button" class="btn bg-black text-white w-15" >Cancel</button></a>
    </div>
</form>
@endsection
