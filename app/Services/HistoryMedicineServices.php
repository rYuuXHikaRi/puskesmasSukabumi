<?php

namespace App\Services;

use App\Models\ObatKeluar;
use App\Models\ObatMasuk;
use App\Models\HistoriGudangObat;

use Illuminate\Support\Facades\Validator;


/**
 * Class HistoryMedicine.
 */
class HistoryMedicineServices
{

    public function medicineIn($obat_id, $gudang_id, $user_id, $emergency_kit_id = null, $jumlah, $keterangan = null,) {
        try {
            $validator = Validator::make(
                [
                    'obat_id' => $obat_id,
                    'gudang_id' => $gudang_id,
                    'user_id' => $user_id,
                    'jumlah' => $jumlah,
                ],
                [
                    'obat_id' => 'required|exists:m_obat,id',
                    'gudang_id' => 'required|exists:m_gudang,id',
                    'user_id' => 'required|exists:users,id',
                    'jumlah' => 'required|integer|min:1',
                ]
            );

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periksa data inputan kembali !',
                    'errors' => $validator->errors()
                ]);
            } else {
                $medicineIn = ObatMasuk::create([
                    'obat_id' => $obat_id,
                    'gudang_id' => $gudang_id,
                    'user_id' => $user_id,
                    'emergency_kit_id' => $emergency_kit_id,
                    'jumlah' => $jumlah,
                    'keterangan' => $keterangan,
                ]);


                return $medicineIn;
            }
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data Emergency Kits, Periksa input kembali !',
            ], 422);
        }

    }

    public function medicineOut($pengambilan_obat_id, $obat_id, $jumlah, $keterangan = null, $created_by, $updated_by) {
        
        try {
            $validator = Validator::make(
                [
                    'pengambilan_obat_id' => $pengambilan_obat_id,
                    'obat_id' => $obat_id,
                    'jumlah' => $jumlah,
                    'created_by' => $created_by,
                    'updated_by' => $updated_by,
                ],
                [
                    'pengambilan_obat_id' => 'required|exists:pengambilan_obat,id',
                    'obat_id' => 'required|exists:m_obat,id',
                    'jumlah' => 'required|integer|min:1',
                    'created_by' => 'required|exists:users,id',
                    'updated_by' => 'required|exists:users,id',
                ]
            );

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periksa data inputan kembali !',
                    'errors' => $validator->errors()
                ]);
            } else {
                $medicineOut = ObatKeluar::create([
                    'pengambilan_obat_id' => $pengambilan_obat_id,
                    'obat_id' => $obat_id,
                    'jumlah' => $jumlah,
                    'keterangan' => $keterangan,
                    'created_by' => $created_by,
                    'updated_by' => $updated_by,
                ]);

                return $medicineOut;
            }
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data Emergency Kits, Periksa input kembali !',
            ], 422);
        }

    }

    public function medicineHistory($obat_id, $gudang_id, $user_id, $jumlah, $tipe, $keterangan = null, $emergency_kit_id = null) {
        try {
            $validator = Validator::make(
                [
                    'obat_id' => $obat_id,
                    'gudang_id' => $gudang_id,
                    'user_id' => $user_id,
                    'jumlah' => $jumlah,
                    'tipe' => $tipe,
                ],
                [
                    'obat_id' => 'required|exists:m_obat,id',
                    'gudang_id' => 'nullable|exists:m_gudang,id',
                    'user_id' => 'required|exists:users,id',
                    'jumlah' => 'required|integer|min:1',
                    'tipe' => 'required|in:masuk,keluar',
                ]
            );

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periksa data inputan kembali !'
                ]);
            } else {
                $medicineHistory = HistoriGudangObat::create([
                    'obat_id' => $obat_id,
                    'gudang_id' => $gudang_id,
                    'user_id' => $user_id,
                    'emergency_kit_id' => $emergency_kit_id,
                    'jumlah' => $jumlah,
                    'tipe' => $tipe,
                    'keterangan' => $keterangan,
                ]);

                return $medicineHistory;
            }
        } catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data Emergency Kits, Periksa input kembali !',
            ], 422);
        }

    }
}
