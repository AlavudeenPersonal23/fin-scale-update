@extends('layouts.finscale')
@section('header')
	<div class="back-arrow p-2 bd-highlight"> 
		Supervisor Management
	</div>
	<div class="p-2 bd-highlight" style="font-size: 12px !important">
		Records Per Page 
		<select id="page_size">
			@php
				$selected = '';
				if(Session::get('page_size') == 10){
					$selected = 'selected';
				}
			@endphp
			<option {{$selected}}>10</option>
			@php
				$selected = '';
				if(Session::get('page_size') == 25){
					$selected = 'selected';
				}
			@endphp
			<option {{$selected}}>25</option>
			@php
				$selected = '';
				if(Session::get('page_size') == 50){
					$selected = 'selected';
				}
			@endphp
			<option {{$selected}}>50</option>
			@php
				$selected = '';
				if(Session::get('page_size') == 100){
					$selected = 'selected';
				}
			@endphp
			<option {{$selected}}>100</option>
		</select>
	</div>
@endsection
@section('search-section')
<div class="container-fluid">

  <div class="row">  
   <div class="col-lg-10">
    <form class="needs-validation" method="GET" action="{{ route('user-search') }}">
    @csrf
      <div class="input-group mb-3">
        @php
          $key = isset($searchkey) ? $searchkey : null;
        @endphp
        <input type="text" class="form-control" placeholder="Search for Supervisor by name or mobile number"  aria-describedby="button-addon2" name="searchkey" value="{{$key}}">
        <div class="input-group-append">
          <button class="btn bg-green text-white" type="submit" id="button-addon2"><i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </form>
   </div>
    <div class="col-lg-2"> 
      @can('user-management-edit')
        <a href="{{url('users/create')}}" class="btn bg-green text-white w-100"><i class="fa fa-plus" aria-hidden="true"></i>
        Create</a>  
      @endcan
    </div>
  </div>

</div>
@endsection
@section('content')
<div class="container-lg">
  <div class="table-responsive-sm">
  <table class="table">
    <thead class="thead-light">
      <tr>
        <th class="wt-50 p-2" scope="col">S.No</th>
        <th class="wt-500 p-2" scope="col">Supervisor Name</th>
        <th class="wt-500 p-2" scope="col">Mobile Number</th>
        <th class="wt-500 p-2" scope="col">Email Address</th>
        <th class="wt-500 p-2" scope="col">Shed Name</th>
        <th class="wt-500 p-2" scope="col">Type</th>
        <th class="wt-75 p-2" scope="col">Action</th>
        <th class="wt-75 p-2" scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @if( count($users) == 0)
      <tr>
          <td colspan="8" class="text-center">
              No Users Found
          </td>
      @else
		  @php
			$rec_per_page = Session::get('page_size') ?? 10;
			$startIndex = Request::has('page') ? ((int) Request::get('page') - 1) * (int) $rec_per_page : 0;
		  @endphp
          @foreach($users as $key => $current_user)
              <tr>
                <td scope="row">{{$key + $startIndex + 1}}</td>
                <td>{{$current_user->name}}</td>
                <td>{{$current_user->contact_number ? '+91-'.$current_user->contact_number : 'NA'}}</td>
                <td>{{$current_user->email}}</td>
                <td>{{$current_user->shed}}</td>
                <td>
                  @php
                    $role = '';
                    if( $current_user->hasRole('Admin') ){
                      $role = 'Admin';
                    }else{
                      $role = 'Shed Supervisor';
                    }
                  @endphp
                  {{$role}}
                </td>
                <td>
                  @can('user-management-edit')
                    <a href="{{route('user-edit',$current_user->id)}}" class="btns border-0 btn-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="pl-2">Edit</span></a>
                  @endcan
                </td>
                <td>
                  @can('user-management-delete')
                    <a  id="delete-{{$current_user->id}}" href="#" class="btns border-0 btn-delete"><i class="fa fa-trash" aria-hidden="true"></i><span class="pl-2">Delete</span></a>
                  @endcan
                </td>
              </tr>
          @endforeach
      @endif
    </tbody>
  </table>
</div>

{{ $users->links() }}

</div>
@endsection
@section('scripts')
<script>
  @foreach ($users as $key => $current_user)
    $('#delete-'+{{$current_user->id}}).on('click', function (event) {
        event.preventDefault();
        var url = "{{route('user-delete',$current_user->id)}}";
        swal({
            title: 'Are you sure?',
            text: 'The {{$current_user->name}} user will be permanantly deleted, This action cannot be undone!',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
        }).then(function(value) {
            if (value) {
                window.location.href = url;
            }
        });
    });
  @endforeach
</script>
@endsection