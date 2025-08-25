<?php

namespace App\Http\Controllers\Api;

use App\Models\Gudang;
use App\Models\GudangObat;
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
            $user = JWTAuth::parseToken()->authenticate();
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
            $user = JWTAuth::parseToken()->authenticate();
            Validator::make($request->all(),[
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
        } catch(TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Invalid ! Silahkan re:login kembali',
            ],401);
        }

    }

    public function update(Request $request, string $id) {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $medicineStorage = Gudang::findOrFail($id);
            Validator::make($request->all(),[
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

    public function medicineStockIndex(Request $request) {
        $gudangId = $request->input('gudangId');
        $obatId = $request->input('obatId');
        $gudang = Gudang::find($gudangId);
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if(!empty($gudangId) && !empty($obatId)) {
                $medicineStock = $gudang->obat()->where('obat_id', $obatId)->first();
            } else if(!empty($gudangId)) {
                $medicineStock = GudangObat::where('gudang_id', $gudangId)
                                            ->get();
            } else if(!empty($obatId)) {
                $medicineStock = GudangObat::where('obat_id', $obatId)
                                            ->get();
            } else {
                $medicineStock = GudangObat::all();
            }
            
            if ($medicineStock->isEmpty() || !$medicineStock) {
                return response()->json(['message' => 'Data stok tidak ditemukan.'], 404);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => $medicineStock
                ], 200);
            };
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

    public function medicineStockStore(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $validator = Validator::make($request->all(),[
                'obat_id' => ['required', 'integer', Rule::exists('m_obat', 'id')],
                'gudang_id' => ['required', 'integer', Rule::exists('m_gudang', 'id')],
                'stok' => 'required|integer|min:0'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periksa data inputan kembali !'
                ], 422);
            };

            $gudang = Gudang::find($request->gudang_id);
            $isExist = $gudang->obat()->where('obat_id', $request->obat_id)->first();
           
            if($isExist) {
                return response()->json([
                    'message' => 'Entri stok untuk obat ini di gudang sudah ada. Gunakan PUT untuk memperbarui.',
                    'data' => $isExist->pivot
                ], 409);
            } else {
                
                // $medicineStock = GudangObat::create([
                //     'obat_id' => $request->obat_id,
                //     'gudang_id' => $request->gudang_id,
                //     'stok' => $request->stok
                // ]);

                
                $gudang->obat()->attach($request->obat_id, ['stok' => $request->stok]);
                $medicineStock = $gudang->obat()->where('obat_id', $request->obat_id)->first();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Entry Stok Obat berhasil ditambahkan',
                    'data' => $medicineStock->pivot,
                ], 201);
            }

        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data stock gudang, Periksa input kembali !',
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
        }
    }

    public function medicineStockUpdate(Request $request) {
        $gudangId = $request->input('gudangId');
        $obatId = $request->input('obatId');
        $gudang = Gudang::find($gudangId);

        $validator = Validator::make($request->all(),[
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data stok, periksa input kembali!',
                'errors' => $validator->errors() // Ambil semua error validasi
            ], 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $medicineStock = GudangObat::where('obat_id', $obatId)
                                    ->where('gudang_id', $gudangId)
                                    ->first();
            if (!$medicineStock) {
                return response()->json([
                    'message' => 'Data stok tidak ditemukan.'
                ],404);
            } else {

                $gudang->obat()->updateExistingPivot($obatId, [
                    'stok' => $request->stok,
                ]);
                $medicineStock = $gudang->obat()->where('obat_id', $obatId)->first();

                return response()->json([
                    'message' => 'Stok gudang obat berhasil diperbarui.',
                    'data' => $medicineStock->pivot
                ], 200);
            }

        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbaharui data stock obat di gudang, Periksa input kembali !',
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
        }
    }

    public function medicineStockGet() {
        
    }


}
