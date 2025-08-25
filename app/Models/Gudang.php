<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $table = 'm_gudang';
    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'tipe',
        'lokasi',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function gudangObat()
    {
        return $this->hasMany(GudangObat::class, 'gudang_id');
    }

    public function obatMasuk()
    {
        return $this->hasMany(ObatMasuk::class, 'gudang_id');
    }

    public function historiGudangObat()
    {
        return $this->hasMany(HistoriGudangObat::class, 'gudang_id');
    }

    public function obat()
    {
        return $this->belongsToMany(Obat::class, 'gudang_obat', 'gudang_id', 'obat_id')
                    ->withPivot('stok');
    }
}