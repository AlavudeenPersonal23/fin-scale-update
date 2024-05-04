<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function passwordReset()
    {
        return view('password-reset');
    }
	
	public function passwordReseting(Request $request){
		DB::table('users')->where('id',Auth::user()->id)->update([
			'password' => bcrypt($request->password)
		]);
		
		return redirect('/dashboard')->with('success','Password Changed Successfully');
	}
	
	public function setPageSize($page_size){
		Session::put('page_size',$page_size);
		return back();
	}
}
