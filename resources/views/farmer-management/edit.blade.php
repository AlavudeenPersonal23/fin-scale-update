@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/farmers')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Edit Employee</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('farmer-update') }}">
  @csrf
  <input type="hidden" name="id" value="{{$farmer->id}}">
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label >Company Name</label>
                    <select class="form-control" required name="company">
                      <option value="">Select Company Name</option>
                      @foreach($companies as $company)
                            @php
                                $selected = '';
                                if($company->id == $farmer->company){
                                    $selected = 'selected';
                                }
                            @endphp
                          <option value="{{$company->id}}" {{$selected}}>{{$company->name}}</option>
                     @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label >Route</label>
                    <select class="form-control"  required name="shed">
                      <option value="">Select Route</option>
                      @foreach($sheds as $shed)
                            @php
                                $selected = '';
                                if($shed->id == $farmer->shed){
                                    $selected = 'selected';
                                }
                            @endphp
                          <option value="{{$shed->id}}" {{$selected}}>{{$shed->name}}</option>
                     @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-md-4 mb-4">
                <label for="validationCustom01">Employee Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Employee Name" name="name" required value="{{$farmer->name}}">
                @error('name')
                  <div class="invalid-feedback">
                      Enter The Employee Name
                  </div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="validationCustom01">Employee ID</label>
                <input type="number" class="form-control @error('member_id') is-invalid @enderror" placeholder="Enter Employee ID" name="member_id" required value="{{$farmer->member_id}}">
                @error('name')
                  <div class="invalid-feedback">
                      Enter The Employee ID
                  </div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="validationCustom01">Mobile Number</label>
                <input type="tel" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Mobile Number" name="contact_number" pattern="[1-9]{1}[0-9]{9}" title="please enter valid mobile number" value="{{$farmer->contact_number}}">
                @error('contact_number')
                  <div class="invalid-feedback">
                      Enter The Mobile Number
                  </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-footer-fixed position-sticky ">
        <button class="btn bg-green text-white w-15" type="submit">Update</button>
        <a href="{{url('/farmers')}}"><button type="button" class="btn bg-black text-white w-15" >Cancel</button></a>
    </div>
</form>
@endsection
