@extends('layouts.finscale')
@section('header')
	<div class="back-arrow p-2 bd-highlight"> 
		Weighment Management
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

  <form class="needs-validation" method="GET" action="{{ route('weignment-search') }}">
  @csrf
    <div class="row">  
     <div class="col-lg-2">
        <div class="input-group mb-3">
          @php
            $key = isset($searchkey) ? $searchkey : null;
          @endphp
          <input type="text" class="form-control" placeholder="Search for Employee"  aria-describedby="button-addon2" name="searchkey" value="{{$key}}">
        </div>
     </div>
      <div class="col-lg-2">
          <div class="form-group">
              <select class="form-control" name="branchkey">
                  <option value="">Branch: All</option>
				  @foreach($branches as $branch)
					  @php
						$key = isset($branchkey) ? $branchkey : null;
						$selected = '';
						if($key == $branch->id){
							$selected = 'selected';
						}
					  @endphp
					<option value="{{$branch->id}}" {{$selected}}>{{$branch->name}}</option>
				  @endforeach
              </select>
          </div>
      </div>


      <div class="col-lg-2">
          <div class="form-group">
              <select class="form-control"  name="shedkey">
                  <option value="">Route: All</option>
				  @foreach($sheds as $shed)
					  @php
						$key = isset($shedkey) ? $shedkey : null;
						$selected = '';
						if($key == $shed->id){
							$selected = 'selected';
						}
					  @endphp
					<option value="{{$shed->id}}" {{$selected}}>{{$shed->name}}</option>
				  @endforeach
              </select>
          </div>
      </div>

      <div class="col-lg-2">
          <div class="form-group">
              <input type="date" id="birthday" name="dateKey" class="form-control" value="{{isset($dateKey) ? $dateKey : null}}">
          </div>
      </div>
	  <div class="col-lg-2"> 
		<button class="btn bg-green text-white w-100" type="submit">
			<i class="fa fa-search" aria-hidden="true"></i>
			Search
		</button>
      </div>
      <div class="col-lg-2"> 
        @can('weighment-management-edit')
          <a href="{{url('weignments/create')}}" class="btn bg-green text-white w-100"><i class="fa fa-plus" aria-hidden="true"></i>
          Create</a>
        @endcan 
      </div>
    </div>
  </form>

</div>
@endsection
@section('content')
<div class="container-lg">
  <div class="table-responsive-sm">
  <table class="table">
    <thead class="thead-light">
      <tr>
        <th class="wt-50 p-1" scope="col">S.No</th>
        <th class="wt-500 p-1" scope="col">Date</th>
        <th class="wt-350 p-1" scope="col">Time</th>
        <th class="wt-500 p-1" scope="col">Employee</th>
        <th class="wt-250 p-1" scope="col">Route</th>
        <th class="wt-500 p-1" scope="col">Gross Weight(kg)</th>
        <th class="wt-500 p-1" scope="col">Total Wastage(kg)</th>
        <th class="wt-500 p-1" scope="col">Net Weight(kg)</th>
        <th class="wt-350 p-1" scope="col">Action</th>
        <th class="wt-350 p-1" scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @if( count($weignments) == 0)
      <tr>
          <td colspan="9" class="text-center">
              No Data Found
          </td>
      @else
		  @php
			$rec_per_page = Session::get('page_size') ?? 10;
			$startIndex = Request::has('page') ? ((int) Request::get('page') - 1) * (int) $rec_per_page : 0;
		  @endphp
          @foreach($weignments as $key => $weignment)
              <tr>
                <td scope="row">{{$key + $startIndex + 1}}</td>
                <td>{{Carbon\Carbon::parse($weignment->weignment_date)->format('d-m-Y')}}</td>
                <td>{{Carbon\Carbon::parse($weignment->weignment_date)->format('h:m:s A')}}</td>
                <td>{{$weignment->farmer}}</td>
                <td>{{$weignment->shed}}</td>
                <td>{{$weignment->gross_weight}}</td>
                <td>{{$waste[$weignment->id]}}</td>
                <td>{{$grade[$weignment->id]}}</td>
                <td>
                  @can('shed-management-edit')
                    <a href="{{route('weignment-edit',$weignment->id)}}" class="btns border-0 btn-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="pl-2">Edit</span></a>
                  @endcan
                </td>
                <td>
                  @can('shed-management-delete')
                    <a  id="delete-{{$weignment->id}}" href="#" class="btns border-0 btn-delete"><i class="fa fa-trash" aria-hidden="true"></i><span class="pl-2">Delete</span></a>
                  @endcan
                </td>
              </tr>
          @endforeach
      @endif
    </tbody>
  </table>
</div>

{{ $weignments->links() }}

</div>
@endsection
@section('scripts')
<script>
  @foreach ($weignments as $key => $weignment)
    $('#delete-'+{{$weignment->id}}).on('click', function (event) {
        event.preventDefault();
        var url = "{{route('weignment-delete',$weignment->id)}}";
        swal({
            title: 'Are you sure?',
            text: 'This weignmant will be permanantly deleted, This action cannot be undone!',
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