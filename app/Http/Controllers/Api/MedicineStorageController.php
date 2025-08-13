<?php

namespace App\Http\Controllers\Api;

use App\Models\Gudang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class MedicineStorageController extends Controller
{
    
    public function index() {
        try {
            $medicineStorage = Gudang::all();
            return response()->json($medicineStorage);
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
        }
    }
    
    public function store(Request $request) {
        
        try {
            $validator = Validator::make($request->all(),[
                'kode_gudang' => 'required|unique:m_gudang, kode_gudang',
                'nama_gudang' => 'required',
                'tipe' => 'required',
                'lokasi' => 'required',
                'keterangan' => 'nullable',
            ]);

            $userId = auth('api')->user()->id;

            $medicineStorage = Gudang::create([
                'kode_gudang' => $request->kode_gudang,
                'nama_gudang' => $request->nama_gudang,
                'tipe' => $request->tipe,
                'lokasi' => $request->lokasi,
                'keterangan' => $request->keterangan,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Entry Gudang berhasil ditambahkan',
                'data' => $medicineStorage
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data gudang, Periksa input kembali !',
            ], 422);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Expired ! Silahkan re:login kembali',
            ], 401);
        }

    }

    public function update(Request $request, string $id) {
        try {
            $medicineStorage = Gudang::findOrFail($id);
            $validator = Validator::make($request->all(),[
                'kode_gudang' => ['required', Rule::unique('m_gudang', 'kode_gudang')->ignore($medicineStorage->id)],
                'nama_gudang' => 'required',
                'tipe' => 'required',
                'lokasi' => 'required',
                'keterangan' => 'nullable',
            ]);
            
            $userId = auth('api')->user()->id;
            
            $medicineStorage->update([
                'kode_gudang' => $request->kode_gudang,
                'nama_gudang' => $request->nama_gudang,
                'tipe' => $request->tipe,
                'lokasi' => $request->lokasi,
                'keterangan' => $request->keterangan,
                'updated_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan obat berhasil di-update',
                'data' => $medicineStorage,
            ], 200);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbaharui data satuan obat, Periksa input kembali !',
            ], 422);
        } catch(TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Expired ! Silahkan re:login kembali',
            ], 401);
        }
    }
}
