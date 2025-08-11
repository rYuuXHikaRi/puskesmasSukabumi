<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriGudangObat extends Model
{
    use HasFactory;

    protected $table = 'histori_gudang_obat';
    protected $fillable = [
        'obat_id',
        'gudang_id',
        'user_id',
        'emergency_kit_id',
        'jumlah',
        'tipe',
        'keterangan',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function emergencyKit()
    {
        return $this->belongsTo(EmergencyKit::class, 'emergency_kit_id');
    }
}