<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'm_obat';
    protected $fillable = [
        'nama_obat',
        'kode_obat',
        'satuan_id',
        'jenis_obat',
        'tanggal_kadaluarsa',
        'bpom',
        'gambar_obat',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function satuan()
    {
        return $this->belongsTo(SatuanObat::class, 'satuan_id');
    }
}