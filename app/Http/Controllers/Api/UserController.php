<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function register(Request $request) {
        
        $validator = Validator::make($request->all(),[
        'nik' => 'required',
        'name' => 'required',
        'username' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
        'age' => 'required',
        'gender' => 'required',
        'address' => 'required',
        'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $file = $request->file('foto');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('/public/images', $fileName);

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'unit_layanan_id' => $request->unit_layanan_id,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'foto' => $fileName,
        ]);

        if ($user) {
            return response()-> json([
                'success' => true,
                'message' => 'Register Account Berhasil',
                'data' => $user,
            ], 201);
        } else {
            return response()-> json([
                'success' => false,
                'message' => 'Register Account Gagal :(',
            ], 409);
        }
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(),[
        'email' => 'required',
        'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
            ], 422);
        }

        $credentials =  $request->only('email', 'password');
        $token = auth('api')->attempt($credentials);

        if(!$token) {
            return response()->json([
                'success' => false,
                'message'=> "Email atau Password salah"
            ], 401);
        } else {
            return response()->json([
                'success' => true,
                'user' => auth('api')->user(),
                'token' => $token, 
            ], 200);
        }
    }

    public function deauth(Request $request) {

        try {
            $token = JWTauth::getToken();
            if(!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan',
                ], 400);
            } else {
                auth('api')->logout(true);
                return response()->json([
                    'success' => true,
                    'message' => 'Logout berhasil',
                ],200);
            }
        } catch(TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired!',
            ],401);
        } catch(TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Invalid!',
            ],401);
        } catch(JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => "Token not found or can't be processed!",
            ],401);
        }
    }

}
