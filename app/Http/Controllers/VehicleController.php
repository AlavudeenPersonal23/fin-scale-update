<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Session;

class VehicleController extends Controller
{
    const TABLE = 'vehicles';

    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $vehicles = DB::table(self::TABLE)->where('status',1);

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $vehicles = $vehicles->where('created_by',Auth::user()->id);
        }

        $vehicles = $vehicles->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

        return view('vehicle-management/view',compact('vehicles'));
    }

    public function create(){
        return view('vehicle-management/create');
    }

    public function save(Request $request){
        $data = [
            "name" => $request->name,
            "status" => 1,
            "created_by" => Auth::user()->id,
            "created_at" => now(),
            "updated_at" => now()
        ];

        DB::table(self::TABLE)->insert($data);

        return redirect()->route('vehicle-index')->with('success', 'Vehicle Created Successfully');
    }

    public function edit($id){
        $vehicle = DB::table(self::TABLE)->where('id',$id)->first();

        return view('vehicle-management/edit',compact('vehicle'));
    }

    public function update(Request $request){
        $data = [
            "name" => $request->name,
            "updated_at" => now()
        ];

        DB::table(self::TABLE)->where('id',$request->id)->update($data);

        return redirect()->route('vehicle-index')->with('success', 'Vehicle Updated Successfully');
    }

    public function delete($id){
        DB::table(self::TABLE)->where('id',$id)->update(['status' => 2]);

        return redirect()->route('vehicle-index')->with('success', 'Vehicle Deleted Successfully');
    }
    public function search(Request $request){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $vehicles = DB::table(self::TABLE)->where('status',1)->where('name','LIKE',"%".$request->searchkey."%");

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $vehicles = $vehicles->where('created_by',Auth::user()->id);
        }

        $vehicles = $vehicles->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

		if( Session::get('vehicle.searchkey') && empty($request->searchkey) || Session::get('vehicle.searchkey') == $request->searchkey){
			$searchkey = Session::get('vehicle.searchkey');
		}else{
			Session::put('vehicle.searchkey',$request->searchkey);
			$searchkey = $request->searchkey;
		}

        return view('vehicle-management/view',compact('vehicles','searchkey'));
    }
}
