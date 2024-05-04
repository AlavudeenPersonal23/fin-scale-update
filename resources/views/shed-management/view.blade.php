@extends('layouts.finscale')
@section('header')
	<div class="back-arrow p-2 bd-highlight"> 
		Route Management
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
    <form class="needs-validation" method="GET" action="{{ route('sheds-search') }}">
    @csrf
      <div class="input-group mb-3">
        @php
          $key = isset($searchkey) ? $searchkey : null;
        @endphp
        <input type="text" class="form-control" placeholder="Search for Route Name"  aria-describedby="button-addon2" name="searchkey" value="{{$key}}">
        <div class="input-group-append">
          <button class="btn bg-green text-white" type="submit" id="button-addon2"><i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </form>
   </div>
    <div class="col-lg-2"> 
      @can('shed-management-edit')
        <a href="{{url('sheds/create')}}" class="btn bg-green text-white w-100"><i class="fa fa-plus" aria-hidden="true"></i>
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
        <th class="wt-500 p-2" scope="col">Route Name</th>
        <th class="wt-75 p-2" scope="col">Action</th>
        <th class="wt-75 p-2" scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @if( count($sheds) == 0)
      <tr>
          <td colspan="4" class="text-center">
              No Data Found
          </td>
      @else
		  @php
			$rec_per_page = Session::get('page_size') ?? 10;
			$startIndex = Request::has('page') ? ((int) Request::get('page') - 1) * (int) $rec_per_page : 0;
		  @endphp
          @foreach($sheds as $key => $shed)
              <tr>
                <td scope="row">{{$key + $startIndex + 1}}</td>
                <td>{{$shed->name}}</td>
                <td>
                  @can('shed-management-edit')
                    <a href="{{route('shed-edit',$shed->id)}}" class="btns border-0 btn-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="pl-2">Edit</span></a>
                  @endcan
                </td>
                <td>
                  @can('shed-management-delete')
                    <a  id="delete-{{$shed->id}}" href="#" class="btns border-0 btn-delete"><i class="fa fa-trash" aria-hidden="true"></i><span class="pl-2">Delete</span></a>
                  @endcan
                </td>
              </tr>
          @endforeach
      @endif
    </tbody>
  </table>
</div>

{{ $sheds->links() }}

</div>
@endsection
@section('scripts')
<script>
  @foreach ($sheds as $key => $shed)
    $('#delete-'+{{$shed->id}}).on('click', function (event) {
        event.preventDefault();
        var url = "{{route('shed-delete',$shed->id)}}";
        swal({
            title: 'Are you sure?',
            text: 'The {{$shed->name}} shed will be permanantly deleted, This action cannot be undone!',
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