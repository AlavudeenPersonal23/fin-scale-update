<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Auth;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function __construct() {
        auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function getUser(){
        $user = User::where('users.id',Auth::user()->id)
			->join('user_additional_info','user_additional_info.user_id','=','users.id')
			->join('sheds','sheds.id','=','user_additional_info.shed')
			->select('users.*','user_additional_info.*','sheds.name as shed','users.id as id')
			->first();
		 return response()->json($user);
	}
	public function getTodayWeignments(){
		$now = Carbon::now();
		$todayWeighments = DB::table('weignments')->where('created_by',Auth::user()->id)->whereBetween('weignment_date',[$now->startOfDay()->toDateTimeString(),$now->endOfDay()->toDateTimeString()])->count();
		return response()->json(['weighment' => $todayWeighments]);
	}
	public function getPendingWeignments(){
		return response()->json($this->pendingWeighment());
	}
	public function pendingWeighment(){
       $weignments = DB::table('weignments') 
            ->join('sheds','sheds.id','=','weignments.shed')
            ->join('company','company.id','=','weignments.company')
            ->join('farmers','farmers.id','=','weignments.farmer')
			->where('weignments.created_by',Auth::user()->id)
			->select('weignments.gross_weight','weignments.weignment_date','farmers.name as farmer','sheds.name as shed', 'company.name as company',
				'weignments.id as id')
			->orderBy('weignments.weignment_date','DESC')
			->get();
		$data = [];
		foreach($weignments as $weignment){
			if(sizeof($data) != 2){
				$waste = DB::table('weignment_wastages')->where('weignment',$weignment->id)->sum('weight');
				$grade = DB::table('weignment_grades')->where('weignment',$weignment->id)->sum('weight');
				
				if(($waste == 0)||($grade == 0)){
					$data[] = $this->getWeignmentData($weignment->id);
				}
			}
		}
		
		return $data;
	}
	public function getWeignment($id){
		return response()->json($this->getWeignmentData($id));
	}
	public function getWeignmentData($id){
       $weignments = DB::table('weignments') 
            ->join('sheds','sheds.id','=','weignments.shed')
            ->join('company','company.id','=','weignments.company')
            ->join('farmers','farmers.id','=','weignments.farmer')
			->where('weignments.created_by',Auth::user()->id)
			->where('weignments.id',$id)
			->select(
				'weignments.gross_weight',
				'weignments.weignment_date',
				'farmers.name as farmer',
				'farmers.id as farmer_id',
				'farmers.member_id as farmer_member_id',
				'sheds.name as shed',
				'sheds.id as shed_id',
				'company.name as company',
				'company.id as company_id',
				'weignments.id as id'
			)
			->orderBy('weignments.weignment_date','DESC')
			->first();
		if($weignments){
			$weignments->rcpt_no = $weignments->farmer_member_id.$weignments->id;
		}
		$data['weighment'] = $weignments;

		$wastes = DB::table('weignment_wastages')->where('weignment',$id)
				->join('waste_types','waste_types.id','=','weignment_wastages.waste')
				->select(
					'waste_types.name',
					'waste_types.id as waste_id',
					'weignment_wastages.weight'
				)
				->get();
		$data['wastes'] = $wastes;		
				
		$grades = DB::table('weignment_grades')->where('weignment',$id)
				->join('grades','grades.id','=','weignment_grades.grade')
				->select(
					'grades.name',
					'grades.id as grade_id',
					'weignment_grades.weight'
				)
				->get();
		$data['grades'] = $grades;		
		
		return $data;
	}
	public function getVehicles(){
		$data = DB::table('vehicles')->where('status',1)->get();
		return response()->json($data);
	}
	public function getSheds(){
		$data = DB::table('sheds')->where('status',1)->get();
		return response()->json($data);
	}
	public function getWastes(){
		$data = DB::table('waste_types')->where('status',1)->get();
		return response()->json($data);
	}
	public function getWastesDefaults($id){
		$data = DB::table('table_waste_types_default')->where('waste_type',$id)->get();
		return response()->json($data);
	}
	public function getWastesDefaultsByShed($id){
		$data = DB::table('table_waste_types_default')
				->join('waste_types','waste_types.id','=','table_waste_types_default.waste_type')
				->where('shed',$id)
				->select('table_waste_types_default.*','waste_types.name')
				->get();
		return response()->json($data);
	}
	public function getFarmers(Request $request){
		$shed = DB::table('user_additional_info')->where('user_id',Auth::user()->id)->value('shed');
		if($shed){
			$data = DB::table('farmers')->where('farmers.status',1)->where('farmers.shed',$shed)
				->join('company','company.id','=','farmers.company');
			if($request->has('search') && $request->search != ''){
				$data = $data->where('farmers.name','LIKE',"%".$request->search."%");
			}
			$data = $data->select('farmers.*','company.name as company_name')->get();
			return response()->json($data);
		}else{
			return response()->json(['error' => 'Shed is not assigned for the you, indly ask the admin to assign a shed for you'], 404);
		}
	}
	public function getGrades(){
		$data = DB::table('grades')->where('status',1)->get();
		return response()->json($data);
	}
	public function createWeignment(Request $request){
		if(count($this->pendingWeighment()) >= 2){
			if(!$request->has('waste') || !$request->has('grade')){
				return response()->json(['message' => 'You need to complete atleast one of two pending weighment inorder to create new weighment'], 412);
			}
		}
		
		$check = DB::table('api_requests')
				->where('requested_by',Auth::user()->id)
				->first();
		$new_request_data = json_encode($request->all());

		if($check){
			$last_request_diff = Carbon::parse($check->requested_at)->diffInSeconds(Carbon::now());
			
			DB::table('api_requests')
				->where('requested_by', Auth::user()->id)
				->update([
					'options' => $new_request_data,
					'requested_at' => Carbon::now()
				]);

			if($last_request_diff < 20 && $new_request_data == $check->options){
				return response()->json(['message' => 'Weighment Updated Successfully', 'data' => []], 200);
			}
			
		} else {
			DB::table('api_requests')->insert([
				'options' => $new_request_data,
				'requested_by' => Auth::user()->id,
				'requested_at' => Carbon::now()
			]);
		}
		
		$company = DB::table('farmers')->where('id',$request->farmer)->value('company');
		$data = [
			"company" => $company,
			"shed" => $request->shed ?? "",
			"farmer" => $request->farmer ?? "",
			"created_by" => Auth::user()->id,
			"gross_weight" => round($request->gross_weight, 3) ?? 0,
			"bag_count" => $request->bag_count ?? 1,
			"weignment_date" => Carbon::parse($request->weignment_date)->toDateTimeString(),
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];
        $weighment = DB::table('weignments')->insertGetId($data);
		$data = [];		
		$wastes = $request->waste;
		if($wastes){
			foreach($wastes as $key => $waste){
				if( !empty($waste) && !empty($waste['waste']) && !empty($waste['value'])){
					$data = [
						"weignment" => $weighment,
						"waste" => $waste['waste'],
						"weight" => round($waste['value'], 3),
						"created_at" => Carbon::now(),
						"updated_at" => Carbon::now()
					];
					DB::table('weignment_wastages')->insert($data);
					$data = [];
				}
			}
		}
		
		$grades = $request->grade;
		if($grades){
			foreach($grades as $key => $grade){
				if( !empty($grade) && !empty($grade['grade']) && !empty($grade['value'])){
					$data = [
						"weignment" => $weighment,
						"grade" => $grade['grade'],
						"weight" => round($grade['value'], 3),
						"created_at" => Carbon::now(),
						"updated_at" => Carbon::now()
					];
					DB::table('weignment_grades')->insert($data);
					$data = [];
				}
			}
		}
		return response()->json(['message' => 'Weighment Created Successfully', 'id' => $weighment, 'data' => $this->getWeignmentData($weighment)], 200);
	}

	public function updateWeighment(Request $request){
		$company = DB::table('farmers')->where('id',$request->farmer)->value('company');
		$data = [
			"company" => $company,
			"shed" => $request->shed ?? "",
			"farmer" => $request->farmer ?? "",
			"gross_weight" => round($request->gross_weight, 3) ?? 0,
			"weignment_date" => Carbon::parse($request->weignment_date)->toDateTimeString(),
			"bag_count" => $request->bag_count ?? 1,
			"updated_at" => Carbon::now()
		];
        $weighment = DB::table('weignments')->where('id',$request->id)->update($data);
		$data = [];
		
		DB::table('weignment_wastages')->where('weignment',$request->id)->delete();
		$wastes = $request->waste;
		foreach($wastes as $key => $waste){
			if( !empty($waste) && !empty($waste['waste']) && !empty($waste['value'])){
				$data = [
					"weignment" => $request->id,
					"waste" => $waste['waste'],
					"weight" => round($waste['value'], 3),
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
				DB::table('weignment_wastages')->insert($data);
				$data = [];
			}
		}
		
		DB::table('weignment_grades')->where('weignment',$request->id)->delete();
		$grades = $request->grade;
		foreach($grades as $key => $grade){
			if( !empty($grade) && !empty($grade['grade']) && !empty($grade['value'])){
				$data = [
					"weignment" => $request->id,
					"grade" => $grade['grade'],
					"weight" => round($grade['value'], 3),
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
				DB::table('weignment_grades')->insert($data);
				$data = [];
			}
		}
		
		return response()->json(['message' => 'Weighment Updated Successfully', 'data' => $this->getWeignmentData($request->id)], 200);
	}
	public function summaryReport(Request $request)
	{
		if($request->has('date')){
			$date = Carbon::parse($request->date)->toDateString();
			$weignments = DB::table('weignments')
					->where('created_by',Auth::user()->id)
					->whereDate('created_at',$date)->get();
			$data = [];
			$data['Total Receipts'] = count($weignments);
			$data['Total Bags'] = $weignments->sum('bag_count');
			$data['Total Gross'] = $weignments->sum('gross_weight');

			$wastes = DB::table('weignment_wastages')->whereIn('weignment',$weignments->pluck('id')->toArray())->get()->sum('weight');
			$data['Net Gross'] = $data['Total Gross'] - $wastes;
			$data['Total Wastes'] = $wastes;
	
			return response()->json(['data' => $data], 200);
		}else{
			return response()->json(['error' => 'date is required to generate report'], 404);	
		}
	}
	public function detailedReport(Request $request)
	{
		if($request->has('date')){
			$date = Carbon::parse($request->date)->toDateString();
			$weignments = DB::table('weignments')
					->where('created_by',Auth::user()->id)
					->whereDate('created_at',$date)->get();
			$wastes = DB::table('weignment_wastages')->whereIn('weignment',$weignments->pluck('id')->toArray())->get();
			$farmers = DB::table('farmers')->whereIn('id',$weignments->pluck('farmer')->toArray())->get();

			$data = [];
			foreach($weignments as $weignment){
				$temp = [];

				$temp['Member Code'] = $farmers->where('id',$weignment->farmer)->first()->member_id;
				$temp['Gross Weight'] = $weignment->gross_weight;
				$temp['No Of Bags'] = $weignment->bag_count;
				$temp['Net Weight'] = $wastes->where('weignment',$weignment->id)->sum('weight');;

				$data[] = $temp;
			} 

			return response()->json(['data' => $data], 200);
		}else{
			return response()->json(['error' => 'date is required to generate report'], 404);	
		}
	}
	public function weignmentHistory(Request $request){
		if($request->has('from_date') && $request->has('to_date')){
			$from_date = Carbon::parse($request->from_date)->startOfDay();
			$to_date = Carbon::parse($request->to_date)->endOfDay();
			$weignments = DB::table('weignments')
					->where('weignments.created_by',Auth::user()->id)
					->whereBetween('weignments.created_at',[$from_date,$to_date])
		            ->join('sheds','sheds.id','=','weignments.shed')
		            ->join('farmers','farmers.id','=','weignments.farmer')
					->where('weignments.created_by',Auth::user()->id)
					->select(
						'weignments.gross_weight',
						'weignments.weignment_date',
						'farmers.name as farmer',
						'farmers.id as farmer_id',
						'farmers.member_id as farmer_member_id',
						'sheds.name as shed',
						'sheds.id as shed_id',
						'weignments.id as id'
					)
					->orderBy('weignments.weignment_date','DESC')
					->get();

			$wastes = DB::table('weignment_wastages')
						->whereIn('weignment_wastages.weignment',$weignments->pluck('id')->toArray())
						->join('waste_types','waste_types.id','=','weignment_wastages.waste')
						->select('weignment_wastages.*','waste_types.name as waste_name')
						->get();

			$data = [];

			foreach($weignments as $weignment){
				$temp = [];
				$temp['weignments'] = $weignment;
				$temp['wastes'] = $wastes->where('weignment',$weignment->id);
				$data[$weignment->id] = $temp;
			}

			return response()->json(['data' => $data], 200);
		}else{
			return response()->json(['error' => 'both from_date and to_date are required to generate history'], 404);	
		}
	}
}
