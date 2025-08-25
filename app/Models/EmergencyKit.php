<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyKit extends Model
{
    use HasFactory;

    protected $table = 'emergency_kit';
    protected $fillable = [
        'nama',
        'keterangan',
        'qr-code',
        'unit_layanan_id',
        'gudang_id',
    ];

    public function unitLayanan()
    {
        return $this->belongsTo(UnitLayanan::class, 'unit_layanan_id');
    }

    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'emergency_kit_obat', 'emergency_kit_id', 'obat_id')
                    ->withPivot('stok')->withTimestamps();
    }
}