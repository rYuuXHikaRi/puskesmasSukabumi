<?php

namespace App\Http\Controllers\Api;

use App\Services\HistoryMedicineServices;

use App\Models\Gudang;
use App\Models\EmergencyKit;
use App\Models\EmergencyKitObat;
use App\Models\PengambilanObat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmergencyKitsController extends Controller
{
    
    protected $historyMedicineServices;

    public function __construct(HistoryMedicineServices $historyMedicineServices) {
        $this->historyMedicineServices = $historyMedicineServices;
    }

    public function index() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $kit = EmergencyKit::all();

            return response()->json([
                'success' => true,
                'medkitData' => $kit
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

    public function stockIndex(Request $request) {
        $medkitId = $request->input('medkitId');
        $obatId = $request->input('obatId');
        $unitLayananId = $request->input('unitLayananId');
        $emergencyKit = EmergencyKit::find($medkitId);
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $query = DB::table('emergency_kit_obat')
                    ->join('emergency_kit', 'emergency_kit_obat.emergency_kit_id', '=', 'emergency_kit.id')
                    ->join('m_obat', 'emergency_kit_obat.obat_id', '=', 'm_obat.id')
                    ->join('m_satuan_obat', 'm_obat.satuan_id', '=', 'm_satuan_obat.id')
                    ->select(
                        'emergency_kit.id as emergency_kit_id',
                        'emergency_kit.nama as kit',
                        'm_obat.id as obat_id',
                        'm_obat.nama_obat as obat',
                        'm_satuan_obat.nama_satuan as satuan_obat',
                        DB::raw("CASE 
                                    WHEN m_obat.jenis_obat = 1 THEN 'Injeksi'
                                    WHEN m_obat.jenis_obat = 2 THEN 'Oral'
                                    ELSE 'Lainnya'
                                END as jenis_obat"),
                        'emergency_kit_obat.stok',
                        'm_obat.tanggal_kadaluarsa as expired'
                    );
            if(!empty($medkitId) && !empty($obatId)) {
                $medicineStock = $query->where('emergency_kit.id', $medkitId)
                                ->where('m_obat.id', $obatId)
                                ->get();
            } else if(!empty($medkitId)) {
                $medicineStock = $query->where('emergency_kit.id', $medkitId)->get();
            } else if(!empty($obatId)) {
                $medicineStock = EmergencyKitObat::where('obat_id', $obatId)
                                                   ->get();
            } else if(!empty($unitLayananId)) {
                $medicineStock = $query->where('emergency_kit.unit_layanan_id', $unitLayananId)
                                ->get();
            } else {
                $medicineStock = $query->get();
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

    public function stockStore(Request $request) {
        $gudangId = $request->input('fromGudangId');
        try {
            $user = JWTAuth::parseToken()->authenticate()->id;
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
            $gudang = Gudang::find($gudangId);
            $medicineOnWarehouse = $gudang->obat()->where('obat_id', $request->obat_id)->first()->pivot->stok;
            if($medicineOnWarehouse < $request->stok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok di gudang tidak mencukupi ! Stok di gudang saat ini : '.$medicineOnWarehouse,
                ], 422);
            }

            if (!$medicineOnWarehouse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Obat tidak ditemukan di gudang !',
                ], 404);
            }

            $isExist = $emergencyKit->obat()->where('obat_id', $request->obat_id)->first();
            if($isExist) {
                return response()->json([
                    'message' => 'Entri stok untuk obat ini di emergencyKit sudah ada. Gunakan PUT untuk memperbarui.',
                    'data' => $isExist->pivot
                ], 409);
            } else {
                
                $emergencyKit->obat()->attach($request->obat_id, ['stok' => $request->stok]);
                $gudang->obat()->updateExistingPivot($request->obat_id, ['stok' => $gudang->obat()->where('obat_id', 
                                                                         $request->obat_id)->first()->pivot->stok - $request->stok
                                                                    ]);
                $medicineIn = $this->historyMedicineServices->medicineIn(
                    $request->obat_id,
                    $gudangId,
                    $user,
                    $request->emergency_kit_id,
                    $request->stok,
                    "Penambahan Obat ke Emergency Kit"
                );

                $medicineHistoryOut = $this ->historyMedicineServices->medicineHistory(
                    $request->obat_id,
                    $gudangId,
                    $user,
                    $request->stok,
                    'keluar',
                    "Penambahan Obat ke Emergency Kit",
                    $request->emergency_kit_id
                );

                $medicineStock = $emergencyKit->obat()->where('obat_id', $request->obat_id)->first();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Entry Stok Obat pada Emergency Kit berhasil ditambahkan',
                    'medicineStock' => $medicineStock->pivot,
                    'medicineIn' => $medicineIn,
                    'medicineHistoryOut' => $medicineHistoryOut
                ], 201);
            }

        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data stock emergency kit, Periksa input kembali !',
            ], 422);
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

    public function getMedicine(Request $request) {
        
        $obatId = $request->input('obatId');
        $validator = Validator::make($request->all(),[
            'pengambilan_id' => ['required', Rule::exists('pengambilan_obat', 'id')],
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate()->id;

            $emergency_kit_id = PengambilanObat::where('id', $request->pengambilan_id)->first()->emergency_kit_id;
            $gudang_id = PengambilanObat::where('id', $request->pengambilan_id)->first()->gudang_id;


            $emergencyKit = EmergencyKit::find($emergency_kit_id);
            $gudang = Gudang::find($gudang_id);

            if($emergencyKit->obat()->where('obat_id', $obatId)->first()->pivot->stok > $request->jumlah) {
                $emergencyKit->obat()->updateExistingPivot($obatId, [
                    'stok' => $emergencyKit->obat()->where('obat_id', $obatId)->first()->pivot->stok - $request->jumlah,
                ]);
            } else if ($gudang->obat()->where('obat_id', $obatId)->first()->pivot->stok > $request->jumlah) {
                $gudang->obat()->updateExistingPivot($obatId, [
                    'stok' => $gudang->obat()->where('obat_id', $obatId)->first()->pivot->stok - $request->jumlah,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok di emergency kit dan gudang tidak mencukupi !',
                ], 422);
            }
            
            $acquiredMedicine = $this->historyMedicineServices->medicineOut(
                $request->pengambilan_id,
                $obatId,
                $request->jumlah,
                $request->keterangan,
                $user,
                $user
            );

            $pushToHistory = $this->historyMedicineServices->medicineHistory(
                $obatId,
                $gudang_id,
                $user,
                $request->jumlah,
                'keluar',
                $request->keterangan,
                $emergency_kit_id
            );  

            if($acquiredMedicine) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengambilan obat berhasil',
                    'acquiredMedicine' => $acquiredMedicine,
                    'pushedToHistoryIn' => $pushToHistory
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal melakukan pengambilan obat, periksa input kembali !',
                ], 422);
            }
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
