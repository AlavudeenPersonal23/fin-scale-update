<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		//if(Auth::user()->hasRole('Shed Supervisor')){
		//}else{
			$sheds = DB::table('sheds')->where('status',1)->count();
			$farmers = DB::table('farmers')->where('status',1)->count();
			$vehicles = DB::table('vehicles')->where('status',1)->count();
			$wastes = DB::table('waste_types')->where('status',1)->count();
			$users = DB::table('users')->where('status',1)->count();
			
			$now = Carbon::now();
			$todayWeighment = DB::table('weignments')->whereBetween('weignment_date',[$now->startOfDay()->toDateTimeString(),$now->endOfDay()->toDateTimeString()])->pluck('id')->toArray();
			$todayWeighments = DB::table('weignment_grades')->whereIn('weignment',$todayWeighment)->sum('weight');
			
			$now = Carbon::now();
			$weekStartDate = $now->startOfWeek()->toDateString();
			$weekEndDate = $now->endOfWeek()->toDateString();
			$currentWeekWeignmentsData = DB::table('weignments')->whereBetween('weignment_date',[$weekStartDate,$weekEndDate])->get();
			$currentWeekWeignments = [];
			foreach($currentWeekWeignmentsData as $currentWeekWeignment){
				$weignment_date = Carbon::parse($currentWeekWeignment->weignment_date)->toDateString();
				$net_weights = DB::table('weignment_grades')->where('weignment',$currentWeekWeignment->id)->get();
				foreach($net_weights as $net_weight){
					if(array_key_exists($weignment_date,$currentWeekWeignments)){
						$currentWeekWeignments[$weignment_date] += $net_weight->weight;
					}else{
						$currentWeekWeignments[$weignment_date] = $net_weight->weight;
					}
				}
			}

			$now = Carbon::now();
			$monthStartDate = $now->firstOfMonth()->toDateString();
			$monthEndDate = $now->lastOfMonth()->toDateString();
			$currentMonthWeignmentsData = DB::table('weignments')->whereBetween('weignment_date',[$monthStartDate,$monthEndDate])->get();
			$currentMonthWeignments = [];
			foreach($currentMonthWeignmentsData as $currentMonthWeignment){
				$weignment_date = Carbon::parse($currentMonthWeignment->weignment_date)->toDateString();
				$net_weights = DB::table('weignment_grades')->where('weignment',$currentMonthWeignment->id)->get();
				foreach($net_weights as $net_weight){
					if(array_key_exists($weignment_date,$currentMonthWeignments)){
						$currentMonthWeignments[$weignment_date] += $net_weight->weight;
					}else{
						$currentMonthWeignments[$weignment_date] = $net_weight->weight;
					}
				}
			}

			$currentYearWeignmentsData = DB::table('weignments')->whereYear('weignment_date',$now->year)->get();
			$currentYearWeignments = [];
			foreach($currentYearWeignmentsData as $currentMonthWeignment){
				$weignment_date = Carbon::parse($currentMonthWeignment->weignment_date)->format('M');
				$net_weights = DB::table('weignment_grades')->where('weignment',$currentMonthWeignment->id)->get();
				foreach($net_weights as $net_weight){
					if(array_key_exists($weignment_date,$currentYearWeignments)){
						$currentYearWeignments[$weignment_date] += $net_weight->weight;
					}else{
						$currentYearWeignments[$weignment_date] = $net_weight->weight;
					}
				}
			}
			
			$users = User::where('status',1)->get();
			$admin = 0;
			$supervisor = 0;
			foreach($users as $user){
				if($user->hasRole('Admin')){
					$admin++;
				}
				if($user->hasRole('Shed Supervisor')){
					$supervisor++;
				}
			}
		//}
        return view('home',compact('sheds','farmers','vehicles','wastes','users','todayWeighments','currentWeekWeignments','currentMonthWeignments','currentYearWeignments','admin','supervisor'));
    }
}
