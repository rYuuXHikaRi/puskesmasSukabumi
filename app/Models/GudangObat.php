<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GudangObat extends Pivot
{
    use HasFactory;

    protected $table = 'gudang_obat';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['obat_id', 'gudang_id'];
    protected $fillable = [
        'stok',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}