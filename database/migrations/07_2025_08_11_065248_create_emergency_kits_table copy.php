<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_kit', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('keterangan')->nullable();
            $table->string('qr_code')->nullable();
            $table->foreignId('unit_layanan_id')->nullable()->constrained('m_unit_layanan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('gudang_id')->nullable()->constrained('m_gudang')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_kit');
    }
};