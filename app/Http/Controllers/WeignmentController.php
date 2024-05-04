<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;

class WeignmentController extends Controller
{
    const TABLE = 'weignments';
    const WEIGNMENTTABLE = 'weignment_wastages';
    const GRADETABLE = 'weignment_grades';

    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $sheds = DB::table('sheds')->where('status',1)->get();
        $branches = DB::table('company')->where('status',1)->get();
       $weignments = DB::table(self::TABLE) 
            //->join(self::WEIGNMENTTABLE,self::WEIGNMENTTABLE.'.weignment','=',self::TABLE.'.id')
            //->join(self::GRADETABLE,self::GRADETABLE.'.weignment','=',self::TABLE.'.id')
            ->join('sheds','sheds.id','=',self::TABLE.'.shed')
            ->join('company','company.id','=',self::TABLE.'.company')
            ->join('farmers','farmers.id','=',self::TABLE.'.farmer');

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $weignments = $weignments->where(self::TABLE.'.created_by',Auth::user()->id);
        }

        $weignments = $weignments
            ->select(self::TABLE.'.gross_weight',self::TABLE.'.weignment_date','farmers.name as farmer','sheds.name as shed', 'company.name as company',
				//DB::raw('SUM('.self::WEIGNMENTTABLE.'.weight) as wastage'),DB::raw('SUM('.self::GRADETABLE.'.weight) as grade'),
				self::TABLE.'.id as id')
            //->groupBy(self::TABLE.'.id',self::WEIGNMENTTABLE.'.weignment',self::GRADETABLE.'.weignment')
            ->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);
		$waste = [];
		$grade = [];
		foreach($weignments as $weignment){
			$waste[$weignment->id] = DB::table(self::WEIGNMENTTABLE)->where('weignment',$weignment->id)->sum('weight');
			$grade[$weignment->id] = $weignment->gross_weight - $waste[$weignment->id];
		}

        return view('weignment-management/view',compact('weignments','waste','grade','sheds','branches'));
    }

    public function create(){
        $sheds = DB::table('sheds')->where('status',1)->get();
        $companies = DB::table('company')->where('status',1)->get();
        $farmers = DB::table('farmers')->where('status',1)->get();
        $wastes = DB::table('waste_types')->where('status',1)->get();
        $grades = DB::table('grades')->where('status',1)->get();
        return view('weignment-management/create',compact('sheds','companies','farmers','wastes','grades'));
    }

    public function save(Request $request){
		$company = DB::table('farmers')->where('id',$request->farmer)->value('company');
		$data = [
			"company" => $company,
			"shed" => $request->shed ?? "",
			"farmer" => $request->farmer ?? "",
			"created_by" => Auth::user()->id,
			"gross_weight" => round($request->gross_weight, 3) ?? 0,
			"weignment_date" => Carbon::parse($request->weignment_date)->toDateTimeString(),
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];
        $weighment = DB::table(self::TABLE)->insertGetId($data);
		$data = []; 
		
		$wastes = $request->waste;
		$waste_weights = $request->waste_weight;
		
		foreach($wastes as $key => $waste){
			if( !empty($waste) && !empty($waste_weights[$key])){
				$data = [
					"weignment" => $weighment,
					"waste" => $waste,
					"weight" => round($waste_weights[$key], 3),
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
			}
			DB::table(self::WEIGNMENTTABLE)->insert($data);
			$data = []; 
		}
		
		/*$grades = $request->grade;
		$grade_weights = $request->grade_weight;
		
		foreach($grades as $key => $grade){
			if( !empty($grade) && !empty($grade_weights[$key])){
				$data = [
					"weignment" => $weighment,
					"grade" => $grade,
					"weight" => round($grade_weights[$key], 3),
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
			}
			DB::table(self::GRADETABLE)->insert($data);
			$data = []; 
		}*/

        return redirect()->route('weignment-index')->with('success', 'Weignment Created Successfully');
    }

    public function edit($id){
        $sheds = DB::table('sheds')->where('status',1)->get();
        $companies = DB::table('company')->where('status',1)->get();
        $farmers = DB::table('farmers')->where('status',1)->get();
        $wastes = DB::table('waste_types')->where('status',1)->get();
        $grades = DB::table('grades')->where('status',1)->get();
		$weighment = DB::table(self::TABLE)->where(self::TABLE.'.id',$id)->first();
		$weighmentwastes = DB::table(self::WEIGNMENTTABLE)->where('weignment',$id)->get();
		$weighmentgrades = DB::table(self::GRADETABLE)->where('weignment',$id)->get();
		
        return view('weignment-management/edit',compact('sheds','companies','farmers','wastes','grades','weighment','weighmentwastes','weighmentgrades'));
    }

    public function update(Request $request){
		$company = DB::table('farmers')->where('id',$request->farmer)->value('company');
		$data = [
			"company" => $company,
			"shed" => $request->shed ?? "",
			"farmer" => $request->farmer ?? "",
			"gross_weight" => round($request->gross_weight, 3) ?? 0,
			"weignment_date" => Carbon::parse($request->weignment_date)->toDateTimeString(),
			"updated_at" => Carbon::now()
		];
        $weighment = DB::table(self::TABLE)->where('id',$request->id)->update($data);
		$data = []; 
		
		$wastes = $request->waste;
		$waste_weights = $request->waste_weight;
		DB::table(self::WEIGNMENTTABLE)->where('weignment',$request->id)->delete();
		foreach($wastes as $key => $waste){
			if( !empty($waste) && !empty($waste_weights[$key])){
				$data = [
					"weignment" => $request->id,
					"waste" => $waste,
					"weight" => round($waste_weights[$key], 3),
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
				DB::table(self::WEIGNMENTTABLE)->insert($data);
				$data = []; 
			}
		}
		
		/*$grades = $request->grade;
		$grade_weights = $request->grade_weight;
		DB::table(self::GRADETABLE)->where('weignment',$request->id)->delete();
		foreach($grades as $key => $grade){
			if( !empty($grade) && !empty($grade_weights[$key])){
				$data = [
					"weignment" => $request->id,
					"grade" => $grade,
					"weight" => round($grade_weights[$key], 3),
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
				DB::table(self::GRADETABLE)->insert($data);
				$data = []; 
			}
		}*/

        return redirect()->route('weignment-index')->with('success', 'Weignment Updated Successfully');
    }

    public function delete($id){
        DB::table(self::TABLE)->where('id',$id)->delete();

        return redirect()->route('weignment-index')->with('success', 'Weignment Deleted Successfully');
    }
    public function search(Request $request){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $sheds = DB::table('sheds')->where('status',1)->get();
        $branches = DB::table('company')->where('status',1)->get();
       $weignments = DB::table(self::TABLE)
            //->join(self::WEIGNMENTTABLE,self::WEIGNMENTTABLE.'.weignment','=',self::TABLE.'.id')
            //->join(self::GRADETABLE,self::GRADETABLE.'.weignment','=',self::TABLE.'.id')
			->join('sheds','sheds.id','=',self::TABLE.'.shed')
            ->join('company','company.id','=',self::TABLE.'.company')
            ->join('farmers','farmers.id','=',self::TABLE.'.farmer');
			
		if($request->has('searchKey') && $request->searchKey != ''){
			$weignments = $weignments->where('farmers.name','like','%'.$request->searchKey.'%');
		}

		if($request->has('dateKey') && $request->dateKey != ''){
			$weignments = $weignments->whereDate(self::TABLE.'.weignment_date',$request->dateKey);
		}
		if($request->has('branchkey') && $request->branchkey != ''){
			$weignments = $weignments->where('company.id',$request->branchkey);
		}
		if($request->has('shedkey') && $request->shedkey != ''){
			$weignments = $weignments->where('sheds.id',$request->shedkey);
		}

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $weignments = $weignments->where(self::INFOTABLE.'.created_by',Auth::user()->id);
        }

        $weignments = $weignments
            ->select(self::TABLE.'.gross_weight',self::TABLE.'.weignment_date','farmers.name as farmer','sheds.name as shed', 'company.name as company',
				//DB::raw('SUM('.self::WEIGNMENTTABLE.'.weight) as wastage'),DB::raw('SUM('.self::GRADETABLE.'.weight) as grade'),
				self::TABLE.'.id as id')
            //->groupBy(self::TABLE.'.id',self::WEIGNMENTTABLE.'.weignment',self::GRADETABLE.'.weignment')
            ->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);
		$waste = [];
		$grade = [];
		foreach($weignments as $weignment){
			$waste[$weignment->id] = DB::table(self::WEIGNMENTTABLE)->where('weignment',$weignment->id)->sum('weight');
			$grade[$weignment->id] = DB::table(self::GRADETABLE)->where('weignment',$weignment->id)->sum('weight');
		}

		if( Session::get('weighment.key') && empty($request->key) || Session::get('weighment.key') == $request->key){
			$key = Session::get('weighment.key');
		}else{
			Session::put('weighment.key',$request->key);
			$key = $request->key;
		}

		if( Session::get('weighment.branchkey') && empty($request->branchkey) || Session::get('weighment.branchkey') == $request->branchkey){
			$branchkey = Session::get('weighment.branchkey');
		}else{
			Session::put('weighment.branchkey',$request->branchkey);
			$branchkey = $request->branchkey;
		}

		if( Session::get('weighment.shedkey') && empty($request->shedkey) || Session::get('weighment.shedkey') == $request->shedkey){
			$shedkey = Session::get('weighment.shedkey');
		}else{
			Session::put('weighment.shedkey',$request->shedkey);
			$shedkey = $request->shedkey;
		}

		if( Session::get('weighment.dateKey') && empty($request->dateKey) || Session::get('weighment.dateKey') == $request->dateKey){
			$dateKey = Session::get('weighment.dateKey');
		}else{
			Session::put('weighment.dateKey',$request->dateKey);
			$dateKey = $request->dateKey;
		}

        return view('weignment-management/view',compact('weignments','waste','grade','sheds','branches','key','branchkey','shedkey','dateKey'));
    }
}
