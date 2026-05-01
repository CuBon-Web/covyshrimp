<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Auth,Hash,DB;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    public function login(Request $request,User $admin)
    {
    	$request->validate($admin->rule());
    	$arr = request(['name','password']);
    	if (!Auth::attempt($arr)) {
    		return response()->json([
                'code' => 500,
    			'message'=>'Sai mật khẩu'
    		],401);
    	}else{
    		$user = $request->user();
            $user->load('roles.permissions');
            $userArray = $user->toArray();
            $userArray['permission_slugs'] = $user->permissionSlugs();
	        $tokenResult = $user->createToken('Personal Access Token');
	        $token = $tokenResult->token;
	        if ($request->remember_me){
	            $token->expires_at = Carbon::now()->addWeeks(1);
	        }
	        $token->save();

		        return response()->json([
	            'success' => true,
                'message' => '',
                'data' => [
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'user' => $userArray,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]
	        ]);
    	}
    }
    public function logout(Request $request)
    {
    	$accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return response()->json([
            'message' => 'Logout Success'
        ], 200);
	}
	public function register(Request $request)
	{
		$user = new User();
		$user->name = $request->name;
		$user->avatar = $request->avatar;
		$user->phone = $request->phone;
		$user->code = rand();
		$user->password = Hash::make($request->password);
		$user->email = $request->email;
		$user->type = 1;
		$user->save();
	}
	public function authentication()
	{
        $user = Auth::user();
        if ($user) {
            $user->load('roles.permissions');
            $user->permission_slugs = $user->permissionSlugs();
        }
		return $user;
	}
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = trim($request->name);
        $user->email = trim($request->email);

        if (Schema::hasColumn('users', 'phone') && $request->has('phone')) {
            $user->phone = $request->phone;
        }
        if (Schema::hasColumn('users', 'address') && $request->has('address')) {
            $user->address = $request->address;
        }
        if (Schema::hasColumn('users', 'avatar') && $request->has('avatar')) {
            $user->avatar = $request->avatar;
        }

        $user->save();

        return response()->json([
            'data' => $user,
            'message' => 'Cập nhật thông tin thành công'
        ]);
    }
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu hiện tại không đúng'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }
}
