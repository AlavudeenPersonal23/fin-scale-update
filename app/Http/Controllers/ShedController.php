<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Session;

class ShedController extends Controller
{
    const TABLE = 'sheds';

    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;

        $sheds = DB::table(self::TABLE)->where('status',1);

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $sheds = $sheds->where('created_by',Auth::user()->id);
        }

        $sheds = $sheds->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

        return view('shed-management/view',compact('sheds'));
    }

    public function create(){
        return view('shed-management/create');
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

        return redirect()->route('shed-index')->with('success', 'Shed Created Successfully');
    }

    public function edit($id){
        $shed = DB::table(self::TABLE)->where('id',$id)->first();

        return view('shed-management/edit',compact('shed'));
    }

    public function update(Request $request){
        $data = [
            "name" => $request->name,
            "updated_at" => now()
        ];

        DB::table(self::TABLE)->where('id',$request->id)->update($data);

        return redirect()->route('shed-index')->with('success', 'Shed Updated Successfully');
    }

    public function delete($id){
        DB::table(self::TABLE)->where('id',$id)->update(['status' => 2]);
		$users = DB::table('user_additional_info')->where('shed',$id)->pluck('user_id')->toArray();
		DB::table('users')->whereIn('id',$users)->update(['status'=>2]);
		$weighments = DB::table('weignments')->where('shed',$id)->pluck('id')->toArray();
		DB::table('weignment_grades')->whereIn('weignment',$weighments)->delete();
		DB::table('weignment_wastages')->whereIn('weignment',$weighments)->delete();
		DB::table('weignments')->where('shed',$id)->delete();

        return redirect()->route('shed-index')->with('success', 'Shed Deleted Successfully');
    }
    public function search(Request $request){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;

        $sheds = DB::table(self::TABLE)->where('status',1)->where('name','LIKE',"%".$request->searchkey."%");

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $sheds = $sheds->where('created_by',Auth::user()->id);
        }

        $sheds = $sheds->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);
		
		if( Session::get('shed.searchkey') && empty($request->searchkey) || Session::get('shed.searchkey') == $request->searchkey){
			$searchkey = Session::get('shed.searchkey');
		}else{
			Session::put('shed.searchkey',$request->searchkey);
			$searchkey = $request->searchkey;
		}

        return view('shed-management/view',compact('sheds','searchkey'));
    }
}
