@extends('layouts.finscale')
@section('header')
    <div class="back-arrow"> 
        <a href="{{url('/waste-types')}}"> <img src="{{asset('img/svg/arrow.svg')}}" alt="back-arrow" /> Edit Waste Type</a>
    </div>
@endsection
@section('content')
<form class="needs-validation" method="POST" action="{{ route('waste-type-update') }}">
  @csrf
  <input type="hidden" name="id" value="{{$waste->id}}">
    <div class="min-hight-600">
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationCustom01">Waste Type Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Waste Type Name" name="name" required value="{{$waste->name}}">
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
                        @php
                            $default = $waste_defaults->where('shed',$shed->id)->first();
                            $default_val = !empty($default) ? $default->value : '';
                            $default_type = !empty($default) ? $default->type : '';
                        @endphp
                        <tr>
                            <td>
                                {{$shed->name}}
                            </td>
                            <td>
                                <input type="text" class="form-control" name="default_value[{{$shed->id}}]" value="{{$default_val}}">
                            </td>
                            <td>
                                <select class="form-control" name="default_value_type[{{$shed->id}}]">
                                    @php
                                        $selected = $default_type == 'value' ? 'selected' : '';
                                    @endphp
                                    <option value="value" {{$selected}}>Value</option>
                                    @php
                                        $selected = $default_type == '%' ? 'selected' : '';
                                    @endphp
                                    <option value="%" {{$selected}}>%</option>
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
        <button class="btn bg-green text-white w-15" type="submit">Update</button>
        <a href="{{url('/waste-types')}}"><button type="button" class="btn bg-black text-white w-15" >Cancel</button></a>
    </div>
</form>
@endsection
