<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UnitLayanan;
use App\Http\Controllers\Controller;
use Carbon\Unit;
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $users = User::all();
            return response()->json([
                'success' => true,
                'data' => $users,
            ], 200);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'tokenStatus' => 'expired',
                'message' => 'Silahkan re:login !',
            ], 401);
        } catch(TokenInvalidException $e) {
            return response()->json([
                'tokenStatus' => 'invalid',
                'message' => 'Silahkan re:login !',
            ],401);
        } catch(JWTException $e) {
            return response()->json([
                'tokenStatus' => 'notFound',
                'message' => "Please Login First !",
            ],400);
        }
        
    }

    public function checkToken() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'tokenStatus' => 'valid',
                'data' => $user,
            ], 200);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'tokenStatus' => 'expired',
                'message' => 'Silahkan re:login !',
            ], 401);
        } catch(TokenInvalidException $e) {
            return response()->json([
                'tokenStatus' => 'invalid',
                'message' => 'Silahkan re:login !',
            ],401);
        } catch(JWTException $e) {
            return response()->json([
                'tokenStatus' => 'notFound',
                'message' => "Please Login First !",
            ],400);
        }
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
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }
        
        $file = $request->file('foto');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('/public/images', $fileName);

        try {
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

            return response()-> json([
                'success' => true,
                'message' => 'Register Account Berhasil',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()-> json([
                'success' => false,
                'message' => 'Register Account Gagal :(, Reason: ' . $e->getMessage(),
            ], 409);
        }
    }

    public function authenticate(Request $request) {
        $isEmailSame = \App\Models\User::whereRaw('BINARY email = ?', [$request->email])->first();

        if (!$isEmailSame) {
            return response()->json([
                'success' => false,
                'isLoginAttempt' => true,
                'message' => "Email tidak ditemukan atau case tidak sesuai"
            ], 401);
        }

        $validator = Validator::make($request->all(),[
        'email' => 'required',
        'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $credentials =  $request->only('email', 'password');
        $token = auth('api')->attempt($credentials);

        if(!$token) {
            return response()->json([
                'success' => false,
                'isLoginAttempt' => true,
                'message'=> "Email atau Password salah"
            ], 401);
        } else {
            $user = auth('api')->user();
            $user->foto = url('storage/images/' . $user->foto);
            $user->unit_layanan = UnitLayanan::where('id', $user->unit_layanan_id)
                                             ->value('unit_layanan');
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token, 
            ], 200);
        }
    }

    public function deauth(Request $request) {

        try {
            $token = JWTauth::getToken();
            auth('api')->logout(true);
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ],200);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'tokenStatus' => 'expired',
                'message' => 'Silahkan re:login !',
            ], 401);
        } catch(TokenInvalidException $e) {
            return response()->json([
                'tokenStatus' => 'invalid',
                'message' => 'Silahkan re:login !',
            ],401);
        } catch(JWTException $e) {
            return response()->json([
                'tokenStatus' => 'notFound',
                'message' => "Please Login First !",
            ],400);
        }
    }

}
