<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // register
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => 'validation error'], 401);
            }
            $user = User::create(
                array_merge(
                    $validator->validated()
                )
            );
            return response()->json([
                'status' => true,
                'message' => 'User successfully registered',
                'user' => $user,
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e, 'message' => 'api exception'], 500);
        }
    }

    // login
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => 'validation error'], 401);
            }
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json(['status' => false, 'message' => 'invalid email or password'], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User login successful',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage(), 'message' => 'api exception'], 500);
        }
    }

    // profile

    public function profile(Request $request)
    {
        $userData = auth()->user();
        return response()->json([
            'status' => true,
            'user' => $userData

        ], 200);
    }

    // logout

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['status' => true, 'message' => 'logout success',], 200);
    }
}
