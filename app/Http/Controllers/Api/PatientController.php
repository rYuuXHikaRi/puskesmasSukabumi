<?php

namespace App\Http\Controllers\Api;

use App\Models\Pasien;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PatientController extends Controller
{
public function index() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $patient = Pasien::all();

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
                'nomor_rm' => 'required|unique:pasiens, nomor_rm',
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
}
