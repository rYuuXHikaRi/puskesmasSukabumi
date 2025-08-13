<?php

namespace App\Http\Controllers\Api;

use App\Models\SatuanObat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class MedicineUnitMeasurenmentController extends Controller
{
    
    public function index() {
        try {
            $medicineUnitMeasurement = SatuanObat::all();
            return response()->json($medicineUnitMeasurement);
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
                'nama_satuan' => 'required|max:255|unique:m_satuan_obat',
                'keterangan' => 'nullable|max:255',
            ]);

            $userId = auth('api')->user()->id;

            $medicineUnitMeasurement = SatuanObat::create([
                'nama_satuan' => $request->nama_satuan,
                'keterangan' => $request->keterangan,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan obat berhasil ditambahkan',
                'data' => $medicineUnitMeasurement
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data satuan obat, Periksa input kembali !',
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
            $medicineUnitMeasurement = SatuanObat::findOrFail($id);
            $validator = Validator::make($request->all(),[
                'nama_satuan' => ['required', Rule::unique('m_satuan_obat')->ignore($medicineUnitMeasurement->id)],
                'keterangan' => 'nullable|max:255',
            ]);
            
            $userId = auth('api')->user()->id;
            
            $medicineUnitMeasurement->update([
                'nama_satuan' => $request->nama_satuan,
                'keterangan' => $request->keterangan,
                'updated_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan obat berhasil di-update',
                'data' => $medicineUnitMeasurement,
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
