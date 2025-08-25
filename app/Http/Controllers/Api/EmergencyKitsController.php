<?php

namespace App\Http\Controllers\Api;

use App\Models\EmergencyKit;
use App\Models\EmergencyKitObat;
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
                'emergencyKit_id' => ['nullable', 'integer', Rule::exists('m_emergencyKit', 'id')]
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
                'emergencyKit_id' => $request->emergencyKit_id,
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
                'emergencyKit_id' => ['nullable', 'integer', Rule::exists('m_emergencyKit', 'id')]
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
                'emergencyKit_id' => $request->emergencyKit_id,
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

    public function stockIndex(Request $request) {
        $medkitId = $request->input('medkitId');
        $obatId = $request->input('obatId');
        $emergencyKit = EmergencyKit::find($medkitId);
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if(!empty($medkitId) && !empty($obatId)) {
                $medicineStock = $emergencyKit->obat()->where('obat_id', $obatId)->first();
            } else if(!empty($medkitId)) {
                $medicineStock = EmergencyKitObat::where('emergency_kit_id', $medkitId)
                                            ->get();
            } else if(!empty($obatId)) {
                $medicineStock = EmergencyKitObat::where('obat_id', $obatId)
                                                   ->get();
            } else {
                $medicineStock = EmergencyKitObat::all();
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

    public function stockStore(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $validator = Validator::make($request->all(),[
                'obat_id' => ['required', 'integer', Rule::exists('m_obat', 'id')],
                'emergency_kit_id' => ['required', 'integer', Rule::exists('emergency_kit', 'id')],
                'stok' => 'required|integer|min:0'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periksa data inputan kembali !'
                ], 422);
            };

            $emergencyKit = EmergencyKit::find($request->emergency_kit_id);
            $isExist = $emergencyKit->obat()->where('obat_id', $request->obat_id)->first();
           
            if($isExist) {
                return response()->json([
                    'message' => 'Entri stok untuk obat ini di emergencyKit sudah ada. Gunakan PUT untuk memperbarui.',
                    'data' => $isExist->pivot
                ], 409);
            } else {
                
                $emergencyKit->obat()->attach($request->obat_id, ['stok' => $request->stok]);
                $medicineStock = $emergencyKit->obat()->where('obat_id', $request->obat_id)->first();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Entry Stok Obat pada Emergency Kit berhasil ditambahkan',
                    'data' => $medicineStock->pivot,
                ], 201);
            }

        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data stock emergency kit, Periksa input kembali !',
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

    public function stockUpdate(Request $request) {
        $medkitId = $request->input('medkitId');
        $obatId = $request->input('obatId');
        $medkit = EmergencyKit::find($medkitId);

        $validator = Validator::make($request->all(),[
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data stok, periksa input kembali!',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $medicineStock = EmergencyKitObat::where('obat_id', $obatId)
                                    ->where('emergency_kit_id', $medkitId)
                                    ->first();
            if (!$medicineStock) {
                return response()->json([
                    'message' => 'Data stok tidak ditemukan.'
                ],404);
            } else {

                $medkit->obat()->updateExistingPivot($obatId, [
                    'stok' => $request->stok,
                ]);
                $medicineStock = $medkit->obat()->where('obat_id', $obatId)->first();

                return response()->json([
                    'success' => true,
                    'message' => 'Stok gudang obat berhasil diperbarui.',
                    'data' => $medicineStock->pivot
                ], 200);
            }

        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbaharui data stock obat di emergency kit, Periksa input kembali !',
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

}
