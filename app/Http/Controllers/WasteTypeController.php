<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Session;

class WasteTypeController extends Controller
{
    const TABLE = 'waste_types';

    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $wastes = DB::table(self::TABLE)->where('status',1);

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $wastes = $wastes->where('created_by',Auth::user()->id);
        }

        $wastes = $wastes->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

        return view('waste-management/view',compact('wastes'));
    }

    public function create(){
        $sheds = DB::table('sheds')->where('status',1)->get();

        return view('waste-management/create',compact('sheds'));
    }

    public function save(Request $request){
        $data = [
            "name" => $request->name,
            "status" => 1,
            "created_by" => Auth::user()->id,
            "created_at" => now(),
            "updated_at" => now()
        ];

        $waste = DB::table(self::TABLE)->insertGetId($data);

        if($request->has('default_value'))
        {
            $data = [];
            foreach($request->default_value as $shed => $value){
                if(!empty($value)){
                    $temp = [];
                    $temp['shed'] = $shed;
                    $temp['value'] = $value;
                    $temp['type'] = $request->default_value_type[$shed] ?? 'value';
                    $temp['waste_type'] = $waste;
                    $data[] = $temp;
                }
            }

            if(count($data) > 0){
                DB::table('table_waste_types_default')->insert($data);
            }
        }

        return redirect()->route('waste-type-index')->with('success', 'Waste Type Created Successfully');
    }

    public function edit($id){
        $waste = DB::table(self::TABLE)->where('id',$id)->first();
        $waste_defaults = DB::table('table_waste_types_default')->where('waste_type',$id)->get();
        $sheds = DB::table('sheds')->where('status',1)->get();

        return view('waste-management/edit',compact('waste','sheds','waste_defaults'));
    }

    public function update(Request $request){
        $data = [
            "name" => $request->name,
            "updated_at" => now()
        ];

        DB::table(self::TABLE)->where('id',$request->id)->update($data);

        if($request->has('default_value'))
        {
            $data = [];
            foreach($request->default_value as $shed => $value){
                if(!empty($value)){
                    $temp = [];
                    $temp['shed'] = $shed;
                    $temp['value'] = $value;
                    $temp['type'] = $request->default_value_type[$shed] ?? 'value';
                    $temp['waste_type'] = $request->id;
                    $data[] = $temp;
                }
            }

            if(count($data) > 0){
                DB::table('table_waste_types_default')->where('waste_type',$request->id)->delete();
                DB::table('table_waste_types_default')->insert($data);
            }
        }

        return redirect()->route('waste-type-index')->with('success', 'Waste Type Updated Successfully');
    }

    public function delete($id){
        DB::table(self::TABLE)->where('id',$id)->update(['status' => 2]);

        return redirect()->route('waste-type-index')->with('success', 'Waste Type Deleted Successfully');
    }

    public function search(Request $request){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $wastes = DB::table(self::TABLE)->where('status',1)->where('name','LIKE',"%".$request->searchkey."%");

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $wastes = $wastes->where('created_by',Auth::user()->id);
        }

        $wastes = $wastes->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

		if( Session::get('waste-types.searchkey') && empty($request->searchkey) || Session::get('waste-types.searchkey') == $request->searchkey){
			$searchkey = Session::get('waste-types.searchkey');
		}else{
			Session::put('waste-types.searchkey',$request->searchkey);
			$searchkey = $request->searchkey;
		}

        return view('waste-management/view',compact('wastes','searchkey'));
    }
}
