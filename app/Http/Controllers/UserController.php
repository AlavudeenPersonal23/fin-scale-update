<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    const TABLE = 'users';
    const INFOTABLE = 'user_additional_info';

    public function __construct() {
        auth()->setDefaultDriver('web');
        $this->middleware('auth:web');
    }

    public function index(){
		$page_size = Session::get('page_size') ? Session::get('page_size') : 10 ;
        $users = User::join(self::INFOTABLE,self::INFOTABLE.'.user_id','=',self::TABLE.'.id')
			->where(self::TABLE.'.status',1)
            ->join('sheds','sheds.id','=',self::INFOTABLE.'.shed');

        if(Auth::user()->hasRole('Shed Supervisor'))
        {
            $users = $users->where(self::INFOTABLE.'.created_by',Auth::user()->id);
        }

        $users = $users
            ->select(self::TABLE.'.*',self::INFOTABLE.'.*','sheds.name as shed',self::TABLE.'.id as id')
            ->orderBy(self::TABLE.".created_at","DESC")->paginate($page_size);

        return view('user-management/view',compact('users'));
    }

    public function create(){
        $sheds = DB::table('sheds')->where('status',1)->get();
        // $roles = Role::all();
		$roles = Role::where('name','Shed Supervisor')->first();
        return view('user-management/create',compact('sheds','roles'));
    }

    public function save(Request $request){
		$this->resolveEmailConflicts($request->email);
		$users =  DB::table(self::TABLE)
			->where(self::TABLE.'.status',1)
			->where(self::TABLE.'.email',$request->email)
			->get();
		if(count($users) > 0){
			return back()->with('error', 'User with same email ID already exists');
		}
		$admin = User::create(['name' => $request->name, 'email' => $request->email, 'password' => bcrypt( $request->password ), "status" => 1 ]);
		if($request->has('permission')){
			$admin->givePermissionTo($request->permission);
		}else{
			$admin->givePermissionTo(['weighment-management-view','weighment-management-edit','weighment-management-delete']);
		}
		$admin->assignRole($request->role);
		$data = [
            "user_id" => $admin->id,
            "created_by" => Auth::user()->id,
            "shed" => $request->shed  ?? 1,
            "contact_number" => $request->contact_number ?? '',
            "remarks" => $request->remarks ?? '',
            "created_at" => now(),
            "updated_at" => now()
        ];

        DB::table(self::INFOTABLE)->insert($data);

        return redirect()->route('user-index')->with('success', 'User Created Successfully');
    }

    public function edit($id){
        $sheds = DB::table('sheds')->where('status',1)->get();
        // $roles = Role::all();
		$roles = Role::where('name','Shed Supervisor')->first();
        $user = User::where(self::TABLE.'.id',$id)
			->join(self::INFOTABLE,self::INFOTABLE.'.user_id','=',self::TABLE.'.id')
			->join('sheds','sheds.id','=',self::INFOTABLE.'.shed')
			->select(self::TABLE.'.*',self::INFOTABLE.'.*','sheds.name as shed',self::TABLE.'.id as id')
			->first();
		$user_role = $user->getRoleNames()->toArray();
		$user_permissions = $user->getPermissionNames()->toArray();

        return view('user-management/edit',compact('sheds','roles','user','user_role','user_permissions'));
    }

    public function update(Request $request){
		$this->resolveEmailConflicts($request->email);
		$users =  DB::table(self::TABLE)
			->where(self::TABLE.'.id','!=',$request->id)
			->where(self::TABLE.'.status',1)
			->where(self::TABLE.'.email',$request->email)
			->get();
		if(count($users) > 0){
			return back()->with('error', 'User with same email ID already exists');
		}
		$user_role = explode(',',$request->user_role);
		$user_permissions = explode(',',$request->user_permissions);
		if( $request->password != ''){
			$admin = User::where('id',$request->id)->update(['name' => $request->name, 'email' => $request->email, 'password' => bcrypt( $request->password ) ]);
		}else{
			$admin = User::where('id',$request->id)->update(['name' => $request->name, 'email' => $request->email]);
		}
		$admin = User::where('id',$request->id)->first();
		$admin->revokePermissionTo($user_permissions);
		$admin->removeRole($user_role[0]);
		if($request->has('permission')){
			$admin->givePermissionTo($request->permission);
		}else{
			$admin->givePermissionTo(['weighment-management-view','weighment-management-edit','weighment-management-delete']);
		}
		$admin->assignRole($request->role);
		$data = [
            "shed" => $request->shed ?? 1,
            "contact_number" => $request->contact_number ?? '',
            "remarks" => $request->remarks ?? '',
            "updated_at" => now()
        ];

        DB::table(self::INFOTABLE)->where("user_id",$request->id)->update($data);

        return redirect()->route('user-index')->with('success', 'User Updated Successfully');
    }

    public function delete($id){
        DB::table(self::TABLE)->where('id',$id)->update(['status' => 2]);

        return redirect()->route('user-index')->with('success', 'User Deleted Successfully');
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

		if( Session::get('user.searchkey') && empty($request->searchkey) || Session::get('user.searchkey') == $request->searchkey){
			$searchkey = Session::get('user.searchkey');
		}else{
			Session::put('user.searchkey',$request->searchkey);
			$searchkey = $request->searchkey;
		}

        return view('farmer-management/view',compact('farmers','searchkey'));
    }
	public function resolveEmailConflicts($email){
		$deletedUsers = DB::table(self::TABLE)
			->where(self::TABLE.'.status',2)
			->where(self::TABLE.'.email',$email)
			->get();

		if(count($deletedUsers) > 0){
			foreach($deletedUsers as $deletedUser){
				$email = Str::random(8);
				
				DB::table('users')->where('id',$deletedUser->id)
					->update(['email' => $email.$deletedUser->email]);
			}
		}
	}
}
