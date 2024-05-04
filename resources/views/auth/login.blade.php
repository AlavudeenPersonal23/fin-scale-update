@extends('layouts.app')

@section('content')
<div class="row p-0">
    <div class="col-lg-6">
        <div class="logo-top" style="margin-top: 90px !important;margin-left: 150px !important;">
			<img src="{{ asset('img/logo.png') }}" alt="logo"/>
		</div>
        <div class="mt-5" style="margin-left: 150px !important;margin-right: 30px !important;">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-row mt-5">
                   <div class="col-lg-12 login-title"><h3>Login</h3></div> 
                   <div class="col-lg-12 mb-3"> <div class="text-gary font-14">Please enter your details below!</div></div>
                    <div class="col-md-12 mb-3">
                        <label for="email">User Name</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >
                       
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" >
                        
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-12 pb-2">
                        <div class="form-check form-check-inline">
                           <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember Me</label>
                          </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn bg-green text-white w-100">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <img src="./img/login.png" alt="login img" class="hs-100"/>
    </div>
</div>
@endsection
