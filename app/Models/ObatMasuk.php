<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatMasuk extends Model
{
    use HasFactory;

    protected $table = 'obat_masuk';
    protected $fillable = [
        'obat_id',
        'gudang_id',
        'user_id',
        'emergency_kit_id',
        'jumlah',
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