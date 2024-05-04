<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Session;

class FarmerController extends Controller
{
    const TABLE = 'farmers';

    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $farmers = DB::table(self::TABLE)
            ->where(self::TABLE.'.status',1)
            ->join('sheds','sheds.id','=',self::TABLE.'.shed')
            ->join('company','company.id','=',self::TABLE.'.company');

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $farmers = $farmers->where(self::TABLE.'.created_by',Auth::user()->id);
        }

        $farmers = $farmers
            ->select(self::TABLE.'.id',self::TABLE.'.name',self::TABLE.'.contact_number','sheds.name as shed', 'company.name as company', self::TABLE.'.member_id')
            ->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

        return view('farmer-management/view',compact('farmers'));
    }

    public function create(){
        $sheds = DB::table('sheds')->where('status',1)->get();
        $companies = DB::table('company')->where('status',1)->get();
        return view('farmer-management/create',compact('sheds','companies'));
    }

    public function save(Request $request){
        $data = [
            "name" => $request->name,
            "status" => 1,
            "shed" => $request->shed,
            "company" => $request->company,
            "contact_number" => $request->contact_number ?? '',
            "created_by" => Auth::user()->id,
            "created_at" => now(),
            "updated_at" => now(),
			"member_id" => $request->member_id
        ];
		
		$check = DB::table(self::TABLE)->where('member_id',$request->member_id)->first();
		
		if(!empty($check)){
			return redirect()->back()->with('error', 'Member ID already taken');
		}

        DB::table(self::TABLE)->insert($data);

        return redirect()->route('farmer-index')->with('success', 'Farmer Created Successfully');
    }

    public function edit($id){
        $sheds = DB::table('sheds')->where('status',1)->get();
        $companies = DB::table('company')->where('status',1)->get();
        $farmer = DB::table(self::TABLE)->where('id',$id)->first();

        return view('farmer-management/edit',compact('farmer','sheds','companies'));
    }

    public function update(Request $request){
        $data = [
            "name" => $request->name,
            "shed" => $request->shed,
            "company" => $request->company,
            "contact_number" => $request->contact_number ?? '',
            "updated_at" => now(),
			"member_id" => $request->member_id
        ];

		$check = DB::table(self::TABLE)->where('id','!=',$request->id)->where('member_id',$request->member_id)->first();
		
		if(!empty($check)){
			return redirect()->back()->with('error', 'Member ID already taken');
		}

        DB::table(self::TABLE)->where('id',$request->id)->update($data);

        return redirect()->route('farmer-index')->with('success', 'Farmer Updated Successfully');
    }

    public function delete($id){
        DB::table(self::TABLE)->where('id',$id)->update(['status' => 2]);

        return redirect()->route('farmer-index')->with('success', 'Farmer Deleted Successfully');
    }
    public function search(Request $request){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $farmers = DB::table(self::TABLE)
            ->where(self::TABLE.'.status',1)
            ->join('sheds','sheds.id','=',self::TABLE.'.shed')
            ->join('company','company.id','=',self::TABLE.'.company')
            ->where(self::TABLE.'.name','LIKE',"%".$request->searchkey."%")
            ->orwhere(self::TABLE.'.contact_number','LIKE',"%".$request->searchkey."%");

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $farmers = $farmers->where(self::TABLE.'.created_by',Auth::user()->id);
        }

        $farmers = $farmers
            ->select(self::TABLE.'.id',self::TABLE.'.name',self::TABLE.'.contact_number','sheds.name as shed', 'company.name as company')
            ->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

		if( Session::get('farmer.searchkey') && empty($request->searchkey) || Session::get('farmer.searchkey') == $request->searchkey){
			$searchkey = Session::get('farmer.searchkey');
		}else{
			Session::put('farmer.searchkey',$request->searchkey);
			$searchkey = $request->searchkey;
		}

        return view('farmer-management/view',compact('farmers','searchkey'));
    }
	
	public function getShedFarmer(Request $request){
        $farmers = DB::table(self::TABLE)
            ->where(self::TABLE.'.status',1)
			->where(self::TABLE.'.shed',$request->shed);

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $farmers = $farmers->where(self::TABLE.'.created_by',Auth::user()->id);
        }

        $farmers = $farmers
            ->select(self::TABLE.'.id',self::TABLE.'.name')
            ->orderBy(self::TABLE.".created_at","DESC")->get();
			
		return response()->json($farmers);
	}
}
