<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasiens';
    protected $fillable = [
        'nomor_rm',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'umur',
        'alamat',
        'no_hp',
        'email',
        'gol_darah',
        'status_nikah',
        'pekerjaan',
        'nama_kk',
        'hubungan_kk',
        'keluhan',
        'diagnosa',
    ];

    public function pengambilanObat()
    {
        return $this->hasMany(PengambilanObat::class, 'pasien_id');
    }
}