<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'success'   => false,
                    'errors'    =>
                    [
                        'password'  => $validator->errors()->first('password'),
                        'email'     => $validator->errors()->first('email'),
                    ]
                    ], 400
            );
        }

        $credentials = request(['email', 'password']);

        if ($token = auth('api')->attempt($credentials)) {
            $user = User::where('email', $request->input('email'))->first();
            return $this->respondWithToken($token, $user);
        }

        return response()->json(['success' => false, 'errors' => ['authError' => 'Failed to authenticate']], 400);
    }

    public function token()
    {
//        $token = Jwt::getToken();
//        if (!$token) {
//            return response()->json(['success' => false, 'errors' => ['Token not provided']], 401);
//        }
//        try {
//            $token = JWTAuth::refresh($token);
//        } catch (TokenInvalidException $e) {
//            return response()->json(['success' => false, 'errors' => ['The token is invalid']], 401);
//        }
//        return $this->respondWithToken($token);
    }

    public function guard()
    {
        return Auth::guard();
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['success' => true, 'message' => 'Successfully logged out', 'errors' => []]);
    }

    protected function respondWithToken($token, $user=null)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60 * 24 * 30,
                'user' => $user
            ],
            'errors' => [],
            'message' => 'Successfully generated token',
        ], 200);
    }
}
