<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanObat extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_obat';
    protected $fillable = [
        'user_id',
        'emergency_kit_id',
        'gudang_id',
        'pasien_id',
        'keterangan',
        'updated_by',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function emergencyKit()
    {
        return $this->belongsTo(EmergencyKit::class, 'emergency_kit_id');
    }

    public function obatKeluar()
    {
        return $this->hasMany(ObatKeluar::class, 'pengambilan_obat_id');
    }
}