@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/users')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Create Supervisor</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('user-save') }}" onsubmit="return mySubmitFunction(event)">
  @csrf
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label for="validationCustom01">Supervisor Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Supervisor Name" name="name" required>
                @error('name')
                  <div class="invalid-feedback">
                      Enter The Supervisor Name
                  </div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="validationCustom01">Mobile Number</label>
                <input type="tel" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Mobile Number" name="contact_number" pattern="[1-9]{1}[0-9]{9}" title="please enter valid mobile number">
                @error('contact_number')
                  <div class="invalid-feedback">
                      Enter The Mobile Number
                  </div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="validationCustom01">Email Address</label>
                <input type="email" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Email Address" name="email" required>
                @error('contact_number')
                  <div class="invalid-feedback">
                      Enter The Mobile Number
                  </div>
                @enderror
            </div>
            {{--<div class="col-lg-6">
                <div class="form-group">
                    <label >User Type</label>
                    <select class="form-control" required name="role" id="roles" onchange="setUserAccessVisibility(this.value)">
                      <option value="">Select User Type</option>
                      @foreach($roles as $role)
						@if( $role->name != 'Super Admin' )
                          <option value="{{$role->name}}">{{$role->name}}</option>
						@endif
                     @endforeach
                    </select>
                  </div>
            </div>--}}
			<input type="hidden" name="role" value="{{$roles->name}}" id="roles">
            <div class="col-lg-6">
                <div class="form-group">
                    <label >Shed Name</label>
                    <select class="form-control"  required name="shed" id="shed">
                      <option value="">Select Shed Name</option>
                      @foreach($sheds as $shed)
                          <option value="{{$shed->id}}">{{$shed->name}}</option>
                     @endforeach
                    </select>
                  </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12  mb-2">
                <div class="form-group mb-0">
                    <label>Remarks</label>
                    <textarea class="form-control" rows="4" name="remarks"></textarea>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12  mb-3">
                <label>Password</label>
                <input type="password" class="form-control" placeholder="Enter Password" required name="password">
            </div>
             <div class="col-lg-12 mb-5 mb-5">
                <div class="table-responsive-sm user-access-section">
					<h5>User Accessibility</h5>
                    <table class="table checkbox-hight">
                      <thead class="thead-light ">
                        <tr>
                        
                          <th class="wt-500 p-2" scope="col">Modules</th>
                          <th class="wt-50 p-2" scope="col">View</th>
                          <th class="wt-50 p-2" scope="col">Edit</th>
                          <th class="wt-50 p-2" scope="col">Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td scope="row">Shed Management</td>
                          <td><input type="checkbox" class="form-control" name="permission[]" value="shed-management-view"></td>
                          <td><input type="checkbox" class="form-control" name="permission[]" value="shed-management-edit"></td>
                          <td><input type="checkbox" class="form-control" name="permission[]" value="shed-management-delete"></td>
                        </tr>
						<tr>
							<td scope="row">Farmer Management</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="farmer-management-view"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="farmer-management-edit"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="farmer-management-delete"></td>
						  </tr>
						  <tr>
							<td scope="row">User Management</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="user-management-view"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="user-management-edit"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="user-management-delete"></td>
						  </tr>
						  <tr>
							<td scope="row">Vehicle management</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="vehicle-management-view"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="vehicle-management-edit"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="vehicle-management-delete"></td>
						  </tr>
						  <tr>
							<td scope="row">Wastage Management</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="waste-management-view"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="waste-management-edit"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="waste-management-delete"></td>
						  </tr>
						  <tr>
							<td scope="row">Weighment Management</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="weighment-management-view"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="weighment-management-edit"></td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="weighment-management-delete"></td>
						  </tr>
						  <tr>
							<td scope="row">Dashboard</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="dashboard-management"></td>
							<td></td>
							<td></td>
						  </tr>
						  <tr>
							<td scope="row">Shed Abstract Report</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="shed-abstract-report"></td>
							<td></td>
							<td></td>
						  </tr>

						  <tr>
							<td scope="row">Shed Detail Report</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="shed-detail-report"></td>
							<td></td>
							<td></td>
						  </tr>


						  <tr>
							<td scope="row">Slip Report</td>
							<td><input type="checkbox" class="form-control" name="permission[]" value="slip-report"></td>
							<td></td>
							<td></td>
						  </tr>
                      </tbody>
                    </table>
                  </div>
             </div>
        </div>
    </div>
    <div class="form-footer-fixed position-sticky ">
        <button class="btn bg-green text-white w-15" type="submit">Create</button>
        <a href="{{url('/users')}}"><button type="button" class="btn bg-black text-white w-15" >Cancel</button></a>
    </div>
</form>
@endsection
@section('scripts')
<script>
	$(document).ready(function(){
		$('.user-access-section').hide();
	});
	function mySubmitFunction(e){
		var cnt = $("input[name='permission[]']:checked").length;
		if( $('#roles').val() == 'Admin'){			
			if( cnt == 0){
				alert ('please choose atleast one user-access');
				e.preventDefault();
				return false;
			}else{
				return true;
			}
		}
	}
	
	function setUserAccessVisibility(value){
		if(value == 'Admin'){
			$('.user-access-section').show();
			$('#shed').prop('disabled', true);
		}else{
			$('.user-access-section').hide();
			$('#shed').prop('disabled', false);
		}
	}
</script>
@endsection
