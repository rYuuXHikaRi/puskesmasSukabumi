<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmergencyKitObat extends Pivot
{
    use HasFactory;

    protected $table = 'emergency_kit_obat';

    // Non-aktifkan auto-increment karena primary key adalah kombinasi
    public $incrementing = false;

    // Definisikan primary key majemuk (composite primary key)
    protected $primaryKey = ['obat_id', 'emergency_kit_id'];
    protected $fillable = [
        'stok',
    ];

    /**
     * Relasi many-to-one ke tabel Obat.
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    /**
     * Relasi many-to-one ke tabel EmergencyKit.
     */
    public function emergencyKit()
    {
        return $this->belongsTo(EmergencyKit::class, 'emergency_kit_id');
    }
}