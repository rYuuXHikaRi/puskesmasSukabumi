<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_gudang', function (Blueprint $table) {
            $table->id();
            $table->char('kode_gudang', 100)->unique();
            $table->string('nama_gudang');
            $table->enum('tipe', ['besar', 'kecil']);
            $table->string('lokasi')->nullable();
            $table->string('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_gudang');
    }
};
