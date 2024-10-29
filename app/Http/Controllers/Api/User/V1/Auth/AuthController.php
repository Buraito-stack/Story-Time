<?php

namespace App\Http\Controllers\Api\User\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers, RegistersUsers;

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->credential)
                    ->orWhere('username', $request->credential)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'credential' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => ['token' => $token]], 200);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        event(new Registered($user));

        $this->guard()->login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => ['token' => $token]], 201);
    }

    public function logout(Request $request) 
    {
        $request->user()->tokens()->delete();

        return response()->noContent();
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
