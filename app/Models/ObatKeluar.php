<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatKeluar extends Model
{
    use HasFactory;

    protected $table = 'obat_keluar';
    protected $fillable = [
        'pengambilan_obat_id',
        'obat_id',
        'jumlah',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function pengambilanObat()
    {
        return $this->belongsTo(PengambilanObat::class, 'pengambilan_obat_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}