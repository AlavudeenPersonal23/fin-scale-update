<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use PDF;

class ReportController extends Controller
{
	private $slip_report;
    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }
	
	public function index($report){
		$sheds = DB::table('sheds')->where('status',1)->get();
		$grades = DB::table('grades')->where('status',1)->get();
		
		return view('reports.index',compact('sheds','grades','report'));
	}
	
	public function newIndex(){
		$sheds = DB::table('sheds')->where('status',1)->get();
		
		return view('reports.new_index',compact('sheds'));
	}
	
	public function getNewIndex(Request $request){
		$weighment = DB::table('weignments');
		if($request->has('daterange') && $request->daterange){
			$dates = explode(" - ",$request->daterange);
			if($dates[0] != $dates[1]){
				$weighment = $weighment->whereDate('weignment_date','>=',carbon::createFromFormat('d/m/Y', $dates[0]));
				$weighment = $weighment->whereDate('weignment_date','<=',carbon::createFromFormat('d/m/Y', $dates[1]));
			}else{
				$weighment = $weighment->whereDate('weignment_date','>=',carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->toDateTimeString());
				$weighment = $weighment->whereDate('weignment_date','<=',carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->toDateTimeString());
			}
		}
		if($request->has('shed') && $request->shed){
			$weighment->where('shed',$request->shed);
		}
		if($request->has('farmer') && $request->farmer){
			$farmers = DB::table('farmers')->where('farmers.name','LIKE',"%".$request->farmer."%")->pluck('id')->toArray();
			$weighment->whereIn('farmer',$farmers);
		}
		$weighments = $weighment->orderBy('weignment_date')->get();
		
		$sheds = DB::table('sheds')->where('status',1)->get();
		$farmers = DB::table('farmers')->where('status',1)->select('name','id','member_id')->get();
		$weighment_wastes = DB::table('weignment_wastages')->whereIn('weignment',$weighments->pluck('id'))->get();
		$wastes = DB::table('waste_types')->whereIn('id',$weighment_wastes->unique('waste')->pluck('waste'))->orderBy('name','ASC')->get();
		$report_data = [];
		foreach($weighments as $weighment){
			try{
				$current_wastes = $weighment_wastes->where('weignment',$weighment->id);

				$temp = [];
				$temp['Member No'] = $farmers->where('id',$weighment->farmer)->first()->member_id;
				$temp['Net Kgs'] = $weighment->gross_weight - $current_wastes->sum('weight');
				$temp['Member Name'] = $farmers->where('id',$weighment->farmer)->first()->name;
				$temp['Gross'] = $weighment->gross_weight;
				foreach($wastes as $waste){
					$temp[$waste->name] = $current_wastes->where('waste',$waste->id)->sum('weight');
				}
				$temp['Bag'] = $weighment->bag_count;
				$temp['Rcpt No'] = $temp['Member No'].$weighment->id;
				$temp['Route'] = $sheds->where('id',$weighment->shed)->first()->name;

				$report_data[] = $temp;
			} catch (\Exception $e){
				$temp = [];
			}
		}
		$requestData = (object)$request->all();

		return view('reports.new_report',compact('report_data','sheds','wastes','requestData'));
	}
	
	public function newIndexTwo(){
		$sheds = DB::table('sheds')->where('status',1)->get();
		
		return view('reports.new_index_two',compact('sheds'));
	}
	
	public function getNewIndexTwo(Request $request){
		$weighment = DB::table('weignments');
		if($request->has('daterange') && $request->daterange){
			$dates = explode(" - ",$request->daterange);
			if($dates[0] != $dates[1]){
				$weighment = $weighment->whereDate('weignment_date','>=',carbon::createFromFormat('d/m/Y', $dates[0]));
				$weighment = $weighment->whereDate('weignment_date','<=',carbon::createFromFormat('d/m/Y', $dates[1]));
			}else{
				$weighment = $weighment->whereDate('weignment_date','>=',carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->toDateTimeString());
				$weighment = $weighment->whereDate('weignment_date','<=',carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->toDateTimeString());
			}
		}
		if($request->has('shed') && $request->shed){
			$weighment->where('shed',$request->shed);
		}
		if($request->has('farmer') && $request->farmer){
			$farmers = DB::table('farmers')->where('farmers.name','LIKE',"%".$request->farmer."%")->pluck('id')->toArray();
			$weighment->whereIn('farmer',$farmers);
		}
		$weighments = $weighment->orderBy('weignment_date')->get();
		
		$sheds = DB::table('sheds')->where('status',1)->get();
		$farmers = DB::table('farmers')->where('status',1)->select('name','id','member_id')->get();
		$weighment_wastes = DB::table('weignment_wastages')->whereIn('weignment',$weighments->pluck('id'))->get();
		$wastes = DB::table('waste_types')->whereIn('id',$weighment_wastes->unique('waste')->pluck('waste'))->orderBy('name','DESC')->get();
		$report_data = [];
		$i=1;
		foreach($weighments as $weighment){
			try{
				$current_wastes = $weighment_wastes->where('weignment',$weighment->id);

				$temp = [];
				$temp['S.No'] = $i;
				$temp['Member No'] = $farmers->where('id',$weighment->farmer)->first()->member_id;
				$temp['Party Name'] = $farmers->where('id',$weighment->farmer)->first()->name;
				$temp['GL Receipt No'] = $temp['Member No'].$weighment->id;
				$temp['No of Bags'] = $weighment->bag_count;
				$temp['Gross'] = $weighment->gross_weight;
				foreach($wastes as $waste){
					$temp[$waste->name] = $current_wastes->where('waste',$waste->id)->sum('weight');
				}
				$temp['Net Kgs'] = $weighment->gross_weight - $current_wastes->sum('weight');

				$report_data[] = $temp;
				$i++;
			} catch (\Exception $e){
				$temp = [];
			}
		}
		$requestData = (object)$request->all();

		return view('reports.new_report_two',compact('report_data','sheds','wastes','requestData'));
	}
	
	public function getReport(Request $request){
		$weighment = DB::table('weignments');
		if($request->has('daterange') && $request->daterange){
			$dates = explode(" - ",$request->daterange);
			if($dates[0] != $dates[1]){
				$weighment = $weighment->whereDate('weignment_date','>=',carbon::createFromFormat('d/m/Y', $dates[0]));
				$weighment = $weighment->whereDate('weignment_date','<=',carbon::createFromFormat('d/m/Y', $dates[1]));
			}else{
				$weighment = $weighment->whereDate('weignment_date','>=',carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->toDateTimeString());
				$weighment = $weighment->whereDate('weignment_date','<=',carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->toDateTimeString());
			}
		}
		if($request->has('shed') && $request->shed){
			$weighment->where('shed',$request->shed);
		}
		if($request->has('grade') && $request->grade){
			$grade_id = DB::table('weignment_grades')->where('grade',$request->grade)->pluck('weignment')->toArray();
			$weighment->whereIn('id',$grade_id);
		}
		if($request->has('farmer') && $request->farmer){
			$farmers = DB::table('farmers')->where('farmers.name','LIKE',"%".$request->farmer."%")->pluck('id')->toArray();
			$weighment->whereIn('farmer',$farmers);
		}
		$weighments = $weighment->orderBy('weignment_date')->get();

		$sheds = DB::table('sheds')->where('status',1)->pluck('name','id')->toArray();
		$farmers = DB::table('farmers')->where('status',1)->pluck('name','id')->toArray();
		$grades = DB::table('grades')->where('status',1)->orderBy('name')->pluck('name','id')->toArray();
		$grade_entries = DB::table('weignment_grades')->whereIn('weignment',$weighments->pluck('id'))->get();

		$report_data = [];
		if($request->report == 'shed-abstract-report'){
			$total = array();
			foreach($weighments as $key => $weighment){
				$temp_key = array();
				$temp_key['farmer'] = $farmers[$weighment->farmer];
				foreach($grades as $grade_key => $grade){
					$weighmentGrade = $grade_entries->where('weignment',$weighment->id)->where('grade',$grade_key)->value('weight');
					$temp_key[$grade] = 
						!empty($weighmentGrade) ? $weighmentGrade : 0;
					if(array_key_exists($grade,$total)){
						$total[$grade] += !empty($weighmentGrade) ? $weighmentGrade : 0;
					}else{
						$total[$grade] = 
						!empty($weighmentGrade) ? $weighmentGrade : 0;
					}
				}
				$temp_grades = $grade_entries->where('weignment',$weighment->id);
				if(count($temp_grades) > 0){
					$report_data[$sheds[$weighment->shed]][Carbon::parse($weighment->weignment_date)->format('d/m/Y')][] = $temp_key;
				}
			}
			$requestData = (object)$request->all();
			$sheds = DB::table('sheds')->where('status',1)->get();
			$grades = DB::table('grades')->where('status',1)->get();
			return view('reports.shed_adstract_report',compact('report_data','sheds','grades','requestData','total'));
		}elseif($request->report == 'shed-detail-report'){
			$total = array();
			$wastes = DB::table('waste_types')->where('status',1)->orderBy('name')->pluck('name','id')->toArray();
			$waste_entries = DB::table('weignment_wastages')->whereIn('weignment',$weighments->pluck('id'))->get();
			foreach($weighments as $key => $weighment){
				$temp_key = array();
				$temp_key['farmer'] = $farmers[$weighment->farmer];
				$temp_key['gross_weight'] = $weighment->gross_weight;
				$temp_key['bag_count'] = $weighment->bag_count;
				$temp_key['time'] = Carbon::parse($weighment->weignment_date)->format('h:m:s A');
				if(array_key_exists('gross_weight',$total)){
					$total['gross_weight'] += !empty($temp_key['gross_weight']) ? $temp_key['gross_weight'] : 0;
				}else{
					$total['gross_weight'] = 
					!empty($temp_key['gross_weight']) ? $temp_key['gross_weight'] : 0;
				}
				if(array_key_exists('bag_count',$total)){
					$total['bag_count'] += !empty($temp_key['bag_count']) ? $temp_key['bag_count'] : 0;
				}else{
					$total['bag_count'] = 
					!empty($temp_key['bag_count']) ? $temp_key['bag_count'] : 0;
				}
				$temp_key_waste = 0;
				foreach($wastes as $waste_key => $waste){
					$weighmentGrade = $waste_entries->where('weignment',$weighment->id)->where('waste',$waste_key)->value('weight');
					$temp_key[$waste] = 
						!empty($weighmentGrade) ? $weighmentGrade : 0;
					if(array_key_exists($waste,$total)){
						$total[$waste] += !empty($weighmentGrade) ? $weighmentGrade : 0;
					}else{
						$total[$waste] = 
						!empty($weighmentGrade) ? $weighmentGrade : 0;
					}
					$temp_key_waste += !empty($weighmentGrade) ? $weighmentGrade : 0;
				}
				foreach($grades as $grade_key => $grade){
					$weighmentGrade = $grade_entries->where('weignment',$weighment->id)->where('grade',$grade_key)->value('weight');
					$temp_key[$grade] = 
						!empty($weighmentGrade) ? $weighmentGrade : 0;
					if(array_key_exists($grade,$total)){
						$total[$grade] += !empty($weighmentGrade) ? $weighmentGrade : 0;
					}else{
						$total[$grade] = 
						!empty($weighmentGrade) ? $weighmentGrade : 0;
					}
				}
				$temp_key['net_weight'] = $weighment->gross_weight - $temp_key_waste ;
				if(array_key_exists('net_weight',$total)){
					$total['net_weight'] += !empty($temp_key['net_weight']) ? $temp_key['net_weight'] : 0;
				}else{
					$total['net_weight'] = 
					!empty($temp_key['net_weight']) ? $temp_key['net_weight'] : 0;
				}
				$temp_wastes = $waste_entries->where('weignment',$weighment->id);
				$temp_grades = $grade_entries->where('weignment',$weighment->id);
				if(count($temp_wastes) > 0 && count($temp_grades) > 0){
					$report_data[$sheds[$weighment->shed]][Carbon::parse($weighment->weignment_date)->format('d/m/Y')][] = $temp_key;
				}
			}
			$requestData = (object)$request->all();
			$sheds = DB::table('sheds')->where('status',1)->get();
			$grades = DB::table('grades')->where('status',1)->get();
			$wastes = DB::table('waste_types')->where('status',1)->orderBy('name')->pluck('name')->toArray();
			return view('reports.shed_detail_report',compact('report_data','sheds','grades','requestData','wastes','total'));
		}elseif( $request->report == 'slip-report'){
			$waste_entries = DB::table('weignment_wastages')->whereIn('weignment',$weighments->pluck('id'))->get();
			$grade_entries = DB::table('weignment_grades')->whereIn('weignment',$weighments->pluck('id'))->get();
			$companies = DB::table('company')->where('status',1)->pluck('name','id')->toArray();
			$farmers = DB::table('farmers')->where('status',1)->pluck('name','id')->toArray();
			$sheds = DB::table('sheds')->where('status',1)->pluck('name','id')->toArray();
			$vehicles = DB::table('vehicles')->where('status',1)->pluck('name','id')->toArray();
			$grades = DB::table('grades')->where('status',1)->pluck('name','id')->toArray();
			$wastes = DB::table('waste_types')->where('status',1)->pluck('name','id')->toArray();
			$report_data = array();
			foreach($weighments as $weighment){
				$temp = array();
				$temp['company'] = $companies[$weighment->company];
				$temp['shed'] = $sheds[$weighment->shed];
				$temp['farmer'] = $farmers[$weighment->farmer];
				$temp['vehicle'] = $vehicles[$weighment->vehicle];
				$temp['date'] = Carbon::parse($weighment->weignment_date)->format('d-m-Y');
				$temp['time'] = Carbon::parse($weighment->weignment_date)->format('h:m:s A');
				$temp['bag_count'] = $weighment->bag_count;
				$temp['gross_weight'] = $weighment->gross_weight;
				$temp_wastes = $waste_entries->where('weignment',$weighment->id);
				$total_deduction = 0;
				foreach($temp_wastes as $temp_waste){
					$total_deduction += $temp_waste->weight;
					$temp['waste'][$wastes[$temp_waste->waste]] = $temp_waste->weight;
				}
				$temp['deduction'] = $total_deduction;
				$temp['net_weight'] = $temp['gross_weight'] - $total_deduction;
				$temp_grades = $grade_entries->where('weignment',$weighment->id);
				foreach($temp_grades as $temp_grade){
					$temp['grade'][$grades[$temp_grade->grade]] = $temp_grade->weight;
				}
				if(count($temp_wastes) > 0 && count($temp_grades) > 0){
					$report_data[] = $temp;
				}
			}
			$this->slip_report = $report_data;
			
			$requestData = (object)$request->all();
			$sheds = DB::table('sheds')->where('status',1)->get();
			$grades = DB::table('grades')->where('status',1)->get();
			$wastes = DB::table('waste_types')->where('status',1)->orderBy('name')->pluck('name')->toArray();
			return view('reports.slip_report',compact('report_data','requestData','sheds','grades','wastes'));
		}
	}
}
