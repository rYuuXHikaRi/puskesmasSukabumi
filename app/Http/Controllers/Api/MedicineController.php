<?php

namespace App\Http\Controllers\Api;

use App\Models\Obat;
use App\Models\ObatKeluar;
use App\Models\PengambilanObat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class MedicineController extends Controller
{
    private function jenisObatLabel($jenis)
    {
        return match($jenis) {
            1 => 'Injeksi',
            2 => 'Oral',
            default => 'Lainnya'
        };
    }

    public function index() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $medicine = Obat::all();

            if($medicine) {
                return response()->json([
                    'success' => true,
                    'data' => $medicine
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => "There is no medicine data"
                ], 404);
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
    
    public function store(Request $request) {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            Validator::make($request->all(),[
                'nama_obat' => 'required|unique:m_obat, nama_obat|max:100',
                'kode_obat' => 'required|unique:m_obat, kode_obat|max:100',
                'satuan_id' => 'required',
                'jenis_obat' => 'required',
                'tanggal_kadaluarsa' => 'required|date',
                'bpom' => 'required|max:255',
                'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'nullable'
            ]);

            $userId = auth('api')->user()->id;
            if ($request->file('gambar_obat')) {
                $file = $request->file('gambar_obat');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('/public/images/medicine', $fileName);
            } else {
                $fileName = $request->gambar_obat;
            }

            $medicine = Obat::create([
                'nama_obat' => $request->nama_obat,
                'kode_obat' => $request->kode_obat,
                'satuan_id' => $request->satuan_id,
                'jenis_obat' => $request->jenis_obat,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'bpom' => $request->bpom,
                'gambar_obat' => $fileName,
                'keterangan' => $request->keterangan,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Entry Obat berhasil ditambahkan',
                'data' => $medicine
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data obat, Periksa input kembali !',
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
            $medicine = Obat::findOrFail($id);
            Validator::make($request->all(),[
                'nama_obat' => ['required|max:100', Rule::unique('m_obat, nama_obat')->ignore($medicine->id)],
                'kode_obat' => ['required|max:100', Rule::unique('m_obat, kode_obat')->ignore($medicine->id)],
                'satuan_id' => 'required',
                'jenis_obat' => 'required',
                'tanggal_kadaluarsa' => 'required|date',
                'bpom' => 'required|max:255',
                'gambar_obat' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'keterangan' => 'nullable',
            ]);
            
            $userId = auth('api')->user()->id;
            if ($request->file('gambar_obat')) {
                $file = $request->file('gambar_obat');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('/public/images/medicine', $fileName);
            } else {
                $fileName = $request->gambar_obat;
            }

            
            $medicine->update([
                'nama_obat' => $request->nama_obat,
                'kode_obat' => $request->kode_obat,
                'satuan_id' => $request->satuan_id,
                'jenis_obat' => $request->jenis_obat,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'bpom' => $request->bpom,
                'gambar_obat' => $fileName,
                'keterangan' => $request->keterangan,
                'updated_by' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Obat berhasil di-update',
                'data' => $medicine,
            ], 200);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbaharui data obat, Periksa input kembali !',
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

    public function createRetrievalMedicine(Request $request) {
        $validator = Validator::make($request->all(),[
            'emergency_kit_id' => ['nullable', Rule::exists('emergency_kit', 'id')],
            'gudang_id' => ['nullable', Rule::exists('m_gudang', 'id')],
            'pasien_id' => ['required', Rule::exists('pasiens', 'id')],
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
            $retrievalMedicine = PengambilanObat::create([
                'user_id' => $user,
                'emergency_kit_id' => $request->emergency_kit_id,
                'gudang_id' => $request->gudang_id,
                'pasien_id' => $request->pasien_id,
                'keterangan' => $request->keterangan,
                'updated_by' => $user,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengambilan Obat berhasil ditambahkan',
                'data' => $retrievalMedicine
            ], 201);
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

    public function getMedicineOutData(Request $request) {
        $pengambilanObatId = $request->input("pengambilanObatId");

        if (!$pengambilanObatId) {
            return response()->json([
                'success' => false,
                'errors' => "Value tidak ada"
            ], 422);
        }
        try {
            $user = JWTAuth::parseToken()->authenticate()->id;
            $data = ObatKeluar::with(['obat.satuan'])
            ->where('pengambilan_obat_id', $pengambilanObatId)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_obat'   => $item->obat->nama_obat,
                    'satuan_obat'=> $item->obat->satuan->nama_satuan ?? '-',
                    'jenis_obat' => $this->jenisObatLabel($item->obat->jenis_obat),
                    'jumlah'     => $item->jumlah,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data
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

}
