<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_obat', function (Blueprint $table) {
            $table->id();
            $table->char('nama_obat', 100)->unique();
            $table->char('kode_obat', 100)->unique();
            $table->foreignId('satuan_id')->constrained('m_satuan_obat')->onUpdate('cascade')->onDelete('cascade');
            $table->smallInteger('jenis_obat')->default(1);
            $table->date('tanggal_kadaluarsa');
            $table->char('bpom', 255);
            $table->string('gambar_obat')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('m_obat');

    }
};