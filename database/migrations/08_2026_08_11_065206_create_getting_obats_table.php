<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
  
        Schema::create('pengambilan_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('emergency_kit_id')->nullable()->constrained('emergency_kit')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('gudang_id')->nullable()->constrained('m_gudang')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onUpdate('cascade')->onDelete('cascade');
            $table->string('keterangan')->nullable();
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('pengambilan_obat');
    }
};