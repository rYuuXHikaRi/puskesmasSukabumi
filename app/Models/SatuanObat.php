<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanObat extends Model
{
    use HasFactory;

    protected $table = 'm_satuan_obat';
    protected $fillable = [
        'nama_satuan',
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

    public function obat()
    {
        return $this->hasMany(Obat::class, 'satuan_id');
    }
}