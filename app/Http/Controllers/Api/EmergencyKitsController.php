<?php

namespace App\Http\Controllers\Api;

use App\Models\EmergencyKit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmergencyKitsController extends Controller
{
public function index() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kit = EmergencyKit::all();

            return response()->json([
                'success' => true,
                'data' => $kit
            ], 200);

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
        }  catch(JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => "Missing Token ! Token can't be parsed",
            ],400);  
        }
    }
    
    public function store(Request $request) {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Validator::make($request->all(),[
                'nama' => 'required',
                'keterangan' => 'nullable',
                'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'unit_layanan_id' => ['nullable', 'integer', Rule::exists('m_unit_layanan', 'id')],
                'gudang_id' => ['nullable', 'integer', Rule::exists('m_gudang', 'id')]
            ]);

            if ($request->file('qr_code')) {
                $file = $request->file('qr_code');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('/public/images/emergencykits/qrcodes', $fileName);
            } else {
                $fileName = $request->gambar_obat;
            }    
            

            $medkit = EmergencyKit::create([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'qr_code' => $fileName,
                'unit_layanan_id' => $request->unit_layanan_id,
                'gudang_id' => $request->gudang_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Emergency Kits baru berhasil ditambahkan',
                'data' => $medkit
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data Emergency Kits, Periksa input kembali !',
            ], 422);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Expired ! Silahkan re:login kembali',
            ], 401);
        } catch(TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Invalid ! Silahkan re:login kembali',
            ],401);
        } catch(JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Missing Token !',
            ],400);  
        }

    }

    public function update(Request $request, string $id) {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $medkit = EmergencyKit::findOrFail($id);
            Validator::make($request->all(),[
                'nama' => 'required',
                'keterangan' => 'nullable',
                'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'unit_layanan_id' => ['nullable', 'integer', Rule::exists('m_unit_layanan', 'id')],
                'gudang_id' => ['nullable', 'integer', Rule::exists('m_gudang', 'id')]
            ]);
                            
            if ($request->file('qr_code')) {
                $file = $request->file('qr_code');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('/public/images/emergencykits/qrcodes', $fileName);
            } else {
                $fileName = $request->gambar_obat;
            }   

            $medkit->update([
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'qr_code' => $fileName,
                'unit_layanan_id' => $request->unit_layanan_id,
                'gudang_id' => $request->gudang_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Emergency Kits berhasil di-update',
                'data' => $medkit,
            ], 200);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data Emergency Kits, Periksa input kembali !',
            ], 422);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Expired ! Silahkan re:login kembali',
            ], 401);
        } catch(JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => "Missing Token ! Token can't be parsed",
            ],400);  
        }
    }
}
