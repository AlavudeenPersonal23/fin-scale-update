<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        auth()->setDefaultDriver('api');
        $this->middleware('auth:api', ['except' => ['login', 'loginById', 'register', 'invalidToken']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * Login By ID
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginById(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int'
        ]);

        $user = User::findOrFail($request->id);

        $old_password = $user->password;
        
        $user->password = bcrypt('password');
        $user->save();

        $login = [
            "email" => $user->email,
            "password" => "password"
        ];
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($login)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user->password = $old_password;
        $user->save();
        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $user = User::where('users.id',Auth::user()->id)
			->where('users.status',1)
			->join('user_additional_info','user_additional_info.user_id','=','users.id')
			->join('sheds','sheds.id','=','user_additional_info.shed')
			->select('users.*','user_additional_info.*','sheds.id as shed_id','sheds.name as shed','users.id as id')
			->first();
			
		if($user){
			return response()->json([
				'access_token' => $token,
				'token_type' => 'bearer',
				'expires_in' => auth()->factory()->getTTL() * 60,
				'user' => $user
			]);
		} else {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
		
    }
	
	public function invalidToken(){
		return response()->json(['error' => 'Unauthorized'], 401);
	}
}