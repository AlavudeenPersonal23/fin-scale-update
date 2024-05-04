@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/waste-types')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Create Waste Type</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('waste-type-save') }}">
  @csrf
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationCustom01">Waste Type Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Waste Type Name" name="name" required>
                @error('name')
                  <div class="invalid-feedback">
                      Enter The Waste Type Name
                  </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <div class="table-responsive-sm">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                        <th>Shed</th>
                        <th>Value</th>
                        <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                  @if( count($sheds) == 0)
                  <tr>
                      <td colspan="4" class="text-center">
                          No Sheds Found
                      </td>
                  @else
                    @foreach($sheds as $shed)
                        <tr>
                            <td>
                                {{$shed->name}}
                            </td>
                            <td>
                                <input type="text" class="form-control" name="default_value[{{$shed->id}}]">
                            </td>
                            <td>
                                <select class="form-control" name="default_value_type[{{$shed->id}}]">
                                    <option value="value" selected>Value</option>
                                    <option value="%">%</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                  @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    <div class="form-footer-fixed position-sticky ">
        <button class="btn bg-green text-white w-15" type="submit">Create</button>
        <a href="{{url('/waste-types')}}"><button type="button" class="btn bg-black text-white w-15" >Cancel</button></a>
    </div>
</form>
@endsection
