@extends('layouts.finscale')
@section('header')
	<div class="back-arrow p-2 bd-highlight"> 
		Employee Management
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
    <form class="needs-validation" method="GET" action="{{ route('farmer-search') }}">
    @csrf
      <div class="input-group mb-3">
        @php
          $key = isset($searchkey) ? $searchkey : null;
        @endphp
        <input type="text" class="form-control" placeholder="Search for Employee by name or mobile number"  aria-describedby="button-addon2" name="searchkey" value="{{$key}}">
        <div class="input-group-append">
          <button class="btn bg-green text-white" type="submit" id="button-addon2"><i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </form>
   </div>
    <div class="col-lg-2"> 
      @can('farmer-management-edit')
        <a href="{{url('farmers/create')}}" class="btn bg-green text-white w-100"><i class="fa fa-plus" aria-hidden="true"></i>
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
        <th class="wt-100 p-2" scope="col">Emp ID</th>
        <th class="wt-300 p-2" scope="col">Emp Name</th>
        <th class="wt-300 p-2" scope="col">Route</th>
        <th class="wt-300 p-2" scope="col">Branch</th>
        <th class="wt-300 p-2" scope="col">Mobile Number</th>
        <th class="wt-200 p-2" scope="col">Action</th>
        <th class="wt-200 p-2" scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @if( count($farmers) == 0)
      <tr>
          <td colspan="8" class="text-center">
              No Members Found
          </td>
      @else
		  @php
			$rec_per_page = Session::get('page_size') ?? 10;
			$startIndex = Request::has('page') ? ((int) Request::get('page') - 1) * (int) $rec_per_page : 0;
		  @endphp
          @foreach($farmers as $key => $farmer)
              <tr>
                <td scope="row">{{$key + $startIndex + 1}}</td>
                <td>{{$farmer->member_id}}</td>
                <td>{{$farmer->name}}</td>
                <td>{{$farmer->shed}}</td>
                <td>{{$farmer->company}}</td>
                <td>{{$farmer->contact_number ? '+91-'.$farmer->contact_number : 'NA'}}</td>
                <td>
                  @can('farmer-management-edit')
                    <a href="{{route('farmer-edit',$farmer->id)}}" class="btns border-0 btn-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="pl-2">Edit</span></a>
                  @endcan
                </td>
                <td>
                  @can('farmer-management-delete')
                    <a  id="delete-{{$farmer->id}}" href="#" class="btns border-0 btn-delete"><i class="fa fa-trash" aria-hidden="true"></i><span class="pl-2">Delete</span></a>
                  @endcan
                </td>
              </tr>
          @endforeach
      @endif
    </tbody>
  </table>
</div>

{{ $farmers->links() }}

</div>
@endsection
@section('scripts')
<script>
  @foreach ($farmers as $key => $farmer)
    $('#delete-'+{{$farmer->id}}).on('click', function (event) {
        event.preventDefault();
        var url = "{{route('farmer-delete',$farmer->id)}}";
        swal({
            title: 'Are you sure?',
            text: 'The {{$farmer->name}} farmer will be permanantly deleted, This action cannot be undone!',
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