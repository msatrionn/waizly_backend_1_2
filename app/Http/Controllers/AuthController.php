<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuthenticationLog;
Use Laravel\Sanctum\HasApiTokens;
use Validator;

class AuthController extends Controller {
   public function register(Request $request) {
         $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        AuthenticationLog::create([
            'user_id' => $user->id,
            'table' => 'users',
            'action' => 'register',
        ]);

        $token = $user->createToken('token-secret')->plainTextToken;
        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request) {
          if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('token-secret')->plainTextToken;

        AuthenticationLog::create([
            'user_id' => $user->id,
            'table' => 'users',
            'action' => 'login',
        ]);
        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request) {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            AuthenticationLog::create([
                'user_id' => $user->id,
                'table' => 'users',
                'action' => 'logout',
            ]);

            return response()->json(['message' => 'Logged out'], 200);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }

}

?>
