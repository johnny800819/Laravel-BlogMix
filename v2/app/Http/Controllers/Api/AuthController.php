<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * 註冊新使用者 (Register new user)
     * 驗證輸入並建立使用者帳號與 Token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member', // 預設為一般會員
        ]);

        $deviceName = $request->device_name ?? 'web';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * 使用者登入 (Login)
     * 驗證帳密並發放 Sanctum Token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable',
        ]);
        
        $deviceName = $request->device_name ?? 'web';

        $user = User::where('email', $request->email)->first();

        // 驗證密碼 (Validate Password)
        if (! $user || ! Hash::check($request->password, $user->password)) {
            \Log::error('Login Attempt Failed', [
                'input_email' => $request->email,
                'user_found' => (bool)$user,
                'hash_check' => $user ? Hash::check($request->password, $user->password) : false,
            ]);
            
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 選擇性：登入時撤銷舊 Token (Optional: Revoke old tokens)
        // $user->tokens()->delete();

        return response()->json([
            'token' => $user->createToken($deviceName)->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * 登出 (Logout)
     * 撤銷當前 Access Token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * 取得目前使用者資料 (Get Current User)
     *
     * @param Request $request
     * @return \App\Models\User
     */
    public function me(Request $request)
    {
        return $request->user();
    }
}
