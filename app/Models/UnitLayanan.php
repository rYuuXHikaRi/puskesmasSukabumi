<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitLayanan extends Model
{
    use HasFactory;

    protected $table = 'm_unit_layanan';
    protected $fillable = [
        'unit_layanan',
        'keterangan',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'unit_layanan_id');
    }

    public function emergencyKits()
    {
        return $this->hasMany(EmergencyKit::class, 'unit_layanan_id');
    }
}