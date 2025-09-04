<?php

namespace App\Http\Controllers\Api;

use App\Models\Pasien;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PatientController extends Controller
{
public function index(Request $request) {
        $patientId = $request->input("patientId");
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if($patientId) {
              $patient = Pasien::findOrFail($patientId);
            } else {
              $patient = Pasien::all();
            }
            

            if($patient) {
                return response()->json([
                    'success' => true,
                    'data' => $patient
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "There is no medicine data"
                ], 404);
            };

        } catch(TokenExpiredException $e) {
            return response()->json([
                'tokenStatus' => 'expired',
                'message' => 'Silahkan re:login kembali',
            ], 401);
        } catch(TokenInvalidException $e) {
            return response()->json([
                'tokenStatus' => 'invalid',
                'message' => 'Silahkan re:login kembali',
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
                // 'nomor_rm' => 'required|unique:pasiens, nomor_rm',
                'nama_lengkap' => 'required|max:100',
                'nik' => 'required|max:20',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required|date',
                'umur' => 'required',
                'alamat' => 'required',
                'no_hp' => 'nullable',
                'email' => 'nullable|max:100',
                'gol_darah' => 'nullable',
                'status_nikah' => 'nullable',
                'pekerjaan' => 'required|max:50',
                'nama_kk' => 'required|max:100',
                'hubungan_kk' => "nullable",
                'keluhan' => "required",
                'diagnosa' => 'required',
            ]);

            if($request->jenis_kelamin == "P") {
                $gender = "P";
            } else if($request->jenis_kelamin == "L") {
                $gender = "L";
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Input untuk data 'Jenis Kelamin' tidak valid, silahkan periksa kembali !"
                ], 422);
            }

            $patient = Pasien::create([
                'nomor_rm' => 'TEMP_NUMBER',
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'jenis_kelamin' => $gender, 
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'gol_darah' => $request->gol_darah,
                'status_nikah' => $request->status_nikah,
                'pekerjaan' => $request->pekerjaan,
                'nama_kk' => $request->nama_kk,
                'hubungan_kk' => $request->hubungan_kk,
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
            ]);
            $patient->nomor_rm = "RM" . str_pad($patient->id, 5, "0", STR_PAD_LEFT);
            $patient->save();

            return response()->json([
                'success' => true,
                'message' => 'Pasien baru berhasil ditambahkan',
                'data' => $patient
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data pasien, Periksa input kembali !',
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
            $patient = Pasien::findOrFail($id);
            Validator::make($request->all(),[
                'nomor_rm' => ['required', Rule::unique('pasiens, nomor_rm')->ignore($patient->id)],
                'nama_lengkap' => 'required|max:100',
                'nik' => 'required|max:20',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required|date',
                'umur' => 'required',
                'alamat' => 'required',
                'no_hp' => 'nullable',
                'email' => 'nullable|max:100',
                'gol_darah' => 'nullable',
                'status_nikah' => 'nullable',
                'pekerjaan' => 'required|max:50',
                'nama_kk' => 'required|max:100',
                'hubungan_kk' => "nullable",
                'keluhan' => "required",
                'diagnosa' => 'required',
            ]);
                            
            if($request->jenis_kelamin == "Perempuan") {
                $gender = "P";
            } else if($request->jenis_kelamin == "Laki-laki") {
                $gender = "L";
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Input untuk data 'Jenis Kelamin' tidak valid, silahkan periksa kembali !"
                ], 422);
            }

            $patient->update([
                'nomor_rm' => $request->nomor_rm,
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'jenis_kelamin' => $gender,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'gol_darah' => $request->gol_darah,
                'status_nikah' => $request->status_nikah,
                'pekerjaan' => $request->pekerjaan,
                'nama_kk' => $request->nama_kk,
                'hubungan_kk' => $request->hubungan_kk,
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil di-update',
                'data' => $patient,
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

    public function getHistory(Request $request) {
        $id = $request->input('patientId');
        if (!$id) {
            return response()->json([
                'success' => false,
                'errors' => "ID Patient Required !"
            ], 422);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $pasien = Pasien::findOrFail($id);
            $pasienName = $pasien->nama_lengkap;

            $data = DB::table('pasiens')
                ->leftJoin('pengambilan_obat', 'pasiens.id', '=', 'pengambilan_obat.pasien_id')
                ->leftJoin('obat_keluar', 'obat_keluar.pengambilan_obat_id', '=', 'pengambilan_obat.id')
                ->leftJoin('m_obat', 'm_obat.id', '=', 'obat_keluar.obat_id')
                ->leftJoin('m_satuan_obat', 'm_satuan_obat.id', '=', 'm_obat.satuan_id')
                ->select(
                    DB::raw('DATE(pasiens.created_at) as tanggal_pemeriksaan'),
                    'pasiens.id as pemeriksaan_id',
                    'pengambilan_obat.id as pengambilan_obat_id',
                    'pasiens.keluhan as pasien_keluhan',
                    'pasiens.diagnosa as pasien_diagnosa',
                    'm_obat.nama_obat as obat_nama',
                    'm_satuan_obat.nama_satuan as obat_satuan',
                    'm_obat.jenis_obat as obat_jenis',
                    'obat_keluar.jumlah as jumlah_keluar'
                )
                ->where('pasiens.nama_lengkap', $pasienName)
                ->orderBy('pasiens.created_at', 'desc')
                ->get();

            $grouped = $data->groupBy('tanggal_pemeriksaan')->map(function ($items, $tanggal) {
                $riwayat = $items->groupBy('pemeriksaan_id')->map(function ($riwayatItems, $idPemeriksaan) {
                    $first = $riwayatItems->first();

                    return [
                        'id_pemeriksaan' => $idPemeriksaan,
                        'id_pengambilan_obat' => $first->pengambilan_obat_id ?? null,
                        'keluhan' => $first->pasien_keluhan,
                        'diagnosa' => $first->pasien_diagnosa,
                        'resep_obat' => $first->pengambilan_obat_id
                            ? $riwayatItems->map(function ($item, $index) {
                                return [
                                    'id'     => $index + 1,
                                    'nama'   => $item->obat_nama,
                                    'satuan' => $item->obat_satuan,
                                    'jenis'  => $item->obat_jenis == "1" ? ("Injeksi") : ($item->obat_jenis == "2" ? "Oral" : "Lainnya"),
                                    'jumlah' => $item->jumlah_keluar,
                                ];
                            })->values()
                            : null,
                    ];
                })->values();

                return [
                    'tanggal' => $tanggal,
                    'riwayatPemeriksaan' => $riwayat,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'pasien' => [
                    'nama_lengkap' => $pasien->nama_lengkap,
                    'jenis_kelamin' => $pasien->jenis_kelamin,
                    'umur' => $pasien->umur,
                    'nomor_rm' => $pasien->nomor_rm,
                ],
                'data' => $grouped,
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
